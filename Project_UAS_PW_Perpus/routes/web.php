<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\PHPMailerController;
 
//Route::get("email", [PHPMailerController::class, "email"])->name("email");
 
//Route::post("send-email", [PHPMailerController::class, "composeEmail"])->name("send-email");
 
Route::get('/', function () {
    return view('welcome');
});

Route::get('verifikasi/{data}', 'VerifikasiController@verifikasi');
