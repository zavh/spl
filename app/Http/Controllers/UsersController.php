<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.role_name')
        ->get();
        return view('users.index', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('users.create', ['roles'=>$roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userCreate = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'role_id' => $request['role'],
            'password' => Hash::make($request['password']),
        ]);
        if($userCreate){
            $users = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.role_name')
            ->get();
            return view('users.index', ['users'=>$users]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //$user = User::find($user->id);
        $thisuser = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.role_name')
        ->where('users.id','=',$user->id)
        ->get()
        ->first();
        return view('users.show', ['user'=>$thisuser]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //dd($user);
        $userUpdate = User::where('id', $user->id)->update(
            [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'fname' => $request->input('fname'),
                'sname' => $request->input('sname'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address')
            ]
            );
        if($userUpdate){
            return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
        }
        return back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $findUser = User::find($user->id);

        if($findUser->delete()){
            return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
        }

        return back()->withInput()->with('error', 'Company could not be deleted');
    }
}
