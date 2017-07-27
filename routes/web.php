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

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', function (){return redirect('/home');});
    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('qualification', 'QualificationController');
    Route::resource('user', 'UserController');
    Route::resource('service', 'ServiceController');


    Route::match(['get', 'post'], 'user/approve/{id}', 'UserController@approve_user');

    Route::post('qualification_user/create', 'QualificationController@createQualification_User');
    Route::match(['get', 'post'], 'qualification_user/delete/{user_id}/{qualification_id}', 'QualificationController@deleteQualification_User');

    Route::match(['get', 'post'], 'position/{id}/subscribe', 'PositionController@subscribe');
    Route::match(['get', 'post'], 'position/{id}/authorize', 'PositionController@authorizePos');
    Route::match(['get', 'post'], 'position/{id}/deauthorize', 'PositionController@deauthorizePos');

    Route::match(['get', 'post'], 'position/list_notAuthorized', 'PositionController@index_notAuthorized')->name('position.list_notAuthorized');;
});
