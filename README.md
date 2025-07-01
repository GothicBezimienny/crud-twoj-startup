# CRUD API - Twój Startup

## Wymagania

- PHP 8.2+
- Composer
- SQLite

## Instalacja
1. **Sklonuj repozytorium:**
```bash
git clone <repository-url>
cd crud-twoj-startup
```

2. **Zainstaluj zależności:**
```bash
composer install
```

3. **Skopiuj plik konfiguracyjny:**
```bash
cp .env.example .env
```

4. **Wygeneruj klucz aplikacji:**
```bash
php artisan key:generate
```

5. **Skonfiguruj bazę danych w pliku `.env`:**
```env
DB_CONNECTION=sqlite
```

6. **Uruchom migracje:**
```bash
php artisan migrate
```

7. **Uruchom seeder z przykładowymi danymi:**
```bash
php artisan db:seed
```

## Uruchomienie serwera

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Aplikacja będzie dostępna pod adresem: `http://127.0.0.1:8000`

## API Endpoints

### Użytkownicy (Users)

- `GET /api/users` - Lista wszystkich użytkowników z ich e-mailami
- `POST /api/users` - Utwórz nowego użytkownika
- `GET /api/users/{id}` - Pokaż użytkownika z e-mailami
- `PUT /api/users/{id}` - Aktualizuj użytkownika
- `DELETE /api/users/{id}` - Usuń użytkownika
- `POST /api/users/{id}/send-welcome` - Wyślij powitalny e-mail (informacje o wysyłce są zapisywane w logach wymuszone przez env: MAIL_MAILER=log)

### E-maile (Emails)

- `GET /api/emails` - Lista wszystkich e-maili
- `POST /api/emails` - Utwórz nowy e-mail
- `GET /api/emails/{id}` - Pokaż e-mail
- `PUT /api/emails/{id}` - Aktualizuj e-mail
- `DELETE /api/emails/{id}` - Usuń e-mail

## Przykłady użycia API

### Utworzenie użytkownika
```bash
curl -X POST http://127.0.0.1:8000/api/users \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "Jan",
    "last_name": "Kowalski",
    "phone_number": "123456789"
  }'
```

### Utworzenie e-maila
```bash
curl -X POST http://127.0.0.1:8000/api/emails \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_id": 1,
    "email": "jan.kowalski@example.com"
  }'
```

### Wysłanie powitalnego e-maila
```bash
curl -X POST http://127.0.0.1:8000/api/users/1/send-welcome \
  -H "Accept: application/json"
```

## Struktura projektu

```
app/
├── Application/
│   └── Services/
│       └── EmailService.php
├── Http/
│   ├── Controllers/
│   │   ├── UserController.php
│   │   └── EmailController.php
│   └── Requests/
│       ├── UserRequest.php
│       └── EmailRequest.php
└── Models/
    ├── User.php
    └── Email.php
```

## Konfiguracja e-maili

**Ważne:** Domyślnie aplikacja używa drivera `log` dla e-maili. Oznacza to, że e-maile nie są wysyłane, ale zapisywane w logach.

### Sprawdzenie logów e-maili:
```bash
tail -f storage/logs/laravel.log
```

## Seedery

Aplikacja zawiera seeder z przykładowymi danymi:

- **UserSeeder** - tworzy 5 użytkowników z losowymi danymi
- Każdy użytkownik ma 2-3 adresy e-mail

### Uruchomienie seedera:
```bash
php artisan db:seed
```

### Reset bazy danych i ponowne uruchomienie seedera:
```bash
php artisan migrate:fresh --seed
```

## Walidacja

Aplikacja używa Form Requests do walidacji:

- **UserRequest** - walidacja danych użytkownika
- **EmailRequest** - walidacja adresów e-mail

Wszystkie błędy walidacji zwracane są w formacie JSON z kodem 422.

## Obsługa błędów

- **422** - Błędy walidacji
- **404** - Nie znaleziono zasobu
- **500** - Błąd serwera

Wszystkie błędy są logowane w `storage/logs/laravel.log`.

## Testowanie

```bash
php artisan test
```
