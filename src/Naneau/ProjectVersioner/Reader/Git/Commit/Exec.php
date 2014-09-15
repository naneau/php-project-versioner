<?php
/**
 * Exec.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Git\Commit;

use Naneau\ProjectVersioner\Reader\Git\Exec as GitExec;

/**
 * Exec
 *
 * Reads the latest commit (short) hash from a git repository
 *
 * Example: gd504031
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class Exec extends GitExec
{
    /**
     * Use short hash?
     *
     * @var bool
     **/
    private $short = true;

    /**
     * Constructor
     *
     * @param  bool $short
     * @return void
     **/
    public function __construct($short = true)
    {
        $this->setShort($short);
    }

    /**
     * Get use short commit hash?
     *
     * @return bool
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * Set use short commit hash?
     *
     * @param  bool $short
     * @return Exec
     */
    public function setShort($short)
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Get command for directory
     *
     * @param  string $directory
     * @return string
     **/
    protected function getCommandForDirectory($directory)
    {
        if ($this->getShort()) {
            return sprintf(
                'cd %s && git rev-parse --short HEAD',
                escapeshellarg($directory)
            );
        } else {
            return sprintf(
                'cd %s && git rev-parse HEAD',
                escapeshellarg($directory)
            );
        }
    }
}
