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


Auth::routes();

Route::get('/home', function () {
    return redirect()->route('tasks.index');
})->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::get('tasks', '\App\Http\Controllers\TaskController@index')->name('tasks.index');
    Route::post('tasks', '\App\Http\Controllers\TaskController@create')->name('tasks.create');
    Route::post('tasks/{taskId}/statusId/{statusId}', '\App\Http\Controllers\TaskController@update')->name('tasks.update');
    Route::get('tasks/delete/{taskId}', '\App\Http\Controllers\TaskController@delete')->name('tasks.delete');

});





// the sorts of things we'd need if statuses were configurable:....

// Route::group(['middleware' => 'auth'], function () {
//     Route::post('statuses', 'StatusController@store')->name('statuses.store');
//     Route::put('statuses', 'StatusController@update')->name('statuses.update');
// });
