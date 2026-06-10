<html lang="en">
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        {{-- @vite('resources/css/app.css') --}}
    </head>
    <body>
        <header>
            <nav>
                <h1>Okapi Assessment</h1>
                
                <a href="/leads" class="btn">
                    Inspect Okapi Leads!
                </a>
            </nav>
        </header>

        
        <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            Laravel v{{ app()->version()  }} (PHP v{{ phpversion() }})
        </footer>
    </body>
</html>