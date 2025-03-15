<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
</head>
<body>
<h2>Профиль пользователя</h2>
<p><strong>Имя:</strong> {{ $user->name }}</p>
<p><strong>Email:</strong> {{ $user->email }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Выйти</button>
</form>
</body>
</html>
