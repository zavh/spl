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
        $messages = [
            'task_name.required' => 'Please enter the task name',
            'task_name.min' => 'Task name must be minimum 2 characters',
            'task_name.max' => 'Task name cannot be more than 191 characters',
            'task_name.unique' => 'This task name has already been taken',
            'password.regex'=> 'The password contains characters from at least three of the following five categories:
                                        English uppercase characters (A – Z)
                                        English lowercase characters (a – z)
                                        Base 10 digits (0 – 9)
                                        Non-alphanumeric (For example: !, $, #, or %)
                                        Unicode characters
                                        '
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:191|unique:users,name',
            'email' => 'required',
            'password' =>'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else{
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

    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'Please enter the task name',
            'task_name.min' => 'Task name must be minimum 2 characters',
            'task_name.max' => 'Task name cannot be more than 191 characters',

            'email.required' => 'Please enter the email',
            'email.email' => 'Invalid email format',
            
            'fname.required' => 'please type the first name',
            'fname.min' => 'First name must be minimum 2 characters',
            'fname.max' => 'first name cannot be more than 191 characters',
            
            'sname.required' => 'please type the surname',
            'sname.min' => 'surname must be minimum 2 characters',
            'sname.max' => 'surname cannot be more than 191 characters',

            'phone.required' => 'please type the Phone number',
            'phone.numeric' => 'the phone number must be a number',
            'phone.min' =>  'phone number must be minimum 2 characters',
            'phone.max' => 'phone number cannot be more than 15 characters',

            'address.required' => 'Please enter the address',
            'address.max' => 'address cannot be more than 3000 characters'

            //'role_id.required' => 'Role is required'
        ];

        $validator = Validator::make($request->all(), [
            'name' =>'required|min:2|max:191',
            'email'=>'required|email',
            'fname'=>'required|min:2|max:191',
            'sname'=>'required|min:2|max:191',
            'phone'=>'required|numeric|min:1000000|max:999999999999999',
            'address'=>'required|max:3000'
            //'role_id'=>'required'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
            //$abc = back()->withErrors($validator)->withInput();
            //dd($abc);
        }
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email =  $request->input('email');
        $user->fname = $request->input('fname');
        $user->sname = $request->input('sname');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->role_id = $request->input('role_id');

        $user->save();

        return redirect('/users')->with('success', 'User Updated');
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