<?php
namespace XRA\XRA\Facades;

use Illuminate\Support\Facades\Facade;

class AdminNotify extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'adminNotify';
    }
}
