<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $departments = Department::all();
        return view('departments.index')->with('departments', $departments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $messages = [
            'name.required' => 'Please enter the name',
            'name.min' => 'Name must be minimum 2 characters',
            'name.max' => 'Name cannot be more than 191 characters',
            'name.unique' => 'This name has already been taken'
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:191|unique:departments,name'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        // dd($request);
        $department = new Department;
        $department->name = $request->input('name');
        
        $department->save();
        return redirect('/departments')->with('success', 'Department Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $department = Department::find($id);
        // dd($department);
        return view('departments.edit',['department'=>$department]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $messages = [
            'name.required' => 'Please enter the task name',
            'name.min' => 'Task name must be minimum 2 characters',
            'name.max' => 'Task name cannot be more than 191 characters'
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:191s'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $department = Department::find($id);
        $department->name = $request->input('name');
        
        $department->save();
        return redirect('/departments')->with('success', 'Department Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $department = Department::find($id);
        // dd($department);
        $department->delete();
        return redirect('/departments')->with('success', 'Department Deleted');
    }
}
