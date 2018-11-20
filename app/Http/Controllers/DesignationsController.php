<?php

namespace App\Http\Controllers;

use App\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $designations = Designation::all();
        return view('designations.index')->with('designations', $designations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('designations.create');
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
            'name' => 'required|min:2|max:191|unique:designations,name'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $designation = new Designation;
        $designation->name = $request->input('name');
        
        $designation->save();
        return redirect('/designations')->with('success', 'designation Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $designation = Designation::find($id);
        // dd($department);
        return view('designations.edit',['designation'=>$designation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //
        $messages = [
            'name.required' => 'Please enter the task name',
            'name.min' => 'Task name must be minimum 2 characters',
            'name.max' => 'Task name cannot be more than 191 characters'
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:191'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $department = Designation::find($id);
        $department->name = $request->input('name');
        
        $department->save();
        return redirect('/designations')->with('success', 'designations Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Designation  $designation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $designation = Designation::find($id);
        // dd($department);
        if($designation->delete())
        {
            return redirect('/designations')->with('success', 'Designation Deleted');
        }
        return back()->withInput()->with('error', 'Designation could not be deleted');
    }
}
