<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Salary;
use App\User;
use Illuminate\Support\Facades\Storage;

class SalariesController extends Controller
{

    public function index()
    {        
        $users = User::actual()->where('active', 1)->get();
        $salary = array();
        $tabheads = array('Employee ID','Basic');
        $flag = 0;
        $count = 0;
        foreach($users as $index=>$user)
        {
            $sstructure = $user->salarystructure;

            $x = $user->salary;
            $salary[$count]['name'] = $x->user->name;
            $si = json_decode($x->salaryinfo);
            $salary[$count]['basic'] = $si->basic;
            
            if(empty($sstructure)){
                $salary[$count] = null;
            }
            else {
                $ss = json_decode($sstructure->structure);
                foreach($ss as $breakdown){
                    if($flag == 0)
                        $tabheads[count($tabheads)]=$breakdown->param_uf_name;
                    $salary[$count][$breakdown->param_name] = ($salary[$count]['basic'] * $breakdown->value)/100;
                }
                $flag = 1;
                $count++;
            }
        }
       return view('salaries.index',['salaries'=>$salary, 'heads'=>$tabheads]);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function upload(Request $request)
    {
        $uploadedFile = $request->file('fileToUpload');
        $filename = $uploadedFile->getClientOriginalName();

        Storage::disk('local')->putFileAs('files', $uploadedFile, $filename);
        $contents = Storage::get('/files/'.$filename);
        $lines = explode("\n", $contents);
        $heads = explode(",", $lines[0]);
        for($y=1;$y<count($lines);$y++){
            $tempdat = explode(",", $lines[$y]);
            
            for($i=0;$i<count($heads);$i++){
                $data[$y-1][$heads[$i]] = $tempdat[$i];
            }
        }

        dd($data);
    }

    public function destroy($id)
    {

    }
}
