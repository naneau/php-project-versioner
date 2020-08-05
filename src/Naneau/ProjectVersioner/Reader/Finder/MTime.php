<?php
namespace Naneau\ProjectVersioner\Reader\Finder;

/**
 * MTime
 *
 * Uses the highest mtime from a finder as a version
 */
class MTime extends Finder
{
    /**
     * {@inheritdoc}
     */
    public function read(string $directory)
    {
        $highest = 0;
        foreach ($this->getFinder() as $file) {
            $mtime = filemtime(
                $file->getPath()
                . DIRECTORY_SEPARATOR . $file->getFilename()
            );
            if (is_int($mtime) && $mtime > $highest) {
                $highest = $mtime;
            }
        }

        return $highest;
    }
}
