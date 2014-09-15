# PHP Project Versioner

[![Build Status](https://travis-ci.org/naneau/php-project-versioner.svg?branch=master)](https://travis-ci.org/naneau/php-project-versioner)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/naneau/php-project-versioner/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/naneau/php-project-versioner/?branch=master)

This is a simple tool to obtain "versions" for projects in PHP.

## Installation

Installation through composer:

```bash
composer require naneau/project-versioner=~0
```

## Examples

This library is based around a "Versioner", which accepts one or more "Readers".

### Using Git

If your project is maintained using Git, you can look at it for versions.

#### Commit

Use the last commit as a version:

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Git\Commit\Exec as GitCommitReader;

// Create a versioner
$versioner = new Versioner(array(new GitCommitReader));

// Short commit hash like "gd8587c8"
$version = $versioner->get('/foo/bar');
```

#### Tag

Use the latest tag (reachable from this commit):

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Git\Tag\Exec as GitTagReader;

// Create a versioner
$versioner = new Versioner(array(new GitTagReader));

// Last tag
$version = $versioner->get('/foo/bar');
```

#### Described Version

Use the output of [`git describe`](http://git-scm.com/docs/git-describe), which combines the latest (reachable) tag and subsequent commits:

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Git\Tag\Exec as GitTagReader;

// Create a versioner
$versioner = new Versioner(array(new GitTagReader));

// Last tag + commit info, like 4.3.2-9-gd504031
$version = $versioner->get('/foo/bar');
```

### Using Files

#### Reading Version From A Single File

Imagine you maintain a file called `VERSION` that you (or your CI stack) fills with a version.

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\File as FileReader;

// Create a versioner
$versioner = new Versioner(array(
    // Reader for "VERSION" file
    new FileReader('VERSION')
));

// Retrieve version from versioner
$version = $versioner->get('/foo/bar');
```

#### Using MTime

In a common scenario, a set of files is (independently) updated, and you want to use the highest/most recent time (`mtime`) as a version. This version can be used to bust caches, etc.:

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Finder\MTime as MTimeReader;

// Create a versioner
$versioner = new Versioner(array(
    new MTimeReader('*.txt') // Look at all *.txt files
));

// Highest mtime, like 1410806782
$version = $versioner->get('/foo/bar');
```

#### Using Contents

Using a different reader it is possible to use the *contents* of the files found:

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Finder\Contents as ContentsReader;

$versioner = new Versioner(array(
    new ContentsReader('*.jpg')
));

// Short hash of file contents, like gd504031
$version = $versioner->get('/foo/bar');
```

### Composer

If your project depends on a set of [Composer](https://getcomposer.org) dependencies, you can use the Composer readers to obtain a version based on installed packages.

#### All Packages

To look at all packages combined:

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\Composer as ComposerReader;

$versioner = new Versioner(array(new ComposerReader));

// Short hash like "ae9b8a"
$version = $versioner->get('/foo/bar');
```

#### Using A Specific Package

Or, looking for a specific package:

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\ComposerPackage as ComposerPackageReader;

$versioner = new Versioner(array(
    new ComposerPackageReader('symfony/filesystem')
));

// Composer Version (SemVer) like "v2.5.4"
$version = $versioner->get('/foo/bar');
```

## Combining Readers

For some projects you may want to combine readers, for instance as a fallback mechanism if one reader does not provide output, or to get a version composed of more than one reader's output.

Combining Composer and Git:

```php
use Naneau\ProjectVersioner\Versioner;
use Naneau\ProjectVersioner\Reader\ComposerPackage as ComposerPackageReader;
use Naneau\ProjectVersioner\Reader\Git\Tag\Exec as GitTagReader;

$versioner = new Versioner(array(
    new ComposerPackageReader('symfony/filesystem'),
    new GitTagReader
));

// First version found
$version = $versioner->get('/foo/bar');

// Composite of all readers, like "v0.3.4-gd504031"
$combinedVersion = $versioner->getCombined('/foo/bar');

// You can specify the separator
$combinedVersionUnderscore = $versioner->getCombined('/foo/bar', '_');
```

## Notes

 * Obtaining a version inevitably uses (potentially expensive) I/O. You may want to cache the results.
 * The `exec` based Git readers are UN*X only for now
