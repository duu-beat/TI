<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon (Logo na Aba) --}}
    <link rel="icon" href="{{ asset('images/logosuporteTI.png') }}" type="image/png">

    {{-- 🔥 SEO DINÂMICO --}}
    <title>@yield('title', config('app.name', __('messages.seo.default_title')))</title>
    <meta name="description" content="@yield('meta_description', __('messages.seo.portal_description'))">

    {{-- Open Graph / Facebook / WhatsApp --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', __('messages.seo.default_title')))">
    <meta property="og:description" content="@yield('meta_description', __('messages.seo.portal_og_description'))">
    <meta property="og:image" content="{{ asset('images/logosuporteTI.png') }}">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', __('messages.seo.default_title')))">
    <meta property="twitter:description" content="@yield('meta_description', __('messages.seo.portal_og_description'))">
    <meta property="twitter:image" content="{{ asset('images/logosuporteTI.png') }}">

    {{-- Fonte Outfit --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased relative selection:bg-cyan-500 selection:text-white min-h-screen flex flex-col items-center justify-center p-6">

    {{-- Background Glow --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[40%] h-[40%] rounded-full bg-cyan-600/10 blur-[120px]"></div>
    </div>

    {{-- Conteúdo --}}
    <div class="relative z-10 w-full flex justify-center">
        {{ $slot }}
    </div>

</body>
</html>