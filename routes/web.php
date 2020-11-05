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

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Action Login
Route::get('login', 'AuthController@login')->name('login');

// Action Authenticate
Route::post('auth', 'AuthController@auth')->name('auth');

// Action Logout
Route::get('logout', 'AuthController@logout')->name('logout');
