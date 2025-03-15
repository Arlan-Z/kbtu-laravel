<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>
<h2>Вход</h2>
@if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p style="color: red;">{{ $error }}</p>
        @endforeach
    </div>
@endif
<form method="POST" action="{{ route('login') }}">
    @csrf
    <label>Email:</label>
    <input type="email" name="email" required>
    <br>
    <label>Пароль:</label>
    <input type="password" name="password" required>
    <br>
    <button type="submit">Войти</button>
</form>
</body>
</html>
