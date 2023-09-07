<?php

namespace Luchavez\PassportPgtServer\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class InstallPassportPGTServerCommand
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class InstallPassportPGTServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'pgt:server:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute "luchavez/passport-pgt-server" package setup.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'passport-pgt-server.config',
        ]);

        // Create Passport tables
        $this->call('migrate');

        // Install Passport
        // Todo: Get the Client secret from CLI outputs then set it on .env
        //$process = new Process(['php', 'artisan', 'passport:install']);
        //$process->enableOutput();
        //$process->setTty(true);
        //$process->run();
        $this->call('passport:install');

        return self::SUCCESS;
    }
}
