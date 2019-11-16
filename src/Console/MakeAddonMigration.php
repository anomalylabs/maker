<?php

namespace Anomaly\Streams\Platform\Addon\Console;

use Anomaly\Streams\Platform\Addon\Console\Command\MakeAddonMigrationPaths;
use Anomaly\Streams\Platform\Addon\Console\Command\ScaffoldTheme;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonButtonLang;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonClass;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonComposer;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonFeatureTest;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonFieldLang;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonGitIgnore;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonLang;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonPackage;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonPermissionLang;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonPermissions;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonPhpUnit;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonSectionLang;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonServiceProvider;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonStreamLang;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonWebpack;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteThemePackage;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteThemeWebpack;
use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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
        dd('Migration');
    }
}
