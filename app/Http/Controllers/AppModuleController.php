<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use Illuminate\Support\Facades\DB;

class AppModuleController extends Controller
{
    
    public function index()
    {
        $routeCollection = collect(\Route::getRoutes());
        
        foreach($routeCollection as $route){
                $uri = $route->uri();
                $r['methods'] = $route->methods();
                $a = explode('\\',$route->getActionName());
                $name = $route->getName();
                //$r['action'] = $route->getActionName();
                $action = explode("@", $a[count($a) - 1]);
                if(!isset($modules[$action[0]]))
                    $modules[$action[0]] = array();
                if(count($action) == 1)
                    $modules[$action[0]][count($modules[$action[0]])] = $action[0];
                else {
                    $count = count($modules[$action[0]]);
                    $modules[$action[0]][$count]['function'] = $action[1];
                    $modules[$action[0]][$count]['uri'] = $uri;
                    $modules[$action[0]][$count]['name'] = $name;
                }
        }
        $departments = Department::all();
        
        return view('appmodules.index', ['modules'=>$modules, 'departments'=>$departments]);
    }

    public function addmod(Request $request){
        $data = $request->data;
        $name = $data['modname'];
        return view('appmodules.functions', ['data'=>$data, 'name'=>$name]);
    }

    public function defaultconfig(Request $request){
        $config = DB::table('appdeafultconfig')->where('name', 'default')->get();
        if(count($config) == 0){
            $response['result'] = 'nc';
        }
        else {
            $response['result'] = 'success';
            $response['config'] = json_decode($config->first()->config);
        }
        return response()->json(['response'=>$response]);
    }

    public function deptconfig(Request $request){
        $config = Department::find($request->data)->apppermission;
        $response['result'] = 'success';
        $response['config'] = json_decode($config);
        return response()->json(['response'=>$response]);
    }

    public function changedefaultcfg(Request $request){
        $config = DB::table('appdeafultconfig')->where('name', 'default')->get();
        if(count($config) == 0){
            DB::table('appdeafultconfig')->insert(
                ['config' => json_encode($request->data), 'name'=>'default']
            );
            $response['result'] = 'success';
            $response['message'] = 'Configuration created';
        }
        else {
            DB::table('appdeafultconfig')
            ->where('name', 'default')
            ->update(['config' => json_encode($request->data)]);
            $response['result'] = 'success';
            $response['message'] = 'Configuration changed';
        }

        return response()->json(['response'=>$response]);
    }

    public function changedeptcfg(Request $request){
        $id = $request->department_id;
        $department = Department::find($id);
        $department->apppermission = json_encode($request->data);
        $department->save();
        $response['result'] = 'success';
        $response['message'] = 'Configuration changed';
        return response()->json(['response'=>$response]);
    }
}
