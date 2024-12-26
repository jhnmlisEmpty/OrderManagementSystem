<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="icon" href="{{ asset('storage/other/companyLogo.png') }}" type="image/jpeg">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        

        <section class="flex flex-col md:flex-row h-screen items-center">

            <div class="bg-blue-600 hidden lg:block w-full md:w-1/2 xl:w-2/3 h-screen">
              <img src="https://images.unsplash.com/photo-1444313431167-e7921088a9d3?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1441&q=100" alt="" class="w-full h-full object-cover">
            </div>
          
            <div class="bg-white w-full md:max-w-md lg:max-w-full md:mx-auto md:w-1/2 xl:w-1/3 h-screen px-6 lg:px-16 xl:px-12
                  flex items-center justify-center">
          
              <div class="w-full h-100">
          
                <h1 class="text-xl font-bold">G7 GLOBAL</h1>
          
                <h1 class="text-xl md:text-2xl font-bold leading-tight mt-12">Log in to your account</h1>
          
                {{ $slot }}
              </div>
            </div>
          
          </section>
    </body>
</html>
