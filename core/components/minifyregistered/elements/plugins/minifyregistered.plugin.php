<?php
/**
 * minifyRegistered
 *
 * @package minifyregistered
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'TreehillStudio\MinifyRegistered\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('minifyregistered.core_path', null, $modx->getOption('core_path') . 'components/minifyregistered/');
/** @var MinifyRegistered $minifyregistered */
$minifyregistered = $modx->getService('minifyregistered', 'MinifyRegistered', $corePath . 'model/minifyregistered/', [
    'core_path' => $corePath
]);

if ($minifyregistered) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'MinifyRegistered Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'MinifyRegistered Plugin');
    }
}

return;