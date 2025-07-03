<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="https://smkn2sumedang.sch.id/images/profil/sejarah.png">
    <title>{{ config('app.name', 'Smkn 2 Sumedang') }}</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-dark">

  <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">

    <div class="w-100" style="max-width: 500px;">
      <div>
        {{ $slot }}
      </div>
    </div>
  </div>

</body>
</html>
