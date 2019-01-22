<?php

namespace App\Http\Controllers;

use App\User;
use App\SalaryStructure;
use App\Task;
use App\Client;
use App\TaskUser;
use App\Role;
use App\Department;
use App\Designation;
use App\Salary;
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
        $users = User::actual()->get();
        $me = User::find(Auth::User()->id);
        $completion = $this->profileCalculation($me);
        return view('users.index', ['users'=>$users,'me'=>$me, 'completion'=>$completion]);
    }

    public function create()
    {   
        $roles = Role::all();
        $departments = Department::all();
        $designations = Designation::all();
        $salarystructures = SalaryStructure::all();
        return view('users.create', ['roles'=>$roles,'departments'=>$departments,'designations'=>$designations,'salarystructures'=>$salarystructures]);
    }
    
    public function store(Request $request)
    {
        $useraccount = $request->useraccount;

        $uavalidator = Validator::make($useraccount, [
            'name' => 'required|min:3|max:20|unique:users,name',
            'email' => 'required',
            'password' =>['required','string','min:8',
            function($attribute, $value, $fail){
                $uppercase = preg_match('@[A-Z]@', $value);
                $lowercase = preg_match('@[a-z]@', $value);
                $number    = preg_match('@[0-9]@', $value);
                if(!$uppercase || !$lowercase || !$number) {
                    $fail('Must contain atleast one Uppercase, one Lowercase and one Numeral.');
                  }
                },
            ],
            'password_confirmation' => 'required|same:password',
        ]);

        if($uavalidator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $uavalidator->errors()->messages();
            return response()->json(['result'=>$response]);
        }

        $salary = $request->salary;
        // dd($salary);
        $svalidator = Validator::make($salary, [
            'basic' => 'required|numeric',
            'join_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'bank_account_name'=> 'required_if:pay_out_mode,BANK',
            'bank_account_number'=> 'required_if:pay_out_mode,BANK',
            'bank_name'=> 'required_if:pay_out_mode,BANK',
            'bank_branch'=> 'required_if:pay_out_mode,BANK',
            'end_date'=>'required_unless:tstatus,a'
        ]);

        if($svalidator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $svalidator->errors()->messages();
            return response()->json(['result'=>$response]);
        }
        // dd($useraccount);
        $userCreate = User::create([
            'name' => $useraccount['name'],
            'email' => $useraccount['email'],
            'role_id' => $useraccount['role'],
            'department_id' => $useraccount['department'],
            'designation_id' => $useraccount['designation'],
            'active' => '1',
            'salaryprofile' => $useraccount['salarystructure'],
            'password' => Hash::make($useraccount['password']),
            ]);
        $salaryinfo = json_encode($salary);
        $salaryCreate = Salary::create([
            'user_id' => $userCreate->id,
            'salaryinfo' => $salaryinfo,
            ]);

        if($userCreate || $salaryCreate){
            $users = User::all();
            $me = User::find(Auth::User()->id);
            $completion = $this->profileCalculation($me);
            // return view('users.index', ['users'=>$users,'me'=>$me,'completion'=>$completion]);
            $response['status'] = 'success';
            // $response['messages'] = $uavalidator->errors()->messages();
            return response()->json(['result'=>$response]);
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
                $salarystructures = SalaryStructure::all();
                if(!isset($user->salary)){
                    $salary = NULL;
                    $salaryinfo = array(
                        "basic"=>"", 
                        "join_date"=>"",
                        "tstatus"=>"a",
                        "end_date"=>"",
                        "date_of_birth"=>"",
                        "gender"=>"",
                        "pay_out_mode"=>"",
                        "bank_account_name"=>"",
                        "bank_account_number"=>"",
                        "bank_name"=>"",
                        "bank_branch"=>"",
                    );
                    $salaryinfo = (object)$salaryinfo;
                }
                else {
                    $salary = $user->salary;
                    $salaryinfo = json_decode($salary->salaryinfo);
                }
                return view('users.edit', ['salary'=>$salary,'salaryinfo'=>$salaryinfo,'user'=>$user, 'roles'=>$roles, 'departments'=>$departments, 'designations'=>$designations,'salarystructures'=>$salarystructures]);
            }
            else return redirect('/home');
        }
        else 
            abort(404);
    }

    public function update(Request $request, $id)
    {
        $useraccount = $request->useraccount;
        $uavalidator = Validator::make($useraccount, [
            'name' => 'required|min:3|max:20',
            'fname' =>'required|min:2',
            'sname' =>'required|min:2',
            'phone' => 'required|numeric|min:10000000|max:999999999999999',
            'address'=>'required',
            'email' =>'required|email'
        ]);

        if($uavalidator->fails()){
            $response['status'] = 'failed';
            $response['messages'] = $uavalidator->errors()->messages();
            return response()->json(['result'=>$response]);
        }
        // dd($salary);
        
        $user = User::find($id);
        if (Auth::User()->role_id == 1) //admin
        {
            $user->name = $useraccount['name'];
            $user->email = $useraccount['email'];
            $user->fname = $useraccount['fname'];
            $user->sname = $useraccount['sname'];
            $user->phone = $useraccount['phone'];
            $user->address = $useraccount['address'];
            $user->designation_id = $useraccount['designation'];
            $user->department_id = $useraccount['department'];
            $user->salaryprofile = $useraccount['salarystructure'];
            $user->role_id = $useraccount['role'];
            $user->active = $useraccount['active_status'];

            // dd($user->active);
            $user->save();
    
            $salaryaccount = $request->salary;
    
            $svalidator = Validator::make($salaryaccount, [
                'basic' => 'required|numeric',
                'join_date' => 'required|date',
                'date_of_birth' => 'required|date',
                'bank_account_name'=> 'required_if:pay_out_mode,BANK',
                'bank_account_number'=> 'required_if:pay_out_mode,BANK',
                'bank_name'=> 'required_if:pay_out_mode,BANK',
                'bank_branch'=> 'required_if:pay_out_mode,BANK',
                'end_date'=>'required_unless:tstatus,a'
            ]);
    
            if($svalidator->fails()){
                $response['status'] = 'failed';
                $response['messages'] = $svalidator->errors()->messages();
                return response()->json(['result'=>$response]);
            }
    
            $salaryinfo = json_encode($salaryaccount);
            
            if(isset($user->salary->id)){
                $salary = Salary::where('user_id',$id)->get()->first();
                $salary->salaryinfo = $salaryinfo;
                $salary->save();
            }
            else{
                $salaryCreate = Salary::create([
                    'user_id' => $id,
                    'salaryinfo' => $salaryinfo,
                    ]);        
            }
            $response['status'] = 'success';
            $response['type'] = 'admin';
            return response()->json(['result'=>$response]);
        } 
        else //regular user
        {
            $user->name = $useraccount['name'];
            $user->email = $useraccount['email'];
            $user->fname = $useraccount['fname'];
            $user->sname = $useraccount['sname'];
            $user->phone = $useraccount['phone'];
            $user->address = $useraccount['address'];
            // dd($user->active);
            $user->save();

            $response['status'] = 'success';
            $response['type'] = 'user';
            return response()->json(['result'=>$response]);
        }
        
        // $user->name = $useraccount['name'];
        // $user->email = $useraccount['email'];
        // $user->fname = $useraccount['fname'];
        // $user->sname = $useraccount['sname'];
        // $user->phone = $useraccount['phone'];
        // $user->address = $useraccount['address'];
        // $user->designation_id = $useraccount['designation'];
        // $user->department_id = $useraccount['department'];
        // $user->salaryprofile = $useraccount['salarystructure'];
        
        // if(Auth::User()->role_id == 1){
        //     $user->role_id = $useraccount['role'];
        //     $user->active = $useraccount['active_status'];
        // }
        // else {
        //     $user->role_id = Auth::User()->role_id;
        //     $user->active = Auth::User()->active;
        // }
        // // dd($user->active);
        // $user->save();

        // $salaryaccount = $request->salary;

        // $svalidator = Validator::make($salaryaccount, [
        //     'basic' => 'required|numeric',
        //     'join_date' => 'required|date',
        //     'date_of_birth' => 'required|date',
        //     'bank_account_name'=> 'required_if:pay_out_mode,BANK',
        //     'bank_account_number'=> 'required_if:pay_out_mode,BANK',
        //     'bank_name'=> 'required_if:pay_out_mode,BANK',
        //     'bank_branch'=> 'required_if:pay_out_mode,BANK',
        //     'end_date'=>'required_unless:tstatus,a'
        // ]);

        // if($svalidator->fails()){
        //     $response['status'] = 'failed';
        //     $response['messages'] = $svalidator->errors()->messages();
        //     return response()->json(['result'=>$response]);
        // }

        // $salaryinfo = json_encode($salaryaccount);

        // $salary = Salary::where('user_id',$id)->get()->first();
        // $salary->salaryinfo = $salaryinfo;
        // $salary->save();

        // // if($userCreate || $salaryCreate){
        // //     $response['status'] = 'success';
        // //     return response()->json(['result'=>$response]);
        // // }
        // $response['status'] = 'success';
        // return response()->json(['result'=>$response]);
        
        
        // if(Auth::User()->role_id == 1)
        //     return redirect('/users')->with('success', 'User Updated');
        // else 
        // return redirect('/home')->with('success', 'User Updated');
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