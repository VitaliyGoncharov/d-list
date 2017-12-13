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

/*Route::get('/', function () {
    return view('welcome');
});*/

//Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm')->name('index');
Route::post('/login', 'Auth\LoginController@login')->name('login');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm');
Route::post('register', 'Auth\RegisterController@register')->name('register');


Route::get(
	'password/email',
	'Auth\ShowSendEmailFormController@show'
)->name('password.email.GET');

Route::post(
	'password/email',
	'Auth\SendResetLinkController@send'
)->name('password.email.POST');

Route::get('password/reset/{email}/{token}', 'Auth\ShowResetPasswordFormController@show');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/saveutc', 'SaveUtcController@saveAction');

Route::get('/activate/{user_id}/{key}', 'Auth\ActivateController@activate');
Route::post('/logout', 'Auth\LogoutController@logout')->name('logout');

Route::post('/checkuserinput', 'Auth\CheckUserInputController@checkUserInput');

Route::post('/addnewpost', 'AddNewPostController@addPost');

Route::post('/uploadfiles', 'UploadFilesController@uploadFiles');
