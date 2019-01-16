<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Department;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::User()->role->role_name == 'superadmin'){
            $modules = $this::superadminroute();
            session(['menu' => $modules]);
            return view('home',['menu'=>$modules]);
        }
        else {
            $did = Auth::User()->department->id;
            $modules = $this::regularroute($did);
            //dd($modules);
            session(['menu' => $modules]);
            return view('home',['menu'=>$modules]);
        }
    }

    private function superadminroute(){
        $routeCollection = collect(\Route::getRoutes());
        $modules = array();
        
        foreach($routeCollection as $route){
                $uri = $route->uri();
                $r['methods'] = $route->methods();
                $a = explode('\\',$route->getActionName());
                $name = $route->getName();
                $action = explode("@", $a[count($a) - 1]);
                $action[0] = str_replace("Controller","",$action[0]);
                if(!isset($modules[$action[0]]))
                    $modules[$action[0]] = array();
                if(count($action) > 1){
                    if($action[1] == 'index'){
                        $modules[$action[0]] = $uri;
                    }
                }
        }
        return $modules;
    }

    private function regularroute($id){
        $dept = json_decode(Department::find($id)->apppermission);
        $modules = array();
        foreach($dept as $key=>$value){
            if(count($value->action) == 0) continue;
            for($i=0;$i<count($value->action);$i++){
                if($value->action[$i]->func == 'index')
                    $modules[str_replace("Controller","",$key)] = $value->action[$i]->url;
            }
        }
        return $modules;
    }

}
