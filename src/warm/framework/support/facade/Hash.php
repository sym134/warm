<?php
namespace warm\framework\support\facade;

/**
 * @method static array info(string $hashedValue)
 * @method static bool check(string $value, string $hashedValue, array $options = [])
 * @method static bool needsRehash(string $hashedValue, array $options = [])
 * @method static string make(string $value, array $options = [])
 * @method static extend($driver, \Closure $callback)
 *
 */
class Hash extends Facade
{
    protected static function getFacadeClass(): string
    {
        return 'hash';
    }
}
