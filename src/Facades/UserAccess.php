<?php

namespace Rez1pro\UserAccess\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array all()
 * @method static array values()
 * @method static bool has(string $value)
 */
class UserAccess extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'user-access';
    }
}