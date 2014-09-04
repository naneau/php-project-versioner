<?php
/**
 * FileSet.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

use Symfony\Component\Finder\Finder;

/**
 * File
 *
 * Creates a version based on the highest mtime of a set of files
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class FileSet implements ReaderInterface
{
    /**
     * The finder
     *
     * @var Finder
     **/
    private $finder;

    /**
     * Constructor
     *
     * @param string $files
     * @return void
     **/
    public function __construct($in = '**/*')
    {
        $this
            ->setFinder(new Finder);
    }

    /**
     * {@inheritdoc}
     **/
    public function canRead($directory)
    {
        $this
            ->getFinder()->in($directory)
            ->name($this->getName());
    }

    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
    }

    /**
     * Get the sf finder
     *
     * @return Finder
     */
    public function getFinder()
    {
        return $this->finder;
    }

    /**
     * Set the sf finder
     *
     * @param Finder $finder
     * @return FileSet
     */
    public function setFinder(Finder $finder)
    {
        $this->finder = $finder;

        return $this;
    }
}
