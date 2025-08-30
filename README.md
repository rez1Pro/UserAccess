# 📦 Laravel UserAccess

A Laravel package to manage **Roles & Permissions** using Models, Enums, and Facade support.  
Easily integrate role-based access control into your application.

---

## 🚀 Installation

### 1. Require the package

```bash
composer require rez1pro/user-access
```
---

### 2. Service Provider

The service provider is auto-discovered.  
If needed, register manually in `config/app.php`:

```php
'providers' => [
    Rez1pro\UserAccess\UserAccessServiceProvider::class,
]
```

## ⚡ Installer Command

For a one-shot setup:

```bash
php artisan user-access:install --force
```

This will:

- Publish **config**
- Publish **migrations**
- Publish **models**
- Publish **enums**
- Run database migrations

---

## 🗄️ Database Schema

The migration will create the following tables:

- `roles`  
- `permissions`  
- `role_has_permissions` (pivot)  

---

## 🧩 Models

The package ships with:

- `App\Models\Role`
- `App\Models\Permission`

Example:

```php
$role = Role::create(['name' => 'Admin']);
$role->permissions()->attach(ExamplePermissionEnums::CREATE_USER);
```

---

## 🏷️ Enums

Enums are placed in `App\Enums\Permissions`.

Example:

```php
use Rez1pro\UserAccess\HasAccess;

enum ExamplePermissionEnum: string
{
    use HasAccess;

    case VIEW_EXAMPLE = 'view:example';
    case CREATE_EXAMPLE = 'create:example';
}
```

Usage:

```php
ExamplePermissionEnum::VIEW_EXAMPLE->value; // "view:example"
```

---

## 🎭 Facade

The `UserAccess` Facade provides quick helpers:

```php
use Rez1pro\UserAccess\Facades\UserAccess;

// Get all permission in array
$permissions = UserAccess::all(); // ['user:create','user:view']

// Get permissions for user ID
$permissionWithGroups = UserAccess::withGroup(); // Returns:

```json
[
    {
        "name": "Example Permission Enum",
        "permissions": [
            {
                "id": "view:example",
                "name": "VIEW EXAMPLE"
            },
            {
                "id": "create:example",
                "name": "CREATE EXAMPLE"
            }
        ]
    }
]

   //  $hasPermission = UserAccess::has(ExamplePermissionEnum::CREATE_USER); // true or false

    // $role = Role::create(['name' => 'admin']);  // create new role

    $role = Role::first();
    // return $role->givePermissionTo(ExamplePermissionEnum::VIEW_EXAMPLE); // attach permission to role

    // return $role->permissions; // Role based permission

    // return dd($role->hasPermissionTo(ExamplePermissionEnum::VIEW_EXAMPLE)); // param can be enum, string or id eg (1, 'view_example', ExamplePermissionEnum::VIEW_EXAMPLE)

    // return $role->removePermission(ExamplePermissionEnum::VIEW_EXAMPLE); // detach permission from role

    // $permission = Permission::first();

    // return $permission->roles; // get all roles associated with this permission
```

---

## 🧪 Testing

Publish migrations and run:

```bash
php artisan migrate // make sure user_access migration exists
```

---

## 📌 Summary

- `php artisan user-access:install` → Quick setup  
- `UserAccess` Facade → Easy access to roles & permissions  
- `Enums` → Strongly typed permissions  
- `Models` → Extendable  
- `Migrations` → Already published feel free to change  

---

## 📝 License

This package is open-sourced software licensed under the [MIT license](LICENSE).