<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallationCommand extends Command
{
    protected $signature = 'shop:install';

    protected $description = 'Helps to install a project';

    public function handle(): int
    {
        $this->call('storage:link');
        $this->call('migrate');

        return self::SUCCESS;
    }
}
