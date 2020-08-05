<?php
namespace Naneau\ProjectVersioner\Reader\Finder;

/**
 * Contents
 *
 * Combines contents from all found files
 */
class Contents extends Finder
{
    /**
     * {@inheritdoc}
     */
    public function read(string $directory)
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
