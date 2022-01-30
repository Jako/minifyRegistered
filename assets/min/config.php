<?php
/**
 * The 'min' configuration for minifyRegistered
 *
 * @package minifyregistered
 * @subpackage minify
 *
 * @var \TreehillStudio\MinifyRegistered\MinifyRegistered $minifyregistered
 */

$modx = $GLOBALS['modx'];
$minifyregistered = $GLOBALS['minifyregistered'];

if ($minifyregistered) {
    // See core/components/minifyregistered/vendor/mrclay/minify/config.php for a full documentation of the following options

    $min_enableStatic = false;
    $min_enableBuilder = false;
    $min_concatOnly = false;
    $min_builderPassword = 'admin';
    $min_errorLogger = $minifyregistered->getOption('errorLogger');
    $min_allowDebugFlag = $minifyregistered->getOption('allowDebugFlag');
    $min_cachePath = $minifyregistered->getOption('cachePath');
    $min_documentRoot = $minifyregistered->getOption('documentRoot');
    $min_cacheFileLocking = $minifyregistered->getOption('cacheFileLocking');

    $min_serveOptions = [];
    $min_serveOptions['bubbleCssImports'] = $minifyregistered->getOption('bubbleCssImports');
    $min_serveOptions['maxAge'] = $minifyregistered->getOption('maxAge');
    if ($minifyregistered->getOption('cssCompressor')) {
        $min_serveOptions['minifiers'][Minify::TYPE_CSS] = ['Minify_CSS', 'minify'];
    }
    if ($minifyregistered->getOption('closureCompiler')) {
        $serveOptions['minifiers']['application/x-javascript'] = ['Minify_JS_ClosureCompiler', 'minify'];
    }
    if ($minifyregistered->getOption('allowDirs')) {
        $min_serveOptions['minApp']['allowDirs'] = $minifyregistered->getOption('allowDirs');
    }
    $min_serveOptions['minApp']['groupsOnly'] = $minifyregistered->getOption('groupsOnly');
    $min_serveOptions['minApp']['noMinPattern'] = $minifyregistered->getOption('noMinPattern');
    $min_symlinks = $minifyregistered->getOption('symlinks');
    $min_uploaderHoursBehind = $minifyregistered->getOption('uploaderHoursBehind');
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    @session_write_close();
    exit();
}
