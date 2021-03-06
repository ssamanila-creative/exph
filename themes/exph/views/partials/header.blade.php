<!DOCTYPE html>
<html lang="en">
<head>
    @stack("pre-meta")
    @stack("meta")
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="_token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ url('favicons/manifest.json') }}">
    <link rel="mask-icon" color="#f1b53c" href="{{ url('favicons/safari-pinned-tab.svg') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->
    <title>
        @section("head-title"){{ isset($application) && isset($application->head->title) ? __($application->head->title ): '' }}@show
        @section("head-subtitle"){{ isset($application) && isset($application->head->subtitle) ? __($application->head->subtitle) : '' }}@show
    </title>
    <meta name="description" content="{{ __(@$application->head->description) }}">
    @stack("post-meta")

    @stack("pre-css")

    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    {{-- Theme Specific --}}
    <link href="{{ theme('css/style.css') }}?v={{ $application->version }}" rel="stylesheet">
    {{-- /Theme Specific --}}

    @stack("css")
        {{-- compile this --}}
        @if (env('APP_ENV', 'production') == 'development')
            {{-- <script src="https://unpkg.com/vue/dist/vue.js"></script> --}}
            <script src="{{ assets('frontier/vendors/vue/dist/vue.js') }}?v={{ $application->version }}"></script>
        @else
            <script src="{{ assets('frontier/vendors/vue/dist/vue.min.js') }}?v={{ $application->version }}"></script>
        @endif
        <script src="{{ assets('frontier/vendors/vuetify/dist/vuetify.min.js') }}?v={{ $application->version }}"></script>
        {{-- ^complile this --}}
    @show

    @stack("post-css")
</head>
<body class="with-backdrop">
    @include("Theme::warnings.general")
    @include("Theme::partials.backdrop")
