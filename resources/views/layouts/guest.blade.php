<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Aclonica&family=Lobster+Two&family=Poppins:wght@200;300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div style="font-family: 'Poppins', sans-serif;"
        x-data="mainState"
        class="antialiased"
        :class="{dark: isDarkMode}"
        x-cloak
    >
        <div class="flex flex-col min-h-screen text-gray-900 bg-gray-100 dark:bg-dark-eval-0 dark:text-gray-200">
            {{ $slot }}

        </div>

        {{-- <div class="fixed top-10 right-10">
            <x-button
                type="button"
                icon-only
                variant="secondary"
                sr-text="Toggle dark mode"
                x-on:click="toggleTheme"
            >
                <x-heroicon-o-moon
                    x-show="!isDarkMode"
                    aria-hidden="true"
                    class="w-6 h-6"
                />

                <x-heroicon-o-sun
                    x-show="isDarkMode"
                    aria-hidden="true"
                    class="w-6 h-6"
                />
            </x-button>
        </div> --}}
    </div>
</body>
</html>
