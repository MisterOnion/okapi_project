<x-layout>
    <h2>Leads Received</h2>

    <ul>
        @foreach($leads as $lead)
            <li>
            {{-- attribute (of existing data) and  
            prop (dynamic data like conditionals) passing--}}
                <x-card href="/leads/{{ $lead['lead_id'] }}" :highlight="isset($lead['email'])">
                    <h3>{{ $lead['lead_id'] }}</h3>
                </x-card>
            </li>
        @endforeach
    </ul>
</x-layout>