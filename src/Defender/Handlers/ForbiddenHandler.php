<?php

namespace GenesisTecnologia\Defender\Handlers;

use GenesisTecnologia\Defender\Contracts\ForbiddenHandler as ForbiddenHandlerContract;
use GenesisTecnologia\Defender\Exceptions\ForbiddenException;

class ForbiddenHandler implements ForbiddenHandlerContract
{
    public function handle()
    {
        throw new ForbiddenException;
    }
}
