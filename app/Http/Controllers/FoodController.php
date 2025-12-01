<?php

namespace App\Http\Controllers; 


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller 
{
   

    // Food Management
    public function manageFood()
    {
        $available_food = DB::select("
            SELECT f.*, u.name AS employee_name 
            FROM food f
            LEFT JOIN employee e ON f.employee_id = e.employee_id
            LEFT JOIN users u ON e.user_id = u.user_id
            WHERE f.expire_date >= CURDATE() AND f.quantity > 0
            ORDER BY f.expire_date ASC
        ");

        $expired_food = DB::select("
            SELECT f.*, u.name AS employee_name 
            FROM food f
            LEFT JOIN employee e ON f.employee_id = e.employee_id
            LEFT JOIN users u ON e.user_id = u.user_id
            WHERE f.expire_date < CURDATE() OR f.quantity <= 0
            ORDER BY f.expire_date DESC
        ");

        return view('manage_food', compact('available_food', 'expired_food'));
    }

    public function addFood(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'quantity' => 'required|integer|min:1',
                'expire_date' => 'required|date|after:today'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $employee = DB::selectOne("
                SELECT employee_id FROM employee WHERE user_id = ?
            ", [Auth::id()]);

            if (!$employee) {
                return redirect()->back()->with('error', 'Employee not found');
            }

            DB::insert("
                INSERT INTO food (name, type, description, quantity, expire_date, employee_id) 
                VALUES (?, ?, ?, ?, ?, ?)
            ", [
                $request->name,
                $request->type,
                $request->description,
                $request->quantity,
                $request->expire_date,
                $employee->employee_id
            ]);

            return redirect()->route('manage_food')->with('success', 'Food item added successfully!');
        }

        return view('add_food');
    }

    public function editFood(Request $request, $id)
    {
        $food = DB::selectOne("SELECT * FROM food WHERE food_id = ?", [$id]);

        if (!$food) {
            return redirect()->route('manage_food')->with('error', 'Food item not found');
        }

        if (strtotime($food->expire_date) < time() || $food->quantity <= 0) {
            return view('edit_food_unavailable', compact('food'));
        }

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'quantity' => 'required|integer|min:1',
                'expire_date' => 'required|date|after:today'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $employee = DB::selectOne("
                SELECT employee_id FROM employee WHERE user_id = ?
            ", [Auth::id()]);

            DB::update("
                UPDATE food SET 
                    name = ?, 
                    type = ?, 
                    description = ?, 
                    quantity = ?, 
                    expire_date = ?,
                    employee_id = ?
                WHERE food_id = ?
            ", [
                $request->name,
                $request->type,
                $request->description,
                $request->quantity,
                $request->expire_date,
                $employee->employee_id,
                $id
            ]);

            return redirect()->route('manage_food')->with('success', 'Food item updated successfully!');
        }

        return view('edit_food', compact('food'));
    }

    // Animal Feeding
    public function animalFood(Request $request, $animal_id = null)
    {
        
        
        $foods = DB::select("
            SELECT food_id, name FROM food WHERE quantity > 0 AND expire_date >= CURDATE()
        ");
        $foods = collect($foods)->pluck('name', 'food_id')->toArray();

        $query = "
            SELECT 
                a.animal_id,
                a.animal_name,
                a.image,
                a.status,
                afs.feeding_schedule_id,
                afs.method,
                afs.frequency,
                f.food_id,
                f.name as food_name
            FROM animal a
            JOIN animalfeedingschedule afs ON a.animal_id = afs.animal_id
            JOIN foodfeedingdetails ffd ON afs.feeding_schedule_id = ffd.feeding_schedule_id
            JOIN food f ON ffd.food_id = f.food_id
            WHERE a.status != 'adopted'
        ";

        if ($animal_id) {
            $query .= " AND a.animal_id = $animal_id";
        }

        $query .= " ORDER BY a.animal_id, afs.feeding_schedule_id";

        $result = DB::select($query);

        $animals = [];
        foreach ($result as $row) {
            $animal_id = $row->animal_id;
            $schedule_id = $row->feeding_schedule_id;
            
            if (!isset($animals[$animal_id])) {
                $animals[$animal_id] = [
                    'animal_name' => $row->animal_name,
                    'image' => $row->image,
                    'status' => $row->status,
                    'schedules' => []
                ];
            }
            
            if (!isset($animals[$animal_id]['schedules'][$schedule_id])) {
                $animals[$animal_id]['schedules'][$schedule_id] = [
                    'method' => $row->method,
                    'frequency' => $row->frequency,
                    'foods' => []
                ];
            }
            
            $animals[$animal_id]['schedules'][$schedule_id]['foods'][] = [
                'food_id' => $row->food_id,
                'food_name' => $row->food_name
            ];
        }

        return view('animal_food', compact('animals', 'foods', 'animal_id'));
    }
// Update the createFeeding method
public function createFeeding(Request $request, $animal_id = null)
{
    // Get the specific animal
    $animal = DB::selectOne("SELECT animal_id, animal_name FROM animal WHERE animal_id = ?", [$animal_id]);
    
    if (!$animal) {
        return redirect()->route('animal_food')->with('error', 'Animal not found');
    }

    $foods = DB::select("
        SELECT food_id, name FROM food WHERE quantity > 0 AND expire_date >= CURDATE()
    ");
    $foods = collect($foods)->pluck('name', 'food_id')->toArray();

    if ($request->isMethod('post')) {
        $validator = Validator::make($request->all(), [
            'method' => 'required',
            'frequency' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'foods' => 'required|array|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $employee = DB::selectOne("
            SELECT employee_id FROM employee WHERE user_id = ?
        ", [Auth::id()]);

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found');
        }

        DB::beginTransaction();

        try {
            DB::insert("
                INSERT INTO feedingschedule (schedule_start_date, schedule_end_date, employee_id) 
                VALUES (?, ?, ?)
            ", [
                $request->start_date,
                $request->end_date,
                $employee->employee_id
            ]);

            $schedule_id = DB::getPdo()->lastInsertId();

            DB::insert("
                INSERT INTO animalfeedingschedule (feeding_schedule_id, animal_id, method, frequency) 
                VALUES (?, ?, ?, ?)
            ", [
                $schedule_id,
                $animal_id, // Use the animal_id from the route
                $request->method,
                $request->frequency
            ]);

            foreach ($request->foods as $food_id) {
                DB::insert("
                    INSERT INTO foodfeedingdetails (feeding_schedule_id, food_id) 
                    VALUES (?, ?)
                ", [$schedule_id, $food_id]);
            }

            DB::commit();
            return redirect()->route('animal_food', ['animal_id' => $animal_id])
                ->with('success', 'Feeding schedule created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating feeding schedule: ' . $e->getMessage());
        }
    }

    return view('create_feeding', compact('animal', 'foods'));
}

// Update the editFeeding method
public function editFeeding(Request $request, $schedule_id, $animal_id)
{
    // Get feeding schedule and animal name
    $feeding = DB::selectOne("
        SELECT afs.*, a.animal_name 
        FROM animalfeedingschedule afs
        JOIN animal a ON afs.animal_id = a.animal_id
        WHERE afs.feeding_schedule_id = ? AND afs.animal_id = ?
    ", [$schedule_id, $animal_id]);

    if (!$feeding) {
        return redirect()->route('animal_food')->with('error', 'Feeding schedule not found');
    }

    // Get selected foods for this feeding schedule
    $food_details = DB::select("
        SELECT f.food_id, f.name as food_name 
        FROM foodfeedingdetails ffd
        JOIN food f ON ffd.food_id = f.food_id
        WHERE ffd.feeding_schedule_id = ?
    ", [$schedule_id]);

    // Get available foods (not expired and have quantity)
    $foods = DB::select("
        SELECT food_id, name 
        FROM food 
        WHERE quantity > 0 AND expire_date >= CURDATE()
    ");
    $foods = collect($foods)->pluck('name', 'food_id')->toArray();

    $selected_food_ids = collect($food_details)->pluck('food_id')->toArray();

    // Handle POST request (update feeding schedule)
    if ($request->isMethod('post')) {
        $validator = Validator::make($request->all(), [
            'method' => 'required',
            'frequency' => 'required',
            'foods' => 'required|array|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $has_changes = false;

            // Get current feeding data
            $current_data = DB::selectOne("
                SELECT method, frequency 
                FROM animalfeedingschedule 
                WHERE feeding_schedule_id = ? AND animal_id = ?
            ", [$schedule_id, $animal_id]);

            // Check if method or frequency changed
            if ($current_data->method != $request->method || $current_data->frequency != $request->frequency) {
                $has_changes = true;

                DB::update("
                    UPDATE animalfeedingschedule 
                    SET method = ?, frequency = ? 
                    WHERE feeding_schedule_id = ? AND animal_id = ?
                ", [
                    $request->method,
                    $request->frequency,
                    $schedule_id,
                    $animal_id
                ]);
            }

            // Get current foods assigned
            $current_foods = DB::select("
                SELECT food_id 
                FROM foodfeedingdetails 
                WHERE feeding_schedule_id = ?
            ", [$schedule_id]);
            $current_foods = collect($current_foods)->pluck('food_id')->toArray();

            $submitted_foods = $request->input('foods');
            sort($submitted_foods);
            sort($current_foods);

            if ($submitted_foods != $current_foods) {
                $has_changes = true;

                // Delete old food records
                DB::delete("
                    DELETE FROM foodfeedingdetails 
                    WHERE feeding_schedule_id = ?
                ", [$schedule_id]);

                // Insert new food records
                foreach ($submitted_foods as $food_id) {
                    DB::insert("
                        INSERT INTO foodfeedingdetails (feeding_schedule_id, food_id) 
                        VALUES (?, ?)
                    ", [$schedule_id, $food_id]);
                }
            }

            if (!$has_changes) {
                throw new \Exception("No changes were made to the feeding schedule");
            }

            DB::commit();
            return redirect()->route('animal_food', ['animal_id' => $animal_id])
                ->with('success', 'Feeding schedule updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating feeding schedule: ' . $e->getMessage());
        }
    }

    return view('edit_feeding', compact('feeding', 'foods', 'selected_food_ids', 'animal_id'));
}
public function addFoodToSchedule(Request $request)
{
    $validator = Validator::make($request->all(), [
        'schedule_id' => 'required|integer',
        'food_id' => 'required|integer'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Get the animal_id for this schedule
    $schedule = DB::selectOne("
        SELECT animal_id 
        FROM animalfeedingschedule 
        WHERE feeding_schedule_id = ?
    ", [$request->schedule_id]);

    if (!$schedule) {
        return redirect()->back()->with('error', 'Schedule not found');
    }

    $animal_id = $schedule->animal_id;

    $exists = DB::selectOne("
        SELECT * FROM foodfeedingdetails 
        WHERE feeding_schedule_id = ? AND food_id = ?
    ", [$request->schedule_id, $request->food_id]);

    if ($exists) {
        
        return redirect()->back()->with('error', 'This food is already assigned to the schedule');
    }

    DB::insert("
        INSERT INTO foodfeedingdetails (feeding_schedule_id, food_id) 
        VALUES (?, ?)
    ", [$request->schedule_id, $request->food_id]);

    return redirect()->back()->with('success', 'Food added to schedule successfully');
}

public function removeFoodFromSchedule($schedule_id, $food_id)
{
    // Get the animal_id for this schedule
    $schedule = DB::selectOne("
        SELECT animal_id 
        FROM animalfeedingschedule 
        WHERE feeding_schedule_id = ?
    ", [$schedule_id]);

    if (!$schedule) {
         return redirect()->back()->with('error', 'Schedule not found');
    }

    $animal_id = $schedule->animal_id;

    DB::delete("
        DELETE FROM foodfeedingdetails 
        WHERE feeding_schedule_id = ? AND food_id = ?
    ", [$schedule_id, $food_id]);

  return redirect()->back()->with('success', 'Food removed from schedule successfully');
}
    // Room Management
    public function editRoom(Request $request, $id)
    {
        $room = DB::selectOne("SELECT * FROM room WHERE room_id = ?", [$id]);

        if (!$room) {
            return redirect()->route('room')->with('error', 'Room not found');
        }

        $animal_data = DB::selectOne("
            SELECT COUNT(*) as animal_count, GROUP_CONCAT(animal_name) as animal_names 
            FROM animal WHERE room_id = ?
        ", [$id]);

        $animal_count = $animal_data->animal_count;
        $animal_names = $animal_data->animal_names ? explode(',', $animal_data->animal_names) : [];
        $is_over_capacity = ($animal_count > $room->capacity);
        $min_capacity = $is_over_capacity ? $animal_count : max($animal_count, 1);

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'capacity' => "required|integer|min:$min_capacity|max:30",
                'description' => 'required',
                'category' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if ($request->capacity < $animal_count) {
                return redirect()->back()
                    ->with('error', "Cannot reduce capacity below current animal count ($animal_count)")
                    ->withInput();
            }

            DB::update("
                UPDATE room SET 
                    name = ?, 
                    capacity = ?, 
                    description = ?, 
                    category = ?, 
                    availability = ? 
                WHERE room_id = ?
            ", [
                $request->name,
                $request->capacity,
                $request->description,
                $request->category,
                $request->has('availability') ? 1 : 0,
                $id
            ]);

            return redirect()->route('room')->with('success', 'Room updated successfully');
        }

        return view('edit_room', compact('room', 'animal_count', 'animal_names', 'is_over_capacity', 'min_capacity'));
    }
}