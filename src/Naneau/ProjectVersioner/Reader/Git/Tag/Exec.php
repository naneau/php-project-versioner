<?php
/**
 * Exec.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Git\Tag;

use Naneau\ProjectVersioner\Reader\Git\Exec as GitExec;

use Naneau\ProjectVersioner\ReaderInterface;

/**
 * Exec
 *
 * Reads the latest tag reachable from the current commit
 *
 * For example: 3.0.2
 *
 * @see http://git-scm.com/docs/git-describe
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class Exec extends GitExec
{

    /**
     * Get command for directory
     *
     * @param string $directory
     * @return string
     **/
    protected function getCommandForDirectory($directory)
    {
        return sprintf(
            'cd %s && git describe --abbrev=0',
            escapeshellarg($directory)
        );
    }
}
