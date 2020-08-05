<?php
namespace Naneau\ProjectVersioner\Reader;

use Naneau\ProjectVersioner\ReaderInterface;

use stdClass;
use RuntimeException;

/**
 * ComposerPackage
 *
 * Finds the version of a specific Composer package from the lock file
 */
class ComposerPackage implements ReaderInterface
{
    /**
     * The page name to look for
     *
     * @var string
     */
    private $package;

    public function __construct(string $package)
    {
        $this->setPackage($package);
    }

    /**
     * {@inheritdoc}
     */
    public function canRead(string $directory): bool
    {
        if (!is_readable($directory . '/composer.lock')) {
            return false;
        }

        $package = $this->getPackageFromLockFile($directory);

        if ($package === null) {
            return false;
        }

        return !empty($package->version);
    }

    /**
     * {@inheritdoc}
     */
    public function read(string $directory)
    {
        $package = $this->getPackageFromLockFile($directory);

        if ($package === null) {
            throw new RuntimeException(sprintf(
                'No composer.lock file found in directory "%s".',
                $directory
            ));
        }

        return $package->version;
    }

    /**
     * Get the package
     */
    public function getPackage(): string
    {
        return $this->package;
    }

    /**
     * Set the package
     */
    public function setPackage(string $package): self
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get the package from the lockfile
     */
    private function getPackageFromLockFile(string $directory): ?stdClass
    {
        $contents = file_get_contents($directory . '/composer.lock');
        if (!$contents) {
            return null;
        }

        $parsed = json_decode($contents, false);
        if (!isset($parsed->packages)) {
            return null;
        }

        foreach ($parsed->packages as $package) {
            if ($package->name === $this->getPackage()) {
                return $package;
            }
        }

        return null;
    }
}
