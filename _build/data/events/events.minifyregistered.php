<?php
/**
 * minifyRegistered
 *
 * Copyright 2011 by Thomas Jakobi <thomas.jakobi@partout.info>
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
 * Adds events to minifyRegistered plugin
 */
$events = array();

$events['OnWebPagePrerender'] = $modx->newObject('modPluginEvent');
$events['OnWebPagePrerender']->fromArray(array(
	'event' => 'OnWebPagePrerender',
	'priority' => 0,
	'propertyset' => 0,
		), '', true, true);

return $events;

