<?php

use Facade\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::group(['middleware' => 'auth:api'], function (){
    Route::get('user/{id}', 'Api\AuthController@show');
    Route::put('user/{id}', 'Api\AuthController@update');
    Route::delete('user/{id}', 'Api\AuthController@destroy');

    //profil
    Route::get('show/{id}','Api\AuthController@show');
    Route::put('update/{id}','Api\AuthController@update');
    Route::post('userprofile/{id}', 'Api\UserProfileController@store');

    Route::get('staff', 'Api\StaffController@index');
    Route::get('staff/{id}', 'Api\StaffController@show');
    Route::post('staff', 'Api\StaffController@store');
    Route::put('staff/{id}', 'Api\StaffController@update');
    Route::delete('staff/{id}', 'Api\StaffController@destroy');

    Route::get('user', 'Api\UserController@index');
    Route::get('user/{id}', 'Api\UserController@show');
    Route::post('user', 'Api\UserController@store');
    Route::put('user/{id}', 'Api\UserController@update');
    Route::delete('user/{id}', 'Api\UserController@destroy');

    Route::get('buku', 'Api\BukuController@index');
    Route::get('buku/{id}', 'Api\BukuController@show');
    Route::post('buku', 'Api\BukuController@store');
    Route::put('buku/{id}', 'Api\BukuController@update');
    Route::delete('buku/{id}', 'Api\BukuController@destroy');

    Route::get('registeroffline', 'Api\RegisterOfflineController@index');
    Route::get('registeroffline/{id}', 'Api\RegisterOfflineController@show');
    Route::post('registeroffline', 'Api\RegisterOfflineController@store');
    Route::put('registeroffline/{id}', 'Api\RegisterOfflineController@update');
    Route::delete('registeroffline/{id}', 'Api\RegisterOfflineController@destroy');

    Route::get('peminjaman', 'Api\PeminjamanController@index');
    Route::get('peminjaman/{id}', 'Api\PeminjamanController@show');
    Route::post('peminjaman', 'Api\PeminjamanController@store');
    Route::put('peminjaman/{id}', 'Api\PeminjamanController@update');
    Route::delete('peminjaman/{id}', 'Api\PeminjamanController@destroy');

    //Change password
    Route::put('change-password/{id}', 'Api\PasswordController@store');
    Route::get('change-password/{id}', 'Api\PasswordController@index');
});