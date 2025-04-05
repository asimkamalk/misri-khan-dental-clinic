{{-- resources/views/errors/layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Misri Khan Dental Clinic') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .error-code {
            font-size: 100px;
            font-weight: 700;
            color: #3b82f6;
            margin: 0;
            line-height: 1;
        }

        .error-title {
            font-size: 24px;
            margin-top: 10px;
            margin-bottom: 20px;
            color: #1f2937;
        }

        .error-description {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #2563eb;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <h1 class="error-code">@yield('code')</h1>
        <h2 class="error-title">@yield('title')</h2>
        <div class="error-description">@yield('message')</div>
        <a href="{{ url('/') }}" class="btn">Return to Homepage</a>
    </div>
</body>

</html>

{{-- resources/views/errors/404.blade.php --}}
@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('message', 'Sorry, the page you are looking for could not be found.')

{{-- resources/views/errors/403.blade.php --}}
@extends('errors.layout')

@section('title', 'Forbidden')
@section('code', '403')
@section('message', 'Sorry, you are not authorized to access this page.')

{{-- resources/views/errors/500.blade.php --}}
@extends('errors.layout')

@section('title', 'Server Error')
@section('code', '500')
@section('message', 'Oops! Something went wrong on our servers.')

{{-- resources/views/errors/419.blade.php --}}
@extends('errors.layout')

@section('title', 'Page Expired')
@section('code', '419')
@section('message', 'Your session has expired. Please refresh and try again.')

{{-- resources/views/errors/429.blade.php --}}
@extends('errors.layout')

@section('title', 'Too Many Requests')
@section('code', '429')
@section('message', 'You have made too many requests recently. Please try again later.')
