<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Teman Jalan')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 10px 25px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #555;
        }
        .back:hover {
            color: black;
        }
        h1, h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    {{-- Bagian konten utama tiap halaman --}}
    @yield('content')
</body>
</html>
