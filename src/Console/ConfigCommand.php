<?php

namespace DigitalEquation\Helpers\Console;

use Illuminate\Console\Command;

class ConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helpers:config {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-publish the Helper config file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'helpers-config',
            '--force' => $this->option('force'),
        ]);
    }
}