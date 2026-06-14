<x-layout>
    <!-- Stripped heavy borders and background card styling for a clean canvas -->
    <div style="font-family: system-ui, -apple-system, sans-serif; max-width: 1200px; margin: 0 auto; padding: 40px 24px; color: #111827;">
        
        <!-- Header Section - Lightened borders and balanced spacing -->
        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 32px; border-bottom: 1px solid #f3f4f6; padding-bottom: 16px;">
            <h2 style="font-size: 1.25rem; font-weight: 600; margin: 0;">Admin Dashboard</h2>
            
            <!-- Filter Form -->
            <form method="GET" action="{{ route('leads.admin') }}" style="display: flex; align-items: center; gap: 8px;">
                @csrf
                <label for="status" style="font-size: 0.875rem; color: #6b7280;">Filter Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #e5e7eb; font-size: 0.875rem; background-color: #fff; cursor: pointer; color: #4b5563; outline: none;">
                    <option value="">All Leads</option>
                    <option value="qualified"    {{ ($statusFilter ?? '') === 'qualified'    ? 'selected' : '' }}>Qualified</option>
                    <option value="disqualified" {{ ($statusFilter ?? '') === 'disqualified' ? 'selected' : '' }}>Disqualified</option>
                    <option value="under_review" {{ ($statusFilter ?? '') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                </select>
            </form>
        </div>

        <!-- Success Toast Notification - Refined to match clean palette -->
        @if (session('success'))
            <div style="margin-bottom: 24px; font-size: 0.875rem; color: #0f766e; background: #f0fdfa; padding: 12px 16px; border-radius: 6px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Data Table Container - Removed card shadows, borders, and fixed background -->
        <div style="width: 100%; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem;">
                <thead style="border-bottom: 2px solid #f3f4f6;">
                    <tr>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280;">Name</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280;">Email</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280;">Phone Number</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280;">State</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280;">Property</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280;">Roof Type</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280; text-align: right;">Bill (RM)</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280; text-align: center;">Update Details</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280; text-align: center;">Status</th>
                        <th style="padding: 12px 8px; font-weight: 500; color: #6b7280; text-align: center;">Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    <!-- Rows transition softly via opacity instead of shifting backgrounds -->
                    <tr style="border-bottom: 1px solid #f3f4f6; transition: opacity 0.15s;">

                        <form method="POST" action="{{ route('leads.update', $lead->id) }}" style="display: contents;">
                            @csrf
                            @method('PATCH')                                
                            
                            <!-- Customer Name -->
                            <td style="padding: 12px 8px;">
                                <input type="text" name="customer_name" value="{{ old('customer_name', $lead->customer_name) }}" style="width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: 500;" required>
                            </td>
                            <!-- email -->
                            <td style="padding: 12px 8px;">
                                <input type="text" name="email" value="{{ old('customer_name', $lead->email) }}" style="width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: 500;" required>
                            </td>
                            <!-- phone number -->
                            <td style="padding: 12px 8px;">
                                <input type="text" name="phone_number" value="{{ old('customer_name', $lead->phone_number) }}" style="width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-weight: 500;" required>
                            </td>

                            <!-- State (Peninsular Malaysia Only) -->
                            <td style="padding: 12px 8px;">
                                <select name="state" style="width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px; color: #111827;" required>
                                    <option value="" disabled>Select State</option>
                                    @foreach([
                                        'Johor', 
                                        'Kedah', 
                                        'Kelantan', 
                                        'Melaka', 
                                        'Negeri Sembilan', 
                                        'Pahang', 
                                        'Perak', 
                                        'Perlis', 
                                        'Pulau Pinang', 
                                        'Selangor', 
                                        'Terengganu', 
                                        'Kuala Lumpur', 
                                        'Putrajaya'
                                    ] as $stateOption)
                                        <option value="{{ $stateOption }}" {{ old('state', $lead->state) == $stateOption ? 'selected' : '' }}>
                                            {{ $stateOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <!-- Property Type (Matches 'in:landed,condo,apartment,commercial') -->
                            <td style="padding: 12px 8px;">
                                <select name="property_type" style="width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                                    @foreach(['landed', 'condo', 'apartment', 'commercial'] as $type)
                                        <option value="{{ $type }}" {{ old('property_type', $lead->property_type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <!-- Roof Type (Matches 'in:tile,metal,flat,concrete') -->
                            <td style="padding: 12px 8px;">
                                <select name="roof_type" style="width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px;" required>
                                    @foreach(['tile', 'metal', 'flat', 'concrete'] as $roof)
                                        <option value="{{ $roof }}" {{ old('roof_type', $lead->roof_type) == $roof ? 'selected' : '' }}>
                                            {{ ucfirst($roof) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <!-- Monthly Bill -->
                            <td style="padding: 12px 8px;">
                                <input type="number" step="0.01" min="0" name="monthly_electricity_bill_rm" value="{{ old('monthly_electricity_bill_rm', $lead->monthly_electricity_bill_rm) }}" style="width: 100%; padding: 6px 8px; border: 1px solid #d1d5db; border-radius: 4px; text-align: right;" required>
                            </td>
                            
                            <!-- Save Button -->
                            <td style="padding: 12px 8px; text-align: center;">
                                <button type="submit" style="background-color: #111827; color: white; border: none; padding: 6px 12px; font-size: 0.8125rem; font-weight: 500; border-radius: 4px; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.backgroundColor='#374151'" onmouseout="this.style.backgroundColor='#111827'">
                                    Save
                                </button>
                            </td>
                        </form>


                        <!-- Dynamic Badges - Replaced loud background fills with sophisticated color accents -->
                        <td style="padding: 16px 8px; text-align: center;">
                            @if($lead->status === 'qualified')
                                <span style="color: #16a34a; font-weight: 500; font-size: 0.8125rem;">Qualified</span>
                            @elseif($lead->status === 'disqualified')
                                <span style="color: #dc2626; font-weight: 500; font-size: 0.8125rem;">Disqualified</span>
                            @else
                                <span style="color: #d97706; font-weight: 500; font-size: 0.8125rem;">Review</span>
                            @endif
                        </td>

                        <!-- Quick Inline Update Form - Styled dropdown and muted update button -->
                        <td style="padding: 16px 8px; text-align: center;">
                            <form method="POST" action="{{ route('leads.updateStatus', $lead->id) }}" style="display: flex; align-items: center; justify-content: center; gap: 6px; margin: 0;">
                                @csrf
                                @method('PATCH')
                                <select name="status" required style="padding: 4px 8px; border-radius: 4px; border: 1px solid #e5e7eb; font-size: 0.8125rem; background: #fff; color: #4b5563; outline: none; cursor: pointer;">
                                    <option value="" disabled selected>Change...</option>
                                    <option value="qualified">Qualified</option>
                                    <option value="disqualified">Disqualified</option>
                                    <option value="under_review">Under Review</option>
                                </select>
                                <button type="submit" style="background-color: #111827; color: white; border: none; padding: 5px 10px; font-size: 0.8125rem; font-weight: 500; border-radius: 4px; cursor: pointer; transition: background 0.15s;" onmouseover="this.style.backgroundColor='#374151'" onmouseout="this.style.backgroundColor='#111827'">Update Status</button>
                            </form>
                        </td>

                        <!-- View Button Link - Swapped bright royal blue link for a timeless dark neutral underline look -->
                        <td style="padding: 16px 8px; text-align: center;">
                            <a href="{{ route('leads.show', $lead->id) }}" style="color: #111827; font-weight: 500; text-decoration: none; font-size: 0.875rem;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 48px 8px; text-align: center; color: #9ca3af;">No solar leads currently found matching filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section Container -->
        <div style="margin-top: 32px; justify-content: center;">
            {{ $leads->links() }}
        </div>
    </div>
</x-layout>
