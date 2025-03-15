<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
<h2>Регистрация</h2>

@if ($errors->any())
    <div style="color: red;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    <label for="name">Имя:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Пароль:</label>
    <input type="password" id="password" name="password" required>

    <label for="password_confirmation">Подтвердите пароль:</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>

    <button type="submit">Зарегистрироваться</button>
</form>
</body>
</html>
