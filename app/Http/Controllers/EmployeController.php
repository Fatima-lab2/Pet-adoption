<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
{
    // Manage Animals
    public function manageAnimals()
    {
        $animals = DB::select("
            SELECT a.*, r.name as room_name 
            FROM animal a 
            LEFT JOIN room r ON a.room_id = r.room_id
            ORDER BY a.status
        ");

        // Group animals by status
        $groupedAnimals = [
            'adopted' => [],
            'available' => [],
            'under_medical_care' => [],
            'foster' => []
        ];

        foreach ($animals as $animal) {
            $groupedAnimals[$animal->status][] = $animal;
        }

        return view('manage_animals', compact('groupedAnimals'));
    }

    // Add Animal Form
   public function addAnimalForm()
{
    $rooms = DB::select("
        SELECT r.room_id, r.name, r.category,
               (r.capacity - COUNT(a.animal_id)) AS available_slots
        FROM room r
        LEFT JOIN animal a ON r.room_id = a.room_id
        WHERE r.availability = 1
        GROUP BY r.room_id, r.name, r.capacity, r.category
        HAVING available_slots > 0
    ");

    return view('add_animal', compact('rooms'));
}


    // Add Animal Submit
    public function addAnimal(Request $request)
    {
        $request->validate([
            'animal_name' => 'required',
            'gender' => 'required',
            'type' => 'required',
            'status' => 'required',
            'arrival_date' => 'required|date',
        ]);

        $image = 'img/animal.jpg'; // Default image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($extension, $allowed)) {
                $filename = uniqid('animal_images/', true) . '.' . $extension;
                $file->move(public_path('storage/animal_images'), $filename);
                $image = $filename;
            }
        }

        DB::insert("
            INSERT INTO animal (
                animal_name, gender, type, breed, birth_date, color, weight, image, 
                status, price, arrival_date, room_id, is_adopted
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $request->animal_name,
            $request->gender,
            $request->type,
            $request->breed,
            $request->birth_date,
            $request->color,
            $request->weight,
            $image,
            $request->status,
            $request->price,
            $request->arrival_date,
            $request->room_id,
            $request->status == 'adopted' ? 1 : 0
        ]);

        if ($request->room_id) {
            DB::update("
                UPDATE room SET availability = availability - 1 WHERE room_id = ?
            ", [$request->room_id]);
        }

        return redirect()->route('manage_animals')->with('success', 'Animal added successfully!');
    }

    // Edit Animal Form
    public function editAnimalForm($id)
    {
        $animal = DB::selectOne("SELECT * FROM animal WHERE animal_id = ?", [$id]);
        
        if (!$animal) {
            return redirect()->route('manage_animals')->with('error', 'Animal not found');
        }

    $rooms = DB::select("
    SELECT r.room_id, r.name, r.category
    FROM room r
    LEFT JOIN (
        SELECT room_id, COUNT(*) as animal_count 
        FROM animal 
        GROUP BY room_id
    ) a ON r.room_id = a.room_id
    WHERE r.availability = 1 
    AND (a.animal_count IS NULL OR a.animal_count < r.capacity)
    ORDER BY r.name
");


        return view('edit_animal', compact('animal', 'rooms'));
    }

    // Edit Animal Submit
   public function editAnimal(Request $request, $id)
{
    $request->validate([
        'animal_name' => 'required',
        'type' => 'required',
        'breed' => 'required',
        'birth_date' => 'required|date',
        'status' => 'required',
        'room_id' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Check room availability
    $room = DB::selectOne("
        SELECT r.room_id, r.capacity,  r.category, 
              (SELECT COUNT(*) FROM animal WHERE room_id = r.room_id) as current_count
        FROM room r
        WHERE r.room_id = ? AND r.availability = 1
    ", [$request->room_id]);

    if (!$room) {
        return back()->with('error', 'Selected room is not available');
    }

    $currentAnimal = DB::selectOne("SELECT room_id, image FROM animal WHERE animal_id = ?", [$id]);

    if ($currentAnimal->room_id != $request->room_id && $room->current_count >= $room->capacity) {
        return back()->with('error', 'Selected room is at full capacity');
    }

    // رفع الصورة إن وُجدت
    $imagePath = $currentAnimal->image; // احتفظ بالصورة القديمة
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time().'_'.$image->getClientOriginalName();
        $image->move(public_path('storage/animal_images'), $imageName);
        $imagePath = 'animal_images/' . $imageName;
    }

    // التحديث باستخدام Raw SQL
    DB::update("
        UPDATE animal SET 
            animal_name = ?, 
            type = ?, 
            breed = ?, 
            birth_date = ?, 
            color = ?, 
            weight = ?, 
            status = ?, 
            room_id = ?,
            is_adopted = ?,
            image = ?
        WHERE animal_id = ?
    ", [
        $request->animal_name,
        $request->type,
        $request->breed,
        $request->birth_date,
        $request->color,
        $request->weight,
        $request->status,
        $request->room_id,
        $request->status == 'adopted' ? 1 : 0,
        $imagePath,
        $id
    ]);

    return redirect()->route('manage_animals')->with('success', 'Animal updated successfully!');
}
}
