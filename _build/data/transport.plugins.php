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
 * Package in plugins.
 */
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','minifyRegistered');
$plugins[0]->set('description','Collect the registered javascript and css files/chunks and minify them.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'plugin.minifyregistered.php'));
$plugins[0]->set('category', 0);

$events = include $sources['events'].'events.minifyregistered.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for minifyRegistered.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for minifyRegistered!');
}
unset($events);

return $plugins;
