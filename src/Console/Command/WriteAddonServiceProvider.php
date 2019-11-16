<?php

namespace Anomaly\MakerExtension\Console\Command;

use Anomaly\Streams\Platform\Support\Parser;
use Illuminate\Filesystem\Filesystem;

/**
 * Class WriteAddonServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class WriteAddonServiceProvider
{

    /**
     * The addon path.
     *
     * @var string
     */
    private $path;

    /**
     * The addon type.
     *
     * @var string
     */
    private $type;

    /**
     * The addon slug.
     *
     * @var string
     */
    private $slug;

    /**
     * The vendor slug.
     *
     * @var string
     */
    private $vendor;

    /**
     * Create a new WriteAddonServiceProvider instance.
     *
     * @param $path
     * @param $type
     * @param $slug
     * @param $vendor
     */
    public function __construct($path, $type, $slug, $vendor)
    {
        $this->path   = $path;
        $this->slug   = $slug;
        $this->type   = $type;
        $this->vendor = $vendor;
    }

    /**
     * Handle the command.
     *
     * @param Parser     $parser
     * @param Filesystem $filesystem
     */
    public function handle(Parser $parser, Filesystem $filesystem)
    {
        $slug   = ucfirst(camel_case($this->slug));
        $type   = ucfirst(camel_case($this->type));
        $vendor = ucfirst(camel_case($this->vendor));

        $addon     = $slug . $type;
        $provider  = $addon . 'ServiceProvider';
        $namespace = "{$vendor}\\{$addon}";

        $path = "{$this->path}/src/{$provider}.php";

        $template = $filesystem->get(
            base_path('vendor/anomaly/streams-platform/resources/stubs/addons/provider.stub')
        );

        $filesystem->makeDirectory(dirname($path), 0755, true, true);

        $filesystem->put($path, $parser->parse($template, compact('namespace', 'provider')));
    }
}
