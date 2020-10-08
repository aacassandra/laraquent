# Beauty Eloquent

This is a package for laravel framework especially for CRUD activities. This package uses the eloquent laravel style and doesn't leave the default eloquent style. It's just that this package makes it easier for us to perform CRUD activities without including the model dependency on the associated controller.

## Suported Features

- Create
- Read
- Update
- Delete
- User
- CreateUser
- UpdateUser
- DeleteUser
- Users

## Laravel Permission Features

if you use the [spatie/laravel-permission](https://github.com/spatie/laravel-permission), then the features below can be used in a shorter method

- Roles
- CreateRole
- UpdateRole
- DeleteRole
- Permissions

## Installation in Laravel

This package can be used with Laravel 5 or higher.

1. Consult the Prerequisites page for important considerations regarding your User models!

2. This package publishes a config/laraquent.php file. If you already have a file by that name, you must rename or remove it.

3. You can install the package via composer:

```
composer require aacassandra/laraquent
```

4. In the \$providers array add the service providers in your config/app.php file:

```
'providers' => [
    /*
    * Package Service Providers
    */
    // ...
    Laraquent\BeautyEloquentProvider::class,
];
```

5. Add the facade of this package to the \$aliases array.

```
'aliases' => [
    // ...
    'BeautyEloquent' => Laraquent\BeautyEloquent::class,
]
```

6. You must publish the config. which will later be on the config/laraquent.php config file with:

```
php artisan vendor:publish --provider="Laraquent\BeautyEloquentProvider"
```

7. Now the Image Class will be auto-loaded by Laravel.

## Default Config File Contents

You can view the default config file contents at:
https://github.com/aacassandra/laraquent/resources/config/laraquent.php

## Basic Usage

Below are some basic examples for using laravel's CRUD functions

#### Create Object

Format

```
BeautyEloquent::Create($model = '', $data = []);
```

Example

```
$data = [
    ['name', 'Jhon Doe'],
    ['age', 33],
    ['address', 'Kampoeng Street']
];
$create = BeautyEloquent::Create('Product', $data);
dd($create); // Output
```

#### Read Object

Format

```
BeautyEloquent::Read($model = '', $options = []);
```

Example

```
$options = [
    'where' => [
        ['role','=','admin'],
        [...], //and more
    ],
    'orWhere' => [
        ['role','=','admin'],
        [...], //and more
    ],
    'whereJsonContains' => [
        ['slug->en', 'article-part-2'],
        [...], //and more
    ],
    'whereRaw' => [
        ['price > IF(state = "TX", ?, 100)', [200]],
        [...], //and more
    ],
    'orWhereRaw' => [
        ['price > IF(state = "TX", ?, 100)', [200]],
        [...], //and more
    ]
    'orderBy' => ['id', 'DESC'], //ASC or DESC
    'limit' => 2,
    'first' => true //if the result has a return value of 1 and no more and return json objects,
    'paginate' => [3, 'home'] //{x, y} : x => the amount of content to be displayed, y : pageName for multi pagination (optional)
];

$read = BeautyEloquent::Read('Product', $options);
dd($read); // Output
```

#### Update Object

Format

```
BeautyEloquent::Update($model = '', $data = [], $options = []);
```

Example

```
$data = [
    ['name', 'Jhon Doe'],
    ['age', 33],
    ['address', 'Kampoeng Street']
];

$options = [
    'where' => [
        ['status','=','sale'],
        [...], //and more
    ],
    'orwhere => [
        ['price','>=', 100],
        [...], //and more
    ],
    'id' => 3
];

$update = BeautyEloquent::Update('Product', $data, $options);
dd($update); // Output
```

#### Delete Object

Format

```
BeautyEloquent::Delete($table = '', $options = []);
```

Example

```
$options = [
    'where' => [
        ['status','=','sale'],
        [...], //and more
    ],
    'orwhere => [
        ['price','>=', 100],
        [...], //and more
    ],
    'id' => 3
];

$delete = BeautyEloquent::Delete('Product', $options);
dd($delete); // Output
```

## Advance Usage

Below are some advance examples with [spatie/laravel-permission](https://github.com/spatie/laravel-permission) integration if needed

#### Roles

Show all roles

```
$options = [
    //Same as with the conditionals in the Read Object function
];
$roles = BeautyEloquent::Roles($options)
dd($roles); // Output
```

#### Create Role

```
$name = 'Admin';
$permissions = ['1','2','3']; // id of permission, you can use string or number format
$createRole = BeautyEloquent::CreateRole($name, $permissions)
dd($createRole); // Output
```

#### Update Role

```
$id = 1;
$name = 'New Admin';
$permissions = ['1','2','3']; // id of permission, you can use string or number format
$updateRole = BeautyEloquent::UpdateRole($id, $name, $permissions)
dd($updateRole); // Output
```

#### Delete Role

```
$id = 1;
$deleteRole = BeautyEloquent::DeleteRole($id)
dd($deleteRole); // Output
```

#### Permissions

Show all permissions

```
$options = [
    //Same as with the conditionals in the Read Object function
];
$permissions = BeautyEloquent::Permissions($options)
dd($permissions); // Output
```

#### User

Get specific user by id

```
$id = 1;
$user = BeautyEloquent::User($id)
dd($user); // Output
```

#### Users

Get all users

```
$options = [
    //Same as with the conditionals in the Read Object function
];
$users = BeautyEloquent::Users($options)
dd($users); // Output
```

#### Create User

```
$data = [
    ['name', 'Jhon Doe'],
    ['age', 33],
    ['address', 'Kampoeng Street'],
    [...], //and more
];
$roles = ['Admin', 'Super Admin', ...]; // string of role name
$createUser = BeautyEloquent::CreateUser($data, $roles)
dd($createUser); // Output
```

#### Update User

```
$id = 1;
$data = [
    ['name', 'Jhon Doe'],
    ['age', 33],
    ['address', 'Kampoeng Street'],
    [...], //and more
];
$roles = ['Admin', 'Super Admin', ...]; // string of role name
$updateUser = BeautyEloquent::UpdateUser($id, $data, $roles)
dd($updateUser); // Output
```

#### Delete User

```
$id = 1;
$deleteUser = BeautyEloquent::DeleteUser($id)
dd($deleteUser); // Output
```

## Package Config Must Be Configure

Config file must be configure if you using laravel 8, because file models on laravel 8 has been moved to Models directory

```
    'model' => [
        /**
         * The default directory of models
         */
        'directory' => 'App\Models',
        /**
         * Default of User Model
         */
        'user' => 'App\Models\User',
        /**
         * If using [spatie/laravel-permission]
         * you must set default model off this
         */
        'role' => 'Spatie\Permission\Models\Role',
        'permission' => 'Spatie\Permission\Models\Permission'
    ],
```

make sure you always update about **role** models and **permissions**. you can check it on their official website of [spatie/laravel-permission](https://github.com/spatie/laravel-permission)

## License

This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/aacassandra/laraquent/blob/master/LICENSE) file for details
