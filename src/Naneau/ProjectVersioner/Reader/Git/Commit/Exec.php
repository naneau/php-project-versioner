<?php
namespace Naneau\ProjectVersioner\Reader\Git\Commit;

use Naneau\ProjectVersioner\Reader\Git\Exec as GitExec;

/**
 * Exec
 *
 * Reads the latest commit (short) hash from a git repository
 *
 * Example: gd504031
 */
class Exec extends GitExec
{
    /**
     * Use short hash?
     *
     * @var bool
     */
    private $short = true;

    public function __construct(bool $short = true)
    {
        $this->setShort($short);
    }

    /**
     * Get use short commit hash?
     */
    public function getShort(): bool
    {
        return $this->short;
    }

    /**
     * Set use short commit hash?
     */
    public function setShort(bool $short): self
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Get command for directory
     */
    protected function getCommandForDirectory(string $directory): string
    {
        if ($this->getShort()) {
            return sprintf(
                'cd %s && git rev-parse --short HEAD',
                escapeshellarg($directory)
            );
        }

        return sprintf(
            'cd %s && git rev-parse HEAD',
            escapeshellarg($directory)
        );
    }
}
