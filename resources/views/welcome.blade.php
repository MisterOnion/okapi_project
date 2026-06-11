<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Okapi Leads</title>

        @vite('resources/css/app.css')
    </head>
    <body>
        <header>
            <nav>
                <h1>Okapi Assessment</h1>

                <a href="{{route('leads.index')}}" class="btn">
                    Inspect Okapi Leads!
                </a>
            </nav>
        </header>

        
        <footer class="py-16 text-center text-sm text-black dark:text-black/70">
            Laravel v{{ app()->version()  }} (PHP v{{ phpversion() }})
        </footer>
    </body>
</html>