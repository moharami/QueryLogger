<?php

namespace Moharami\QueryLogger;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Moharami\QueryLogger\Skeleton\SkeletonClass
 */
class QueryLoggerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'querylogger';
    }
}
