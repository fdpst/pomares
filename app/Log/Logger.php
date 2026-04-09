<?php

namespace App\Log;

use Closure;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Log\Events\MessageLogged;
use Psr\Log\LoggerInterface;
use RuntimeException;

class Logger extends \Illuminate\Log\Logger
{
    /**
     * Create a new log writer instance.
     *
     * @param  \Psr\Log\LoggerInterface  $logger
     * @param  \Illuminate\Contracts\Events\Dispatcher|null  $dispatcher
     * @return void
     */
    public function __construct(LoggerInterface $logger, ?Dispatcher $dispatcher = null)
    {
        parent::__construct($logger, $dispatcher);
    }
}

