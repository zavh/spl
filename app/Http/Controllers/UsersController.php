<?php

namespace App\Http\Controllers;

use App\User;
use App\Task;
use App\Client;
use App\TaskUser;
use App\Role;
use App\Department;
use App\Designation;
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
        if(Auth::User()->role_id ==1){
            $users = User::all();
            $me = User::find(Auth::User()->id);
            $completion = $this->profileCalculation($me);
            return view('users.index', ['users'=>$users,'me'=>$me, 'completion'=>$completion]);
        }
        else abort(404);
    }

    public function create()
    {   
        $roles = Role::all();
        $departments = Department::all();
        $designations = Designation::all();
        return view('users.create', ['roles'=>$roles,'departments'=>$departments,'designations'=>$designations]);
    }

    public function store(Request $request)
    {
        $messages = [
            'task_name.required' => 'Please enter the task name',
            'task_name.min' => 'Task name must be minimum 2 characters',
            'task_name.max' => 'Task name cannot be more than 191 characters',
            'task_name.unique' => 'This task name has already been taken'
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:191|unique:users,name',
            'email' => 'required',
            'password' =>['required','string','min:8',
            function($attribute, $value, $fail){
                $uppercase = preg_match('@[A-Z]@', $value);
                $lowercase = preg_match('@[a-z]@', $value);
                $number    = preg_match('@[0-9]@', $value);
                if(!$uppercase || !$lowercase || !$number) {
                    $fail('Must contain atleast one Uppercase, one Lowercase and one Numeral.');
                  }
                }
            ],
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
                'department_id' => $request['department'],
                'designation_id' => $request['designation'],
                'active' => '1',
                'password' => Hash::make($request['password']),
                ]);
            
            if($userCreate){
                $users = User::all();
                $me = User::find(Auth::User()->id);
                $completion = $this->profileCalculation($me);
                return view('users.index', ['users'=>$users,'me'=>$me,'completion'=>$completion]);
                }
            }
        }
        
    }

    public function show(User $user)
    {
        if(Auth::Check()){
            $user = User::find($user->id);
            $completion = $this->profileCalculation($user);
            return view('users.show', ['user'=>$user, 'completion'=>$completion]);
        }
        else {
            return view('partial.sessionexpired');
        }
    }

    private function profileCalculation($user){
        $completion = 0;

        if($user->fname != NULL) $completion+=22;
        if($user->sname != NULL) $completion+=22;
        if($user->phone != NULL) $completion+=6;
        if($user->address != NULL) $completion+=6;
        if($user->designation_id > 0) $completion+=22;
        if($user->department_id > 0) $completion+=22;
        
        //$completion = $fname+$lname+$phone+$address+$designation+$department;

        return $completion;
    }
    public function edit(User $user)
    {   
        if(Auth::Check()){
            if(Auth::User()->role_id == 1 || $user->id == Auth::User()->id){
                $roles = DB::table('roles')->get();
                $departments = Department::all();
                $designations = Designation::all();
                return view('users.edit', ['user'=>$user, 'roles'=>$roles, 'departments'=>$departments, 'designations'=>$designations]);
            }
            else return redirect('/home');
        }
        else 
            abort(404);
    }

    public function update(Request $request, $id)
    {
        $messages = [
            'email.required' => 'Please enter the email',
            'email.email' => 'Invalid email format',
            
            'fname.required' => 'please type the first name',
            'fname.min' => 'First name must be minimum 2 characters',
            'fname.max' => 'first name cannot be more than 191 characters',
            
            'sname.required' => 'please type the surname',
            'sname.min' => 'surname must be minimum 2 characters',
            'sname.max' => 'surname cannot be more than 191 characters',

            'phone.numeric' => 'the phone number must be a number',
            'phone.min' =>  'phone number must be minimum 2 characters',
            'phone.max' => 'phone number cannot be more than 15 characters',

            'address.max' => 'Address cannot be more than 100 characters',
            'address.min' => 'Address cannot be less than 5 characters',
        ];

        $validator = Validator::make($request->all(), [
            'name' =>'required|min:2|max:191',
            'email'=>'required|email',
            'fname'=>'required|min:2|max:191',
            'sname'=>'required|min:2|max:191',
            'phone'=>'numeric|min:1000000|max:999999999999999',
            'address'=>'max:100|min:5'
        ],$messages);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email =  $request->input('email');
        $user->fname = $request->input('fname');
        $user->sname = $request->input('sname');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->designation_id = $request->input('designation');
        $user->department_id = $request->input('department');
        
        if(Auth::User()->role_id == 1){
            $user->role_id = $request->input('role_id');
            $user->active = $request->input('active');
        }
        else {
            $user->role_id = Auth::User()->role_id;
            $user->active = Auth::User()->active;
        }
        // dd($user->active);
        $user->save();
        
        
        
        if(Auth::User()->role_id == 1)
            return redirect('/users')->with('success', 'User Updated');
        else 
        return redirect('/home')->with('success', 'User Updated');
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

    public function tasks(){
        $tasks = User::find(Auth::User()->id)->tasks->where('completed',0);
        foreach($tasks as $task){
            $task['project_name'] = Task::find($task->id)->project->project_name;
        }
        return view('users.usertasks', ['tasks'=>$tasks]);
    }

    public function reports(){
        $x = User::find(Auth::User()->id)->reports;
        $complete = array();
        $incomplete = array();
        foreach($x as $index=>$report){
            if($report->completion == 0){
                $incomplete[$index]['data'] = json_decode($report->report_data);
                $incomplete[$index]['id'] = $report->id;
            }
            else {
                $complete[$index]['data'] = json_decode($report->report_data);
                $complete[$index]['id'] = $report->id;
            }
        }
        return view('users.userreports', ['complete'=>$complete, 'incomplete'=>$incomplete]);
    }
    
    public function deactivate($id)
    {
        $user = User::find($id);
        // dd($user);
        if($user->active==1)
        {
            $user->active=0;
            $user->save();
            return back()->with('success','Deactivated Successfully');
        }
        else
        {
            $user->active=1;
            $user->save();
            return back()->with('success','Activated Successfully');
        }       
    }
}