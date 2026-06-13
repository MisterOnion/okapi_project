<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Okapi Leads</title>

        @vite('resources/css/app.css')
    </head>
    <body>

        <header>
            <nav>
                <h1>Okapi Leads</h1>
                {{-- <a href="/leads">Leads Received</a> --}}
                <a href="{{ route('leads.index') }}">Leads Received</a>
                <a href="{{ route('leads.create') }}">Admin Dashboard</a>
                <a href="{{ route('emails.customer') }}">Customer Email Template</a>
                <a href="{{ route('emails.internal') }}">Internal Team Email Template</a>
            </nav>
        </header>

        <main class="container">
            {{ $slot }}
        </main>

    </body>
</html>