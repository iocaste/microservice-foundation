<?php

namespace Iocaste\Microservice\Foundation\Command;

use Illuminate\Console\Command as IlluminateCommand;

/**
 * Class Command
 */
class Command extends IlluminateCommand
{
    /**
     * @param $output
     * @param string $type
     * @param array $context
     * @param bool $toConsole
     * @param bool $toLog
     *
     * @return void
     */
    protected function logOutput($output, $type = 'info', $context = [], $toConsole = true, $toLog = true)
    {
        if ($toConsole) {
            $this->{$type}(' ' . $output);
        }

        $type = ($type === 'line') ? 'info' : $type;

        if ($toLog) {
            app('log')->$type($output, $context);
        }
    }
}
