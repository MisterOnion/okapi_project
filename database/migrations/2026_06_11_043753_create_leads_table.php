<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('email')->unique;
            $table->string('phone_number', 20);
            $table->decimal('monthly_electricity_bill_rm', 10, 2);
            $table->enum('property_type', ['landed', 'condo', 'apartment', 'commercial']);
            $table->enum('roof_type', ['tile', 'metal', 'flat', 'concrete']);
            $table->enum('state',[
                'Johor',
                'Kedah',
                'Kelantan',
                'Melaka',
                'Negeri Sembilan',
                'Pahang',
                'Perak',
                'Perlis',
                'Pulau Pinang',
                'Sabah',
                'Sarawak',
                'Selangor',
                'Terengganu',
                'Kuala Lumpur',
                'Labuan',
                'Putrajaya',
            ]);
            // composite unique lead index in DB. (task 3)
            // just a string, since concat of email, phone_number, and monthly bill
            $table->string('unique_lead');
            $table->timestamps(); // created_at updated_at 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
