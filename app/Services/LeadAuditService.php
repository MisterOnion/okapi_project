<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Audit;
use App\Models\Lead;
// need to be the same name as the file
class LeadAuditService
{
    public function logStatusChange(Lead $lead, string $oldStatus, string $newStatus): void
    {
        Audit::create([
            'lead_id'   => $lead->id,
            'event'     => 'status_changed',
            'field'     => 'status',
            'old_value' => $oldStatus,
            'new_value' => $newStatus,
            'changed_at' => now(),
        ]);
    }

    public function logFieldChanges(Lead $lead, array $oldValues, array $newValues): void
    {
        // only track fields that actually changed
        $trackedFields = ['customer_name', 'email', 'phone_number', 'monthly_electricity_bill_rm', 'property_type', 'roof_type', 'state'];

        foreach ($trackedFields as $field) {
            if (!array_key_exists($field, $newValues)) continue;
            if ((string) $oldValues[$field] === (string) $newValues[$field]) continue;

            Audit::create([
                'lead_id'    => $lead->id,
                'event'      => 'field_updated',
                'field'      => $field,
                'old_value'  => $oldValues[$field],
                'new_value'  => $newValues[$field],
                'changed_at' => now(),
            ]);
        }
    }
}
