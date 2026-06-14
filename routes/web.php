<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
// gathers and passes data among diff MVC components
// beware of route ordering conflicts

Route::get('/', function () {
    return view('welcome');
});

// [] passes objects and public functions
// name() = assign name to route
Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
// only admins can use create function
Route::get('/leads/admin', [LeadController::class, 'create'])->name('leads.admin');
Route::get('/leads/audit', [LeadController::class, 'showAudit'])->name('leads.audit');
Route::get('/leads/{id}', [LeadController::class, 'show'])->name('leads.show');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');

Route::patch('/leads/{id}/status',  [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
Route::patch('/leads/{id}',  [LeadController::class, 'update'])->name('leads.update');

Route::get('/emails/customer', function () {
    return view('emails.customer-lead'); 
})->name('emails.customer');

Route::get('/emails/internal', function () {
    return view('emails.internal-lead'); 
})->name('emails.internal');