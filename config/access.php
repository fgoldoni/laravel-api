<?php

return [
    'roles' => [
        ['name' => 'User', 'guard_name' => 'web'],
        ['name' => 'Admin', 'guard_name' => 'web']
    ],
    'permissions' => [
        ['name' => 'READ_USERS', 'guard_name' => 'web'],
        ['name' => 'EDIT_USERS', 'guard_name' => 'web'],
        ['name' => 'UPDATE_USERS', 'guard_name' => 'web'],
        ['name' => 'DELETE_USERS', 'guard_name' => 'web'],
        ['name' => 'RESTORE', 'guard_name' => 'web'],
        ['name' => 'FORCE_DELETE', 'guard_name' => 'web'],


        ['name' => 'READ_ROLES', 'guard_name' => 'web'],
        ['name' => 'EDIT_ROLES', 'guard_name' => 'web'],
    ]
];
