<?php
namespace Naneau\ProjectVersioner\Reader\Finder;

use Naneau\ProjectVersioner\ReaderInterface;

use Symfony\Component\Finder\Finder as SfFinder;

/**
 * Finder
 *
 * Base class for finder based readers
 */
abstract class Finder implements ReaderInterface
{
    /**
     * the finder
     *
     * @var SfFinder
     */
    private $finder;

    public function __construct(?string $name = null, ?SfFinder $finder = null)
    {
        // Create finder if not given
        if ($finder === null) {
            $finder = new SfFinder;
        }

        // Set name if given
        if (!empty($name)) {
            $finder->name($name);
        }

        // Sort by name
        $finder->sortByName();

        // Set the finder
        $this->setFinder($finder);
    }

    /**
     * {@inheritdoc}
     */
    public function canRead(string $directory): bool
    {
        // Update finder directory
        $this->getFinder()->in($directory);

        // If at least one file/dir can be found, assume we can read
        return $this->getFinder()->hasResults();
    }

    /**
     * Get the finder
     */
    public function getFinder(): SfFinder
    {
        return $this->finder;
    }

    /**
     * Set the finder
     */
    public function setFinder(SfFinder $finder): self
    {
        $this->finder = $finder;

        return $this;
    }
}
