<?php declare(strict_types=1);

namespace Circli\WebCore\Session;

final class PhpObjectSession implements ObjectSession
{
    private const SESSION_KEY = '_object_session_';

    public function addObject(object $value, string $key = null): void
    {
        $_SESSION[self::SESSION_KEY][$key ?? get_class($value)] = json_encode($value);
    }

    public function getObject(string $key)
    {
        if (isset($_SESSION[self::SESSION_KEY][$key])) {
            $value = json_decode($_SESSION[self::SESSION_KEY][$key], true);
          //  unset($_SESSION[self::SESSION_KEY][$key]);
            if (interface_exists($key) && defined("$key::IMPLEMENTATIONS")) {
                $implementations = (array)$key::IMPLEMENTATIONS;
                foreach ($implementations as $implementation) {
                    if (method_exists($implementation, 'fromJson')) {
                        return $implementation::fromJson($value);
                    }
                }
            }

            if (method_exists($key, 'fromJson')) {
                return $key::fromJson($value);
            }

            return $value;
        }

        return null;
    }
}
