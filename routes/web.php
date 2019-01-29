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
    return view('auth.login');
});

//Auth::routes();
Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
 ]);
 Route::get('/home', 'HomeController@index')->name('home');

 Route::post('/filtros', 'HomeController@index');
 Route::get('/filtros', 'HomeController@index');

 Route::get('generate/{id}', 'FileController@imprimir');

 Route::get('previa/{id}/', 'HomeController@previaimpresion');


//Route::get('/home/{progid}/{resid}/{dateid}/', 'HomeController@index')->name('home');
