<?php
/**
 * ComposerPackage.php
 *
 * @package         ProjectVersioner
 * @subpackage      Reader
 */

namespace Naneau\ProjectVersioner\Reader;

/**
 * ComposerPackage
 *
 * Finds the version of a specific Composer package from the lock file
 *
 * @category        Naneau
 * @package         ProjectVersioner
 * @subpackage      Reader
 */
class ComposerPackage
{
    /**
     * The page name to look for
     *
     * @var string
     **/
    private $package;

    /**
     * Constructor
     *
     * @param  string $package
     * @return void
     **/
    public function __construct($package)
    {
        $this->setPackage($package);
    }

    /**
     * {@inheritdoc}
     **/
    public function canRead($directory)
    {
        if (!is_readable($directory . '/composer.lock')) {
            return false;
        }

        $package = $this->getPackageFromLockFile($directory);

        if ($package === false) {
            return false;
        }

        return !empty($package->version);
    }

    /**
     * {@inheritdoc}
     **/
    public function read($directory)
    {
        $package = $this->getPackageFromLockFile($directory);

        return $package->version;
    }

    /**
     * Get the package
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set the package
     *
     * @param  string          $package
     * @return ComposerPackage
     */
    public function setPackage($package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get the package from the lockfile
     *
     * @return stdClass|bool returns false when package can not be found
     **/
    private function getPackageFromLockFile($directory)
    {
        $parsed = json_decode(file_get_contents($directory . '/composer.lock'));
        if (!isset($parsed->packages)) {
            return false;
        }

        foreach ($parsed->packages as $package) {
            if ($package->name === $this->getPackage()) {
                return $package;
            }
        }

        return false;
    }
}
