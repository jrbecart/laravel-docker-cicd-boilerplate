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

  // Testing route for pdf
  // Home
  Route::get('/', 'RouteController@home');
  Route::get('/template', 'RouteController@home'); // This route fix bug for Laravel caching based on symfony, should always be equal to / route
  // Test / Healthceck page
  Route::get('/healthcheck/', 'RouteController@healthcheck');
  // Logout
  Route::get('/logout', 'RouteController@logout');
  // Switch lang
  Route::get('/lang/{locale}', 'LangController@switchlang');

