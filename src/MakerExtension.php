<?php

namespace Anomaly\MakerExtension;

use Anomaly\Streams\Platform\Addon\Extension\Extension;

class MakerExtension extends Extension
{

    /**
     * This addon is always installed.
     *
     * @var bool
     */
    protected $installed = true;

    /**
     * This extension provides...
     *
     * This should contain the dot namespace
     * of the addon this extension is for followed
     * by the purpose.variation of the extension.
     *
     * For example anomaly.module.store::gateway.stripe
     *
     * @var null|string
     */
    protected $provides = null;
}
