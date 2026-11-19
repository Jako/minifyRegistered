<?php
/**
 * Resolve Composer dependencies
 *
 * @package minifyregistered
 * @subpackage build
 *
 * @var array $options
 * @var xPDOTransport $transport
 */

use Composer\Console\HtmlOutputFormatter;
use Composer\Factory;
use Composer\Installer;
use Composer\IO\BufferIO;
use Symfony\Component\Console\Output\StreamOutput;

/** @var modX $modx */
$modx = $transport->xpdo;
@set_time_limit(0);

$success = true;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $modx->log(xPDO::LOG_LEVEL_INFO, 'Installing/updating dependencies, this may take some time...');

        $path = MODX_CORE_PATH . "components/{$options['namespace']}/";

        // Set Composer environment variables
        putenv("COMPOSER={$path}composer.json");
        putenv("COMPOSER_HOME={$path}.composer");
        putenv("COMPOSER_VENDOR_DIR={$path}vendor/");

        // Remember the current working directory, it is restored after running Composer
        $cwd = getcwd();

        // Change the path to the package namespace folder, to prevent autoloading other namespaces
        chdir($path);

        // Another package resolver (i.e. of a package dependency) could have loaded a composer.phar in the same
        // request. Loading a second composer.phar autoloader causes a fatal error, the loaded one is used instead.
        if (!class_exists(Factory::class)) {
            require "phar://{$path}composer.phar/vendor/autoload.php";
        }

        // Since PHP 8 the @ operator doesn't set error_reporting() to 0 anymore, so the MODX 2 error handler logs
        // the suppressed warnings of Composer, i.e. the open_basedir warnings during the search for a CA bundle.
        // These are skipped here, all other errors are passed to the MODX error handler.
        $previousErrorHandler = set_error_handler(function ($errno, $errstr, $errfile = null, $errline = null) use (&$previousErrorHandler) {
            if (!(error_reporting() & $errno)) {
                return true;
            }
            return $previousErrorHandler ? call_user_func($previousErrorHandler, $errno, $errstr, $errfile, $errline) : false;
        });

        // Run Composer without proc_open/exec
        $io = new BufferIO('', StreamOutput::VERBOSITY_NORMAL, new HtmlOutputFormatter());
        $composer = Factory::create($io);
        $install = Installer::create($io, $composer);
        $install
            ->setPreferDist()
            ->setDevMode(false)
            ->setOptimizeAutoloader(true)
            ->setUpdate(false)
            ->setPreferStable();

        try {
            $result = $install->run();
            if ($result !== 0) {
                $modx->log(xPDO::LOG_LEVEL_ERROR, 'Composer could not install the dependencies (exit code ' . $result . ').');
                $success = false;
            }
        } catch (Exception $e) {
            $modx->log(xPDO::LOG_LEVEL_ERROR, get_class($e) . ' installing dependencies: ' . $e->getMessage());
            echo get_class($e) . ': ' . $e->getMessage() . "\n";
            $success = false;
        }

        $output = $io->getOutput();
        $output = nl2br(trim($output));
        $modx->log(xPDO::LOG_LEVEL_INFO, $output);

        // Restore the previous error handler and working directory for the following resolvers
        restore_error_handler();
        chdir($cwd);
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}
return $success;
