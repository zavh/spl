<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
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
        return view('departments.index')->with('departments', $dt);
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
        $departments = Department::all();
        return view('departments.create',['departments'=>$departments]);
    }


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
        $config = DB::table('appdeafultconfig')->where('name', 'default')->get()->first();
        $department->apppermission = $config->config;
        $department->dirname = 'default';

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

    public function show(Department $department)
    {
        //
    }

    public function edit($id)
    {
        $department = Department::find($id);
        $departments = Department::all();
        return view('departments.edit',['department'=>$department, 'departments'=>$departments]);
    }

    public function update(Request $request, $id)
    {
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
        else
            $department->path = json_encode(array(0));

        $department->save();
        return redirect('/departments')->with('success', 'Department Updated');
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        if($department->delete())
            return redirect('/departments')->with('success', 'Designation Deleted');
        return back()->withInput()->with('error', 'Designation could not be deleted');
    }
}
