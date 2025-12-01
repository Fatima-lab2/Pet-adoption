<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    //
    function medicine_page(){
        $antibiotics=DB::select('SELECT * FROM medicine JOIN medicine_category ON medicine.category_id=medicine_category.category_id WHERE is_available=1 AND medicine_category.category_id=1 AND medicine_category.added_by_employee=0');
        $vaccines=DB::select('SELECT * FROM medicine JOIN medicine_category ON medicine.category_id=medicine_category.category_id WHERE is_available=1 AND medicine_category.category_id=2 AND medicine_category.added_by_employee=0');
        $pain_relievers=DB::select('SELECT * FROM medicine JOIN medicine_category ON medicine.category_id=medicine_category.category_id WHERE is_available=1 AND medicine_category.category_id=3 AND medicine_category.added_by_employee=0');
        $vitamins=DB::select('SELECT * FROM medicine JOIN medicine_category ON medicine.category_id=medicine_category.category_id WHERE is_available=1 AND medicine_category.category_id=4 AND medicine_category.added_by_employee=0');
        $dermatologicals=DB::select('SELECT * FROM medicine JOIN medicine_category ON medicine.category_id=medicine_category.category_id WHERE is_available=1 AND medicine_category.category_id=5 AND medicine_category.added_by_employee=0');
        $others=DB::select('SELECT * FROM medicine JOIN medicine_category ON medicine.category_id=medicine_category.category_id WHERE is_available=1 AND medicine_category.added_by_employee=1');
        return view('medicines',compact('antibiotics','vaccines','pain_relievers','vitamins','dermatologicals','others'));
    }
    function add_new_medicine_page(){
        $categories=DB::select('SELECT * FROM medicine_category');
       return view('add_new_medicine',compact('categories'));
    }
    function add_new_medicine_action(Request $request){
        $fields=$request->validate([
            'picture'=>['nullable','image', 'max:2048'],
            'name'=>['required','string'],
            'description' =>['required','string'],
            'details' =>['required','string'],
            'quantity_in_stock'=>['required','numeric', 'min:0'],
            'price'=>['required','numeric','min:0'],
            'expire_date'=>['required','date'],
            'category_id' => ['required', 'exists:medicine_category,category_id']
        ]);
        if ($request->hasFile('picture')) {
            $imagePath = $request->file('picture')->store('medicines', 'public');
            $imagePath = 'storage/' . $imagePath;
        } else {
            $imagePath = 'images/medicine.png';
        }
        DB::insert('INSERT INTO medicine(picture,name,description,details,quantity_in_stock,price,expire_date,category_id) VALUES (?,?,?,?,?,?,?,?)',
        [$imagePath,
        $fields['name'],
        $fields['description'],
        $fields['details'],
        $fields['quantity_in_stock'],
        $fields['price'],
        $fields['expire_date'],
        $fields['category_id']
        ]);
    
    return redirect('/medicines')->with('success','Medicine is successfuly added!');
    }

    function update_medicine_page($medicine_id){

        $medicine=DB::selectOne('SELECT * FROM medicine JOIN medicine_category ON medicine.category_id=medicine_category.category_id WHERE medicine.medicine_id=?',[$medicine_id]);
        $categories=DB::select('SELECT * FROM medicine_category');
        return view('update_medicine',compact('medicine','categories'));
    }
    function update_medicine_action(Request $request,$medicine_id){
        $fields=$request->validate([
            'picture' => ['nullable', 'image', 'max:2048'],
            'name'=>['required','string'],
            'description'=>['required','string'],
            'details'=>['required','string'],
            'quantity_in_stock'=>['required','numeric','min:0'],
            'expire_date'=>['required','date'],
            'price'=>['required','numeric','min:0'],
            'is_available' => ['required', 'boolean'],
            'category_id' => ['required', 'exists:medicine_category,category_id'],
        ]);
        $old_picture = DB::selectOne('SELECT picture FROM medicine WHERE medicine_id = ?', [$medicine_id]);

        if ($request->hasFile('picture')) {
            $imagePath = $request->file('picture')->store('medicines', 'public');
            $fields['picture'] = $imagePath;
        } else {
            $fields['picture'] = $old_picture ? $old_picture->picture : 'images/medicine.png'; 
        }
        DB::update('UPDATE medicine SET picture=? ,name=?,description=?,details=?,quantity_in_stock=?,expire_date = ?,price=?,is_available=?,category_id=? WHERE medicine_id=?',[
            $fields['picture'],
            $fields['name'],
            $fields['description'],
            $fields['details'],
            $fields['quantity_in_stock'],
            $fields['expire_date'],
            $fields['price'],
            $fields['is_available'],
            $fields['category_id'],
            $medicine_id
    ]);
    return redirect('/medicines')->with('success','Medicine Updated successfuly');
    }
    function remove_medicine_page($medicine_id){
        $medicine=DB::selectOne('SELECT * FROM medicine WHERE medicine_id= ?',[$medicine_id]);
        return view('remove_medicine',compact('medicine'));
    }
    function remove_medicine_action($medicine_id){
        DB::update('UPDATE medicine SET is_available=0 WHERE medicine_id=?',[$medicine_id]);
        return redirect('/medicines')->with('success', 'Medicine removed successfully.');
    }
    function add_new_category_page(){
        return view('add_new_category');
    }
function add_new_category_action(Request $request){
    $fields = $request->validate([
        'category_name' => ['required', 'string'],
        'description' => ['required', 'string'],
    ]);

    // Check if the category already exists
    $existing = DB::select('SELECT * FROM medicine_category WHERE category_name = ?', [$fields['category_name']]);

    if ($existing) {
        return redirect('/medicines')->with('error', 'Category already exists.');
    }

    // Insert the new category
    $added_by_employee = 1;
    DB::insert(
        'INSERT INTO medicine_category (category_name, description, added_by_employee) VALUES (?, ?, ?)',
        [$fields['category_name'], $fields['description'], $added_by_employee]
    );

    return redirect('/medicines')->with('success', 'Category is added.');
}

    function disactivated_medicines_page(){
        $disactivated_medicines=DB::select('SELECT * FROM medicine WHERE is_available=0');
        return view('disactivated_medicines',compact('disactivated_medicines'));
    }
    function disactivated_medicines_action($medicine_id){
        DB::update('UPDATE medicine SET is_available=1 WHERE medicine_id=?',[$medicine_id]);
        return redirect('/medicines')->with('success','Medicine is now available.');
    }
    function search_medicine(Request $request){
        //Get the searched parameters
        $query = $request->query('query');

        $medicines = DB::select('SELECT * FROM medicine WHERE name LIKE ? OR description LIKE ?',
        ['%' . $query . '%', '%' . $query . '%']);

        return view('search_medicine', compact('query','medicines'));
    }
}