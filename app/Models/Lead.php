<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// provides ORM to the lead model in DB
class Lead extends Model
{   
    // eloquent disables mass assignment of column values when making record saves to table
    // to prevent mass assignment attacks. assignning values without knowing which right column
    // the code below say what columns can be mass assigns during updates, without posing security risks
    protected $fillable = [
        'customer_name', 
        'email', 
        'phone_number', 
        'monthly_electricity_bill_rm', 
        'property_type', 
        'roof_type', 
        'state'
    ];
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory;
}

// Model, View, Controller
