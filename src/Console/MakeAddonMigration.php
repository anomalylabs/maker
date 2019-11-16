<?php

namespace Anomaly\MakerExtension\Console;

use Illuminate\Console\Command;

/**
 * Class MakeAddonMigration
 *
 * @link   http://pyrocms.com/
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class MakeAddonMigration extends Command
{

    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = 'make:addon_migration {addon} {name} {--fields}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new addon.';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        [$vendor, $type, $slug] = addon_map($addon = $this->argument('addon'));
        dd('MakeAddonMigration');
    }
}
