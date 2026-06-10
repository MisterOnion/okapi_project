<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/leads', function () {
    $leads = [
        ["customer_id" => "1", "customer_name" => "mario", "lead_id" => "mario179", "email" => "mario@email.com"],
        ["customer_id" => "2", "customer_name" => "luigi", "lead_id" => "luigi017", "email" => "luigi@email.com"],
        
    ];
    // passes data to view
    return view('leads.main', ["leads" => $leads,]);
});

Route::get('/leads/admin', function () {
    return view('leads.admin');
});

Route::get('/leads/{id}', function ($lead_id) {
    return view('leads.show', ["lead_id" => $lead_id]);
});

