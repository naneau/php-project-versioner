<?php
namespace Naneau\ProjectVersioner\Reader\Git\Tag;

use Naneau\ProjectVersioner\Reader\Git\Exec as GitExec;

/**
 * Exec
 *
 * Reads the latest tag reachable from the current commit
 *
 * For example: 3.0.2
 *
 * @see http://git-scm.com/docs/git-describe
 */
class Exec extends GitExec
{
    /**
     * Get command for directory
     */
    protected function getCommandForDirectory(string $directory): string
    {
        return sprintf(
            'cd %s && git describe --abbrev=0',
            escapeshellarg($directory)
        );
    }
}
