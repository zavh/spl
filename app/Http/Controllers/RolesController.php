<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $assignments = Role::all();
        return view('roles.index')->with('assignments', $assignments);
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        if(Auth::check()){
            $messages = [
                'role_name.required' => 'Please enter the role name',
                'role_name.min' => 'role name must be minimum 2 characters',
                'role_name.max' => 'role name cannot be more than 191 characters',
                'role_name.unique' =>'role name has already been taken',

                'role_description.required' => 'Please enter the email',
                'role_description.max' => 'role description cannot be more than 3000 characters'
            ];

            $validator = Validator::make($request->all(), [
                'role_name' =>'required|min:2|max:191|unique:roles,role_name',
                'role_description'=>'required|max:3000'
            ],$messages);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            $role = new role;
            $role->role_name = $request->input('role_name');
            $role->role_description =  $request->input('role_description');

            $role->save();

            return redirect('/roles')->with('success', 'Role Updated');
        }
    }

    public function show($id)
    {
        $assignment = Role::find($id);
        return view('roles.show')->with('assignment',$assignment);
    }

    public function edit($id)
    {
        $roles = Role::find($id);
        return view('roles.edit')->with('roles', $roles);
    }


    public function update(Request $request, $id)
    {
        if(Auth::check()){
            $messages = [
                'role_name.required' => 'Please enter the role name',
                'role_name.min' => 'role name must be minimum 2 characters',
                'role_name.max' => 'role name cannot be more than 191 characters',

                'role_description.required' => 'Please enter the email',
                'role_description.max' => 'role description cannot be more than 3000 characters'
            ];

            $validator = Validator::make($request->all(), [
                'role_name' =>'required|min:2|max:191',
                'role_description'=>'required|max:3000'
            ],$messages);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
            }
            $role = Role::find($id);
            $role->role_name = $request->input('role_name');
            $role->role_description =  $request->input('role_description');

            $role->save();

            return redirect('/roles')->with('success', 'Role Updated');
        }
    }

    public function destroy(Role $role)
    {
        $findRole = Role::find($role->id);

        if($findRole->delete()){
            return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
        }

        return back()->withInput()->with('error', 'Role could not be deleted');
    }
}
