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

//Auth::routes();

Route::get('/','HomeController@show')->name('home');
Route::post('/login','Auth\LoginController@login')->name('login');

// Registration Routes...
Route::get('register','Auth\RegisterController@showRegistrationForm');
Route::post('register','Auth\RegisterController@register')->name('register');


Route::get(
    'password/email',
    'Auth\ShowSendEmailFormController@show'
)->name('password.email.GET');

Route::post(
    'password/email',
    'Auth\SendResetLinkController@send'
)->name('password.email.POST');

Route::get('password/reset/{email}/{token}','Auth\ShowResetPasswordFormController@show');
Route::post('password/reset','Auth\ResetPasswordController@reset')->name('password.reset');


Route::group(['prefix' => 'news'], function () {
    Route::get('/','NewsController@index');
    Route::get('/search','NewsController@renderSearch');
    Route::post('/search/{keys}','NewsController@searchNews');
});

Route::post('/saveutc','SaveUtcController@saveAction');

Route::get('/activate/{user_id}/{key}','Auth\ActivateController@activate');
Route::post('/logout','Auth\LogoutController@logout')->name('logout');

Route::post('/checkuserinput','Auth\CheckUserInputController@checkUserInput');

Route::post('/uploadfiles','UploadFilesController@uploadFiles');

Route::post('/addpost','AddNewPostController@addPost');
Route::post('/loadpostscol','NewsController@loadPostsCollection');
Route::post('/addcomment','AddCommentController@addComment');


Route::post('/like/add','LikeController@add');
Route::post('/like/delete','LikeController@delete');

Route::post('/dislike/add','DislikeController@add');
Route::post('/dislike/delete','DislikeController@delete');

Route::get('/messanger','MessangerController@index');
Route::get('/msg/write{userId}','ChatController@index');

Route::get('/friends','FriendsController@index');

Route::group(['prefix' => 'friend'],function()
{
    Route::get('search','FriendsController@showFoundUsers');
    Route::post('search','FriendsController@findUser');

    Route::post('request/send/{userId}','FriendsController@sendRequest');
    Route::post('request/accept/{userId}','FriendsController@acceptRequest');
    Route::post('request/cancel/{userId}','FriendsController@cancelRequest');
    Route::get('requests/outgoing','FriendsController@showOutgoingRequests');
    Route::get('requests/incoming','FriendsController@showIncomingRequests');
});

Route::post('/deleteAttachedPhoto','AddNewPostController@deleteAttachedPhoto');
Route::post('/deleteAttachedFile','AddNewPostController@deleteAttachedFile');

Route::get('/profile/{profileNameOrId}','ProfileController@index');

Route::get('/test','TestController@index');

Route::group(['prefix' => 'ws'],function()
{
    Route::get('check-auth',function()
    {
        return response()->json([
            'auth' => \Auth::check(),
            'userId' => \Auth::user()->id
        ]);
    });
});
