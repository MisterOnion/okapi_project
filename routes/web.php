<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
// gathers and passes data among diff MVC components

Route::get('/', function () {
    return view('welcome');
});

// get a full namespace path to the controller class as a string value
// 'index' auto fires if the request route comes in 
// now it passes object intead of string id
// use named routes for easier routing changing, (resource name. page)
Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::get('/leads/admin', [LeadController::class, 'create'])->name('leads.create');
Route::get('/leads/{id}', [LeadController::class, 'show'])->name('leads.show');

Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
// Route::get('/leads/{id}', function (string $id) {
//     // return show function in controller
//     return view('leads.show', ["id" => $id]);
// });

