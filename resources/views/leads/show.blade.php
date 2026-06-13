<x-layout>
    {{-- since we passing objects, need to specify data within the object --}}
    <h2>Lead id - #{{ $lead->id }}</h2>
    <div class="bg-gray-200 p-4 rounded">
        <p><b>Customer Name: </b>{{ $lead->customer_name }}</p>
        <p><b>Email: </b>{{ $lead->email }}</p>
        <p><b>Phone Number: </b>{{ $lead->phone_number }}</p>
        <p><b>Monthly Electricity Bill: </b>RM {{ $lead->monthly_electricity_bill_rm }}</p>
        <p><b>Property Type: </b>{{ $lead->property_type }}</p>
        <p><b>Roof Type: </b>{{ $lead->roof_type }}</p>
        <p><b>State: </b>{{ $lead->state }}</p>
        <p><b>Created at: </b>{{ $lead->created_at }}</p>
        <p><b>Updated at: </b> {{ $lead->updated_at}}</p>
        <p><b>Status: </b> {{ $lead->status}}</p>
    </div>
</x-layout>