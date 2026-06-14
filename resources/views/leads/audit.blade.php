<x-layout>
    <h2>Audit Table</h2>

    @if($logs->isEmpty())
    <p>No activity recorded yet.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
            <thead style="background:#f0f0f0;">
                <tr>
                    <th>Event</th>
                    <th>Field</th>
                    <th>From</th>
                    <th>To</th>
                    <th>When</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->event }}</td>
                    <td>{{ $log->field }}</td>
                    <td>{{ $log->old_value ?? '—' }}</td>
                    <td>{{ $log->new_value ?? '—' }}</td>
                    <td>{{ $log->changed_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</x-layout>