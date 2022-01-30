<?php
/**
 * The 'min' endpoint for minifyRegistered
 *
 * @package minifyregistered
 * @subpackage minify
 *
 */

use Minify\App;

require_once dirname(__FILE__, 3) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize();

$corePath = $modx->getOption('minifyregistered.core_path', null, $modx->getOption('core_path') . 'components/minifyregistered/');
/** @var MinifyRegistered $minifyregistered */
$minifyregistered = $modx->getService('minifyregistered', 'MinifyRegistered', $corePath . 'model/minifyregistered/', [
    'core_path' => $corePath
]);

$app = new App(__DIR__);
$app->runServer();
