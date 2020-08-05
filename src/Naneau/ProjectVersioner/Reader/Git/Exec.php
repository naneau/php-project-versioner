<?php
namespace Naneau\ProjectVersioner\Reader\Git;

use Naneau\ProjectVersioner\ReaderInterface;

use RuntimeException;

/**
 * Exec
 *
 * Base class for exec based git reading
 */
abstract class Exec implements ReaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function canRead(string $directory): bool
    {
        return $this->canExec(
            $this->getCommandForDirectory($directory),
            $directory
        );
    }

    /**
     * {@inheritDoc}
     */
    public function read(string $directory)
    {
        return $this->exec(
            $this->getCommandForDirectory($directory)
        );
    }

    /**
     * Get the command for a directory
     */
    abstract protected function getCommandForDirectory(string $directory): string;

    /**
     * Can a git command be executed?
     */
    private function canExec(string $command, string $directory): bool
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
        $output = [];
        $return = 0;
        @exec($command, $output, $return);

        // Check return code
        return $return === 0;
    }

    /**
     * Execute a git command and return first line of output
     */
    private function exec(string $command): string
    {
        $output = [];
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
