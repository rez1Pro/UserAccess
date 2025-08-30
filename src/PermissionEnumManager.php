<?php

namespace Rez1pro\UserAccess;

use App\Models\Permission;
use BackedEnum;
use Rez1pro\UserAccess\Enums\BasePermissionEnums;

class PermissionEnumManager
{
    public function all(): array
    {
        return Permission::pluck('name')->toArray();
    }

    public function withGroups(): array
    {
        $permissionsFromDB = Permission::pluck('name')->toArray();

        $result = [];
        foreach (BasePermissionEnums::getGroupWithPermissions() as $permissionsWithGroup) {
            $filteredPermissions = array_filter($permissionsWithGroup['permissions'], function ($permission) use ($permissionsFromDB) {
                return array_filter($permission, fn($perm) => in_array($perm, $permissionsFromDB, true));
            });

            if (!empty($filteredPermissions)) {
                $result[] = [
                    'group' => $permissionsWithGroup['name'],
                    'permissions' => array_values($filteredPermissions),
                ];
            }
        }

        return $result;
    }

    public function values(): array
    {
        return array_map(fn($case) => $case->value, BasePermissionEnums::cases());
    }

    public function has(BackedEnum|string $value): bool
    {
        if (is_string($value)) {
            return in_array($value, $this->all(), true);
        }

        return in_array($value->value, $this->all(), true);
    }
}