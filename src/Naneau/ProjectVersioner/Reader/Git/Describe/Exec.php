<?php
/**
 * Exec.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Git\Describe;

use Naneau\ProjectVersioner\Reader\Git\Exec as GitExec;

/**
 * Exec
 *
 * Reads the latest "described" version from git, which includes the latest
 * (reachable) tag, and a postfix for any commits added after that
 *
 * For example: 3.0.2-12-gd504031 for tag 3.0.2, with 12 additional commits,
 * the latest being gd504031
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
     * @param  string $directory
     * @return string
     **/
    protected function getCommandForDirectory($directory)
    {
        return sprintf(
            'cd %s && git describe',
            escapeshellarg($directory)
        );
    }
}
