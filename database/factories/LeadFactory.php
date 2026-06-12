<?php
declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // apply lead qualifier logic for test data (task 2)
        $bill_rm = fake()->randomFloat(2, 50, 1500);
        $property_type = fake()->randomElement(['landed', 'condo', 'apartment', 'commercial']);
        $roof_type = fake()->randomElement(['tile', 'metal', 'flat', 'concrete']);
        $state = fake()->randomElement([
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
        
        $qualifier = new \App\Services\LeadQualificationService();
        $status = $qualifier->qualify([
            'monthly_electricity_bill_rm' => $bill_rm,
            'property_type' => $property_type,
            'roof_type' => $roof_type,
            'state' => $state,
        ]);

        // return fake api generated data to populate model
        return [
            'customer_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone_number' => $this->malaysianPhoneNumber(),
            'monthly_electricity_bill_rm' => $bill_rm,
            'property_type' => $property_type,
            'roof_type' => $roof_type,
            'state' => $state,
            'status' => $status,
        ];
    }
    private function malaysianPhoneNumber(): string
    {
        $prefix = fake()->randomElement(['010', '011', '012', '013', '014', 
        '016', '017', '018', '019']);

        if ($prefix == '011') {
            $digits = fake()->numerify('########');
        } else {
            $digits = fake()->numerify('#######');
        }

        return "{$prefix}-{$digits}";
    }    
}
