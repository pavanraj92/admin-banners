# Admin Banner Manager

This module provides CRUD operations for managing banners in the admin panel.

## Features

- Create new banners
- View a list of existing banners
- Update banner details
- Delete banners

---
## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

---

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-banners.git"
    }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/banners:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan banner:publish --force
    ```
---

## Usage

1. **Create**: Add a new banner with title, image, and link.
2. **Read**: View all banners in a paginated list.
3. **Update**: Edit banner information and images.
4. **Delete**: Remove banners that are no longer needed.

## Example Endpoints

| Method | Endpoint          | Description         |
|--------|-------------------|---------------------|
| GET    | `/banners`        | List all banners    |
| POST   | `/banners`        | Create a new banner |
| GET    | `/banners/{id}`   | Get banner details  |
| PUT    | `/banners/{id}`   | Update a banner     |
| DELETE | `/banners/{id}`   | Delete a banner     |

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // Admin banner routes here
});
```
---

## Database Tables

- `banners` - Stores banners information

---

## License

This package is open-sourced software licensed under the MIT license.
