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
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {      
        $users = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.role_name')
        ->get();
        return view('users.index', ['users'=>$users]);
    }

    public function create()
    {   
        $roles = DB::table('roles')->get();
        return view('users.create', ['roles'=>$roles]);
    }

    public function store(Request $request)
    {
        if(Auth::Check()){
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
    }

    public function show(User $user)
    {
        $user = User::find($user->id);
        $role = User::find($user->id)->role->role_name;
        $user['role_name'] = $role;
/*        $thisuser = DB::table('users')
        ->join('roles', 'users.role_id', '=', 'roles.id')
        ->select('users.*', 'roles.role_name')
        ->where('users.id','=',$user->id)
        ->get()
        ->first();*/
        return view('users.show', ['user'=>$user]);
    }

    public function edit(User $user)
    {
        $roles = DB::table('roles')->get();
        return view('users.edit', ['user'=>$user, 'roles'=>$roles]);
    }

    public function update(Request $request, User $user)
    {
        $command = $request->input('command');
        if($command === 'profile_update'){
            $userUpdate = User::where('id', $user->id)->update(
                [
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'fname' => $request->input('fname'),
                    'sname' => $request->input('sname'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'role_id' => $request->input('role_id')
                ]
                );
            if($userUpdate){
                return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
            }
            return back()->withInput();
        }
        else if($command === 'change_pass'){
            dump($request);
        }
    }

    public function destroy(User $user)
    {
        $findUser = User::find($user->id);

        if($findUser->delete()){
            return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
        }

        return back()->withInput()->with('error', 'Company could not be deleted');
    }

    public function changepass(Request $request){
        if(Auth::Check()){
            $request_data = $request->All();
            $validator = $this->credential_rules($request_data);
            if($validator->fails()){
               return back()->withErrors($validator)->withInput();
            }
            else {
                $userUpdate = User::where('id', Auth::User()->id)->update(
                    [ 
                        'password' => Hash::make($request['new-password'])
                    ]
                );
                if($userUpdate)
                    return back()->with('success','Password Changed Successfully');
            }
        }
    }
    private function credential_rules(array $data)
    {
        $messages = [
            'current-password.required' => 'Please enter current password',
            'new-password.required' => 'Please enter password',
            'new-password.min' => 'New Password needs to be at least 8 characters long',
            'password_confirmation.same' => 'Password Confirmation and New Password must match'
        ];

        $validator = Validator::make($data, [
            'current-password' => 
                ['required',
                    function($attribute, $value, $fail){
                        $current_password = Auth::User()->password;
                        if(!(Hash::check($value, $current_password)))
                            $fail('Current Password is invalid.');
                    },
                ],
            'new-password' => ['required','string','min:8',
                    function($attribute, $value, $fail){
                        $uppercase = preg_match('@[A-Z]@', $value);
                        $lowercase = preg_match('@[a-z]@', $value);
                        $number    = preg_match('@[0-9]@', $value);
                        if(!$uppercase || !$lowercase || !$number) {
                            $fail('Must contain atleast one Uppercase, one Lowercase and one Numeral.');
                          }
                    },
                ],
            'password_confirmation' => 'required|same:new-password',     
        ], $messages);

        return $validator;
    }
}