# admin-banners

This module provides CRUD operations for managing banners in the admin panel.

## Features

- Create new banners
- View a list of existing banners
- Update banner details
- Delete banners

## Usage

1. **Create**: Add a new banner with title, image, and link.
2. **Read**: View all banners in a paginated list.
3. **Update**: Edit banner information and images.
4. **Delete**: Remove banners that are no longer needed.

## Example Endpoints

| Method | Endpoint           | Description         |
|--------|-------------------|---------------------|
| GET    | `/banners`        | List all banners    |
| POST   | `/banners`        | Create a new banner |
| GET    | `/banners/{id}`   | Get banner details  |
| PUT    | `/banners/{id}`   | Update a banner     |
| DELETE | `/banners/{id}`   | Delete a banner     |

## Requirements

- PHP 8.2+
- Laravel Framework

## Need to update `composer.json` file

Add the following to your `composer.json` to use the package from a local path:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/pavanraj92/admin-banners.git"
    }
]
```

## Installation

```bash
composer require admin/banners
```

## Usage

1. Publish the configuration and migration files:
    ```bash
    php artisan banner:publish --force

    composer dump-autoload
    
    php artisan migrate
    ```
2. Access the Banner manager from your admin dashboard.


## Customization

You can customize views, routes, and permissions by editing the configuration file.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
