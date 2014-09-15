<?php
/**
 * Exec.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Git\Commit;

use Naneau\ProjectVersioner\ReaderInterface;

/**
 * Exec
 *
 * Read version from git using exec
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class Exec implements ReaderInterface
{
    /**
     * {@inheritDoc}
     **/
    public function canRead($directory)
    {
        // We rely on exec, so it needs to exist
        if (!function_exists('exec')) {
            return false;
        }

        // (Cheap) check for git directory
        if (!is_dir($directory . '/.git')) {
            return false;
        }

        // Try to get version
        $output = array();
        $return = 0;
        @exec(self::getCommandForDirectory($directory), $output, $return);

        return $return === 0;
    }

    /**
     * {@inheritDoc}
     **/
    public function read($directory)
    {
        $output = array();
        $return = 0;

        // Try to find last commit hash
        @exec(self::getCommandForDirectory($directory), $output, $return);

        // Make sure it worked
        if ($return !== 0) {
            throw new RuntimeException('Can not parse version from git');
        }

        // Return hash with "git#" prefixed for clarity
        return implode('#', $output);

    }

    /**
     * Get command for directory
     *
     * @param string $directory
     * @return string
     **/
    private static function getCommandForDirectory($directory)
    {
        return sprintf(
            'cd %s && git rev-parse --short head',
            escapeshellarg($directory)
        );
    }
}
