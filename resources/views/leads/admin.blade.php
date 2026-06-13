<x-layout>
    <div style="font-family: 'Segoe UI', system-ui, sans-serif; max-width: 1200px; margin: 0 auto; padding: 24px; color: #1f2937;">
        
        <!-- Header Section -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; border-bottom: 1px solid #e5e7eb; padding-bottom: 16px;">
            <h2>Admin Dashboard</h2>
            
            <!-- Filter Form -->
            <form method="GET" action="{{ route('leads.admin') }}" style="display: flex; align-items: center; gap: 8px;">
                @csrf
                <label for="status" style="font-size: 0.875rem; font-weight: 500; color: #4b5563;">Filter Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 0.875rem; background-color: #fff; cursor: pointer; outline: none;">
                    <option value="">All Leads</option>
                    <option value="qualified"    {{ ($statusFilter ?? '') === 'qualified'    ? 'selected' : '' }}>Qualified</option>
                    <option value="disqualified" {{ ($statusFilter ?? '') === 'disqualified' ? 'selected' : '' }}>Disqualified</option>
                    <option value="under_review" {{ ($statusFilter ?? '') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                </select>
            </form>
        </div>

        <!-- Success Toast Notification -->
        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <!-- Main Data Card Container -->
        <div style="background: #ffffff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06); overflow: hidden; border: 1px solid #e5e7eb;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Name</th>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151;">State</th>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Property</th>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151;">Roof Type</th>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151; text-align: right;">Bill (RM)</th>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151; text-align: center;">Status</th>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151; text-align: center;">Actions</th>
                        <th style="padding: 12px 16px; font-weight: 600; color: #374151; text-align: center;">Details</th>
                    </tr>
                </thead>
                <tbody style="background-color: #ffffff;">
                    @forelse($leads as $lead)
                    <tr style="border-bottom: 1px solid #edf2f7; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
                        <td style="padding: 14px 16px; font-weight: 500; color: #111827;">{{ $lead->customer_name }}</td>
                        <td style="padding: 14px 16px; color: #4b5563;">{{ $lead->state }}</td>
                        <td style="padding: 14px 16px; color: #4b5563; text-transform: capitalize;">{{ $lead->property_type }}</td>
                        <td style="padding: 14px 16px; color: #4b5563; text-transform: capitalize;">{{ $lead->roof_type }}</td>
                        <td style="padding: 14px 16px; color: #111827; text-align: right; font-weight: 500;">{{ number_format((float)$lead->monthly_electricity_bill_rm, 2) }}</td>

                        <!-- Dynamic Badges based on Status -->
                        <td style="padding: 14px 16px; text-align: center;">
                            @if($lead->status === 'qualified')
                                <span style="background-color: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Qualified</span>
                            @elseif($lead->status === 'disqualified')
                                <span style="background-color: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Disqualified</span>
                            @else
                                <span style="background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Review</span>
                            @endif
                        </td>

                        <!-- Quick Inline Update Form -->
                        <td style="padding: 14px 16px; text-align: center;">
                            <form method="POST" action="{{ route('leads.updateStatus', $lead->id) }}" style="display: flex; align-items: center; justify-content: center; gap: 6px; margin: 0;">
                                @csrf
                                @method('PATCH')
                                <select name="status" required style="padding: 4px 8px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 0.8125rem; background: #fff; outline: none;">
                                    <option value="" disabled selected>Change...</option>
                                    <option value="qualified">Qualified</option>
                                    <option value="disqualified">Disqualified</option>
                                    <option value="under_review">Under Review</option>
                                </select>
                                <button type="submit" style="background-color: #2563eb; color: white; border: none; padding: 5px 10px; font-size: 0.8125rem; font-weight: 500; border-radius: 4px; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#1d4ed8'" onmouseout="this.style.backgroundColor='#2563eb'">Update</button>
                            </form>
                        </td>

                        <!-- View Button Link -->
                        <td style="padding: 14px 16px; text-align: center;">
                            <a href="{{ route('leads.show', $lead->id) }}" style="color: #2563eb; font-weight: 500; text-decoration: none; font-size: 0.875rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 32px 16px; text-align: center; color: #9ca3af; font-style: italic;">No solar leads currently found matching filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section Container -->
        <div style="margin-top: 20px;  justify-content: center;">
            {{ $leads->links() }}
        </div>
    </div>
</x-layout>
