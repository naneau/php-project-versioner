<?php
/**
 * Contents.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Finder;

/**
 * Contents
 *
 * Combines contents from all found files
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class Contents extends Finder
{
    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
        $hash = '';
        foreach ($this->getFinder() as $file) {

            // MD5 hash of the file
            $fileHash = md5_file(
                $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename()
            );

            // Mix into result hash
            $hash = md5($hash . $fileHash);
        }

        return substr($hash, 0, 6);
    }
}
