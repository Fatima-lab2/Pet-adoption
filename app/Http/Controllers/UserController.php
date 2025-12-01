<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function usersPage(){
        return view ("users") ; 
    }
function userList() {
    $doctors = DB::select("
        SELECT users.*, doctor.specialization, doctor.experience_year, doctor.doctor_id,user_role.role_id
        FROM users
        JOIN user_role ON users.user_id = user_role.user_id
        JOIN doctor ON users.user_id = doctor.user_id
        WHERE user_role.role_id = (
            SELECT role_id FROM role WHERE LOWER(role_name) = 'doctor'
        ) AND user_role.is_active=1 
    ");

    $donors = DB::select("
        SELECT users.*, donor.previous_donation_times,user_role.role_id
        FROM users
        JOIN user_role ON users.user_id = user_role.user_id
        JOIN donor ON users.user_id = donor.user_id
        WHERE user_role.role_id = (
            SELECT role_id FROM role WHERE LOWER(role_name) = 'donor'
        )  AND user_role.is_active=1 
    ");

    $adopters = DB::select("
        SELECT users.*, adopter.previous_adoption_times,user_role.role_id
        FROM users
        JOIN user_role ON users.user_id = user_role.user_id
        JOIN adopter ON users.user_id = adopter.user_id
        WHERE user_role.role_id = (
            SELECT role_id FROM role WHERE LOWER(role_name) = 'adopter'
        )  AND user_role.is_active=1 
    ");

    $employees = DB::select("
        SELECT users.*, employee.responsibility, employee.type_of_work, employee.employee_id,user_role.role_id
        FROM users
        JOIN user_role ON users.user_id = user_role.user_id
        JOIN employee ON users.user_id = employee.user_id
        WHERE user_role.role_id = (
            SELECT role_id FROM role WHERE LOWER(role_name) = 'employee'
        )  AND user_role.is_active=1 
    ");

    $volunteers = DB::select("
        SELECT users.*, volunteer.responsibility,user_role.role_id
        FROM users
        JOIN user_role ON users.user_id = user_role.user_id
        JOIN volunteer ON users.user_id = volunteer.user_id
        WHERE user_role.role_id = (
            SELECT role_id FROM role WHERE LOWER(role_name) = 'volunteer'
        )  AND user_role.is_active=1 
    ");

    $unassignedUsers = DB::select("
        SELECT * ,user_role.role_id
        FROM users
        JOIN user_role ON users.user_id = user_role.user_id
        WHERE user_role.role_id = (
            SELECT role_id FROM role WHERE LOWER(role_name) = 'users'
        )  AND user_role.is_active=1 
    ");

    $admin_roles = DB::select("
        SELECT users.*, role.role_name,user_role.role_id
        FROM users
        JOIN user_role ON users.user_id = user_role.user_id
        JOIN role ON user_role.role_id = role.role_id
        WHERE role.added_by_admin=1  AND user_role.is_active=1 
    ");

    return view('users', compact('doctors', 'donors', 'adopters', 'employees', 'volunteers', 'unassignedUsers', 'admin_roles'));
}

    function make_donation_page(){
        return view('make_donation');
    }
function make_donation_action(Request $request)
{
    $fields = $request->validate([
        'payment_method' => ['required', 'string'],
        'donation_amount' => ['required', 'numeric'],
        'transaction_reference' => ['required', 'string', 'size:4'],
        'message' => ['string', 'nullable', 'max:500'],
    ]);

    $user_id = Auth::id();

    // Get current role_id from users table
    $user = DB::selectOne('SELECT role_id FROM user_role WHERE user_id = ?', [$user_id]);

    // If role_id is 14 (normal user), update to 5 (donor)
    if ($user && $user->role_id == 14) {
        DB::update('UPDATE user_role SET role_id = ? WHERE user_id = ?', [5, $user_id]);
    }

    // Add donor role (5) to user_role if not already present
    $exists = DB::selectOne('SELECT * FROM user_role WHERE user_id = ? AND role_id = ?', [$user_id, 5]);
    if (!$exists) {
        DB::insert('INSERT INTO user_role (user_id, role_id) VALUES (?, ?)', [$user_id, 5]);
    }

    // Check if the user has a donor profile
    $donor = DB::selectOne('SELECT * FROM donor WHERE user_id = ?', [$user_id]);

    if (!$donor) {
        // Create donor profile
        DB::insert('INSERT INTO donor (user_id) VALUES (?)', [$user_id]);

        // Re-fetch donor
        $donor = DB::selectOne('SELECT * FROM donor WHERE user_id = ?', [$user_id]);
    }

    // Insert the donation
    DB::insert('INSERT INTO donation (payment_method, donation_amount, transaction_reference, message, donor_id, created_at)
                VALUES (?, ?, ?, ?, ?, ?)', [
        $fields['payment_method'],
        $fields['donation_amount'],
        $fields['transaction_reference'],
        $fields['message'],
        $donor->donor_id,
        now()
    ]);

    // Update donation count
    DB::update('UPDATE donor SET previous_donation_times = previous_donation_times + 1 WHERE user_id = ?', [$user_id]);

    return redirect('/index')->with('success', 'Donation is sent successfully!');
}

  function index_page(Request $request){
    $search = $request->input('search');
    
    $availableQuery = 'SELECT *, room.name AS room_name 
        FROM animal JOIN room ON animal.room_id = room.room_id 
        WHERE animal.status = "available" AND is_adopted=0';
    
    $adoptedQuery = 'SELECT *, room.name AS room_name 
        FROM animal JOIN room ON animal.room_id = room.room_id 
        WHERE animal.status = "adopted" AND is_adopted=1';
    
    $sickQuery = 'SELECT *, room.name AS room_name 
        FROM animal JOIN room ON animal.room_id = room.room_id 
        WHERE animal.status = "under_medical_care" AND is_adopted=0';
    
    // Add search condition if search term exists
    if ($search) {
        $searchTerm = "%{$search}%";
        $availableQuery .= ' AND (animal.animal_name LIKE ? OR animal.breed LIKE ? OR animal.type LIKE ? OR animal.color LIKE ?)';
        $adoptedQuery .= ' AND (animal.animal_name LIKE ? OR animal.breed LIKE ? OR animal.type LIKE ? OR animal.color LIKE ?)';
        $sickQuery .= ' AND (animal.animal_name LIKE ? OR animal.breed LIKE ? OR animal.type LIKE ? OR animal.color LIKE ?)';
        
        $available_animals = DB::select($availableQuery, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $adopted_animals = DB::select($adoptedQuery, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $sick_animals = DB::select($sickQuery, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    } else {
        $available_animals = DB::select($availableQuery);
        $adopted_animals = DB::select($adoptedQuery);
        $sick_animals = DB::select($sickQuery);
    }
    
    return view('index', compact('available_animals', 'adopted_animals', 'sick_animals', 'search'));
} 

    function adopte_animal_page($animal_id){

        if (!Auth::check()) {
            return redirect('/login')->with('error','You must be logged in to be able to send adoption request :)');
        }

        $available_animal = DB::selectOne('
        SELECT *, room.name AS room_name 
        FROM animal 
        LEFT JOIN room ON animal.room_id = room.room_id 
        WHERE animal.status = "Available" AND animal.animal_id = ?', [$animal_id]);
        return view('adopte_animal', [
            'available_animal' => $available_animal,
            'animal_id' => $animal_id,
        ]);    }

    function adopte_animal_action(Request $request){
        $fields=$request->validate([
        'reason' => ['required', 'string'],
        'other_pets' => ['required', 'in:yes,no'],
        'has_children' => ['required', 'in:yes,no'],
        'home_type' => ['required', 'in:apartment,house,farm'],
        'experience' => ['nullable', 'string'],
        'animal_id' => ['required', 'exists:animal,animal_id']
        ]);
         // Get the ID of the logged-in user
        $user_id = Auth::id();
        DB::insert('INSERT INTO adoption_requests(reason,other_pets,has_children,home_type,experience,animal_id,user_id) VALUES(?,?,?,?,?,?,?)',[
            $fields['reason'],
            $fields['other_pets'],
            $fields['has_children'],
            $fields['home_type'],
            $fields['experience'],
            $fields['animal_id'],
            $user_id
        ]);
        return redirect('/index')->with('success', 'Request sent successfully. We will respond as soon as possible.');
    }
    function welcome_page(){
        $animals =DB::select('SELECT * FROM animal WHERE is_adopted=0  AND status="available" LIMIT 4');
        return view('welcome',compact('animals'));
    }
    
    
    
}
