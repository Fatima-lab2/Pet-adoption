<?php

namespace App\Http\Controllers;


use Log;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\AdoptionRequestStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\ValidationException;



class AdminController extends Controller
{
    
    //Retreive users' information
    function retreive_old_data_info($user_id,$role_id){
          if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
        $user=DB::selectOne('SELECT * FROM users JOIN user_role ON users.user_id=user_role.user_id WHERE users.user_id=? AND user_role.role_id=? ',[$user_id,$role_id]);
        $roles = DB::select('SELECT * FROM role WHERE status=1'); 
        // Retreine role name of the user
        $role = DB::selectOne('SELECT role_name FROM role WHERE role_id=?', [$role_id]);
        $role_name = strtolower($role->role_name);
        $role_data = null;

        // fetch role-specific data if table exists
        if (Schema::hasTable($role_name)) {
            $role_data = DB::selectOne("SELECT * FROM $role_name WHERE user_id = ?", [$user_id]);
        }
    }
}
        return view('update_profile',['user'=>$user,'roles'=>$roles,$role_name => $role_data ]);
    }
    //Update users information process
    //1-

function update_user_profile(Request $request, $user_id, $old_role_id) {
    try {
        // General validation
        $fields = $request->validate([
            "name" => ['required', 'max:50', 'min:3'],
            "email" => ['required', 'email', "unique:users,email,$user_id,user_id"],
            "phone_number" => ['required', 'regex:/^\+?[0-9]{8,15}$/'],
            "date_of_birth" => ['required'],
        ]);

        // Update user
        DB::update('
            UPDATE users 
            SET name = ?, email = ?, date_of_birth = ?, phone_number = ? 
            WHERE user_id = ?', [
                $fields['name'],
                $fields['email'],
                $fields['date_of_birth'],
                $fields['phone_number'],
                $user_id
        ]);

        // Role-specific update
        $role = DB::selectOne('SELECT role_name FROM role WHERE role_id = ?', [$old_role_id]);
        $role_name = strtolower($role->role_name);

        if (Schema::hasTable($role_name)) {
            switch ($old_role_id) {
                case 2: // Doctor
                    $request->validate([
                        'experience_year' => 'required|integer|min:0',
                        'specialization' => 'required|string|max:100',
                      
                    ]);
                    DB::update("
                        UPDATE doctor
                        SET experience_year = ?, specialization = ?
                        WHERE user_id = ?", [
                            $request->experience_year,
                            $request->specialization,
                            $user_id
                    ]);
                    break;

                case 3: // Volunteer
                    $request->validate([
                        'responsibility' => 'required|string|max:255',
                    ]);
                    DB::update("UPDATE volunteer SET responsibility = ? WHERE user_id = ?", [
                        $request->responsibility,
                        $user_id
                    ]);
                    break;

                case 4: // Adopter
                    $request->validate([
                        'previous_adoption_times' => 'required|integer|min:0',
                    ]);
                    DB::update("UPDATE adopter SET previous_adoption_times = ? WHERE user_id = ?", [
                        $request->previous_adoption_times,
                        $user_id
                    ]);
                    break;

                case 5: // Donor
                    $request->validate([
                        'previous_donation_times' => 'required|integer|min:0',
                    ]);
                    DB::update("UPDATE donor SET previous_donation_times = ? WHERE user_id = ?", [
                        $request->previous_donation_times,
                        $user_id
                    ]);
                    break;

                case 6: // Employee
                    $request->validate([
                        'responsibility' => 'required|string|max:255',
                        'type_of_work' => 'required|string|max:100',
                    ]);
                    DB::update("
                        UPDATE employee
                        SET responsibility = ?, type_of_work = ?
                        WHERE user_id = ?", [
                            $request->responsibility,
                            $request->type_of_work,
                            $user_id
                    ]);
                    break;
            }
        }

        return redirect('/users')->with('success', 'Profile updated successfully!');
    
    } catch (ValidationException $e) {
        return back()->withErrors($e->validator)->withInput();
    }
}


    function remove_user_page($user_id){
        $user=DB::selectOne('SELECT * FROM users WHERE user_id=?',[$user_id]);
        return view('admin_remove_user',['user'=>$user]);
    }
    function admin_remove_user($user_id){
        DB::update('
        UPDATE user_role SET is_active=0 WHERE user_id=?',[$user_id]
        );
        return redirect('/users')->with('success','User is removed successfully!');
    }
    //Add new user
    function add_new_user_page(){
        $roles=DB::select('SELECT * FROM role WHERE status= 1');
        return view('add_new_user',['roles'=>$roles]);
    }
    function add_new_user_action(Request $request){
        $fields= $request->validate([
        
            "name" => ['required','max:50','min:3','alpha_num'],
            "email" => ['required','email','unique:users,email'],
            "password" => ['required','max:50','min:8'],
            "phone_number" => ['required','regex:/^\+?[0-9]{8,15}$/'],
            "role_id" => [ 'exists:role,role_id'],
            "date_of_birth" => ['required'],
        ]);
        //After validating the data ,we insert them to the db.
      DB::insert('INSERT INTO users(name,email,password,phone_number,date_of_birth,image) VALUES(?,?,?,?,?,?)',
      [$fields['name'],
      $fields['email'],
      bcrypt($fields['password']),
      $fields['phone_number'],
      $fields['date_of_birth'],
      'images/default.png'
    ]);
    // Get the last inserted user_id
    $user_id = DB::getPdo()->lastInsertId();

    DB::insert('INSERT INTO user_role(user_id,role_id) VALUES(?,?)',
    [   $user_id,
        $fields['role_id']
    ]
    );
     // Get the last inserted user_id
     if($fields['role_id'] != 14){
    $user_id = DB::getPdo()->lastInsertId();

        //Based on the selected role,we insert the user to his role table(employee,admin...)
        $role = DB::selectOne('SELECT role_name FROM role WHERE role_id = ?', [$fields['role_id']]);
        $role_name=strtolower($role->role_name);

        DB::insert("INSERT INTO $role_name (user_id) VALUES (?)", [$user_id]);
     }
    return redirect('/users')->with('success','User added successfully');
    }
    //Add new role
    function new_role_page(){
        return view('role_management');
    }
  function retreive_roles() {
    // Step 1: Get all active roles
    $roles = DB::select('SELECT * FROM role WHERE status = 1');

    // Step 2: Get counts of active and inactive users per role
    $counts = DB::select('
        SELECT 
            role.role_id,
            SUM(CASE WHEN user_role.is_active = 1 THEN 1 ELSE 0 END) AS active_count,
            SUM(CASE WHEN user_role.is_active = 0 THEN 1 ELSE 0 END) AS inactive_count
        FROM role
        LEFT JOIN user_role ON role.role_id = user_role.role_id
        GROUP BY role.role_id
    ');

    // Step 3: Store counts in arrays by role_id
    $total_nb_roles = [];  // Active users per role
    $total_nb_roles2 = []; // Inactive users per role

    foreach ($counts as $row) {
        $total_nb_roles[$row->role_id] = $row->active_count;
        $total_nb_roles2[$row->role_id] = $row->inactive_count;
    }

    // Step 4: Return to view
    return view('role_management', compact('roles', 'total_nb_roles', 'total_nb_roles2'));
}

   
    function add_new_role(Request $request){
       $fields=$request->validate([
        'role_name'=>['required','alpha_num','unique:role,role_name'],
        'description'=>['required'],
        'details'=>['nullable', 'string']
    ]);
    $fields['added_by_admin'] = 1;

    //Insert role to the role table
    $role=Role::create($fields);

    $tableName = strtolower($role->role_name);

    // Dynamically create a table with the same name
   

    if (!Schema::hasTable($tableName)) {
    try {
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('details')->nullable();

            // Make sure this FK is valid
            $table->foreign('user_id')->references('user_id')->on('user_role')->onDelete('cascade');
        });
    } catch (\Exception $e) {
         return redirect('/role_management')->with('error', 'Failed to create role table: ' . $e->getMessage());    }
    }
    return redirect('/role_management')->with('success','Role added!');
    }
    function roles(){
        $roles= DB::select('SELECT * FROM role');
        return view('update_profile',compact('roles'));
    }
    function remove_role_page($role_id){
        $role=DB::selectOne('SELECT * FROM role WHERE role_id=?',[$role_id]);
        return view('remove_role',['role'=>$role]);
    }
    function remove_role_action($role_id){
       $userCount = DB::table('user_role')->where('role_id', $role_id)->count();
    if ($userCount > 0) {
        // If users exist, redirect with an error message
        return redirect('/role_management')->with('error', 'Cannot remove role. There are users assigned to this role.');
    }
    // If no users, allow the removal
    DB::update('UPDATE role SET status = 0 WHERE role_id = ?', [$role_id]);
    return redirect('/role_management')->with('success', 'Role removed!');
    }
    function edit_role_description_page($role_id){
        $role=DB::selectOne('SELECT * FROM role WHERE role_id=?',[$role_id]);
        return view('edit_role_description',compact('role_id','role'));
    }

    function edit_role_description_action(Request $request,$role_id){

        $fields=$request->validate([
            "description"=>['required','string']
        ]);
        DB::update('UPDATE role SET description = ? WHERE role_id = ?', [
                $fields['description'],
                $role_id
            ]);

        return redirect('/role_management')->with('success','Description updated!!');
    }
    function disactivated_roles_page() {

        $roles = DB::select('SELECT * FROM role WHERE status = 0');
        return view('disactivated_roles', compact('roles'));

    }
    function disactivated_roles_action($role_id){

    DB::update('UPDATE role SET status = 1 WHERE role_id = ?', [$role_id]);
    return redirect('/role_management')->with('success', 'Role reactivated successfully.');

    }

    function assign_role_page($user_id){
       $user=DB::selectOne('SELECT * FROM users WHERE user_id=?',[$user_id]);
       $roles=DB::select('SELECT * FROM role WHERE status= 1');
       return view('/assign_role',['user'=>$user,'roles'=>$roles]);
    }

  function assign_role_action(Request $request, $user_id) {
    $fields = $request->validate([
        "role_id" => ['exists:role,role_id', 'required'],
    ]);

    $new_role_id = $fields['role_id'];

    // Check if the user already has this role
    $existing = DB::selectOne('SELECT * FROM user_role WHERE user_id = ? AND role_id = ?', [$user_id, $new_role_id]);

    if ($existing) {
        return redirect('/users')->with('error', 'User already has this role.');
    }

    // Check if the user has only role 14
    $user_roles = DB::select('SELECT * FROM user_role WHERE user_id = ? AND is_active = 1', [$user_id]);
    $has_only_role_14 = count($user_roles) === 1 && $user_roles[0]->role_id == 14;

    if ($has_only_role_14) {
        // Update role 14 to new role
        DB::update('UPDATE user_role SET role_id = ? WHERE user_id = ?', [$new_role_id, $user_id]);

        // Add to role-specific table if not already exists
        $role = DB::selectOne('SELECT role_name FROM role WHERE role_id = ?', [$new_role_id]);
        $role_name = strtolower($role->role_name);

        $already_in_role_table = DB::selectOne("SELECT * FROM $role_name WHERE user_id = ?", [$user_id]);
        if (!$already_in_role_table) {
            DB::insert("INSERT INTO $role_name (user_id) VALUES (?)", [$user_id]);
        }

        return redirect('/users')->with('success', 'User role updated successfully.');
    }

    // Assign new role (user has more than one role or not role 14)
    DB::insert('INSERT INTO user_role (user_id, role_id, is_active) VALUES (?, ?, 1)', [$user_id, $new_role_id]);

    // Add to role-specific table if not already exists
    $role = DB::selectOne('SELECT role_name FROM role WHERE role_id = ?', [$new_role_id]);
    $role_name = strtolower($role->role_name);

    $already_in_role_table = DB::selectOne("SELECT * FROM $role_name WHERE user_id = ?", [$user_id]);
    if (!$already_in_role_table) {
        DB::insert("INSERT INTO $role_name (user_id) VALUES (?)", [$user_id]);
    }

    return redirect('/users')->with('success', 'User role updated successfully.');
}


     function view_doctor_schedule_page($user_id){
        $doctor = DB::selectOne('SELECT * FROM doctor JOIN users ON users.user_id=doctor.user_id WHERE doctor.user_id = ? ', [$user_id]);
    
        // If no doctor found, return or abort
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor not found.');
        }
        $schedules = DB::select('SELECT * FROM doctor_schedule WHERE is_active=1 AND  doctor_id = ?', [$doctor->doctor_id]);
        $appointments = DB::select(
            'SELECT appointments.*, animal.animal_name
            FROM appointments
            JOIN animal ON appointments.animal_id = animal.animal_id
            WHERE appointments.doctor_schedule_id IN (
                SELECT doctor_schedule_id FROM doctor_schedule 
                WHERE doctor_id = ? AND is_active = 1
            )',
            [$doctor->doctor_id]
        );

    
        return view('view_doctor_schedule', compact('doctor', 'schedules', 'appointments'));
    }
    function disactivated_users_page(){
        $disactivated_users=DB::select('SELECT * FROM users JOIN user_role ON users.user_id=user_role.user_id  WHERE user_role.is_active=0');
        $doctors = DB::select('SELECT * FROM doctor JOIN users ON doctor.user_id=users.user_id');
        $volunteers = DB::select('SELECT * FROM volunteer JOIN users ON volunteer.user_id=users.user_id');
        $adopters = DB::select('SELECT * FROM adopter JOIN users ON adopter.user_id=users.user_id');
        $donors = DB::select('SELECT * FROM donor JOIN users ON donor.user_id=users.user_id');
        $employees = DB::select('SELECT * FROM employee JOIN users ON employee.user_id=users.user_id');
        return view('disactivated_users',compact('disactivated_users',
        'doctors',
        'volunteers',
        'adopters',
        'donors',
        'employees'));
    }
    
    function disactivated_users_action(Request $request){
        DB::update('UPDATE user_role SET is_active = 1 WHERE user_id = ?', [
            $request->input('user_id')
        ]);
        return redirect('/users')->with('success','User is activated!');
    }

    function donors_page(){
        $donations = collect(DB::select("
        SELECT donation.*, users.name, users.email, users.phone_number, users.user_id
        FROM donation 
        INNER JOIN donor ON donor.donor_id = donation.donor_id
        INNER JOIN users ON users.user_id = donor.user_id
        ORDER BY donation.created_at ASC
    "))->map(function ($donation) {
        $donation->created_at = \Carbon\Carbon::parse($donation->created_at);//Carbon is a library that handles time
        $donation->formatted_date = $donation->created_at->format('d F Y');
        $donation->group_month = $donation->created_at->format('F Y');
        return $donation;
    });
//Map function:
//1-Input: A collection of donation records (as objects).
//2-For each donation: Converts the created_at string into a Carbon date object.Adds two new properties:formatted_date: a nice string like "19 May 2025" group_month: a string like "May 2025" for grouping.
//3-Output: A new collection where each donation object now has these extra fields.
    $grouped_donations = $donations->groupBy('group_month');
    // Get total donation amount per month using raw query
    $monthly_totals = DB::select("
        SELECT DATE_FORMAT(created_at, '%M %Y') AS month_year, SUM(donation_amount) AS total_amount
        FROM donation
        GROUP BY month_year
    ");
    // Convert monthly_totals to associative array [month_year => total_amount]
    $monthly_totals_map = [];
    foreach ($monthly_totals as $row) {
        $monthly_totals_map[$row->month_year] = $row->total_amount;
    }

    // month with highest total
    $highest_donation_month = DB::selectOne("
        SELECT 
            DATE_FORMAT(created_at, '%M %Y') AS month_year,
            SUM(donation_amount) AS total_amount
        FROM donation
        GROUP BY month_year
        ORDER BY total_amount DESC
        LIMIT 1
    ");

    if ($highest_donation_month !== null) {
        $highest_month = $highest_donation_month->month_year;
        $highest_total = $highest_donation_month->total_amount;
    } else {
        $highest_month = null;
        $highest_total = null;
    }
    

    return view('donors', compact('grouped_donations', 'highest_month', 'highest_total','monthly_totals_map'));
}

    public function view_volunteer_schedule_page($user_id){
        $volunteer = DB::selectOne('SELECT * FROM volunteer WHERE user_id = ? ', [$user_id]);

        // If no doctor found, return or abort
        if (!$volunteer) {
            return redirect()->back()->with('error', 'Volunteer not found.');
        }
        $schedules = DB::select('SELECT * FROM volunteer_schedule WHERE is_active=1 AND volunteer_id = ?', [$volunteer->volunteer_id]);
        $tasks = DB::select(
            'SELECT * FROM task
             WHERE task.volunteer_schedule_id IN (
                SELECT volunteer_schedule_id FROM volunteer_schedule WHERE volunteer_id = ?
            )',
            [$volunteer->volunteer_id]
        );
        $user=DB::selectOne('SELECT * FROM users JOIN volunteer ON users.user_id=volunteer.user_id WHERE volunteer.user_id=?',[$user_id]);
        return view('view_volunteer_schedule', compact('volunteer', 'schedules', 'tasks','user'));
    }
    function adoptions_management_page(){
      
        $pending=DB::select('SELECT *,animal.animal_id AS animal_id,animal.animal_name FROM adoption_requests
         JOIN users ON adoption_requests.user_id=users.user_id
         JOIN animal ON adoption_requests.animal_id=animal.animal_id
         WHERE adoption_requests.status="pending" ');
        $aproved=DB::select('SELECT * FROM adoption_requests
          JOIN users ON adoption_requests.user_id=users.user_id 
          JOIN animal ON adoption_requests.animal_id=animal.animal_id
          WHERE adoption_requests.status="approved" ');
        $rejected=DB::select('SELECT * FROM adoption_requests
          JOIN users ON adoption_requests.user_id=users.user_id
          JOIN animal ON adoption_requests.animal_id=animal.animal_id
          WHERE adoption_requests.status="rejected" ');

        return view('adoptions',compact('pending','aproved','rejected'));
    }
    function approve_adoption_page($request_id){
        $adoption_request=DB::selectOne('SELECT *,users.user_id AS user_id,animal.animal_id AS animal_id FROM adoption_requests
         JOIN users ON adoption_requests.user_id=users.user_id
         JOIN animal ON adoption_requests.animal_id=animal.animal_id
         WHERE adoption_requests.id=?',[$request_id]);
        return view('approve_adoption',compact('request_id','adoption_request'));
    }
   function approve_adoption_action($request_id) {
    // 1. Get adoption request info: user ID, email, current role, and animal ID
    $adoption_request = DB::selectOne('
        SELECT users.user_id, users.email, user_role.role_id, animal.animal_id
        FROM adoption_requests
        JOIN users ON adoption_requests.user_id = users.user_id
        JOIN user_role ON users.user_id=user_role.user_id
        JOIN animal ON adoption_requests.animal_id = animal.animal_id
        WHERE adoption_requests.id = ?', [$request_id]);

    // If no request found, redirect back
    if (!$adoption_request) {
        return redirect('/adoptions')->with('error', 'Adoption request not found.');
    }

    $user_id = $adoption_request->user_id;
    $email = $adoption_request->email;
    $animal_id = $adoption_request->animal_id;
    $current_role_id = $adoption_request->role_id;

    // 2. Send email to user to notify about approval (try/catch optional)
    Mail::to($email)->send(new AdoptionRequestStatus('approved'));

    // 3. Get role ID for 'adopter' from role table
    $adopter_role = DB::selectOne('SELECT role_id FROM role WHERE role_name = ?', ['adopter']);
    $adopter_role_id = $adopter_role->role_id;

    // 4. If current role is 14 (normal user), update their role in user_role table
    if ($current_role_id == 14) {
        DB::update('UPDATE user_role SET role_id = ? WHERE user_id = ?', [$adopter_role_id, $user_id]);
    }

    // 5. Check if user already has adopter role; if not, insert it
    $existing_role = DB::selectOne('SELECT * FROM user_role WHERE user_id = ? AND role_id = ?', [$user_id, $adopter_role_id]);
    if (!$existing_role) {
        DB::insert('INSERT INTO user_role (user_id, role_id) VALUES (?, ?)', [$user_id, $adopter_role_id]);
    }

    // 6. Check if user exists in adopter table; if not, insert them
    $adopter_exists = DB::selectOne('SELECT * FROM adopter WHERE user_id = ?', [$user_id]);
    if (!$adopter_exists) {
        DB::insert('INSERT INTO adopter (user_id, previous_adoption_times) VALUES (?, ?)', [$user_id, 0]);
    }

    // 7. Increase user's adoption count
    DB::update('UPDATE adopter SET previous_adoption_times = previous_adoption_times + 1 WHERE user_id = ?', [$user_id]);

    // 8. Update animal record: set status to 'adopted' and mark as adopted
    DB::update('UPDATE animal SET status = ?, is_adopted = ? WHERE animal_id = ?', ['adopted', 1, $animal_id]);

    // 9. Set adoption request status to 'approved'
    DB::update('UPDATE adoption_requests SET status = ? WHERE id = ?', ['approved', $request_id]);

    
    return redirect('/adoptions')->with('success', 'Adoption request approved successfully.');
}


        function reject_adoption_page($request_id){
            return view('reject_adoption_request', compact('request_id'));
        }
        function reject_adoption_action(Request $request, $request_id) {
           $fields=$request->validate([
            'rejection_reason'=>['required']
           ]);
            DB::update('
                UPDATE adoption_requests 
                SET status = ?, rejection_reason = ? 
                WHERE id = ?', ['rejected', $fields['rejection_reason'], $request_id]);
                $user = DB::selectOne('
                SELECT users.email
                FROM adoption_requests
                JOIN users ON adoption_requests.user_id = users.user_id
                WHERE adoption_requests.id = ?', [$request_id]);
            Mail::to($user->email)->send(new AdoptionRequestStatus('rejected', $fields['rejection_reason']));
            return redirect('/adoptions')->with('success', 'Adoption request rejected successfully.');
            }
     function disactivated_doctor_schedule_page($doctor_id){
        $doctor = DB::selectOne('SELECT * FROM doctor WHERE doctor_id = ? ', [$doctor_id]);
    
        // If no doctor found, return or abort
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor not found.');
        }
        $schedules = DB::select('SELECT * FROM doctor_schedule WHERE is_active=0 AND doctor_id = ?', [$doctor->doctor_id]);
        $appointments = DB::select(
            'SELECT appointments.*, animal.animal_name 
             FROM appointments 
             JOIN animal ON appointments.animal_id = animal.animal_id 
             WHERE appointments.doctor_schedule_id IN (
                SELECT doctor_schedule_id FROM doctor_schedule WHERE doctor_id = ?
             )',
            [$doctor->doctor_id]
        );
    
        return view('view_doctor_schedule', compact('doctor', 'schedules', 'appointments'));
    }
        function disactivated_volunteer_schedule_page($volunteer_id){
        $volunteer = DB::selectOne('SELECT * FROM volunteer WHERE volunteer_id = ? ', [$volunteer_id]);

        // If no doctor found
        if (!$volunteer) {
            return redirect()->back()->with('error', 'Volunteer not found.');
        }
        $schedules = DB::select('SELECT * FROM volunteer_schedule WHERE is_active=0 AND volunteer_id = ?', [$volunteer->volunteer_id]);
        $tasks = DB::select(
            'SELECT * FROM task
             WHERE task.volunteer_schedule_id IN (
                SELECT volunteer_schedule_id FROM volunteer_schedule WHERE volunteer_id = ?
            )',
            [$volunteer->volunteer_id]
        );
        $user = DB::selectOne('
        SELECT * FROM users 
        JOIN volunteer ON users.user_id = volunteer.user_id 
        WHERE volunteer.volunteer_id = ?', [$volunteer_id]);
        return view('disactivated_volunteer_schedule', compact('volunteer', 'schedules', 'tasks','user'));
    }
    
}    
