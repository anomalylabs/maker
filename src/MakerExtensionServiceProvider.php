<?php

namespace Anomaly\MakerExtension;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class MakerExtensionServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class MakerExtensionServiceProvider extends AddonServiceProvider implements DeferrableProvider
{

    /**
     * The addon Artisan commands.
     *
     * @type array|null
     */
    public $commands = [];

    /**
     * Return the provided services.
     */
    public function provides()
    {
        return [MakerExtension::class, 'anomaly.extension.maker'];
    }
}
