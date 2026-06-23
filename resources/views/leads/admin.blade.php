<x-layout>
    <head>
        @vite('resources/css/admin.css')
    </head>

    <!-- Stripped heavy borders and background card styling for a clean canvas -->
    <div class="dashboard-container">
        
        <!-- Header Section - Lightened borders and balanced spacing -->
        <div class="dashboard-header">
            <h2 class="dashboard-title">Admin Dashboard</h2>
            
            <!-- Filter Form -->
            <form method="GET" action="{{ route('leads.admin') }}" class="filter-form">
                @csrf
                <label for="status" class="filter-label">Filter Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="filter-select">
                    <option value="">All Leads</option>
                    <option value="qualified"    {{ ($statusFilter ?? '') === 'qualified'    ? 'selected' : '' }}>Qualified</option>
                    <option value="disqualified" {{ ($statusFilter ?? '') === 'disqualified' ? 'selected' : '' }}>Disqualified</option>
                    <option value="under_review" {{ ($statusFilter ?? '') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                </select>
            </form>
        </div>

        <!-- Success Toast Notification - Refined to match clean palette -->
        @if (session('success'))
            <div class="success-toast">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Data Table Container - Removed card shadows, borders, and fixed background -->
        <div class="table-responsive-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>State</th>
                        <th>Property</th>
                        <th>Roof Type</th>
                        <th>Bill (RM)</th>
                        <th>Update Details</th>
                        <th>Status</th>
                        <th>Update Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                    <!-- Rows transition softly via opacity instead of shifting backgrounds -->
                    <tr>
                        <form method="POST" action="{{ route('leads.update', $lead->id) }}" style="display: contents;">
                            @csrf
                            @method('PATCH')                                
                            
                            <!-- Customer Name -->
                            <td>
                                <input type="text" name="customer_name" value="{{ old('customer_name', $lead->customer_name) }}" class="admin-input" required>
                            </td>
                            <!-- email -->
                            <td>
                                <input type="text" name="email" value="{{ old('customer_name', $lead->email) }}" class="admin-input" required>
                            </td>
                            <!-- phone number -->
                            <td>
                                <input type="text" name="phone_number" value="{{ old('customer_name', $lead->phone_number) }}" class="admin-input" required>
                            </td>

                            <!-- State (Peninsular Malaysia Only) -->
                            <td>
                                <select name="state" class="table-select" required>
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
                            <td>
                                <select name="property_type" class="table-input" required>
                                    @foreach(['landed', 'condo', 'apartment', 'commercial'] as $type)
                                        <option value="{{ $type }}" {{ old('property_type', $lead->property_type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <!-- Roof Type (Matches 'in:tile,metal,flat,concrete') -->
                            <td>
                                <select name="roof_type" class="table-input" required>
                                    @foreach(['tile', 'metal', 'flat', 'concrete'] as $roof)
                                        <option value="{{ $roof }}" {{ old('roof_type', $lead->roof_type) == $roof ? 'selected' : '' }}>
                                            {{ ucfirst($roof) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            
                            <!-- Monthly Bill -->
                            <td>
                                <input type="number" step="0.01" min="0" name="monthly_electricity_bill_rm" value="{{ old('monthly_electricity_bill_rm', 
                                $lead->monthly_electricity_bill_rm) }}" class="admin-input" required>
                            </td>
                            
                            <!-- Save Button -->
                            <td>
                                <button type="submit" class="admin-button">
                                    Save
                                </button>
                            </td>
                        </form>


                        <!-- Dynamic Badges - Replaced loud background fills with sophisticated color accents -->
                        <td>
                            @if($lead->status === 'qualified')
                                <span class="qualified">Qualified</span>
                            @elseif($lead->status === 'disqualified')
                                <span class="disqualified">Disqualified</span>
                            @else
                                <span class="review">Review</span>
                            @endif
                        </td>

                        <!-- Quick Inline Update Form - Styled dropdown and muted update button -->
                        <td>
                            <form method="POST" action="{{ route('leads.updateStatus', $lead->id) }}" 
                                style="display: flex; align-items: center; justify-content: center; gap: 6px; margin: 0;">
                                @csrf
                                @method('PATCH')
                                <select name="status" required class="admin-status">
                                    <option value="" disabled selected>Change...</option>
                                    <option value="qualified">Qualified</option>
                                    <option value="disqualified">Disqualified</option>
                                    <option value="under_review">Under Review</option>
                                </select>
                                <button type="submit" class="admin-button">Update Status</button>
                            </form>
                        </td>

                        <!-- View Button Link - Swapped bright royal blue link for a timeless dark neutral underline look -->
                        <td>
                            <a href="{{ route('leads.show', $lead->id) }}" class="admin-view" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="admin-no-solar">No solar leads currently found matching filters.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section Container -->
        <div class="admin-pagination">
            {{ $leads->links() }}
        </div>
    </div>
</x-layout>
