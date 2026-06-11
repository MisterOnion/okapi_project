<x-layout>
    <h2>Leads Received</h2>
    <ul>
        @foreach($leads as $lead)
            <li>
            {{-- attribute (of existing data) and prop (dynamic data like conditionals) passing--}}
                {{-- <x-card href="/leads/{{ $lead->id }}" :highlight="isset($lead['email'])"> --}}
                
                {{-- can put in wildcard with route func   --}}
                <x-card href="{{ route('leads.show', $lead->id) }}" :highlight="isset($lead['email'])">
                    <h3>Lead {{ $lead->id }}, {{ $lead->customer_name }}</h3>
                    {{-- <h3>{{ $lead['id'] }}</h3> --}}
                </x-card>
            </li>
        @endforeach
    </ul>

    {{-- dynamically implement pagination links here --}}
    {{ $leads->links() }}
</x-layout>