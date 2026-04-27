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

### Admin-only API

The API is designed for **internal administration only**. All authenticated users are administrators by default. Therefore, there is no public user creation route

### Role enforcement

Since the application only manages admin accounts But there is no distinction between "authenticated user" and "admin user" for the api, they are the same.

If you ever need to introduce regular users in the future, you can extract the role check into a separate `RoleMiddleware`.


### Role-based access control – (Planned)

The payload contains the user's role, making it easy to implement role-specific middleware. (to be implemented):


### Password hashing

All passwords are securely hashed using PHP's native `password_hash()` with the default BCRYPT algorithm. Verification is done via `password_verify()`.

### Accessing the authenticated user

Anywhere after the middleware, you can retrieve the current user via:

```php
use App\Auth\Auth;

$user = Auth::user(); // stdClass with sub, email, data->role, etc.
$userId = Auth::id();
$userEmail = Auth::email();
```


### Security considerations

- **JWT secret** must be at least 32 characters long and stored in `.env` (`JWT_SECRET`).
- **HTTPS** is strongly recommended in production to prevent token interception.
- **CORS** is configurable via `.env` variables (`ALLOWED_ORIGINS`, `ALLOWED_HEADERS`, `ALLOWED_METHODS`).

---

## Database Migrations (Route-based)

Unlike traditional migration systems (like Laravel's Artisan or Phinx), this API implements a **simplified, route-driven migration system** that runs directly from an HTTP endpoint.

### How it works

The endpoint `/admin/db` (protected by authentication) triggers the `SchemaManager::sync()` method, which:

1. **Disables foreign key constraints** temporarily to avoid conflicts during table creation.
2. **Checks for missing tables** – For each model (users, customers, tickets, etc.), it verifies if the corresponding table exists.
3. **Creates missing tables** – If a table doesn't exist, it calls the table's schema class to build it.
4. **Applies column updates** – The `updateTables()` method checks for new columns that need to be added to existing tables.
5. **Re-enables foreign key constraints** – Regardless of success or failure.

### Schema definition pattern

Each entity has its own schema class that defines the table structure:

### Column evolution with `alter()` helper

When you need to add a new column to an existing table, you don't rebuild the whole table. Instead, you add it to the `updateTables()` method:

```php
private function updateTables(): int {
    $updatesCount = 0;
    
    // Adding a 'phone' column to 'users' table
    $updatesCount += (int) $this->alter('users', 'phone', function($table) {
        $table->string('phone', 20)->nullable();
    });
    
    // Adding a 'priority' column to 'tickets' table
    $updatesCount += (int) $this->alter('tickets', 'priority', function($table) {
        $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
    });
    
    return $updatesCount;
}
```

The `alter()` method:
- Checks if the table exists before attempting modifications.
- Checks if the column already exists (to avoid duplication).
- Applies the callback only if both conditions are met.

### Response example

**Success (created new tables):**
```json
{
    "status": "success",
    "message": "Tables created: users, customers. Applied 2 new columns"
}
```

**Success (everything up to date):**
```json
{
    "status": "success", 
    "message": "Tables created: . Applied 0 new columns"
}
```

**Error:**
```json
{
    "status": "error",
    "message": "Error creating tables: Base table or view already exists"
}
```

### Advantages of this approach

- **No CLI required** – Perfect for shared hosting environments without command-line access.
- **Idempotent** – Running it multiple times only creates missing tables and adds new columns.
- **Self-contained** – Database structure is versioned alongside the application code.
- **Easy to test** – You can programmatically trigger migrations in test suites.

### Limitations (and why they're acceptable for this use case)

- No rollback support (for learning/development scenarios, you can manually drop tables).
- Sequential execution only (not a bottleneck for small to medium projects).
- No migration history table (but you can easily add one if needed).


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
