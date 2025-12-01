<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
public function dashboard()
{
  $doctor_id = Auth::user()->user_id;
    
    // Get doctor's ID from users table
    $doctor = DB::selectOne("SELECT doctor_id FROM doctor WHERE user_id = ?", [$doctor_id]);

    if (!$doctor) {
        abort(403, 'You are not authorized as a doctor');
    }

    // ** استعلام الفحوصات القادمة ** //
   $upcomingCheckups = DB::select("
    SELECT cu.*, an.animal_name, an.type, an.animal_id
    FROM checkup cu
    JOIN healthrecord hr ON cu.health_record_id = hr.health_record_id
    JOIN animal an ON hr.health_record_id = an.health_record_id
    WHERE cu.doctor_id = ? AND cu.next_checkup > NOW()
    ORDER BY cu.next_checkup ASC
", [$doctor->doctor_id]);


    

    // Animals needing attention
   $animals_needing_attention = DB::select("
    SELECT a.animal_id, a.animal_name, a.type, a.breed, a.status 
    FROM animal a
    JOIN healthrecord hr ON a.health_record_id = hr.health_record_id
    WHERE a.status = 'under_medical_care' 
");


    return view('doctor_dashboard', [
    'upcoming_checkups' => $upcomingCheckups,
    'animals_needing_attention' => $animals_needing_attention
]);

}
public function healthRecord($animal_id)
{
    $doctor_id = Auth::user()->user_id;
    
    // Verify the animal exists first
    $animal = DB::selectOne("
        SELECT a.*, hr.health_record_id, hr.created_date, hr.created_by
        FROM animal a
        LEFT JOIN healthrecord hr ON a.health_record_id = hr.health_record_id
        WHERE a.animal_id = ?
    ", [$animal_id]);

    if (!$animal) {
        abort(404, 'Animal not found');
    }

    // If no health record exists, redirect to create one
    if (!$animal->health_record_id) {
        return redirect()->route('create_health_record', ['animal_id' => $animal_id])
            ->with('info', 'This animal has no health record. Please create one.');
    }

    // Rest of the method remains the same...
    $health_records = DB::select("
        SELECT 
            c.checkup_id,
            c.health_record_id,
            c.checkup_date,
            c.details,
            c.next_checkup,
            u.name as doctor_name,
            GROUP_CONCAT(DISTINCT m.name SEPARATOR ', ') as medicines,
            GROUP_CONCAT(DISTINCT v.name SEPARATOR ', ') as vaccinations
        FROM checkup c
        LEFT JOIN doctor d ON c.doctor_id = d.doctor_id
        LEFT JOIN users u ON d.user_id = u.user_id
        LEFT JOIN checkupmedicine cm ON c.checkup_id = cm.checkup_id
        LEFT JOIN medicine m ON cm.medicine_id = m.medicine_id
        LEFT JOIN checkupvaccination cv ON c.checkup_id = cv.checkup_id
        LEFT JOIN vaccination v ON cv.vaccination_id = v.vaccination_id
        WHERE c.health_record_id = ?
        GROUP BY c.checkup_id, c.checkup_date, c.details, c.next_checkup, u.name, c.health_record_id
        ORDER BY c.checkup_date DESC
    ", [$animal->health_record_id]);

    return view('health_record', [
        'animal' => $animal,
        'health_records' => $health_records,
        'animal_id' => $animal_id
    ]);
}

    public function createHealthRecord($animal_id)
    {
          $doctor_id = Auth::user()->user_id;

        $animal = DB::selectOne("SELECT animal_id, animal_name, type, breed FROM animal WHERE animal_id = ?", [$animal_id]);

        if (!$animal) {
            abort(404);
        }

        return view('create_health_record', ['animal' => $animal]);
    }

    public function storeHealthRecord(Request $request, $animal_id)
    {
         $user_id = Auth::id();
$doctor = DB::selectOne('SELECT doctor_id FROM doctor WHERE user_id = ?', [$user_id]);

if (!$doctor) {
    return back()->with('error', 'Doctor profile not found.');
}

$current_doctor_id = $doctor->doctor_id;


        DB::beginTransaction();

        try {
            // Create health record
            DB::insert("
                INSERT INTO healthrecord (created_by, created_date)
                VALUES (?, NOW())
            ", [$current_doctor_id]);

            $health_record_id = DB::getPdo()->lastInsertId();

            // Create initial checkup
            DB::insert("
                INSERT INTO checkup (health_record_id, doctor_id, checkup_date, details)
                VALUES (?, ?, NOW(), ?)
            ", [$health_record_id, $current_doctor_id, $request->details]);

            // Update animal with health record
            DB::update("
                UPDATE animal 
                SET health_record_id = ? 
                WHERE animal_id = ?
            ", [$health_record_id, $animal_id]);

            DB::commit();

           return redirect()->route('health_record', ['animal_id' => $animal_id])
                ->with('success', 'Health record created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating health record: ' . $e->getMessage());
        }
    }
public function createCheckup($animal_id)
{
   $user_id = Auth::user()->user_id;

// استرجعي doctor_id المرتبط بـ user_id
$doctor = DB::table('doctor')->where('user_id', $user_id)->first();

if (!$doctor) {
    return back()->withErrors(['error' => 'Doctor not found for the current user.']);
}

$doctor_id = $doctor->doctor_id;

    // Get animal data
    $animal = DB::selectOne("SELECT * FROM animal WHERE animal_id = ?", [$animal_id]);

    if (!$animal) {
        abort(404);
    }

    // Get or create health record
    if (!$animal->health_record_id) {
        DB::insert("INSERT INTO healthrecord (created_by, created_date) VALUES (?, NOW())", [$doctor_id]);
        $health_record_id = DB::getPdo()->lastInsertId();
        DB::update("UPDATE animal SET health_record_id = ? WHERE animal_id = ?", [$health_record_id, $animal_id]);
        $animal->health_record_id = $health_record_id;
    }

    // Get medicines with expiration status
    $medicines = DB::select("
        SELECT *, 
            CASE WHEN expire_date < CURDATE() THEN 1 ELSE 0 END as is_expired,
            CASE WHEN quantity_in_stock < 5 THEN 1 ELSE 0 END as is_low_stock
        FROM medicine
        WHERE is_available = 1
        ORDER BY name
    ");

    // Get vaccinations
    $vaccinations = DB::select("SELECT * FROM vaccination ORDER BY name");

    return view('create_checkup', [
        'animal' => $animal,
        'medicines' => $medicines,
        'vaccinations' => $vaccinations
    ]);
}

    public function storeCheckup(Request $request, $animal_id)
    {
         $user_id = Auth::user()->user_id;
$doctor = DB::table('doctor')->where('user_id', $user_id)->first();

if (!$doctor) {
    return back()->withInput()->with('error', 'Doctor not found for the current user.');
}

$doctor_id = $doctor->doctor_id;

        // Get animal data
        $animal = DB::selectOne("SELECT * FROM animal WHERE animal_id = ?", [$animal_id]);

        if (!$animal) {
            abort(404);
        }

        // Validate no expired medicines are being prescribed
        if (!empty($request->medicines)) {
            foreach ($request->medicines as $med_id => $med_data) {
                if (!empty($med_data['prescribed'])) {
                    $medicine = DB::selectOne("
                        SELECT *, 
                               CASE WHEN expire_date < CURDATE() THEN 1 ELSE 0 END as is_expired
                        FROM medicine
                        WHERE medicine_id = ?
                    ", [$med_id]);

                    if ($medicine && $medicine->is_expired) {
                        return back()->withInput()->with('error', 'Cannot prescribe expired medicines!');
                    }
                }
            }
        }

        DB::beginTransaction();

        try {
            // Create checkup
            $next_checkup = date('Y-m-d', strtotime('+1 month'));
            DB::insert("
                INSERT INTO checkup (details, health_record_id, doctor_id, checkup_date, next_checkup) 
                VALUES (?, ?, ?, NOW(), ?)
            ", [
                $request->details,
                $animal->health_record_id,
                $doctor_id,
                $next_checkup
            ]);

            $checkup_id = DB::getPdo()->lastInsertId();

            // Add prescribed medicines
            if (!empty($request->medicines)) {
                foreach ($request->medicines as $medicine_id => $medicine) {
                    if (!empty($medicine['prescribed'])) {
                        DB::insert("
                            INSERT INTO checkupmedicine (medicine_id, checkup_id, dosage, frequency, details) 
                            VALUES (?, ?, ?, ?, ?)
                        ", [
                            $medicine_id,
                            $checkup_id,
                            $medicine['dosage'],
                            $medicine['frequency'],
                            $medicine['details']
                        ]);
                        
                        // Update stock
                        DB::update("
                            UPDATE medicine 
                            SET quantity_in_stock = quantity_in_stock - 1 
                            WHERE medicine_id = ?
                        ", [$medicine_id]);
                    }
                }
            }

            // Add administered vaccinations
            if (!empty($request->vaccinations)) {
                foreach ($request->vaccinations as $vaccination_id => $vaccination) {
                    if (!empty($vaccination['administered'])) {
                        DB::insert("
                            INSERT INTO checkupvaccination (vaccination_id, checkup_id, dosage, details, allergy) 
                            VALUES (?, ?, ?, ?, ?)
                        ", [
                            $vaccination_id,
                            $checkup_id,
                            $vaccination['dosage'],
                            $vaccination['details'],
                            $vaccination['allergy']
                        ]);
                    }
                }
            }

            // Update animal status if changed
            if ($request->status != $animal->status) {
                DB::update("UPDATE animal SET status = ? WHERE animal_id = ?", [
                    $request->status,
                    $animal_id
                ]);
            }

            DB::commit();

            return redirect()->route('health_record', ['animal_id' => $animal_id])
                ->with('success', 'Treatment created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating treatment: ' . $e->getMessage());
        }
    }
public function editHealthRecord($health_record_id)
{
    $doctor_id = Auth::user()->user_id;

    // Get health record with animal info
    $health_record = DB::selectOne("
        SELECT hr.*, a.animal_id, a.animal_name, a.type, a.breed
        FROM healthrecord hr
        JOIN animal a ON hr.health_record_id = a.health_record_id
        WHERE hr.health_record_id = ?
    ", [$health_record_id]);

    if (!$health_record) {
        abort(404);
    }

    // Get latest checkup
    $checkup = DB::selectOne("
        SELECT * FROM checkup 
        WHERE health_record_id = ? 
        ORDER BY checkup_date DESC 
        LIMIT 1
    ", [$health_record_id]);

    if (!$checkup) {
        abort(404);
    }

    // Get medicines and vaccinations for this checkup
    $medicines = DB::select("
        SELECT m.* FROM medicine m 
        JOIN checkupmedicine cm ON m.medicine_id = cm.medicine_id 
        WHERE cm.checkup_id = ?
    ", [$checkup->checkup_id]);

    $vaccinations = DB::select("
        SELECT v.* FROM vaccination v 
        JOIN checkupvaccination cv ON v.vaccination_id = cv.vaccination_id 
        WHERE cv.checkup_id = ?
    ", [$checkup->checkup_id]);

    return view('edit_health_record', [
        'health_record_id' => $health_record_id,
        'health_record' => $health_record,
        'animal' => $health_record,
        'checkup' => $checkup,
        'medicines' => $medicines,
        'vaccinations' => $vaccinations
    ]);
}

    public function updateHealthRecord(Request $request, $health_record_id)
    {
          $doctor_id = Auth::user()->user_id;

        $request->validate([
            'details' => 'required',
            'next_checkup' => 'required|date'
        ]);

        // Get latest checkup for this health record
        $checkup = DB::selectOne("
            SELECT checkup_id FROM checkup 
            WHERE health_record_id = ? 
            ORDER BY checkup_date DESC 
            LIMIT 1
        ", [$health_record_id]);

        if (!$checkup) {
            abort(404);
        }

        // Get animal information
        $animal = DB::selectOne("SELECT animal_id FROM animal WHERE health_record_id = ?", [$health_record_id]);

        if (!$animal) {
            abort(404);
        }

        DB::update("
            UPDATE checkup 
            SET details = ?, next_checkup = ? 
            WHERE checkup_id = ?
        ", [
            $request->details,
            $request->next_checkup,
            $checkup->checkup_id
        ]);

        return redirect()->route('health_record', ['animal_id' => $animal->animal_id])
            ->with('success', 'Health record updated successfully');
    }

    public function searchAnimals(Request $request)
    {
        $search_results = [];
        $search_performed = false;

        if ($request->isMethod('get') && $request->has('search')) {
            $search_performed = true;
            $search_term = '%' . $request->input('search') . '%';
            $search_type = $request->input('search_type');
            $gender = $request->input('gender', '');
            
            $sql = "SELECT * FROM animal WHERE ";
            $params = [];
            $types = "";
            
            switch ($search_type) {
                case 'name':
                    $sql .= "animal_name LIKE ?";
                    $params[] = $search_term;
                    $types .= "s";
                    break;
                case 'type':
                    $sql .= "type LIKE ?";
                    $params[] = $search_term;
                    $types .= "s";
                    break;
                case 'breed':
                    $sql .= "breed LIKE ?";
                    $params[] = $search_term;
                    $types .= "s";
                    break;
                default:
                    $sql .= "(animal_name LIKE ? OR type LIKE ? OR breed LIKE ?)";
                    $params = [$search_term, $search_term, $search_term];
                    $types .= "sss";
                    break;
            }
            
            if (!empty($gender)) {
                $sql .= " AND gender = ?";
                $params[] = $gender;
                $types .= "s";
            }
            
            $sql .= " ORDER BY animal_name";
            
            $search_results = DB::select($sql, $params);
        }

        return view('search_animals', [
            'search_results' => $search_results,
            'search_performed' => $search_performed,
            'request' => $request
        ]);
    }
public function viewAnimal($id)
{
    $doctor_id = Auth::user()->user_id;
    $is_doctor = Auth::user()->role_id == 2;

    // Get animal info with room and health record details
    $animal = DB::selectOne("
        SELECT a.*, r.name as room_name, r.category as room_category,
               hr.health_record_id, hr.created_date, u.name as doctor_name
        FROM animal a
        LEFT JOIN room r ON a.room_id = r.room_id
        LEFT JOIN healthrecord hr ON a.health_record_id = hr.health_record_id
        LEFT JOIN doctor d ON hr.created_by = d.doctor_id
        LEFT JOIN users u ON d.user_id = u.user_id
        WHERE a.animal_id = ?
    ", [$id]);

    if (!$animal) {
        abort(404);
    }

    // Get checkups if health record exists
    $checkups = [];
    if ($animal->health_record_id) {
        $checkups = DB::select("
            SELECT 
                c.checkup_id,
                c.checkup_date,
                c.details,
                c.next_checkup,
                c.doctor_id,
                c.health_record_id,
                u.name as doctor_name,
                GROUP_CONCAT(DISTINCT m.name SEPARATOR ', ') as medicines,
                GROUP_CONCAT(DISTINCT v.name SEPARATOR ', ') as vaccinations
            FROM checkup c
            LEFT JOIN doctor d ON c.doctor_id = d.doctor_id
            LEFT JOIN users u ON d.user_id = u.user_id
            LEFT JOIN checkupmedicine cm ON c.checkup_id = cm.checkup_id
            LEFT JOIN medicine m ON cm.medicine_id = m.medicine_id
            LEFT JOIN checkupvaccination cv ON c.checkup_id = cv.checkup_id
            LEFT JOIN vaccination v ON cv.vaccination_id = v.vaccination_id
            WHERE c.health_record_id = ?
            GROUP BY 
                c.checkup_id,
                c.checkup_date,
                c.details,
                c.next_checkup,
                c.doctor_id,
                c.health_record_id,
                u.name
            ORDER BY c.checkup_date DESC
        ", [$animal->health_record_id]);
    }

  

    return view('view_animal', [
        'animal' => $animal,
        'checkups' => $checkups,
        'is_doctor' => $is_doctor,
        'doctor_id' => $doctor_id
    ]);
}

    public function viewMedicinesVaccinations()
    {
        // Get all data in single query
        $results = DB::select("
            (SELECT 'medicine' as type, medicine_id as id, name, description, 
                    quantity_in_stock as quantity, expire_date, price, NULL as symptoms
             FROM medicine)
            UNION ALL
            (SELECT 'vaccination' as type, vaccination_id as id, name, description, 
                    NULL as quantity, NULL as expire_date, NULL as price, symptoms
             FROM vaccination)
            ORDER BY type, name
        ");

        $medicines = [];
        $vaccinations = [];

        foreach ($results as $row) {
            if ($row->type == 'medicine') {
                $medicines[] = $row;
            } else {
                $vaccinations[] = $row;
            }
        }

        return view('view_medicines_vaccinations', [
            'medicines' => $medicines,
            'vaccinations' => $vaccinations
        ]);
    }
    
public function viewSchedule()
{
    $doctor_id = Auth::user()->user_id;
    
    // Get doctor's ID from users table
    $doctor = DB::selectOne("SELECT doctor_id FROM doctor WHERE user_id = ?", [$doctor_id]);

    if (!$doctor) {
        abort(403, 'You are not authorized as a doctor');
    }

    // Get doctor's schedules with appointments
    $schedules = DB::select("
        SELECT ds.*, 
               a.appointment_id, a.start_time, a.end_time, a.appointment_details,
               a.is_active, an.animal_name, an.type as animal_type, an.animal_id
        FROM doctor_schedule ds
        LEFT JOIN appointments a ON ds.doctor_schedule_id = a.doctor_schedule_id
        LEFT JOIN animal an ON a.animal_id = an.animal_id
        WHERE ds.doctor_id = ? AND ds.is_active = 1
        ORDER BY ds.date, ds.from_duration, a.start_time
    ", [$doctor->doctor_id]);

    // Organize schedules by date
    $organizedSchedules = [];
    foreach ($schedules as $schedule) {
        $date = $schedule->date;
        if (!isset($organizedSchedules[$date])) {
            $organizedSchedules[$date] = [
                'schedule_info' => $schedule,
                'appointments' => []
            ];
        }
        
        if ($schedule->appointment_id) {
            $organizedSchedules[$date]['appointments'][] = [
                'appointment_id' => $schedule->appointment_id,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'appointment_details' => $schedule->appointment_details,
                'animal_name' => $schedule->animal_name,
                'animal_type' => $schedule->animal_type,
                'animal_id' => $schedule->animal_id
            ];
        }
    }

    // ** استعلام الفحوصات القادمة ** //
    $upcomingCheckups = DB::select("
        SELECT cu.*, an.animal_name, an.type
FROM checkup cu
JOIN healthrecord hr ON cu.health_record_id = hr.health_record_id
JOIN animal an ON hr.health_record_id = an.health_record_id
WHERE cu.doctor_id = ? AND cu.next_checkup > NOW()
ORDER BY cu.next_checkup ASC

    ", [$doctor->doctor_id]);

    // إرسال كل شيء للـ View
   return view('view_schedule', [
    'organizedSchedules' => $organizedSchedules,
    'checkups' => $upcomingCheckups,  
]);

}
}