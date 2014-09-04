<?php
/**
 * MTime.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Finder;

use Naneau\ProjectVersioner\Reader\Finder\Finder;

/**
 * MTime
 *
 * Uses the highest mtime from a finder as a version
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class MTime extends Finder
{
    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
        $highest = 0;
        foreach ($this->getFinder() as $file) {

            $mtime = filemtime(
                $file->getPath()
                . DIRECTORY_SEPARATOR . $file->getFilename()
            );
            if ($mtime > $highest) {
                $highest = $mtime;
            }
        }

        return $highest;
    }
}
