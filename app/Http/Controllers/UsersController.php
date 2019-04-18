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
use App\Http\Traits\SalaryGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UsersController extends Controller
{
    use SalaryGenerator;
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

        ########### Yearly Income Generator & Tax Calculation starts ############
        $join_date = Carbon::parse($salary['join_date']);
        $current_month = date('n');
        $current_year = date('Y');
        if($current_month > 6){
            $next_year = $current_year + 1;
            $current_pay_year_to = Carbon::parse($next_year.'-07-01');
        }
        else {
            $next_year = $current_year;
            $current_year = $current_year - 1;
            $current_pay_year_to = Carbon::parse($next_year.'-07-01');
        }
        $db_table_name = 'yearly_income_'.$current_year."_".$next_year;
        if($join_date->lt($current_pay_year_to) && Schema::hasTable($db_table_name)){
            
            $data = $this->yearly_generator($userCreate, "$current_year-07-01", "$next_year-06-30");
            $tax_config = $this->income_tax_calculation($data);
            $data['tax_config'] = $tax_config;
            $data= $this->generate_monthly_tax($data, $tax_config['finalTax']);
            $this->yearly_income_table_data_entry($data,$db_table_name);
        }                
        ########### Yearly Income Generator & Tax Calculation starts ############

        if($userCreate && $salaryCreate){
            $me = User::find(Auth::User()->id);
            $completion = $this->profileCalculation($me);
            $response['status'] = 'success';
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
            $user->save();

            $response['status'] = 'success';
            $response['type'] = 'user';
            return response()->json(['result'=>$response]);
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

    public function tasks(){
        $tasks = User::find(Auth::User()->id)->tasks->where('completed',0);
        foreach($tasks as $task){
            $task['project_name'] = Task::find($task->id)->project->project_name;
        }
        return view('users.usertasks', ['tasks'=>$tasks]);
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