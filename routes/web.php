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

Route::get('/tasks/create/{project_id?}', 'TasksController@create');
Route::get('/tasks/project/{project_id?}', 'TasksController@index');
Route::get('/project/enquiries/{project_id?}', 'ProjectsController@enquiries');
Route::post('/tasks/{project_id?}/edit', 'TasksController@edit');


Route::get('/enquiries/project/{project_id?}', 'EnquiriesController@index');
Route::get('/enquiries/create/{project_id?}', 'EnquiriesController@create');
Route::post('/enquiries/{project_id?}/edit', 'EnquiriesController@edit');
Route::post('/enquiries/store', 'EnquiriesController@store')->name('addenquiries');


Route::post('/users/credentials', 'UsersController@changepass')->name('changepass');
Route::get('/user/tasks', 'UsersController@tasks')->name('usertasks');
Route::get('/user/reports', 'UsersController@reports')->name('userreports');
Route::post('/user/deactivate/{user_id?}', 'UsersController@deactivate');

Route::post('/clients/store', 'ClientsController@store')->name('addclient');
Route::post('/clients/validateonly', 'ClientsController@validateonly')->name('validateclient');
Route::get('/client/cancel/{client_id?}', 'ClientsController@cancel');
Route::get('/clients/listing', 'ClientsController@clientslisting')->name('clientlist');

Route::get('/clientcontacts/create/{client_id?}', 'ClientcontactsController@create');
Route::get('/clientcontacts/{client_id?}/edit', 'ClientcontactsController@edit');
Route::get('/clientcontacts/revert/{client_id?}', 'ClientcontactsController@contactheader');
Route::get('/clientcontacts/index/{client_id?}', 'ClientcontactsController@index');
Route::get('/clientcontacts/listing/{client_id?}', 'ClientcontactsController@contactlist');

Route::get('/report/clientdetails/{report_id?}', 'ReportsController@clientdetails');

Route::resource('clients','ClientsController');
Route::resource('projects','ProjectsController');
Route::resource('enquiries','EnquiriesController');
Route::resource('tasks','TasksController');
Route::resource('users', 'UsersController');
Route::resource('roles', 'RolesController');
Route::resource('departments', 'DepartmentsController');
Route::resource('designations', 'DesignationsController');
Route::resource('clientcontacts', 'ClientcontactsController');
Route::resource('reports', 'ReportsController');
