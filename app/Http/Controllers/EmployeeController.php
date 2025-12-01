<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    //Manage Doctor Part
    function doctor_page_action(){
        $doctors=DB::select('SELECT * FROM doctor JOIN users ON users.user_id = doctor.user_id JOIN user_role ON users.user_id=user_role.user_id  WHERE user_role.is_active=1');
        return view('doctors',compact('doctors'));
    }
    function doctor_schedule_action($doctor_id){
        $schedules = DB::select('
        SELECT * FROM doctor_schedule 
        WHERE doctor_id = ?', [$doctor_id]);
        $doctors = DB::select('
        SELECT * FROM doctor 
        JOIN users ON doctor.user_id = users.user_id 
        JOIN user_role ON users.user_id=user_role.user_id
        WHERE doctor_id = ? AND user_role.is_active=1 ', [$doctor_id]);
        
        return view('doctor_schedule',compact('schedules','doctors'));
    }
    function create_doctor_schedule_page($doctor_id){
        $doctor =DB::selectOne('
        SELECT * FROM doctor 
        JOIN users ON doctor.user_id = users.user_id 
        JOIN user_role ON user_role.user_id=users.user_id
        WHERE doctor_id = ? AND user_role.is_active=1', [$doctor_id]);
        return view('create_doctor_schedule',compact('doctor'));
    }
    function create_doctor_schedule_action(Request $request ,$doctor_id){
        $fields=$request->validate([
            'date'=>['required','date'],
            'from_duration'=>['required','date_format:H:i'],
            'to_duration'=>['required','date_format:H:i'],
            'appointment_number'=>['required','integer', 'min:1']
        ]);
        $doctor_schedule=DB::insert('
        INSERT INTO doctor_schedule (doctor_id,date,from_duration,to_duration,appointment_number)
        VALUES(?,?,?,?,?)',
        [$doctor_id,$fields['date'],$fields['from_duration'],$fields['to_duration'],$fields['appointment_number']]
        );

        $scheduleId = DB::getPdo()->lastInsertId();
        $doctor = DB::selectOne('SELECT * FROM doctor JOIN users ON users.user_id = doctor.user_id  AND users.is_active=1');
        $doctor_schedule = DB::selectOne('
        SELECT * FROM doctor_schedule WHERE doctor_schedule_id = ?', [$scheduleId]);
        if (!$doctor_schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }
        $animals = DB::select('SELECT * FROM animal');
        return view('create_appointments',compact('scheduleId','doctor','doctor_schedule','animals'));
    }

    function create_appointments_page($scheduleId){
    
    return view('create_appointments', compact('scheduleId'));
    }
    function create_appointments_action(Request $request,$scheduleId){
        $fields=$request->validate([
        'appointments' => ['required','array'],
        'appointments.*.animal_id' => ['required','integer','exists:animal,animal_id'],
        'appointments.*.start_time' => ['required','date_format:H:i'],
        'appointments.*.end_time' => ['required','date_format:H:i,after:appointments.*.start_time'],
        'appointments.*.appointment_details' => ['required','string'],
        ]);
        foreach ($fields['appointments'] as $appointment) {
            DB::insert('
                INSERT INTO appointments (doctor_schedule_id, animal_id, start_time, end_time, appointment_details)
                VALUES (?, ?, ?, ?, ?)
            ', [
                $scheduleId,
                $appointment['animal_id'],
                $appointment['start_time'],
                $appointment['end_time'],
                $appointment['appointment_details']
            ]);
    }
    return redirect('/doctors');
    }
    function view_appointments_page($doctor_id,$doctor_schedule_id){
        $schedule = DB::selectOne('SELECT * FROM doctor_schedule WHERE doctor_id = ? AND doctor_schedule_id = ?', [$doctor_id, $doctor_schedule_id]);
        $appointments=DB::select('SELECT * FROM appointments JOIN animal ON appointments.animal_id = animal.animal_id WHERE doctor_schedule_id=? AND is_active=1',[$doctor_schedule_id]);
        
        return view('view_appointments',compact('doctor_id','doctor_schedule_id','schedule','appointments'));
    }
    function update_doctor_schedule_page($doctor_id,$doctor_schedule_id){
        $doctor = DB::selectOne('SELECT * FROM doctor JOIN users ON users.user_id = doctor.user_id JOIN user_role ON user_role.user_id=users.user_id  WHERE doctor_id = ? AND user_role.is_active=1', [$doctor_id]);
        $schedule = DB::selectOne('SELECT * FROM doctor_schedule WHERE doctor_id = ? AND doctor_schedule_id = ?', [$doctor_id, $doctor_schedule_id]);
        return view('update_doctor_schedule',compact('doctor_id','doctor_schedule_id','doctor','schedule'));
    }
    function update_doctor_schedule_action(Request $request, $doctor_id, $schedule_id){

        $fields = $request->validate([
            'date' => ['required', 'date'],
            'from_duration' => ['required', 'date_format:H:i'],
            'to_duration' => ['required', 'date_format:H:i'],
            'appointment_number' => ['required', 'integer'],
        ]);
        DB::update('UPDATE doctor_schedule SET date=?, from_duration=?, to_duration=?, appointment_number=? WHERE doctor_schedule_id = ?',
        [$fields['date'], $fields['from_duration'], $fields['to_duration'], $fields['appointment_number'], $schedule_id]);

        return redirect()->route('doctor_schedule', ['doctor_id' => $doctor_id]);
    }
    function remove_doctor_schedule_page($doctor_id, $schedule_id){
        $schedule = DB::selectOne('SELECT * FROM doctor_schedule WHERE doctor_id = ? AND doctor_schedule_id = ?', [$doctor_id, $schedule_id]);
        $doctor = DB::selectOne('SELECT * FROM doctor JOIN users ON users.user_id = doctor.user_id JOIN user_role ON user_role.user_id=users.user_id  AND user_role.is_active=1');
        return view('remove_doctor_schedule',compact('schedule','doctor'));
        
    }
    function remove_doctor_schedule_action($doctor_id, $schedule_id){
        DB::update('UPDATE doctor_schedule SET is_active=0 WHERE doctor_schedule_id=?',[$schedule_id]);
        return redirect('/doctor_schedule/'.$doctor_id)->with('success','Schedule has been removed');
    }
    function update_appointment_page($doctor_schedule_id,$appointment_id){
        $schedule=DB::selectOne('SELECT * FROM doctor_schedule WHERE doctor_schedule_id=?',[$doctor_schedule_id]);
        $appointment = DB::selectOne('SELECT * FROM appointments WHERE appointment_id = ? AND doctor_schedule_id = ?', [$appointment_id, $doctor_schedule_id]);
        $animals = DB::select('SELECT * FROM animal');
        return view('update_appointments',compact('appointment','animals','doctor_schedule_id'));
    }
    function update_appointment_action(Request $request,$doctor_schedule_id,$appointment_id){
        $fields=$request->validate([
       'animal_id' => ['required','exists:animal,animal_id'],
        'start_time' => ['required','date_format:H:i'],
        'end_time' => ['required','date_format:H:i','after:start_time'],
        'appointment_details' => ['required','string','max:1000'],
        ]);
        $schedule = DB::selectOne('SELECT * FROM doctor_schedule WHERE doctor_schedule_id = ?', [$doctor_schedule_id]);
        $doctor_id = $schedule->doctor_id;
        DB::update('UPDATE appointments SET animal_id = ?, start_time = ?, end_time = ?, appointment_details = ? WHERE appointment_id = ?', [
            $fields['animal_id'],
            $fields['start_time'],
            $fields['end_time'],
            $fields['appointment_details'],
            $appointment_id
        ]);

        return redirect('/view_appointments/'.$doctor_id.'/'.$doctor_schedule_id)->with('success', 'Appointment updated successfully!');
    }
    function remove_appointments_page($doctor_schedule_id,$appointment_id){
        $schedule=DB::selectOne('SELECT * FROM doctor_schedule WHERE doctor_schedule_id=?',[$doctor_schedule_id]);
        $appointment = DB::selectOne('SELECT * FROM appointments WHERE appointment_id = ? AND doctor_schedule_id = ? AND is_active=1', [$appointment_id, $doctor_schedule_id]);
        $doctor = DB::selectOne('SELECT * FROM doctor JOIN users ON users.user_id = doctor.user_id JOIN user_role ON users.user_id=user_role.user_id WHERE user_role.is_active=1 AND doctor.doctor_id = ?', [$schedule->doctor_id]);
        return view('remove_appointments',compact('appointment','schedule','doctor_schedule_id','doctor'));
    }
    function remove_appointments_action($doctor_schedule_id,$appointment_id){
        DB::update('UPDATE appointments SET is_active=0 WHERE doctor_schedule_id=? AND appointment_id=? ',[$doctor_schedule_id,$appointment_id]);
        $schedule = DB::selectOne('SELECT * FROM doctor_schedule WHERE doctor_schedule_id = ?', [$doctor_schedule_id]);
        $doctor_id = $schedule->doctor_id;
        return redirect('/view_appointments/'.$doctor_id.'/'.$doctor_schedule_id)->with('success', 'Appointment removed successfully!');
    }
    //Manage Volunteer Part
    function volunteers_page(){
        $volunteers=DB::select('SELECT * FROM volunteer JOIN users ON volunteer.user_id=users.user_id JOIN user_role ON user_role.user_id=users.user_id AND user_role.is_active=1');
        return view('volunteers',compact('volunteers'));
    }
    function volunteer_schedule_action($volunteer_id){

        $schedules = DB::select('
        SELECT * FROM volunteer_schedule 
        WHERE volunteer_id = ? AND is_active=1', [$volunteer_id]);
        $volunteers = DB::select('
        SELECT * FROM volunteer 
        JOIN users ON volunteer.user_id = users.user_id 
        JOIN user_role ON user_role.user_id=users.user_id
        WHERE volunteer_id = ? AND user_role.is_active=1', [$volunteer_id]);
        
        return view('volunteer_schedule',compact('schedules','volunteers'));
    }
    function create_volunteer_schedule_page($volunteer_id){
        $volunteer =DB::selectOne('
        SELECT * FROM volunteer 
        JOIN users ON volunteer.user_id = users.user_id 
        JOIN user_role ON user_role.user_id=users.user_id
        WHERE volunteer_id = ? AND user_role.is_active=1 ', [$volunteer_id]);
        return view('create_volunteer_schedule',compact('volunteer'));
    }
    function create_volunteer_schedule_action(Request $request ,$volunteer_id){
        $fields=$request->validate([
            'date'=>['required','date'],
            'from_duration'=>['required','date_format:H:i'],
            'to_duration'=>['required','date_format:H:i'],
            'task_number'=>['required','integer', 'min:1']
        ]);
        $volunteer_schedule=DB::insert('
        INSERT INTO volunteer_schedule (volunteer_id,date,from_duration,to_duration,task_number)
        VALUES(?,?,?,?,?)',
        [
        $volunteer_id,
        $fields['date'],
        $fields['from_duration'],
        $fields['to_duration'],
        $fields['task_number']
        ]
        );

        $scheduleId = DB::getPdo()->lastInsertId();
        $volunteer = DB::selectOne('SELECT * FROM volunteer JOIN users ON users.user_id = volunteer.user_id  AND users.is_active=1');
        $volunteer_schedule = DB::selectOne('
        SELECT * FROM volunteer_schedule WHERE volunteer_schedule_id = ?', [$scheduleId]);
        if (!$volunteer_schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }
        return view('create_task',compact('scheduleId','volunteer','volunteer_schedule'));
    }

    function create_task_page($scheduleId){
    
        return view('create_task', compact('scheduleId'));
        }
        function create_task_action(Request $request,$scheduleId){
            $fields=$request->validate([
            'task' => ['required','array'],
            'task.*.start_time' => ['required','date_format:H:i'],
            'task.*.end_time' => ['required','date_format:H:i,after:task.*.start_time'],
            'task.*.task_details' => ['required','string'],
            ]);
            foreach ($fields['task'] as $task) {
                DB::insert('
                    INSERT INTO task (volunteer_schedule_id,start_time, end_time, task_details)
                    VALUES (?, ?, ?, ?)
                ', [
                    $scheduleId,
                    $task['start_time'],
                    $task['end_time'],
                    $task['task_details']
                ]);
        }
        return redirect('/volunteers');
        }
        function view_tasks_page($volunteer_id,$volunteer_schedule_id){

            $schedule = DB::selectOne('SELECT * FROM volunteer_schedule WHERE volunteer_id = ? AND volunteer_schedule_id = ?', [$volunteer_id, $volunteer_schedule_id]);
            $tasks=DB::select('SELECT * FROM task  WHERE volunteer_schedule_id=? AND is_active=1',[$volunteer_schedule_id]);
            $volunteer=DB::selectOne('SELECT * FROM volunteer Join users ON volunteer.user_id=users.user_id WHERE volunteer_id=?', [$volunteer_id]);
            return view('view_tasks',compact('volunteer_id','volunteer_schedule_id','schedule','tasks','volunteer'));
        }
        function update_volunteer_schedule_page($volunteer_id,$volunteer_schedule_id){
            $volunteer = DB::selectOne('SELECT * FROM volunteer JOIN users ON users.user_id = volunteer.user_id JOIN user_role ON user_role.user_id=users.user_id WHERE volunteer_id = ? AND user_role.is_active=1', [$volunteer_id]);
            $schedule = DB::selectOne('SELECT * FROM volunteer_schedule WHERE volunteer_id = ? AND volunteer_schedule_id = ?', [$volunteer_id, $volunteer_schedule_id]);
            return view('update_volunteer_schedule',compact('volunteer_id','volunteer_schedule_id','volunteer','schedule'));
        }
        function update_volunteer_schedule_action(Request $request, $volunteer_id, $schedule_id){
    
            $fields = $request->validate([
                'date' => ['required', 'date'],
                'from_duration' => ['required', 'date_format:H:i'],
                'to_duration' => ['required', 'date_format:H:i'],
                'task_number' => ['required', 'integer'],
            ]);
            DB::update('UPDATE volunteer_schedule SET date=?, from_duration=?, to_duration=?, task_number=? WHERE volunteer_schedule_id = ?',
            [$fields['date'], $fields['from_duration'], $fields['to_duration'], $fields['task_number'], $schedule_id]);
            return redirect('/volunteer_schedule/' . $volunteer_id)->with('success', 'Schedule updated successfully.');
        }
        function remove_volunteer_schedule_page($volunteer_id, $schedule_id){
            $schedule = DB::selectOne('SELECT * FROM volunteer_schedule WHERE volunteer_id = ? AND volunteer_schedule_id = ?', [$volunteer_id, $schedule_id]);
            $volunteer = DB::selectOne('SELECT * FROM volunteer JOIN users ON users.user_id = volunteer.user_id JOIN user_role ON user_role.user_id=users.user_id WHERE user_role.is_active=1 AND volunteer.volunteer_id = ?', [$volunteer_id]);
            return view('remove_volunteer_schedule',compact('schedule','volunteer'));
            
        }
        function remove_volunteer_schedule_action($volunteer_id, $schedule_id){
            DB::update('UPDATE volunteer_schedule SET is_active=0 WHERE volunteer_schedule_id=?',[$schedule_id]);
            DB::update('UPDATE task SET is_active=0 WHERE volunteer_schedule_id=?',[$schedule_id]);
            return redirect('/volunteer_schedule/'.$volunteer_id)->with('success','Schedule has been removed');
        }
        function update_task_page($volunteer_schedule_id,$task_id){
            $schedule=DB::selectOne('SELECT * FROM volunteer_schedule WHERE doctor_schedule_id=?',[$volunteer_schedule_id]);
            $task = DB::selectOne('SELECT * FROM task WHERE task_id = ? AND volunteer_schedule_id = ?', [$task_id, $volunteer_schedule_id]);
            return view('update_task',compact('task','volunteer_schedule_id'));
        }
        function update_task_action(Request $request,$volunteer_schedule_id,$task_id){
            $fields=$request->validate([
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
            'task_details' => ['required','string','max:1000'],
            ]);

            $schedule = DB::selectOne('SELECT * FROM volunteer_schedule WHERE volunteer_schedule_id = ?', [$volunteer_schedule_id]);
            $volunteer_id = $schedule->volunteer_id;
            DB::update('UPDATE task SET  start_time = ?, end_time = ?, task_details = ? WHERE task_id = ?', [
                $fields['start_time'],
                $fields['end_time'],
                $fields['task_details'],
                $task_id
            ]);
    
            return redirect('/view_tasks/'.$volunteer_id.'/'.$volunteer_schedule_id)->with('success', 'Task updated successfully!');
        }
        function remove_task_page($volunteer_schedule_id,$task_id){
            $schedule=DB::selectOne('SELECT * FROM volunteer_schedule WHERE volunteer_schedule_id=?',[$volunteer_schedule_id]);
            $task = DB::selectOne('SELECT * FROM task WHERE task_id = ? AND volunteer_schedule_id = ? AND is_active=1', [$task_id, $volunteer_schedule_id]);
            $volunteer = DB::selectOne('SELECT * FROM volunteer JOIN users ON users.user_id = volunteer.user_id WHERE users.is_active=1 ');
            return view('remove_task',compact('task','schedule','volunteer_schedule_id','volunteer'));
        }
        function remove_task_action($volunteer_schedule_id,$task_id){
            DB::update('UPDATE task SET is_active=0 WHERE volunteer_schedule_id=? AND task_id=? ',[$volunteer_schedule_id,$task_id]);
            $schedule = DB::selectOne('SELECT * FROM volunteer_schedule WHERE volunteer_schedule_id = ?', [$volunteer_schedule_id]);
            $volunteer_id = $schedule->volunteer_id;
            return redirect('/view_tasks/'.$volunteer_id.'/'.$volunteer_schedule_id)->with('success', 'Appointment removed successfully!');
        }
        function disactivated_volunteers_page(){
            $disactivated_volunteers=DB::select('SELECT * FROM volunteer JOIN users ON users.user_id=volunteer.user_id WHERE users.is_active=0');
            return view('disactivated_volunteers',compact('disactivated_volunteers'));
        }
        
        function disactivated_volunteers_action(Request $request){
            DB::update('UPDATE users SET is_active = 1 WHERE user_id = ?', [
                $request->input('user_id')  // must match the hidden input name
            ]);
            return redirect('/volunteers')->with('success','Volunteer is activated!');
        }
        function add_new_task_page($volunteer_id, $schedule_id) {
            $volunteer = DB::selectOne(
                'SELECT * 
                 FROM volunteer 
                 JOIN volunteer_schedule ON volunteer.volunteer_id = volunteer_schedule.volunteer_id 
                 JOIN users ON users.user_id = volunteer.user_id
                 WHERE volunteer.volunteer_id = ? AND volunteer_schedule.volunteer_schedule_id = ?', 
                [$volunteer_id, $schedule_id]
            );
        
            return view('add_new_task', compact("volunteer"));
        }
        function add_new_task_action(Request $request, $volunteer_id, $schedule_id){
            $fields = $request->validate([
                'task_details' => ['required', 'string'],
                'start_time'   => ['required', 'date_format:H:i'],
                'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            ]);
        
            DB::insert('INSERT INTO task (task_details, start_time, end_time, volunteer_schedule_id) VALUES (?, ?, ?, ?)',
                [
                    $fields['task_details'],
                    $fields['start_time'],
                    $fields['end_time'],
                    $schedule_id
                ]
            );
            return redirect("/view_tasks/$volunteer_id/$schedule_id")->with('success', 'Task added successfully. Do not forget to update the schedule task number.');
        }

        public function add_volunteer_page() {
            return view('add_new_volunteer');
        }
       public function add_volunteer_action(Request $request)
{
    $fields = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone_number' => 'required|string',
        'date_of_birth' => 'required|date',
    ]);

    $image_path = 'images/default.png';
try {
    DB::statement('INSERT INTO users (name, email, password, phone_number, date_of_birth, role_id, image) VALUES (?, ?, ?, ?, ?, ?, ?)', [
        $fields['name'],
        $fields['email'],
        bcrypt($fields['password']),
        $fields['phone_number'],
        $fields['date_of_birth'],
        3,
        $image_path
    ]);
} catch (\Exception $e) {
    dd($e->getMessage()); // shows the SQL error
}


        // Fetch the user ID
        $user = DB::selectOne('SELECT user_id FROM users WHERE email = ? ORDER BY user_id DESC LIMIT 1', [
            $fields['email']
        ]);

        if ($user) {
            DB::insert('INSERT INTO volunteer (user_id) VALUES (?)', [$user->user_id]);

            return redirect('/volunteers')->with('success', 'Volunteer added successfully.');
        } else {
            return back()->withErrors(['error' => 'User inserted but not found.']);
        }
    }
      function add_new_appointment_page($doctor_id, $schedule_id) {
            $doctor = DB::selectOne(
                'SELECT * 
                 FROM doctor 
                 JOIN doctor_schedule ON doctor.doctor_id = doctor_schedule.doctor_id 
                 JOIN users ON users.user_id = doctor.user_id
                 WHERE doctor.doctor_id = ? AND doctor_schedule.doctor_schedule_id = ?', 
                [$doctor_id, $schedule_id]
            );
            $animals=DB::select('SELECT * FROM animal WHERE is_adopted=0');
            return view('add_new_appointment', compact("doctor","animals"));
        }
        function add_new_appointment_action(Request $request, $doctor_id, $schedule_id){
            $fields = $request->validate([
                'animal_id'=>['required'],
                'appointment_details' => ['required', 'string'],
                'start_time'   => ['required', 'date_format:H:i'],
                'end_time'     => ['required', 'date_format:H:i', 'after:start_time'],
            ]);
        
            DB::insert('INSERT INTO appointments (animal_id, appointment_details, start_time, end_time, doctor_schedule_id) VALUES (?,?, ?, ?, ?)',
                [
                    $fields['animal_id'],
                    $fields['appointment_details'],
                    $fields['start_time'],
                    $fields['end_time'],
                    $schedule_id
                ]
            );
            return redirect("/view_appointments/$doctor_id/$schedule_id")->with('success', 'Appointment added successfully. Do not forget to update the schedule appointment number.');
        }
        //Contract Part
        //Show all doctor contract (for one user)

        function doctor_contracts_page($doctor_id){
  
        $doctor = DB::selectOne(
        'SELECT * FROM doctor 
         JOIN users ON users.user_id = doctor.user_id 
         WHERE doctor.doctor_id = ?', 
        [$doctor_id]
    );

    //  contracts linked to the doctor
    $contracts = DB::select(
        'SELECT contract.* FROM contract 
         JOIN doctorcontract ON contract.contract_id = doctorcontract.contract_id 
         WHERE doctorcontract.doctor_id = ?', 
        [$doctor_id]
    );

    return view('doctor_contracts', compact('doctor', 'contracts'));
}

        function add_doctor_contract_page($doctor_id){
            $doctor = DB::selectOne('SELECT * FROM doctor JOIN users ON users.user_id = doctor.user_id WHERE doctor_id = ?', [$doctor_id]);
            return view('add_doctor_contract',compact('doctor_id','doctor'));
        }
     function add_doctor_contract_action(Request $request, $doctor_id) {
    $fields = $request->validate([
        'status' => ['required', 'string'],
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date'],
        'salary' => ['required', 'numeric'],
        'vacation_days' => ['required', 'integer'],
        'violation_penalty' => ['required', 'string'],
        'contract_type' => ['required', 'string'],
    ]);

    $fields['created_by'] = auth()->user()->user_id ?? auth()->id();

    DB::insert("
        INSERT INTO contract 
        (status, start_date, end_date, salary, vacation_days, violation_penalty, contract_type, created_by, termination_date, termination_reason)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ", [
        $fields['status'],
        $fields['start_date'],
        $fields['end_date'],
        $fields['salary'],
        $fields['vacation_days'],
        $fields['violation_penalty'],
        $fields['contract_type'],
        $fields['created_by'],
        null,
        null
    ]);

    $contractId = DB::getPdo()->lastInsertId();

    DB::insert("INSERT INTO doctorcontract (doctor_id, contract_id) VALUES (?, ?)", [
        $doctor_id,
        $contractId
    ]);

    return redirect('/doctor_contracts/' . $doctor_id)->with('success', 'Contract added successfully.');
}


    function doctor_terminate_contract_page($contract_id){
        $contract = DB::selectOne('SELECT * FROM contract WHERE contract_id = ?', [$contract_id]);
        $doctor = DB::selectOne('SELECT * FROM doctorcontract
        JOIN contract ON doctorcontract.contract_id=contract.contract_id
        JOIN doctor ON doctorcontract.doctor_id=doctor.doctor_id
        JOIN users ON users.user_id=doctor.user_id
       WHERE contract.contract_id = ?', [$contract_id]);

        return view('doctor_terminate_contract',compact('contract','doctor'));
    }
    function doctor_terminate_contract_action(Request $request, $contract_id){
        $fields = $request->validate([
        'termination_date' => ['required', 'date'],
        'termination_reason' => ['required', 'string'],
    ]);

    DB::update('UPDATE contract SET termination_date = ?, termination_reason = ?, status = ? WHERE contract_id = ?', [
        $fields['termination_date'],
        $fields['termination_reason'],
        'terminated',
        $contract_id
    ]);
    $doctor = DB::selectOne('SELECT * FROM doctorcontract
        JOIN contract ON doctorcontract.contract_id=contract.contract_id
        JOIN doctor ON doctorcontract.doctor_id=doctor.doctor_id
        JOIN users ON users.user_id=doctor.user_id
       WHERE contract.contract_id = ?', [$contract_id]);


    return redirect('/doctor_contracts'.$doctor->doctor_id )->with('success', 'Contract updated successfully.');
}
        function employee_contracts_page($employee_id){
  
        $employee = DB::selectOne(
        'SELECT * FROM employee 
         JOIN users ON users.user_id = employee.user_id 
         WHERE employee.employee_id = ?', 
        [$employee_id]
    );

    //  contracts linked to the employee
        $contracts = DB::select(
        'SELECT contract.* FROM contract 
        JOIN employeecontract ON contract.contract_id = employeecontract.contract_id 
        WHERE employeecontract.employee_id = ?', 
        [$employee_id]
    );

    return view('employee_contracts', compact('employee', 'contracts'));
}

        function add_employee_contract_page($employee_id){
            $employee = DB::selectOne('SELECT * FROM employee JOIN users ON users.user_id = employee.user_id WHERE employee_id = ?', [$employee_id]);
            return view('add_employee_contract',compact('employee_id','employee'));
        }
        function add_employee_contract_action(Request $request, $employee_id){
        $fields = $request->validate([
        'status' => ['required', 'string'],
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date'],
        'salary' => ['required', 'numeric'],
        'vacation_days' => ['required', 'integer'],
        'violation_penalty' => ['required', 'string'],
        'contract_type' => ['required', 'string'],
       
    ]);
     $fields['created_by'] = auth()->user()->user_id ?? auth()->id();
    DB::insert("
        INSERT INTO contract 
        ( status, start_date, end_date, salary, vacation_days, violation_penalty, 
        contract_type, created_by, termination_date, termination_reason)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)
    ", [
        $fields['status'],
        $fields['start_date'],
        $fields['end_date'],
        $fields['salary'],
        $fields['vacation_days'],
        $fields['violation_penalty'],
        $fields['contract_type'],
        $fields['created_by'],
        null,
        null
    ]);
        // Get the last inserted contract_id
        $contractId = DB::getPdo()->lastInsertId();

        DB::insert("INSERT INTO employeecontract (employee_id, contract_id) VALUES (?, ?)", [
        $employee_id,
        $contractId
    ]);
    return redirect('/employee_contracts/' . $employee_id)->with('success', 'Contract added successfully.');
}
    function employee_terminate_contract_page($contract_id){
        $contract = DB::selectOne('SELECT * FROM contract WHERE contract_id = ?', [$contract_id]);
        $employee = DB::selectOne('SELECT * FROM employee WHERE employee_id = ?', [$contract->employee_id]);

        return view('employee_terminate_contract',compact('contract','employee'));
    }
    function employee_terminate_contract_action(Request $request, $contract_id){
        $fields = $request->validate([
        'termination_date' => ['required', 'date'],
        'termination_reason' => ['required', 'string'],
    ]);

    DB::update('UPDATE contract SET termination_date = ?, termination_reason = ?, status = ? WHERE contract_id = ?', [
        $fields['termination_date'],
        $fields['termination_reason'],
        'terminated',
        $contract_id
    ]);    
    return redirect()->back()->with('success', 'Contract updated successfully.');

}
  function expense_page(){
    $expenses = DB::select("
        SELECT 
            expense.*, users.name, 
            DATE_FORMAT(date, '%Y-%m') AS expense_month
        FROM expense
        JOIN employee ON employee.employee_id=expense.employee_id
        JOIN users ON users.user_id=employee.user_id
        ORDER BY date DESC
    ");

    $groupedExpenses = [];
    foreach ($expenses as $expense) {
        $month = $expense->expense_month;
        $groupedExpenses[$month][] = $expense;
    }

    return view('expenses', compact('groupedExpenses','expenses'));
}

    function add_expense_page(){
        return view('add_expense');
    }

function add_expense_action(Request $request){

    $fields = $request->validate([
        'currency' => ['required', 'string'],
        'amount' => ['required', 'numeric'],
        'category' => ['required', 'string'],
        'date' => ['required', 'date'],
        'details' => ['required', 'string'],
        'payment_method' => ['required', 'string'],
    ]);

    // Get the logged-in user's ID
    $userId = auth()->user()->user_id;

    $employee = DB::selectOne("SELECT * FROM employee WHERE user_id = ?", [$userId]);

    if (!$employee) {
        return redirect()->back()->with('error', 'No employee record linked to this user.');
    }

    DB::insert("
        INSERT INTO expense (currency, amount, category, date, details, payment_method, employee_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ", [
        $fields['currency'],
        $fields['amount'],
        $fields['category'],
        $fields['date'],
        $fields['details'],
        $fields['payment_method'],
        $employee->employee_id,
    ]);

    return redirect('/expenses')->with('success', 'Expense added successfully.');
}

}


