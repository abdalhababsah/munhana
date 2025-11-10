<!DOCTYPE html>
<html lang="en" data-sidebar-color="light" data-topbar-color="light" data-sidebar-view="default">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Authentication') | TailFox - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="MyraStudio" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('dash/assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('dash/assets/css/theme.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dash/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dash/assets/libs/@iconscout/unicons/css/line.css') }}" type="text/css" rel="stylesheet">

    <!-- Head Js -->
    <script src="{{ asset('dash/assets/js/head.js') }}"></script>
</head>

<body class=" h-screen w-screen flex justify-center items-center">
    @yield('content')
</body>

</html>
