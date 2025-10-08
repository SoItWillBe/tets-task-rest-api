# Tets Task REST API

## Description
REST API for user management. The project is implemented in PHP using a layered architecture (Controllers, Services, Core, Helpers, Interfaces). Data is stored in MariaDB.

## Quick Start

### 1. Clone the repository
```bash
git clone https://github.com/SoItWillBe/tets-task-rest-api.git
cd tets-task-rest-api
```

### 2. Start the database
Run SQL scripts from the `sql/` to store data in database.
Prepare connection and run 
`mysql -u your_username -p your_database_name < EACH_FILE`

Not very convenient, but easy...

### 3. Configure the connection
Copy `config/config.php.txt` without as `.php` file `config/config.php`.

Set database connection parameters in `config/config.php`:
- host: `127.0.0.1` (if the application runs on the host)
- user: `your_username`
- pass: `your_password`
- db_name: `your_database_name`

### 4. Install dependencies
```
composer install
composer dumpautoload
```

### 5. Run the application
Entry point: `public/index.php`.

Run `php -S 127.0.0.1:8080 public/index.php` in CLI to start app.

## Project Structure
- `src/Controllers` — controllers
- `src/Services` — business logic
- `src/Core` — application core
- `src/Helpers` — helper classes
- `src/Interfaces` — interfaces
- `sql/` — SQL scripts for database initialization
- `public/` — public directory (entry point)

## API Endpoints
"API Endpoints"
Endpoints stored in `config/routes.php`.

Short doc for usage:

(API Endpoints)

Endpoints marked with `auth()` require a Bearer token in the Authorization header.

| Method | Endpoint     | Controller & Action         | Auth Required |
|--------|--------------|----------------------------|--------------|
| GET    | `/`          | Welcome::index             | No           |
| GET    | `/users`     | UserController::index      | Yes          |
| GET    | `/users/:id` | UserController::show       | Yes          |
| PUT    | `/users/:id` | UserController::update     | Yes          |
| DELETE | `/users/:id` | UserController::delete     | Yes          |
| POST   | `/login`     | AuthController::login      | No           |
| POST   | `/logout`    | AuthController::login      | Yes          |
| POST   | `/register`  | AuthController::register   | No           |

Confirm login with `Authorization: Bearer <token>` header.

Examples of usage:

`POST /login`
Login body data as form-data or x-www-form-urlencoded:
`email` and `password`
Returns token in header.

`POST /register`
Login body data as form-data or x-www-form-urlencoded:
`email` and `password`.
Automatically log in , returns token in header on success.

`PUT /users/:id`
You can only update your current user.
Expects json body:
`
{
    "email": "updated@mail.com",
    "password": "1234"
}
`

`GET /users`
Returns all users. Only for authorized users.
You can add filer for any attribute. Example:
`GET /users?age=22&city=lviv`



You can use token `d39e201ba4621bcf43589023fe79502e31373539383533313234` if you ran all sql files earlier.

Otherwise you can login with any email and password `1234` or register your own user.