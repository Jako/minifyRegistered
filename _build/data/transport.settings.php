<?php
/**
 * minifyRegistered
 *
 * Copyright 2011-2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * minifyRegistered is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free 
 * Software Foundation; either version 2 of the License, or (at your option) any 
 * later version.
 *
 * minifyRegistered is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * minifyRegistered; if not, write to the Free Software Foundation, Inc., 
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minifyregistered
 * @subpackage build
 *
 * System settings for the minify package.
 */
$settings = array();

// Area plugin
$settings['minifyregistered.excludeJs'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.excludeJs']->fromArray(array(
	'key' => 'minifyregistered.excludeJs',
	'value' => '',
	'namespace' => 'minifyregistered',
	'area' => 'system',
		), '', true, true);
$settings['minifyregistered.groupFolder'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.groupFolder']->fromArray(array(
	'key' => 'minifyregistered.groupFolder',
	'value' => 'assets/js',
	'namespace' => 'minifyregistered',
	'area' => 'system',
		), '', true, true);
$settings['minifyregistered.groupJs'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.groupJs']->fromArray(array(
	'key' => 'minifyregistered.groupJs',
	'value' => '0',
	'xtype' => 'combo-boolean',
	'namespace' => 'minifyregistered',
	'area' => 'system',
		), '', true, true);
$settings['minifyregistered.minPath'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.minPath']->fromArray(array(
	'key' => 'minifyregistered.minPath',
	'value' => '/assets/min/',
	'namespace' => 'minifyregistered',
	'area' => 'system',
		), '', true, true);

// Area minify
$settings['minifyregistered.errorLogger'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.errorLogger']->fromArray(array(
	'key' => 'minifyregistered.errorLogger',
	'value' => '0',
	'xtype' => 'combo-boolean',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.allowDebugFlag'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.allowDebugFlag']->fromArray(array(
	'key' => 'minifyregistered.allowDebugFlag',
	'value' => '0',
	'xtype' => 'combo-boolean',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.documentRoot'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.documentRoot']->fromArray(array(
	'key' => 'minifyregistered.documentRoot',
	'value' => '',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.cacheFileLocking'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.cacheFileLocking']->fromArray(array(
	'key' => 'minifyregistered.cacheFileLocking',
	'value' => '1',
	'xtype' => 'combo-boolean',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.bubbleCssImports'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.bubbleCssImports']->fromArray(array(
	'key' => 'minifyregistered.bubbleCssImports',
	'value' => '0',
	'xtype' => 'combo-boolean',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.maxAge'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.maxAge']->fromArray(array(
	'key' => 'minifyregistered.maxAge',
	'value' => '1800',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.closureCompiler'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.closureCompiler']->fromArray(array(
	'key' => 'minifyregistered.closureCompiler',
	'value' => '0',
	'xtype' => 'combo-boolean',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.allowDirs'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.allowDirs']->fromArray(array(
	'key' => 'minifyregistered.allowDirs',
	'value' => '[]',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.groupsOnly'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.groupsOnly']->fromArray(array(
	'key' => 'minifyregistered.groupsOnly',
	'value' => '0',
	'xtype' => 'combo-boolean',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.noMinPattern'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.noMinPattern']->fromArray(array(
	'key' => 'minifyregistered.noMinPattern',
	'value' => '',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.symlinks'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.symlinks']->fromArray(array(
	'key' => 'minifyregistered.symlinks',
	'value' => '[]',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.uploaderHoursBehind'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.uploaderHoursBehind']->fromArray(array(
	'key' => 'minifyregistered.uploaderHoursBehind',
	'value' => '0',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);
$settings['minifyregistered.libPath'] = $modx->newObject('modSystemSetting');
$settings['minifyregistered.libPath']->fromArray(array(
	'key' => 'minifyregistered.libPath',
	'value' => '',
	'namespace' => 'minifyregistered',
	'area' => 'minify',
		), '', true, true);

return $settings;