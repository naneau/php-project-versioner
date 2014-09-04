<?php
/**
 * Finder.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader\Finder;

use Naneau\ProjectVersioner\ReaderInterface;

use Symfony\Component\Finder\Finder as SfFinder;

/**
 * Finder
 *
 * Base class for finder based readers
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
abstract class Finder implements ReaderInterface
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
        // Update finder directory
        $this->getFinder()->in($directory);

        // If at least one file/dir can be found, assume we can read
        foreach ($this->getFinder() as $file) {
            return true;
        }

        return false;
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
     * @param  SfFinder $finder
     * @return SfFinder
     */
    public function setFinder(SfFinder $finder)
    {
        $this->finder = $finder;

        return $this;
    }
}
