<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    //$url = route('login');
    return redirect()->route('login'); 
});

Route::get('/changepass', function () {
    return view('users/changepass');
})->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/tasks/create/{project_id?}', 'TasksController@create')->name('home');
Route::post('/users/credentials', 'UsersController@changepass')->name('changepass');
//Route::get('/clients', 'ClientsController@index')->name('clients');
//Route::get('/projects', 'ProjectsController@index')->name('projects');
//Route::get('/tasks', 'TasksController@index')->name('tasks');
Route::resource('clients','ClientsController');
Route::resource('projects','ProjectsController');
Route::resource('tasks','TasksController');
Route::resource('users', 'UsersController');
Route::resource('roles', 'RolesController');
