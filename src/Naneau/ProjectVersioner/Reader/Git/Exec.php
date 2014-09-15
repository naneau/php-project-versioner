<?php
/**
 * Exec.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Git;

use Naneau\ProjectVersioner\ReaderInterface;

/**
 * Exec
 *
 * Base class for exec based git reading
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
abstract class Exec implements ReaderInterface
{
    /**
     * {@inheritDoc}
     **/
    public function canRead($directory)
    {
        return $this->canExec(
            $this->getCommandForDirectory($directory),
            $directory
        );
    }

    /**
     * {@inheritDoc}
     **/
    public function read($directory)
    {
        return $this->exec(
            $this->getCommandForDirectory($directory)
        );
    }

    /**
     * Get the command for a directory
     *
     * @param  string $directory
     * @return string
     **/
    abstract protected function getCommandForDirectory($directory);

    /**
     * Can a git command be executed?
     *
     * @param  string $command
     * @param  string $directory
     * @return void
     **/
    private function canExec($command, $directory)
    {
        // We rely on exec, so it needs to exist
        if (!function_exists('exec')) {
            return false;
        }

        // (Cheap) check for git directory
        if (!is_dir($directory . '/.git')) {
            return false;
        }

        // Try to exec()
        $output = array();
        $return = 0;
        @exec($command, $output, $return);

        // Check return code
        return $return === 0;
    }

    /**
     * Execute a git command and return first line of output
     *
     * @param  string $command
     * @return string
     **/
    private function exec($command)
    {
        $output = array();
        $return = 0;

        // Try to find last commit hash
        @exec($command, $output, $return);

        // Make sure it worked
        if ($return !== 0) {
            throw new RuntimeException(sprintf(
                'Can not parse version from git using %s',
                $command
            ));
        }

        // Return first line of output
        return $output[0];
    }
}
