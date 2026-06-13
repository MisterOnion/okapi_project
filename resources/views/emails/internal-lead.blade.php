<x-layout>
    <h3><u>New Lead Processed</u></h3>

    <table style="border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr><td style="padding: 8px; font-weight: bold;">Name</td><td style="padding: 8px;">{$lead->customer_name}</td></tr>
        <tr style="background:#f5f5f5"><td style="padding: 8px; font-weight: bold;">Email</td><td style="padding: 8px;">{$lead->email}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Phone</td><td style="padding: 8px;">{$lead->phone_number}</td></tr>
        <tr style="background:#f5f5f5"><td style="padding: 8px; font-weight: bold;">State</td><td style="padding: 8px;">{$lead->state}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Property Type</td><td style="padding: 8px;">{$lead->property_type}</td></tr>
        <tr style="background:#f5f5f5"><td style="padding: 8px; font-weight: bold;">Roof Type</td><td style="padding: 8px;">{$lead->roof_type}</td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Monthly Bill (RM)</td><td style="padding: 8px;">{$lead->monthly_electric_bill_rm}</td></tr>
        <tr>
            <td style="padding: 8px; font-weight: bold;">Status</td>
            <td style="padding: 8px;">
                {$lead->status} 
            </td>
        </tr>
    </table>

    <p style="margin-top: 24px; color: #888; font-size: 13px;">This is an automated internal notification from {{ config('app.name') }}.</p>

</x-layout>