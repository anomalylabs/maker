<?php

namespace Anomaly\MakerExtension\Console\Command;

use Illuminate\Filesystem\Filesystem;
use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Support\Writer;
use Anomaly\Streams\Platform\Addon\Console\Command\WriteAddonPermissionLang;

/**
 * Class AppendEntityPermissionLang
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class AppendEntityPermissionLang
{

    /**
     * The entity slug.
     *
     * @var string
     */
    protected $slug;

    /**
     * The addon instance.
     *
     * @var Addon
     */
    protected $addon;

    /**
     * Create a new WriteEntityModel instance.
     *
     * @param Addon $addon
     * @param       $slug
     */
    public function __construct(Addon $addon, $slug)
    {
        $this->slug  = $slug;
        $this->addon = $addon;
    }

    /**
     * Handle the command.
     *
     * @param Writer $writer
     * @param Filesystem $files
     */
    public function handle(Writer $writer, Filesystem $files)
    {
        if (!$files->exists($path = $this->addon->getPath("resources/lang/en/permission.php"))) {
            dispatch_now(new WriteAddonPermissionLang($this->addon->getPath()));
        }

        $human = humanize($this->slug);

        $name = ucfirst($human);

        $permissions = "    '{$this->slug}' => [\n";
        $permissions .= "        'name'   => '{$name}',\n";
        $permissions .= "        'option' => [\n";
        $permissions .= "            'read'   => 'Can read {$human}?',\n";
        $permissions .= "            'write'  => 'Can create/edit {$human}?',\n";
        $permissions .= "            'delete' => 'Can delete {$human}?',\n";
        $permissions .= "        ],\n";
        $permissions .= "    ],\n";

        $writer->replace(
            $path,
            '/return \[\];/i',
            "return [\n];"
        );

        $writer->prepend(
            $path,
            '/];/i',
            $permissions
        );
    }
}
