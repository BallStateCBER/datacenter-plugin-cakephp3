<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace DataCenter\Console;

use Cake\Utility\Security;
use Composer\Installer\PackageEvent;
use Composer\Script\Event;
use Exception;

/**
 * Provides installation hooks for when this application is installed via
 * composer. Customize this class to suit your needs.
 */
class Installer
{
    /**
     * Does some routine installation tasks so people don't have to.
     *
     * @param \Composer\Script\Event $event The composer event object.
     * @throws \Exception Exception raised by validator.
     * @return void
     */
    public static function postInstall(Event $event)
    {
        $io = $event->getIO();

        $rootDir = self::getRootDir(__DIR__);

        static::createAppConfig($io);
        if (file_exists($rootDir . '/config/app.default.php')) {
            unlink($rootDir . '/config/app.default.php');
        }
        static::createEnvFiles($io);
        static::createWritableDirectories($io);

        // ask if the permissions should be changed
        if ($io->isInteractive()) {
            $validator = function ($arg) {
                if (in_array($arg, ['Y', 'y', 'N', 'n'])) {
                    return $arg;
                }
                throw new Exception('This is not a valid answer. Please choose Y or n.');
            };
            $setFolderPermissions = $io->askAndValidate(
                '<info>Set Folder Permissions ? (Default to Y)</info> [<comment>Y,n</comment>]? ',
                $validator,
                10,
                'Y'
            );

            if (in_array($setFolderPermissions, ['Y', 'y'])) {
                static::setFolderPermissions($io);
            }
        } else {
            static::setFolderPermissions($io);
        }

        if (class_exists('\Cake\Codeception\Console\Installer')) {
            \Cake\Codeception\Console\Installer::customizeCodeceptionBinary($event);
        }

        static::copyVendorFiles($event);
    }

    /**
     * Handles various file-copying from /vendor to /webroot
     *
     * @param \Composer\Installer\PackageEvent $event The composer event object.
     * @throws \Exception Exception raised by validator.
     * @return void
     */
    public static function postUpdate(PackageEvent $event)
    {
        $packageName = self::getPackageName($event);
        if ($packageName == 'twbs/bootstrap') {
            static::copyTwitterBootstrapFiles($event);
        }
        if ($packageName == 'ballstatecber/datacenter-plugin-cakephp3') {
            static::copyDataCenterFiles($event);
        }
    }

    /**
     * Returns the package name associated with $event
     *
     * @param \Composer\Installer\PackageEvent $event Package event
     * @return string
     */
    public static function getPackageName(PackageEvent $event)
    {
        /** @var \Composer\DependencyResolver\Operation\InstallOperation|\DataCenter\Console\UpdateOperation $operation */
        $operation = $event->getOperation();

        $package = method_exists($operation, 'getPackage')
            ? $operation->getPackage()
            : $operation->getInitialPackage();

        return $package->getName();
    }

    /**
     * Create the config/app.php file if it does not exist.
     *
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     * @throws \Exception
     */
    public static function createAppConfig($io)
    {
        $rootDir = self::getRootDir(__DIR__);
        $appConfig = $rootDir . '/config/app.php';
        $defaultConfig = $rootDir . '/config/app.default.php';
        if (!file_exists($appConfig) && file_exists($defaultConfig)) {
            copy($defaultConfig, $appConfig);
            $io->write('Created `config/app.php` file');
        }
    }

    /**
     * Create the `logs` and `tmp` directories.
     *
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     * @throws \Exception
     */
    public static function createWritableDirectories($io)
    {
        $rootDir = self::getRootDir(__DIR__);
        $paths = [
            'logs',
            'tmp',
            'tmp/cache',
            'tmp/cache/models',
            'tmp/cache/persistent',
            'tmp/cache/views',
            'tmp/sessions',
            'tmp/tests',
        ];

        foreach ($paths as $path) {
            $path = $rootDir . '/' . $path;
            if (!file_exists($path)) {
                mkdir($path);
                $io->write('Created `' . $path . '` directory');
            }
        }
    }

    /**
     * Set globally writable permissions on the "tmp" and "logs" directory.
     *
     * This is not the most secure default, but it gets people up and running quickly.
     *
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     * @throws \Exception
     */
    public static function setFolderPermissions($io)
    {
        $rootDir = self::getRootDir(__DIR__);

        // Change the permissions on a path and output the results.
        $changePerms = function ($path, $perms, $io) {
            // Get permission bits from stat(2) result.
            $currentPerms = fileperms($path) & 0777;
            if (($currentPerms & $perms) == $perms) {
                return;
            }

            $res = chmod($path, $currentPerms | $perms);
            if ($res) {
                $io->write('Permissions set on ' . $path);
            } else {
                $io->write('Failed to set permissions on ' . $path);
            }
        };

        $walker = function ($dir, $perms, $io) use (&$walker, $changePerms) {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;

                if (!is_dir($path)) {
                    continue;
                }

                $changePerms($path, $perms, $io);
                $walker($path, $perms, $io);
            }
        };

        $worldWritable = bindec('0000000111');
        $walker($rootDir . '/tmp', $worldWritable, $io);
        $changePerms($rootDir . '/tmp', $worldWritable, $io);
        $changePerms($rootDir . '/logs', $worldWritable, $io);
    }

    /**
     * Copies favicon and other files from Data Center plugin into /webroot
     *
     * @param \Composer\Installer\PackageEvent|\Composer\Script\Event $event The composer event object.
     * @return void
     * @throws \Exception
     */
    public static function copyDataCenterFiles($event)
    {
        $io = $event->getIO();
        $rootDir = self::getRootDir(__DIR__);

        // Files to be copied from => to
        $files = [
            'android-chrome-192x192.png',
            'android-chrome-512x512.png',
            'apple-touch-icon.png',
            'browserconfig.xml',
            'favicon.ico',
            'favicon-16x16.png',
            'favicon-32x32.png',
            'manifest.json',
            'mstile-150x150.png',
            'safari-pinned-tab.svg',
        ];

        foreach ($files as $file) {
            $source = $rootDir . 'vendor/ballstatecber/datacenter-plugin-cakephp3/webroot/' . $file;
            $destination = $rootDir . 'webroot/' . $file;
            if (file_exists($source)) {
                if (copy($source, $destination)) {
                    $io->write("Copied `$file` into webroot");
                } else {
                    $io->write("Error copying `$file` into webroot");
                }
            }
        }
    }

    /**
     * Copies Bootstrap files into /webroot subdirectories
     *
     * @param \Composer\Installer\PackageEvent|\Composer\Script\Event $event The composer event object.
     * @return void
     * @throws \Exception
     */
    public static function copyTwitterBootstrapFiles($event)
    {
        $io = $event->getIO();
        $rootDir = self::getRootDir(__DIR__);

        // Files to be copied from => to
        $copyJobs = [
            $rootDir . '/vendor/twbs/bootstrap/dist/js/bootstrap.min.js' => $rootDir . '/webroot/js/bootstrap.min.js',
        ];
        $fontSourceDir = $rootDir . '/vendor/twbs/bootstrap/dist/fonts';
        $fontDestinationDir = $rootDir . '/webroot/fonts';
        $fontFiles = array_diff(scandir($fontSourceDir), ['.', '..']);
        foreach ($fontFiles as $fontFile) {
            $copyJobs[$fontSourceDir . '/' . $fontFile] = $fontDestinationDir . '/' . $fontFile;
        }

        foreach ($copyJobs as $source => $destination) {
            if (file_exists($source)) {
                $splodeySource = explode('/', $source);
                $filename = array_pop($splodeySource);
                if (copy($source, $destination)) {
                    $io->write("Copied `$filename` into webroot");
                } else {
                    $io->write("Error copying `$filename` into webroot");
                }
            }
        }
    }

    /**
     * Creates the files .env, .env.production, and .env.dev
     *
     * @param \Composer\IO\IOInterface $io IO interface to write to console
     * @return void
     * @throws \Exception
     */
    public static function createEnvFiles($io)
    {
        $rootDir = self::getRootDir(__DIR__);
        $securitySalt = hash('sha256', Security::randomBytes(64));
        $cookieKey = hash('sha256', Security::randomBytes(64));
        $variables = [
            'SECURITY_SALT' => $securitySalt,
            'COOKIE_ENCRYPTION_KEY' => $cookieKey,
        ];
        if ($io->isInteractive()) {
            $appName = $io->ask('App name (\'app_name\' by default):', 'app_name');
            $variables['APP_NAME'] = $appName;
            $fullBaseUrl = $io->ask(
                'Full base URL (\'https://sitename.cberdata.org\' by default):',
                'https://sitename.cberdata.org'
            );
            $variables['FULL_BASE_URL'] = $fullBaseUrl;
        }

        if (!file_exists($rootDir . '/config/.env.dev')) {
            static::createDevEnvFile($variables, $io);
        }

        if (!file_exists($rootDir . '/config/.env.production')) {
            static::createProductionEnvFile($variables, $io);
        }

        if (!file_exists($rootDir . '/config/.env')) {
            static::setCurrentEnv($io, '.env.dev');
        }
    }

    /**
     * Creates .env.dev
     *
     * @param array $variables Variables in .env file to update
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     * @throws \Exception
     */
    public static function createDevEnvFile($variables, $io)
    {
        $rootDir = self::getRootDir(__DIR__);
        $defaultFile = $rootDir . '/config/.env.default';
        $newFile = $rootDir . '/config/.env.dev';

        if (file_exists($newFile)) {
            return;
        }

        copy($defaultFile, $newFile);
        $io->write("Created `config/.env.dev`");

        $variables += [
            'header' => '# Environment variables for development environment',
        ];
        static::modifyEnvFile($newFile, $variables, $io);
    }

    /**
     * Creates .env.production
     *
     * @param array $variables Variables in .env file to update
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @return void
     * @throws \Exception
     */
    public static function createProductionEnvFile($variables, $io)
    {
        $rootDir = self::getRootDir(__DIR__);
        $defaultFile = $rootDir . '/config/.env.default';
        $newFile = $rootDir . '/config/.env.production';

        if (file_exists($newFile)) {
            return;
        }

        copy($defaultFile, $newFile);
        $io->write("Created `config/.env.production`");

        $variables += [
            'header' => '# Environment variables for production environment',
            'DEBUG' => 'FALSE',
        ];
        static::modifyEnvFile($newFile, $variables, $io);
    }

    /**
     * Copies the specified .env.foo file to .env
     *
     * @param \Composer\IO\IOInterface $io IO interface to write to console.
     * @param string $filename Filename to copy to .env
     * @return void
     * @throws \Exception
     */
    public static function setCurrentEnv($io, $filename)
    {
        $rootDir = self::getRootDir(__DIR__);
        $fileToCopy = $rootDir . '/config/' . $filename;
        $newFile = $rootDir . '/config/.env';

        if (file_exists($newFile)) {
            return;
        }

        copy($fileToCopy, $newFile);
        $io->write("Created `config/.env`");
    }

    /**
     * Modifies the specified env file according to the provided options
     *
     * @param string $file Full path to file
     * @param array $options Array of edits to make to env file
     * @param \Composer\IO\IOInterface $io IO interface to write to console
     * @return void
     */
    public static function modifyEnvFile($file, $options, $io)
    {
        $handler = fopen($file, 'r+');
        $toWrite = [];
        $updatedVariables = [];
        while (!feof($handler)) {
            $line = fgets($handler);

            // Replace header
            if (!$toWrite && isset($options['header'])) {
                $line = $options['header'] . "\n";
                $updatedVariables[] = 'header';
                unset($options['header']);
            }

            // Replace default variable values with specified ones
            foreach ($options as $key => $val) {
                if (stripos($line, "export $key = ") === false) {
                    continue;
                }

                // Make sure strings are quoted
                $isBoolOrNull = in_array(strtolower($val), ['null', 'true', 'false']);
                $isNumeric = is_numeric($val);
                $isQuoted = strpos($val, '"') === 0 || strpos($val, '\'') === 0;
                if (!$isBoolOrNull && !$isNumeric && !$isQuoted) {
                    $val = "\"$val\"";
                }

                $line = "export $key = $val\n";
                $updatedVariables[] = $key;
                unset($options[$key]);
            }

            $toWrite[] = $line;
        }

        // Note any missing variables
        $splodeyPath = explode('/', $file);
        $filename = array_pop($splodeyPath);
        if ($options) {
            $skippedVariables = array_keys($options);
            $msg = 'No ' . implode(', ', $skippedVariables) . ' placeholder to replace in ' . $filename;
            $io->write($msg);
        }

        // Note updated variables
        if ($updatedVariables) {
            $updatesString = implode(', ', $updatedVariables) . " in $filename";
            if (file_put_contents($file, implode('', $toWrite))) {
                $io->write("Updated $updatesString");

                return;
            }

            // Note write failure
            $io->write("Unable to update $updatesString");
        }
    }

    /**
     * Finds the root directory of this application
     *
     * @param string $dir Path to a directory under root
     * @return string
     * @throws \Exception
     */
    public static function getRootDir($dir)
    {
        do {
            $lastDir = $dir;
            $dir = dirname($dir);
            if (is_dir($dir . '/vendor/cakephp/cakephp')) {
                return $dir;
            }
        } while ($dir !== $lastDir);

        throw new Exception('Cannot find the root of the application');
    }

    /**
     * Runs all methods that copy files from /vendor to /webroot
     *
     * @param \Composer\Installer\PackageEvent|\Composer\Script\Event $event Composer event object
     * @return void
     * @throws \Exception
     */
    public static function copyVendorFiles($event)
    {
        static::copyTwitterBootstrapFiles($event);
        static::copyDataCenterFiles($event);
    }
}
