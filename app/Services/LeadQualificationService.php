<?php
declare(strict_types=1);

namespace App\Services;

class LeadQualificationService
{
    private const peninsular_malaysia =[
        'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan',
        'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Selangor',
        'Terengganu', 'Kuala Lumpur', 'Putrajaya',
    ];

    public function qualify(array $lead): string
    {
        if($lead['monthly_electricity_bill_rm'] >= 150 
            && $lead['monthly_electricity_bill_rm'] <= 199) 
        {
            return 'under_review';
        }

        if ($this->isDisqualified($lead)) {
            return 'disqualified';
        }

        return 'qualified';
    }

    // disqualify rules
    private function isDisqualified(array $lead): bool
    {
        if($lead['monthly_electricity_bill_rm'] < 150) {
            return true;
        }

        if (!in_array($lead['property_type'], ['landed', 'commercial'], true)) {
            return true;
        }

        if ($lead['roof_type'] == 'flat') {
            return true;
        }

        if (!in_array($lead['state'], self::peninsular_malaysia, true)) {
            return true;
        }

        return false;
    }
}