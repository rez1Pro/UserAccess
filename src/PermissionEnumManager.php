<?php

namespace Rez1pro\UserAccess;

use Rez1pro\UserAccess\Enums\BasePermissionEnums;

class PermissionEnumManager
{
    public function all(): array
    {
        return BasePermissionEnums::getAllPermissionList();
    }

    public function withGroups(): array
    {
        return BasePermissionEnums::getGroupWithPermissions();
    }

    public function values(): array
    {
        return array_map(fn($case) => $case->value, BasePermissionEnums::cases());
    }

    public function has(string $value): bool
    {
        return in_array($value, $this->all(), true);
    }
}