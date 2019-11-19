<?php declare(strict_types=1);

namespace Circli\WebCore\Session;

interface ObjectSession
{
    public function addObject(object $value, string $key = null);

    /**
     * Retrieve object from session
     *
     * If object is found will create object and then delete it from the cache
     *
     * @param string $className
     * @return mixed
     */
    public function getObject(string $className);
}
