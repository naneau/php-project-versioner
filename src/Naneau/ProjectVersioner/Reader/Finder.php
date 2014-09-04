<?php
/**
 * Finder.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

use Symfony\Component\Finder\Finder as SfFinder;

/**
 * Finder
 *
 * Uses the highest mtime from a finder as a version
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class Finder implements ReaderInterface
{
    /**
     * the finder
     *
     * @var SfFinder
     */
    private $finder;

    /**
     * Constructor
     *
     * @param  SfFinder $finder
     * @return void
     **/
    public function __construct(SfFinder $finder = null)
    {
        if ($finder === null) {
            $finder = new SfFinder;
        }

        $this->setFinder($finder);
    }

    /**
     * {@inheritdoc}
     **/
    public function canRead($directory)
    {
        $this->getFinder()->in($directory);

        // If at least one file/dir can be found, assume we can read
        foreach($this->getFinder() as $file) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
        $highest = 0;
        foreach($this->getFinder() as $file) {

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

    /**
     * Get the finder
     *
     * @return SfFinder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /**
     * Set the finder
     *
     * @param SfFinder $finder
     * @return Finder
     */
    public function setFinder(SfFinder $finder)
    {
        $this->finder = $finder;

        return $this;
    }
}
