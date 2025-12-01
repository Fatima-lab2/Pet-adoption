<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MedicineController;
  
//SignUp page
Route::get('/signup',[AuthController::class,"signup"]);
Route::post('/register',[AuthController::class,"register"]);
Route::get('/signup', [AuthController::class, 'roles']);
//Login page
Route::get('/login',[AuthController::class,"loginPage"]);
Route::post('/login',[AuthController::class,"login"]);
//Index page
Route::get('/index',[AuthController::class,"indexPage"]);
//Logout
Route::get('/logout',[AuthController::class,"logout"]);
//User page(admin)
Route::get('/users',[UserController::class,"userList"])->middleware(['auth', 'role:admin']);
//Update Users Profile by Admin
Route::get('/update_profile/{user_id}/{role_id}', [AdminController::class, 'retreive_old_data_info'])->name('update_profile')->middleware(['auth', 'role:admin']);
Route::post('/update_profile/{user_id}/{role_id}',[AdminController::class,'update_user_profile'])->middleware(['auth', 'role:admin']);

//Remove a user(admin)
Route::get('/admin_remove_user/{user_id}',[AdminController::class,'remove_user_page'])->middleware(['auth', 'role:admin']);
Route::put('/admin_remove_user/{user_id}',[AdminController::class,'admin_remove_user'])->middleware(['auth', 'role:admin']);
//Add new user(admin)
Route::get('/add_new_user',[AdminController::class,'add_new_user_page']);
Route::post('/add_new_user',[AdminController::class,'add_new_user_action']);
//Manage roles
Route::get('/role_management',[AdminController::class,'new_role_page'])->middleware(['auth', 'role:admin']);
Route::get('/role_management',[AdminController::class,'retreive_roles'])->middleware(['auth', 'role:admin']);
Route::post('/role_management',[AdminController::class,'add_new_role'])->middleware(['auth', 'role:admin']);
//remove role
Route::get('/remove_role/{role_id}',[AdminController::class,'remove_role_page'])->middleware(['auth', 'role:admin']);
Route::put('/remove_role/{role_id}',[AdminController::class,'remove_role_action'])->middleware(['auth', 'role:admin']);
//Edit role description
Route::get('/edit_role_description/{role_id}',[AdminController::class,'edit_role_description_page']);
Route::post('/edit_role_description/{role_id}',[AdminController::class,'edit_role_description_action']);
//Activate Role
Route::get('/disactivated_roles',[AdminController::class,'disactivated_roles_page']);
Route::post('/disactivated_roles/{role_id}',[AdminController::class,'disactivated_roles_action']);

//Assign Role
Route::get('/assign_role/{user_id}',[AdminController::class,'assign_role_page'])->middleware(['auth', 'role:admin']);
Route::put('/assign_role/{user_id}',[AdminController::class,'assign_role_action'])->middleware(['auth', 'role:admin']);
//Doctor Management Part
Route::get('/doctors',[EmployeeController::class,'doctor_page_action'])->middleware(['auth', 'role:employee']);
Route::get('/doctor_schedule/{doctor_id}',[EmployeeController::class,'doctor_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/create_doctor_schedule/{doctor_id}',[EmployeeController::class,'create_doctor_schedule_page'])->middleware(['auth', 'role:employee']);
Route::post('/create_doctor_schedule/{doctor_id}',[EmployeeController::class,'create_doctor_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/create_appointments/{schedule_id}',[EmployeeController::class,'create_appointments_page'])->middleware(['auth', 'role:employee']);
Route::post('/create_appointments/{schedule_id}',[EmployeeController::class,'create_appointments_action'])->middleware(['auth', 'role:employee']);
Route::get('/view_appointments/{doctor_id}/{doctor_schedule_id}',[EmployeeController::class,'view_appointments_page'])->middleware(['auth', 'role:employee']);
Route::get('/update_doctor_schedule/{doctor_id}/{doctor_schedule_id}',[EmployeeController::class,'update_doctor_schedule_page'])->middleware(['auth', 'role:employee']);
Route::post('/update_doctor_schedule/{doctor_id}/{doctor_schedule_id}',[EmployeeController::class,'update_doctor_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/remove_doctor_schedule/{doctor_id}/{doctor_schedule_id}',[EmployeeController::class,'remove_doctor_schedule_page'])->middleware(['auth', 'role:employee']);
Route::post('/remove_doctor_schedule/{doctor_id}/{doctor_schedule_id}',[EmployeeController::class,'remove_doctor_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/update_appointments/{doctor_schedule_id}/{appointment_id}',[EmployeeController::class,'update_appointment_page'])->middleware(['auth', 'role:employee']);
Route::post('/update_appointments/{doctor_schedule_id}/{appointment_id}',[EmployeeController::class,'update_appointment_action'])->middleware(['auth', 'role:employee']);
Route::get('/remove_appointments/{doctor_schedule_id}/{appointment_id}',[EmployeeController::class,'remove_appointments_page'])->middleware(['auth', 'role:employee']);
Route::post('/remove_appointments/{doctor_schedule_id}/{appointment_id}',[EmployeeController::class,'remove_appointments_action'])->middleware(['auth', 'role:employee']);
Route::get('/view_doctor_schedule/{doctor_id}',[AdminController::class,'view_doctor_schedule_page'])->middleware(['auth', 'role:admin']);
Route::get('/doctor_schedule/{doctor_id}', [EmployeeController::class, 'doctor_schedule_action'])->name('doctor_schedule')->middleware(['auth', 'role:employee']);
Route::get('/add_new_appointment/{doctor_id}/{schedule_id}',[EmployeeController::class,'add_new_appointment_page']);
Route::post('/add_new_appointment/{doctor_id}/{schedule_id}',[EmployeeController::class,'add_new_appointment_action']);
//
Route::get('/disactivated_users',[AdminController::class,'disactivated_users_page'])->middleware(['auth', 'role:admin']);
Route::post('/disactivated_users',[AdminController::class,'disactivated_users_action'])->middleware(['auth', 'role:admin']);
//Donations
Route::get('/make_donation',[UserController::class,'make_donation_page']);
Route::post('/make_donation',[UserController::class,'make_donation_action']);
Route::get('/donors',[AdminController::class,'donors_page'])->middleware(['auth', 'role:admin']);

//Medicines
Route::get('/medicines', [MedicineController::class, 'medicine_page'])->middleware(['auth', 'role:admin|employee']);
Route::get('/add_new_medicine',[MedicineController::class,'add_new_medicine_page'])->middleware(['auth', 'role:employee']);
Route::post('/add_new_medicine',[MedicineController::class,'add_new_medicine_action'])->middleware(['auth', 'role:employee']);
Route::get('/update_medicine/{medicine_id}',[MedicineController::class,'update_medicine_page'])->middleware(['auth', 'role:employee']);
Route::post('/update_medicine/{medicine_id}',[MedicineController::class,'update_medicine_action'])->middleware(['auth', 'role:employee']);
Route::get('/remove_medicine/{medicine_id}',[MedicineController::class,'remove_medicine_page'])->middleware(['auth', 'role:employee']);
Route::post('/remove_medicine/{medicine_id}',[MedicineController::class,'remove_medicine_action'])->middleware(['auth', 'role:employee']);
Route::get('/add_new_category',[MedicineController::class,'add_new_category_page'])->middleware(['auth', 'role:employee']);
Route::post('/add_new_category',[MedicineController::class,'add_new_category_action'])->middleware(['auth', 'role:employee']);
Route::get('/disactivated_medicines',[MedicineController::class,'disactivated_medicines_page'])->middleware(['auth', 'role:employee|admin']);
Route::post('/disactivated_medicines/{medicine_id}',[MedicineController::class,'disactivated_medicines_action'])->middleware(['auth', 'role:employee|admin']);
Route::get('/search_medicine',[MedicineController::class,'search_medicine'])->middleware(['auth', 'role:employee|admin']);
//Manage Volunteer 
Route::get('/volunteers',[EmployeeController::class,'volunteers_page'])->middleware(['auth', 'role:employee']);
Route::get('/volunteer_schedule/{volunteer_id}',[EmployeeController::class,'volunteer_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/create_volunteer_schedule/{volunteer_id}',[EmployeeController::class,'create_volunteer_schedule_page'])->middleware(['auth', 'role:employee']);
Route::post('/create_volunteer_schedule/{volunteer_id}',[EmployeeController::class,'create_volunteer_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/create_task/{schedule_id}',[EmployeeController::class,'create_task_page'])->middleware(['auth', 'role:employee']);
Route::post('/create_task/{schedule_id}',[EmployeeController::class,'create_task_action'])->middleware(['auth', 'role:employee']);
Route::get('/view_tasks/{volunteer_id}/{volunteer_schedule_id}',[EmployeeController::class,'view_tasks_page'])->middleware(['auth', 'role:employee']);
Route::get('/update_volunteer_schedule/{volunteer_id}/{volunteer_schedule_id}',[EmployeeController::class,'update_volunteer_schedule_page'])->middleware(['auth', 'role:employee']);
Route::post('/update_volunteer_schedule/{volunteer_id}/{schedule_id}', [EmployeeController::class, 'update_volunteer_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/remove_volunteer_schedule/{volunteer_id}/{volunteer_schedule_id}',[EmployeeController::class,'remove_volunteer_schedule_page'])->middleware(['auth', 'role:employee']);
Route::post('/remove_volunteer_schedule/{volunteer_id}/{volunteer_schedule_id}',[EmployeeController::class,'remove_volunteer_schedule_action'])->middleware(['auth', 'role:employee']);
Route::get('/update_task/{volunteer_schedule_id}/{task_id}',[EmployeeController::class,'update_task_page'])->middleware(['auth', 'role:employee']);
Route::post('/update_task/{volunteer_schedule_id}/{task_id}',[EmployeeController::class,'update_task_action'])->middleware(['auth', 'role:employee']);
Route::get('/remove_task/{volunteer_schedule_id}/{task_id}',[EmployeeController::class,'remove_task_page'])->middleware(['auth', 'role:employee']);
Route::post('/remove_task/{volunteer_schedule_id}/{task_id}',[EmployeeController::class,'remove_task_action'])->middleware(['auth', 'role:employee']);
Route::get('/disactivated_volunteers',[EmployeeController::class,'disactivated_volunteers_page'])->middleware(['auth', 'role:employee']);
Route::post('/disactivated_volunteers',[EmployeeController::class,'disactivated_volunteers_action'])->middleware(['auth', 'role:employee']);
Route::get('/add_new_task/{volunteer_id}/{schedule_id}',[EmployeeController::class,'add_new_task_page'])->middleware(['auth', 'role:employee']);
Route::post('/add_new_task/{volunteer_id}/{schedule_id}',[EmployeeController::class,'add_new_task_action'])->middleware(['auth', 'role:employee']);
Route::get('/add_new_volunteer',[EmployeeController::class,'add_volunteer_page'])->middleware(['auth', 'role:employee']);
Route::post('/add_new_volunteer',[EmployeeController::class,'add_volunteer_action'])->middleware(['auth', 'role:employee']);
Route::get('/view_volunteer_schedule/{user_id}',[AdminController::class,'view_volunteer_schedule_page'])->middleware(['auth', 'role:admin']);
//Index Page
Route::get('/index',[UserController::class,'index_page']);
//Adoption Process
Route::get('/adopte_animal/{animal_id}',[UserController::class,'adopte_animal_page']);
Route::post('/adopte_animal/{animal_id}',[UserController::class,'adopte_animal_action']);
//Adoptions Management
Route::get('/adoptions',[AdminController::class,'adoptions_management_page'])->middleware(['auth', 'role:admin']);
//Approve adoption
Route::get('/approve_adoption/{request_id}',[AdminController::class,'approve_adoption_page'])->middleware(['auth', 'role:admin']);
Route::post('/approve_adoption/{request_id}',[AdminController::class,'approve_adoption_action'])->middleware(['auth', 'role:admin']);
//Reject Request
Route::get('/reject_adoption_request/{request_id}',[AdminController::class,'reject_adoption_page'])->middleware(['auth', 'role:admin']);
Route::post('/reject_adoption_request/{request_id}',[AdminController::class,'reject_adoption_action'])->middleware(['auth', 'role:admin']);
Route::get('/disactivated_doctor_schedule/{doctor_id}',[AdminController::class,'disactivated_doctor_schedule_page'])->middleware(['auth', 'role:admin']);
Route::get('/disactivated_volunteer_schedule/{volunteer_id}',[AdminController::class,'disactivated_volunteer_schedule_page'])->middleware(['auth', 'role:admin']);
//Doctor Contract
Route::get('/doctor_contracts/{doctor_id}',[EmployeeController::class,'doctor_contracts_page']);
Route::get('/add_doctor_contract/{doctor_id}',[EmployeeController::class,'add_doctor_contract_page']);
Route::post('/add_doctor_contract/{doctor_id}',[EmployeeController::class,'add_doctor_contract_action']);
Route::get('/doctor_terminate_contract/{contract_id}',[EmployeeController::class,'doctor_terminate_contract_page']);
Route::post('/doctor_terminate_contract/{contract_id}',[EmployeeController::class,'doctor_terminate_contract_action']);
//Employee Contract
Route::get('/employee_contracts/{employee_id}',[EmployeeController::class,'employee_contracts_page']);
Route::get('/add_employee_contract/{employee_id}',[EmployeeController::class,'add_employee_contract_page']);
Route::post('/add_employee_contract/{employee_id}',[EmployeeController::class,'add_employee_contract_action']);
Route::get('/employee_terminate_contract/{contract_id}',[EmployeeController::class,'employee_terminate_contract_page']);
Route::post('/employee_terminate_contract/{contract_id}',[EmployeeController::class,'employee_terminate_contract_action']);
//Expense 
Route::get('/expenses',[EmployeeController::class,'expense_page']);
Route::get('/add_expense',[EmployeeController::class,'add_expense_page']);
Route::post('/add_expense',[EmployeeController::class,'add_expense_action']);
//Welcome Page
Route::get('/', function () {
    $animals =DB::select('SELECT * FROM animal WHERE is_adopted=0  AND status="available" LIMIT 4');
        return view('welcome',compact('animals'));
});
//Fatimas' route
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FoodController;

// AI Consultation Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/ai_consultation/{animal_id}', [AiController::class, 'showConsultation'])->name('ai_consultation');
    Route::post('/ai_handle', [AiController::class, 'handleConsultation'])->name('ai_handle');
});

 
Route::middleware(['auth'])->group(function () {
    // Doctor routes
        Route::get('/doctor_dashboard', [DoctorController::class, 'dashboard'])->name('doctor_dashboard');
        Route::get('/health_record/{animal_id}', [DoctorController::class, 'healthRecord'])->name('health_record');
        Route::get('/create_health_record/{animal_id}', [DoctorController::class, 'createHealthRecord'])->name('create_health_record');
        Route::post('/store_health_record/{animal_id}', [DoctorController::class, 'storeHealthRecord'])->name('store_health_record');
        Route::get('/create_checkup/{animal_id}', [DoctorController::class, 'createCheckup'])->name('create_checkup');
        Route::post('/store_checkup/{animal_id}', [DoctorController::class, 'storeCheckup'])->name('store_checkup');
        Route::get('/edit_health-record/{health_record_id}', [DoctorController::class, 'editHealthRecord'])->name('edit_health_record');
        Route::put('/update_health-record/{health_record_id}', [DoctorController::class, 'updateHealthRecord'])->name('update_health_record');

        Route::get('/search_animals', [DoctorController::class, 'searchAnimals'])->name('search_animals');
        Route::get('/view_animal/{id}', [DoctorController::class, 'viewAnimal'])->name('view_animal');
        Route::get('/view_medicines_vaccinations', [DoctorController::class, 'viewMedicinesVaccinations'])->name('view_medicines_vaccinations');
        Route::get('/view_schedule', [DoctorController::class, 'viewSchedule'])->name('view_schedule');

        });
        

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile_view', [ProfileController::class, 'viewProfile'])->name('profile_view');
    Route::get('/profile_edit', [ProfileController::class, 'editProfile'])->name('profile_edit');
    Route::post('/profile_update', [ProfileController::class, 'updateProfile'])->name('profile_update');
    Route::get('/change-password', [ProfileController::class, 'changePasswordForm'])->name('change_password');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('password_update');
});

// Volunteer Consultation Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/room', [VolunteerController::class, 'room'])->name('room');
    Route::get('/schedule_volunteer', [VolunteerController::class, 'viewSchedule'])->name('schedule_volunteer');
    Route::get('/food_volunteer', [VolunteerController::class, 'animalFood'])->name('food_volunteer');
});




Route::middleware(['auth'])->group(function () {
    // Animal management routes
        Route::get('/manage_animals', [EmployeController::class, 'manageAnimals'])->name('manage_animals');
        Route::get('/add_animal', [EmployeController::class, 'addAnimalForm'])->name('add_animal');
        Route::post('/store_animal', [EmployeController::class, 'addAnimal'])->name('store_animal');
        Route::get('/edit_animal/{id}', [EmployeController::class, 'editAnimalForm'])->name('edit_animal');
        Route::post('/update_animal/{id}', [EmployeController::class, 'editAnimal'])->name('update_animal');
});


Route::middleware(['auth'])->group(function () {
// Food Management
    Route::get('/manage_food', [FoodController::class, 'manageFood'])->name('manage_food');
    Route::get('/add_food', [FoodController::class, 'addFood'])->name('add_food');
    Route::post('/add_food', [FoodController::class, 'addFood']);
    Route::get('/edit_food/{id}', [FoodController::class, 'editFood'])->name('edit_food');
    Route::post('/edit_food/{id}', [FoodController::class, 'editFood']);

    // Animal Feeding
    Route::get('/animal_food/{animal_id?}', [FoodController::class, 'animalFood'])->name('animal_food');
    Route::get('/animal_food/{animal_id}', [FoodController::class, 'animalFood']);
    Route::get('/create_feeding/{animal_id}', [FoodController::class, 'createFeeding'])->name('create_feeding');
    Route::post('/create_feeding/{animal_id}', [FoodController::class, 'createFeeding']);
    Route::get('/edit_feeding/{schedule_id}/{animal_id}', [FoodController::class, 'editFeeding'])->name('edit_feeding');
    Route::post('/edit_feeding/{schedule_id}/{animal_id}', [FoodController::class, 'editFeeding']);
    Route::post('/add_food_to_schedule', [FoodController::class, 'addFoodToSchedule'])->name('add_food_to_schedule');
    Route::get('/remove_food_from_schedule/{schedule_id}/{food_id}', [FoodController::class, 'removeFoodFromSchedule'])->name('remove_food_from_schedule');

    // Room Management
    Route::get('/edit_room/{id}', [FoodController::class, 'editRoom'])->name('edit_room');
    Route::post('/edit_room/{id}', [FoodController::class, 'editRoom']);
});
