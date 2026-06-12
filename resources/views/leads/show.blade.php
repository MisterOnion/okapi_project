<x-layout>
    {{-- since we passing objects, need to specify data within the object --}}
    <h2>Lead id - #{{ $lead->id }}</h2>
    <div class="bg-gray-200 p-4 rounded">
        <p>{{ $lead->customer_name }}</p>
        <p>{{ $lead->email }}</p>
        <p>{{ $lead->phone_number }}</p>
        <p>RM {{ $lead->monthly_electricity_bill_rm }}</p>
        <p>{{ $lead->property_type }}</p>
        <p>{{ $lead->roof_type }}</p>
        <p>{{ $lead->state }}</p>
        <p><b>Created at: </b>{{ $lead->created_at }}</p>
        <p><b>Updated at: </b> {{ $lead->updated_at}}</p>
        <p><b>Status: </b> {{ $lead->status}}</p>
    </div>
</x-layout>