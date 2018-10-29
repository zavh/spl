<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('roles.index', ['roles'=>$roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
                //$abc = back()->withErrors($validator)->withInput();
                //dd($abc);
            }
            $role = new role;
            $role->role_name = $request->input('role_name');
            $role->role_description =  $request->input('role_description');

            $role->save();

            return redirect('/roles')->with('success', 'Role Updated');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $role = Role::find($id);
        return view('roles.show')->with('role',$role);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $roles = Role::find($id);
        return view('roles.edit')->with('roles', $roles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if(Auth::check()){
            $messages = [
                'role_name.required' => 'Please enter the role name',
                'role_name.min' => 'role name must be minimum 2 characters',
                'role_name.max' => 'role name cannot be more than 191 characters',
                //'role_name.unique' =>'role name has already been taken',

                'role_description.required' => 'Please enter the email',
                'role_description.max' => 'role description cannot be more than 3000 characters'
            ];

            $validator = Validator::make($request->all(), [
                'role_name' =>'required|min:2|max:191',
                'role_description'=>'required|max:3000'
            ],$messages);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput();
                //$abc = back()->withErrors($validator)->withInput();
                //dd($abc);
            }
            $role = Role::find($id);
            $role->role_name = $request->input('role_name');
            $role->role_description =  $request->input('role_description');

            $role->save();

            return redirect('/roles')->with('success', 'Role Updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
