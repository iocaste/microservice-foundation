<?php

namespace Iocaste\Microservice\Foundation\Command;

/**
 * Class PostUpdate
 */
class PostUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microservice:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run microservice post-install / post-update commands.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $consoleKernel = app(\Illuminate\Contracts\Console\Kernel::class);

        $consoleKernel->call('laradox:transform');

        if (app()->environment() !== 'production') {
            $consoleKernel->call('ide-helper:generate');
            $consoleKernel->call('ide-helper:models', ['-q']);
            $consoleKernel->call('ide-helper:meta');
        }
    }
}
