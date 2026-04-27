## API RESTFul with Slim 4 

This template is made with the idea of being easy to modify for small projects, includes Eloquent ORM and authentication via JWT

If you want a even **simpler template** look at [slim4-api-min](https://github.com/devvdiego/slim4-api-min)

## Quick start guide

Follow this instructions to start using the API in your local enviroment

### 1. Install dependencies
Using **Composer** execute the next command in the project root:

```bash
composer install

```

### 2. Configure your .env file

Copy the example .env and configure your credentials depending on the
enviroment you're working (Either development or production)

It is **vital** that you set a safe password for the admin account

1. Create `.env` file:
```bash
cp .env.example .env

```


2. Open `.env` and make sure to configure all the needed varaibles
* `DB_*`, etc. (Database configuration)
* `ADMIN_PASSWORD_HASH`: The hash of the admin account password.

**Tip:** To create quickly your admin password (hashed), type the following command:
```bash
php -r "echo password_hash('your_password', PASSWORD_BCRYPT);"

```

**Tip:** To create your JWT, type the following command (Minimun of 32 characters):
```bash

php -r "echo bin2hex(random_bytes(32));"

```
---


## 🔐 Security and Authentication

### JWT-based Authentication (Stateless)

The API implements **stateless authentication** using **JSON Web Tokens (JWT)**. This allows multiple users with different roles to authenticate and interact with the API.

### How it works

1. **User registration** – New users can be created via `POST /users` (public endpoint).
2. **Login** – `POST /login` receives email/password, validates credentials, and returns a JWT.
3. **Token usage** – The client must include the token in subsequent requests:
   ```
   Authorization: Bearer <jwt_token>
   ```
4. **Authentication middleware** – Protected routes include `AuthMiddleware`, which validates the token and loads the authenticated user into the static `Auth::user()` class.
5. **Session info** – `GET /session` returns the currently authenticated user's data (id, email, role, expiration).

### Password hashing

All passwords are securely hashed using PHP's native `password_hash()` with the default BCRYPT algorithm. Verification is done via `password_verify()`.

### Accessing the authenticated user

Anywhere after the middleware (controllers, services, repositories), you can retrieve the current user via:

```php
use App\Auth\Auth;

$user = Auth::user(); // stdClass with sub, email, data->role, etc.
$userId = Auth::id();
$userEmail = Auth::email();
```

### Role-based access control – (Planned)

The payload contains the user's role, making it easy to implement role-specific middleware. Example (to be implemented):

### Security considerations

- **JWT secret** must be at least 32 characters long and stored in `.env` (`JWT_SECRET`).
- **HTTPS** is strongly recommended in production to prevent token interception.
- **CORS** is configurable via `.env` variables (`ALLOWED_ORIGINS`, `ALLOWED_HEADERS`, `ALLOWED_METHODS`).


## 🤝 Contributions

Contributions are what make open source an amazing place to learn and create. This project can improve drastically, therefore **any contribution is welcome**.

If you have any improvement idea for the API (security, performance, new base routes, etc.), feel free to follow these steps to contribute:

1. **Make a fork** of the proyect.
2. **Create a branch** where you specify your contribution (`git checkout -b feature/improvement`).
3. **Make your changes** (would really apreciate if you follow good coding conventions).
4. **Make a commit** with your changes (`git commit -m 'Add: some amazing improvement'`).
5. **Push your changes** to the branch (`git push origin feature/improvement`).
6. **Open a pull request** and explain the changes you've made.


All suggestions, bug reports or documentation will be really apreciated.
