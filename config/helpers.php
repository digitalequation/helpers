<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Helper Master Switch
    |--------------------------------------------------------------------------
    |
    | This option may be used to disable the Helper package.
    |
    */
    'enabled' => true,

    /*
     |--------------------------------------------------------------------------
     | Table Paginate
     |--------------------------------------------------------------------------
     |
     | Set the default driver and options for table pagination.
     | The driver accepts "react" or "vue".
     |
     */
    'paginate' => [
        'driver' => 'react',
    ],

    /*
     |--------------------------------------------------------------------------
     | Default User Roles
     |--------------------------------------------------------------------------
     |
     | Set the default roles the app has.
     |
     */
    'default_roles' => [
        [
            'name'        => 'admin',
            'description' => 'Full administrative permissions to manage account',
        ],
        [
            'name'        => 'moderator',
            'description' => 'Create recipes, ingredients, appliances',
        ],
        [
            'name'        => 'user',
            'description' => 'Built-in user role',
        ]
    ]
];
