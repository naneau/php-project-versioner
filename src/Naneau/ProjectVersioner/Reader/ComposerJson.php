<?php
namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

use stdClass;

/**
 * Finds the "version" from the composer.json file
 */
class ComposerJson implements ReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function canRead(string $directory): bool
    {
        return is_readable($directory . '/composer.json');
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $directory)
    {
        $json = @file_get_contents($directory . '/composer.json');
        if (!$json) {
            return null;
        }

        $json = json_decode($json, false);
        if (!($json instanceof stdClass) || empty($json->version)) {
            return null;
        }

        return trim($json->version);
    }
}
