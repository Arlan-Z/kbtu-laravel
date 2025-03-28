# CRUD статей
*   **Создание (Create):**  Доступно только пользователям с ролью "User".  Перейдите по адресу `/articles/create` (после входа в систему).
*   **Чтение (Read):**
    *   Пользователи видят только свои статьи (`/articles`).
    *   Модераторы видят все статьи (`/articles`).
    *   Просмотр отдельной статьи: `/articles/{article}`.
*   **Обновление (Update):**  Доступно пользователям для своих статей и модераторам для любых статей (`/articles/{article}/edit`).
*   **Удаление (Delete):**  Доступно пользователям для своих статей и модераторам для любых статей (через форму на странице просмотра или редактирования статьи).

## Авторизация (Политики)

Авторизация (управление доступом) реализована с использованием Laravel Policies.  Файл `app/Policies/ArticlePolicy.php` определяет, какие действия разрешены пользователям с разными ролями.

# Categories, Products Api
## Аутентификация

Большинство эндпоинтов требуют аутентификации через **Bearer Token** Laravel Sanctum.

1.  **Получение токена:**
    *   Отправьте POST-запрос на `{{baseUrl}}/api/login`.
    *   **Тело запроса (JSON):**
        ```json
        {
            "email": "your_user_email@example.com", // admin@example.com
            "password": "your_password" // admin123
        }
        ```
    *   **Успешный ответ (200 OK):**
        ```json
        {
            "token": "YOUR_AUTH_TOKEN"
        }
        ```
    *   **Ошибка (401 Unauthorized):**
        ```json
        {
            "message": "Invalid credentials"
        }
        ```

2.  **Использование токена:**
    *   Включите следующий заголовок во все защищенные запросы:
        ```
        Authorization: Bearer YOUR_AUTH_TOKEN
        ```

3.  **Завершение сеанса (Logout):**
    *   **Метод:** `POST`
    *   **URL:** `{{baseUrl}}/api/logout`
    *   **Аутентификация:** Требуется (Bearer Token).
    *   **Описание:** Отзывает текущий использованный токен доступа.
    *   **Успешный ответ (200 OK):**
        ```json
        {
            "message": "Logged out successfully"
        }
        ```

## Форматы Данных и Заголовки

*   **Тело запроса:** Для `POST` и `PUT` используйте формат `JSON`. Установите заголовок:
    `Content-Type: application/json`
*   **Ответы:** API возвращает ответы в формате `JSON`. Рекомендуется использовать заголовок:
    `Accept: application/json`

## Обработка Ошибок

*   **401 Unauthorized:** Неверные учетные данные при логине или отсутствует/невалидный токен для защищенных эндпоинтов.
*   **403 Forbidden:** У вас нет прав на выполнение этого действия (менее вероятно с текущей настройкой, но возможно при расширении политик).
*   **404 Not Found:** Запрошенный ресурс (категория, продукт) не найден по указанному ID или слагу.
*   **422 Unprocessable Entity:** Ошибки валидации данных. Тело ответа будет содержать объект `errors` с описанием проблем:
    ```json
    {
        "errors": {
            "field_name": [
                "Сообщение об ошибке валидации."
            ]
        }
    }
    ```

## Эндпоинты API

---

### Категории (Categories)

**1. Получить все категории**

*   **Метод:** `GET`
*   **URL:** `{{baseUrl}}/api/categories`
*   **Аутентификация:** Не требуется.
*   **Описание:** Возвращает список всех категорий, отсортированных по имени.
*   **Успешный ответ (200 OK):**
    ```json
    [
        {
            "id": 1,
            "name": "Аксессуары",
            "slug": "aksessuary",
            "created_at": "...",
            "updated_at": "..."
        },
        {
            "id": 2,
            "name": "Смартфоны",
            "slug": "smartfony",
            "created_at": "...",
            "updated_at": "..."
        }
        // ... другие категории
    ]
    ```

**2. Создать новую категорию**

*   **Метод:** `POST`
*   **URL:** `{{baseUrl}}/api/categories`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Описание:** Создает новую категорию. Слаг генерируется автоматически из имени.
*   **Тело запроса (JSON):**
    ```json
    {
        "name": "Ноутбуки и Компьютеры"
    }
    ```
*   **Успешный ответ (201 Created):**
    ```json
    {
        "id": 3,
        "name": "Ноутбуки и Компьютеры",
        "slug": "noutbuki-i-komputery",
        "created_at": "...",
        "updated_at": "..."
    }
    ```

**3. Получить одну категорию (по слагу)**

*   **Метод:** `GET`
*   **URL:** `{{baseUrl}}/api/categories/{category:slug}`
*   **Аутентификация:** Не требуется.
*   **Параметр URL:**
    *   `{category:slug}`: Слаг категории (например, `smartfony`).
*   **Описание:** Возвращает детали одной категории, найденной по её слагу.
*   **Успешный ответ (200 OK):**
    ```json
    {
        "id": 2,
        "name": "Смартфоны",
        "slug": "smartfony",
        "created_at": "...",
        "updated_at": "..."
    }
    ```

**4. Обновить категорию**

*   **Метод:** `PUT`
*   **URL:** `{{baseUrl}}/api/categories/{category}`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Параметр URL:**
    *   `{category}`: **ID** категории, которую нужно обновить.
*   **Описание:** Полностью обновляет данные категории. Слаг будет перегенерирован.
*   **Тело запроса (JSON):**
    ```json
    {
        "name": "Обновленные Смартфоны"
    }
    ```
*   **Успешный ответ (200 OK):**
    ```json
    {
        "id": 2,
        "name": "Обновленные Смартфоны",
        "slug": "obnovlennye-smartfony",
        "created_at": "...", // Остается прежним
        "updated_at": "..."  // Обновляется
    }
    ```

**5. Удалить категорию**

*   **Метод:** `DELETE`
*   **URL:** `{{baseUrl}}/api/categories/{category}`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Параметр URL:**
    *   `{category}`: **ID** категории, которую нужно удалить.
*   **Описание:** Удаляет категорию. Если настроено `onDelete('cascade')`, также удаляются связанные записи в промежуточной таблице `category_product`.
*   **Успешный ответ (204 No Content):** Пустое тело ответа.

---

### Продукты (Products)

**1. Получить все продукты**

*   **Метод:** `GET`
*   **URL:** `{{baseUrl}}/api/products`
*   **Аутентификация:** Не требуется.
*   **Описание:** Возвращает список всех продуктов, включая связанные с ними категории, отсортированных по имени продукта.
*   **Успешный ответ (200 OK):**
    ```json
    [
        {
            "id": 1,
            "name": "iPhone 15",
            "slug": "iphone-15",
            "description": "...",
            "price": "999.00",
            "created_at": "...",
            "updated_at": "...",
            "categories": [
                { "id": 2, "name": "Смартфоны", "slug": "smartfony", ... },
                { "id": 5, "name": "Новинки", "slug": "novinki", ... }
            ]
        },
        // ... другие продукты
    ]
    ```

**2. Создать новый продукт**

*   **Метод:** `POST`
*   **URL:** `{{baseUrl}}/api/products`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Описание:** Создает новый продукт и связывает его с указанными категориями. Слаг генерируется автоматически.
*   **Тело запроса (JSON):**
    ```json
    {
        "name": "Беспроводные Наушники Z",
        "description": "Отличное звучание и шумоподавление.",
        "price": 149.50,
        "categories": [1, 8] // Массив ID существующих категорий
    }
    ```
*   **Успешный ответ (201 Created):** (Возвращает созданный продукт с категориями)
    ```json
    {
        "id": 52,
        "name": "Беспроводные Наушники Z",
        "slug": "besprovodnye-naushniki-z",
        "description": "Отличное звучание и шумоподавление.",
        "price": "149.50",
        "created_at": "...",
        "updated_at": "...",
        "categories": [
             { "id": 1, "name": "Аксессуары", ... },
             { "id": 8, "name": "Аудио", ... }
        ]
    }
    ```

**3. Получить один продукт (по ID)**

*   **Метод:** `GET`
*   **URL:** `{{baseUrl}}/api/products/{product}`
*   **Аутентификация:** Не требуется.
*   **Параметр URL:**
    *   `{product}`: **ID** продукта.
*   **Описание:** Возвращает детали одного продукта, включая связанные категории.
*   **Успешный ответ (200 OK):** (Структура как в примере для POST, но с данными запрошенного продукта).

**4. Обновить продукт**

*   **Метод:** `PUT`
*   **URL:** `{{baseUrl}}/api/products/{product}`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Параметр URL:**
    *   `{product}`: **ID** продукта, который нужно обновить.
*   **Описание:** Полностью обновляет данные продукта и **синхронизирует** его категории (удаляет старые связи, добавляет новые).
*   **Тело запроса (JSON):**
    ```json
    {
        "name": "Обновленные Наушники Z+",
        "description": "Теперь с поддержкой Bluetooth 5.3.",
        "price": 159.99,
        "categories": [8] // Оставить только категорию с ID 8
    }
    ```
*   **Успешный ответ (200 OK):** (Возвращает обновленный продукт с новыми категориями).

**5. Удалить продукт**

*   **Метод:** `DELETE`
*   **URL:** `{{baseUrl}}/api/products/{product}`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Параметр URL:**
    *   `{product}`: **ID** продукта, который нужно удалить.
*   **Описание:** Удаляет продукт. Связи в промежуточной таблице также удаляются (если настроено `onDelete('cascade')`).
*   **Успешный ответ (204 No Content):** Пустое тело ответа.

---

### Связи (Relationships)

**1. Получить все категории для продукта**

*   **Метод:** `GET`
*   **URL:** `{{baseUrl}}/api/products/{product}/categories`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Параметр URL:**
    *   `{product}`: **ID** продукта, для которого нужно получить категории.
*   **Описание:** Возвращает список всех категорий, связанных с указанным продуктом.
*   **Успешный ответ (200 OK):**
    ```json
    [
        { "id": 1, "name": "Аксессуары", "slug": "aksessuary", ... },
        { "id": 8, "name": "Аудио", "slug": "audio", ... }
    ]
    ```

---

### Пользователь (User)

**1. Получить данные аутентифицированного пользователя**

*   **Метод:** `GET`
*   **URL:** `{{baseUrl}}/api/user`
*   **Аутентификация:** Требуется (Bearer Token).
*   **Описание:** Возвращает информацию о пользователе, которому принадлежит текущий токен. Полезно для проверки токена и получения данных пользователя на клиенте.
*   **Успешный ответ (200 OK):**
    ```json
    {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com",
        "email_verified_at": null,
        // ... другие поля пользователя (кроме скрытых)
        "created_at": "...",
        "updated_at": "..."
    }
    ```

---
