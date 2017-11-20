<?php

namespace Iocaste\Microservice\Foundation\Job;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

/**
 * Class Job
 *
 * This job base class provides a central location to place any logic that
 * is shared across all of your jobs. The trait included with the class
 * provides access to the "queueOn" and "delay" queue helper methods.
 */
abstract class Job
{
    use Queueable, SerializesModels;
}
