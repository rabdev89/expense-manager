<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware' => ['guest:api']], function () {
    Route::post('login', 'Auth\AuthController@postLogin');
    Route::post('register', 'Auth\RegisterController@register');
});


Route::group(['middleware' => ['role:administrator', 'auth:api']], function () {
    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('categories', 'CategoryController');
});

Route::group(['middleware' => ['role:administrator|user', 'auth:api']], function () {
    Route::resource('expenses', 'ExpensesController');
    Route::put('user/profile', 'UserController@updateProfile')->name('user.profile.update');
    Route::get('logout', 'Auth\AuthController@logout');
    Route::get('check', 'Auth\AuthController@check');
});
