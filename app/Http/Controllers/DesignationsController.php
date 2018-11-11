<?php

namespace App\Http\Controllers;

use App\Designation;
use Illuminate\Http\Request;

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
        $designation->delete();
        return redirect('/designations')->with('success', 'Department Deleted');
    }
}
