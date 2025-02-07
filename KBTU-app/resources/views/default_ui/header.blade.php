<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    @stack('styles')
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .header {
            width: 100%;
            min-height: 7.5vmin;
            background-color: #4CAF50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-sizing: border-box;
            color: white;
            font-weight: bold;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header-block {
            display: flex;
            align-items: center;
            font-size: 1.2rem;
        }
        .nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }
        .nav li {
            margin: 0 15px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .nav li:hover {
            text-decoration: underline;
            color: #ffeb3b;
        }
        main {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    @stack('scripts')
</head>
<body>
<div class="header">
    <div class="header-block">Header</div>
    <ul class="nav">
        <li class="url">Home</li>
        <li class="url">About</li>
    </ul>
</div>
<main>
    @yield('content')
</main>
</body>
</html>
