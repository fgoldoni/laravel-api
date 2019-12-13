<?php

use App\Flag;

return [
    'roles' => [
        ['name' => Flag::ROLE_USER, 'guard_name' => 'web'],
        ['name' => Flag::ROLE_EXECUTIVE, 'guard_name' => 'web'],
        ['name' => Flag::ROLE_ADMIN, 'guard_name' => 'web'],
        ['name' => Flag::ROLE_EVENT_MANAGER, 'guard_name' => 'web']
    ],
    'permissions' => [
        ['name' => Flag::PERMISSION_USER, 'guard_name' => 'web'],
        ['name' => Flag::PERMISSION_EXECUTIVE, 'guard_name' => 'web'],
        ['name' => Flag::PERMISSION_ADMIN, 'guard_name' => 'web'],
        ['name' => Flag::PERMISSION_EVENT_MANAGER, 'guard_name' => 'web'],

        ['name' => 'READ_USERS', 'guard_name' => 'web'],
        ['name' => 'EDIT_USERS', 'guard_name' => 'web'],
        ['name' => 'UPDATE_USERS', 'guard_name' => 'web'],
        ['name' => 'DELETE_USERS', 'guard_name' => 'web'],
        ['name' => 'RESTORE_USERS', 'guard_name' => 'web'],
        ['name' => 'FORCE_DELETE_USERS', 'guard_name' => 'web'],

        ['name' => 'READ_ROLES', 'guard_name' => 'web'],
        ['name' => 'EDIT_ROLES', 'guard_name' => 'web'],
        ['name' => 'UPDATE_ROLES', 'guard_name' => 'web'],
        ['name' => 'DELETE_ROLES', 'guard_name' => 'web'],
        ['name' => 'RESTORE_ROLES', 'guard_name' => 'web'],
        ['name' => 'FORCE_DELETE_ROLES', 'guard_name' => 'web'],

        ['name' => 'CREATE_EVENT', 'guard_name' => 'web'],
        ['name' => 'UPDATE_EVENT', 'guard_name' => 'web'],
        ['name' => 'DELETE_EVENT', 'guard_name' => 'web'],
        ['name' => 'RESTORE_EVENT', 'guard_name' => 'web'],
        ['name' => 'FORCE_DELETE_EVENT', 'guard_name' => 'web'],
    ]
];
