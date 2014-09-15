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
        $contents = '';
        foreach ($this->getFinder() as $file) {

            $fileContents = file_get_contents(
                $file->getPath()
                . DIRECTORY_SEPARATOR . $file->getFilename()
            );

            $contents = md5($contents . $fileContents);
        }

        return substr($contents, 0, 6);
    }
}
