<?php

namespace Rez1pro\UserAccess\Enums;

use Rez1pro\UserAccess\Traits\HasAccess;

enum BasePermissionEnums
{
    use HasAccess;

    // Get Group wise permissions
    static function getGroupWithPermissions(): array
    {

        $permissions = [];
        $files = glob(app_path('Enums/Permissions/*.php'));

        foreach ($files as $file) {
            $className = basename($file, '.php');
            $fullClassName = "App\\Enums\\Permissions\\{$className}";

            if (class_exists($fullClassName)) {
                $permissions[] = [
                    'name' => preg_replace('/(?<!^)[A-Z]/', ' $0', $className),
                    'permissions' => $fullClassName::getValuesWithNames()
                ];
            }
        }

        return $permissions;
    }

    // Get all permissions
    static function getAllPermissionList(): array
    {

        $permissions = [];
        $files = glob(app_path('Enums/Permissions/*.php'));

        foreach ($files as $file) {
            $className = basename($file, '.php');
            $fullClassName = "App\\Enums\\Permissions\\{$className}";

            if (class_exists($fullClassName)) {
                $permissions = array_merge($permissions, $fullClassName::getValues());
            }
        }

        return $permissions;
    }
}