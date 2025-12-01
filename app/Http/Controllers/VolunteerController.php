<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VolunteerController extends Controller
{
    // Room management
    public function room()
    {
    
   $role_id = Auth::user()->role_id;
   $user_id = Auth::user()->user_id;


        $rooms = DB::select("
            SELECT r.*, 
                (SELECT COUNT(*) FROM animal a WHERE a.room_id = r.room_id) as animal_count
            FROM room r
            ORDER BY r.name
        ");

        $animalsByRoom = [];
        foreach ($rooms as $room) {
            $animals = DB::select("
                SELECT * FROM animal 
                WHERE room_id = ? AND status != 'adopted'
            ", [$room->room_id]);
            
            foreach ($animals as $animal) {
                $animal->image = empty($animal->image) ? 'img/default.png' : $animal->image;
            }
            
            $animalsByRoom[$room->room_id] = $animals;
        }

        return view('room', [
            'rooms' => $rooms,
            'animalsByRoom' => $animalsByRoom,
            'user_id' => $user_id,
            'role_id' => $role_id,
        ]);
    }

    // View schedule
    public function viewSchedule()
    {
      $user_id = Auth::user()->user_id;
$volunteer = DB::table('volunteer')->where('user_id', $user_id)->first();

if (!$volunteer) {
    return back()->withInput()->with('error', 'Doctor not found for the current user.');
}

$volunteer_id = $volunteer->volunteer_id;



        // Get all schedules
        $all_schedules = DB::select("
            SELECT vs.volunteer_schedule_id, vs.date, vs.from_duration, vs.to_duration, vs.is_active
            FROM volunteer_schedule vs
            WHERE vs.volunteer_id = ?
            ORDER BY vs.date DESC
        ", [$volunteer_id]);

        $selected_schedule = null;
        $tasks = [];

        if (request()->has('schedule_id')) {
            $schedule_id = request()->input('schedule_id');
            
            // Verify this schedule belongs to the current volunteer
            $valid_schedule = false;
            foreach ($all_schedules as $sched) {
                if ($sched->volunteer_schedule_id == $schedule_id) {
                    $valid_schedule = true;
                    $selected_schedule = $sched;
                    break;
                }
            }
            
            if ($valid_schedule) {
                $tasks = DB::select("
                    SELECT task_id, start_time, end_time, task_details
                    FROM task
                    WHERE volunteer_schedule_id = ?
                    ORDER BY start_time ASC
                ", [$schedule_id]);
            }
        }

        return view('schedule_volunteer', [
            'all_schedules' => $all_schedules,
            'selected_schedule' => $selected_schedule,
            'tasks' => $tasks,
           
        ]);
    }

    // Animal food
   public function animalFood()
    {
        $volunteer_id = Auth::user()->user_id;

        $animal_filter = '';
        $animal_id = null;
        if (request()->has('animal_id') && is_numeric(request()->input('animal_id'))) {
            $animal_id = intval(request()->input('animal_id'));
            $animal_filter = "AND a.animal_id = $animal_id";
        }

        // Get current date in Y-m-d format
        $currentDate = date('Y-m-d');

        $result = DB::select("
            SELECT 
                a.animal_id,
                a.animal_name,
                a.image,
                afs.feeding_schedule_id,
                afs.method,
                afs.frequency,
                f.food_id,
                f.name as food_name,
                f.type as food_type,
                f.description as food_description,
                f.quantity as food_quantity,
                f.expire_date as food_expire_date,
                fs.schedule_end_date
            FROM animal a
            JOIN animalfeedingschedule afs ON a.animal_id = afs.animal_id
            JOIN feedingschedule fs ON afs.feeding_schedule_id = fs.feeding_schedule_id
            JOIN foodfeedingdetails ffd ON afs.feeding_schedule_id = ffd.feeding_schedule_id
            JOIN food f ON ffd.food_id = f.food_id
            WHERE (fs.schedule_end_date IS NULL OR fs.schedule_end_date >= ?)
            $animal_filter
            ORDER BY a.animal_id, afs.feeding_schedule_id
        ", [$currentDate]);

        // Group data by animal and feeding schedule
        $animals = [];
        foreach ($result as $row) {
            $animal_id = $row->animal_id;
            $schedule_id = $row->feeding_schedule_id;
            
            if (!isset($animals[$animal_id])) {
                $animals[$animal_id] = [
                    'animal_name' => $row->animal_name,
                    'image' => $row->image,
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
                'food_name' => $row->food_name,
                'food_type' => $row->food_type,
                'food_description' => $row->food_description,
                'food_quantity' => $row->food_quantity,
                'food_expire_date' => $row->food_expire_date
            ];
        }

        return view('food_volunteer', [
            'animals' => $animals,
            'animal_id' => $animal_id,
            'user' => $volunteer_id
        ]);
    }
}