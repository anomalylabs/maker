<?php

namespace Anomaly\MakerExtension\Console;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\MakerExtension\Console\Command\AppendEntityBindings;
use Anomaly\MakerExtension\Console\Command\AppendEntityButtonLang;
use Anomaly\MakerExtension\Console\Command\AppendEntityPermissionLang;
use Anomaly\MakerExtension\Console\Command\AppendEntityPermissions;
use Anomaly\MakerExtension\Console\Command\AppendEntityRoutes;
use Anomaly\MakerExtension\Console\Command\AppendEntitySection;
use Anomaly\MakerExtension\Console\Command\AppendEntitySectionLang;
use Anomaly\MakerExtension\Console\Command\AppendEntitySingletons;
use Anomaly\MakerExtension\Console\Command\AppendEntityStreamLang;
use Anomaly\MakerExtension\Console\Command\WriteEntityCollection;
use Anomaly\MakerExtension\Console\Command\WriteEntityController;
use Anomaly\MakerExtension\Console\Command\WriteEntityCriteria;
use Anomaly\MakerExtension\Console\Command\WriteEntityFactory;
use Anomaly\MakerExtension\Console\Command\WriteEntityFormBuilder;
use Anomaly\MakerExtension\Console\Command\WriteEntityModel;
use Anomaly\MakerExtension\Console\Command\WriteEntityModelInterface;
use Anomaly\MakerExtension\Console\Command\WriteEntityObserver;
use Anomaly\MakerExtension\Console\Command\WriteEntityPresenter;
use Anomaly\MakerExtension\Console\Command\WriteEntityRepository;
use Anomaly\MakerExtension\Console\Command\WriteEntityRepositoryInterface;
use Anomaly\MakerExtension\Console\Command\WriteEntityRouter;
use Anomaly\MakerExtension\Console\Command\WriteEntitySeeder;
use Anomaly\MakerExtension\Console\Command\WriteEntityTableBuilder;
use Anomaly\MakerExtension\Console\Command\WriteEntityTestCases;
use Anomaly\MakerExtension\Console\Command\WriteEntityTreeBuilder;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class Make
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class Make extends Command
{
    use DispatchesJobs;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:stream';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a streams entity namespace.';

    /**
     * Execute the console command.
     */
    public function handle(AddonCollection $addons)
    {
        $slug   = $this->argument('slug');
        $nested = $this->option('nested');

        /* @var Addon $addon */
        if (!$addon = $addons->get($this->argument('addon'))) {
            throw new \Exception("The addon [{$this->argument('addon')}] could not be found.");
        }

        if (!$namespace = $this->option('namespace')) {
            $namespace = $addon->getSlug();
        }

        $this->dispatchNow(new WriteEntityModel($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityRouter($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntitySeeder($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityFactory($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityObserver($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityCriteria($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityPresenter($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityCollection($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityRepository($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityFormBuilder($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityController($addon, $slug, $namespace, $nested));

        if ($nested) {
            $this->dispatchNow(new WriteEntityTreeBuilder($addon, $slug, $namespace));
        }

        if (!$nested) {
            $this->dispatchNow(new WriteEntityTableBuilder($addon, $slug, $namespace));
        }

        $this->dispatchNow(new WriteEntityModelInterface($addon, $slug, $namespace));
        $this->dispatchNow(new WriteEntityRepositoryInterface($addon, $slug, $namespace));

        // Run this last since it scans the above.
        $this->dispatchNow(new WriteEntityTestCases($addon, $slug, $namespace));

        // Modify existing addon classes.
        $this->dispatchNow(new AppendEntityBindings($addon, $slug, $namespace));
        $this->dispatchNow(new AppendEntitySingletons($addon, $slug, $namespace));

        // Write Permissions.
        $this->dispatchNow(new AppendEntityStreamLang($addon, $slug));
        $this->dispatchNow(new AppendEntityPermissions($addon, $slug));
        $this->dispatchNow(new AppendEntityPermissionLang($addon, $slug));

        // Module Specific.
        if ($addon->getType() == 'module') {
            $this->dispatchNow(new AppendEntityRoutes($addon, $slug, $namespace));
            $this->dispatchNow(new AppendEntitySection($addon, $slug, $namespace));

            $this->dispatchNow(new AppendEntityButtonLang($addon, $slug));
            $this->dispatchNow(new AppendEntitySectionLang($addon, $slug));
        }

        $this->call(
            'make:migration',
            [
                'name'     => 'create_' . $slug . '_stream',
                '--addon'  => $addon->getNamespace(),
                '--stream' => $slug,
            ]
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['slug', InputArgument::REQUIRED, 'The entity\'s stream slug.'],
            ['addon', InputArgument::REQUIRED, 'The addon in which to put the new entity namespace.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['namespace', null, InputOption::VALUE_OPTIONAL, 'The stream namespace if not the same as the addon.'],
            ['migration', null, InputOption::VALUE_NONE, 'Indicates if an stream migration should be created.'],
            [
                'nested',
                null,
                InputOption::VALUE_NONE,
                'Indicates if a nested builder should be created, instead of table.',
            ],
        ];
    }
}
