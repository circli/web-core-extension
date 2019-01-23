<?php

namespace Circli\WebCore\Session;

use Psr\Http\Message\ServerRequestInterface;

interface Factory
{
    public function fromRequest(ServerRequestInterface $request, $sessionCls);
}