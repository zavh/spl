<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::User()->role->role_name == 'admin'){
            $departments = Department::all();
            $dt = array();
            foreach($departments as $department){
                $node['id'] = $department->id;
                $node['name'] = $department->name;
                $node['user'] = count($department->users);
                $node['child'] = null;
                if($department->parent_id == 0){
                    $dt[$department->id] = $node;
                }
                else{
                    $path = json_decode($department->path, true);
                    $path = array_slice($path, 1);
                    $this::traverseIn($path, $dt[$path[0]], $node);
                }
            }
        //    dd($dt);
            return view('departments.index')->with('departments', $dt);
        }
        else abort(404);
    }

    private function traverseIn($path, &$dt, $node){
        if(count($path)==1){
            $dt['child'][$node['id']] = $node;
        }
        else{
            $path = array_slice($path, 1);
            $pid = $path[0];
            $this::traverseIn($path, $dt['child'][$pid], $node);
        }
    }
    public function create()
    {
        if(Auth::User()->role->role_name == 'admin'){
            $departments = Department::all();
            return view('departments.create',['departments'=>$departments]);
        }
        else abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:191|unique:departments,name'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $department = new Department;
        $department->name = $request->input('name');
        $department->parent_id = $request->input('parent_id');
        if($department->parent_id != 0){
            $pd = Department::find($department->parent_id); //parent department
            $path = json_decode($pd->path, true);
            $path[count($path)] = $department->parent_id;
            $department->path = json_encode($path);
        }
        else {
            $department->path = json_encode(array(0));
        }
        
        $department->save();
        return redirect('/departments')->with('success', 'Department Created');
    }

    private function findpath($id){
        $department = Department::find($id);
    }
    public function show(Department $department)
    {
        //
    }

    public function edit($id)
    {
        if(Auth::User()->role->role_name == 'admin'){
            $department = Department::find($id);
            $departments = Department::all();
            return view('departments.edit',['department'=>$department, 'departments'=>$departments]);
        }
        else abort(404);
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
        if(Auth::User()->role_id == 1){
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
          $department->parent_id = $request->input('parent_id');
          if($department->parent_id != 0){
            $pd = Department::find($department->parent_id); //parent department
            $path = json_decode($pd->path, true);
            $path[count($path)] = $department->parent_id;
            $department->path = json_encode($path);
        }
        else {
            $department->path = json_encode(array(0));
        }

          $department->save();
          return redirect('/departments')->with('success', 'Department Updated');
        }
        else abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        // dd($department);
        if($department->delete())
        {
            return redirect('/departments')->with('success', 'Designation Deleted');
        }
        return back()->withInput()->with('error', 'Designation could not be deleted');
    }
}
