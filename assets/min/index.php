<?php
/**
 * Google minify implementation for minifyRegistered
 * @package minifyregistered
 * @subpackage minify
 */
require_once dirname(dirname(dirname(__FILE__))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
global $modx;
$modx = new modX();
$modx->initialize('web');
//Default settings
$settings = array(
	'errorLogger' => $modx->getOption('minifyregistered.errorLogger', null, false),
	'allowDebugFlag' => $modx->getOption('minifyregistered.allowDebugFlag', null, false),
	'cachePath' => MODX_CORE_PATH . 'cache/minify',
	'documentRoot' => $modx->getOption('minifyregistered.documentRoot', null, ''),
	'cacheFileLocking' => $modx->getOption('minifyregistered.cacheFileLocking', null, true),
	'bubbleCssImports' => $modx->getOption('minifyregistered.bubbleCssImports', null, false),
	'maxAge' => intval($modx->getOption('minifyregistered.maxAge', null, 1800)),
	'closureCompiler' => $modx->getOption('minifyregistered.closureCompiler', null, false),
	'groupsOnly' => $modx->getOption('minifyregistered.groupsOnly', null, false),
	'allowDirs' => json_decode($modx->getOption('minifyregistered.allowDirs', null, '[]')),
	'noMinPattern' => $modx->getOption('minifyregistered.noMinPattern', null, false, true),
	'symlinks' => json_decode($modx->getOption('minifyregistered.symlinks', null, '[]')),
	'uploaderHoursBehind' => intval($modx->getOption('minifyregistered.uploaderHoursBehind', null, 0)),
	'libPath' => $modx->getOption('minifyregistered.libPath', null, dirname(__FILE__) . '/lib', true)
);

$serveOptions['bubbleCssImports'] = $settings['bubbleCssImports'];
$serveOptions['maxAge'] = $settings['maxAge'];
if ($settings['closureCompiler']) {
	$serveOptions['minifiers']['application/x-javascript'] = 'closureCompiler';

	function closureCompiler($js) {
		require_once 'Minify/JS/ClosureCompiler.php';
		return Minify_JS_ClosureCompiler::minify($js);
	}

}
$serveOptions['minApp']['groupsOnly'] = ($settings['groupsOnly']) ? true : false;
if ($settings['noMinPattern']) {
	$settings['noMinPattern'] = (strtolower($settings['noMinPattern']) == 'null') ? null : $settings['noMinPattern'];
	$serveOptions['minApp']['noMinPattern'] = $settings['noMinPattern'];
}

/* minify stuff */
define('MINIFY_MIN_DIR', dirname(__FILE__));

// try to disable output_compression (may not have an effect)
ini_set('zlib.output_compression', '0');

// setup include path
set_include_path($settings['libPath'] . PATH_SEPARATOR . get_include_path());

require 'Minify.php';

Minify::$uploaderHoursBehind = $settings['uploaderHoursBehind'];

if (!file_exists($settings['cachePath'])) {
	mkdir($settings['cachePath']);
}
Minify::setCache($settings['cachePath'], $settings['cacheFileLocking']);

if ($settings['documentRoot']) {
	$_SERVER['DOCUMENT_ROOT'] = $settings['documentRoot'];
	Minify::$isDocRootSet = true;
}

$serveOptions['minifierOptions']['text/css']['symlinks'] = $settings['symlinks'];
// auto-add targets to allowDirs
foreach ($settings['symlinks'] as $target) {
	$serveOptions['minApp']['allowDirs'][] = $target;
}

if ($settings['allowDebugFlag']) {
	require_once 'Minify/DebugDetector.php';
	$serveOptions['debug'] = Minify_DebugDetector::shouldDebugRequest($_COOKIE, $_GET, $_SERVER['REQUEST_URI']);
}

if ($settings['errorLogger']) {
	require_once 'Minify/Logger.php';
	if (true === $settings['errorLogger']) {
		require_once 'FirePHP.php';
		$min_errorLogger = FirePHP::getInstance(true);
	}
	Minify_Logger::setLogger($min_errorLogger);
}

// check for URI versioning
if (preg_match('/&\\d/', $_SERVER['QUERY_STRING'])) {
	$serveOptions['maxAge'] = 31536000;
}
if (isset($_GET['g'])) {
	// well need groups config
	$serveOptions['minApp']['groups'] = (require MINIFY_MIN_DIR . '/groupsConfig.php');
}
if (isset($_GET['f']) || isset($_GET['g'])) {
	// serve!   
	require 'Minify/Controller/MinApp.php';
	$serveController = new Minify_Controller_MinApp();
	Minify::serve($serveController, $serveOptions);
} else {
	header("Location: /");
	exit();
}