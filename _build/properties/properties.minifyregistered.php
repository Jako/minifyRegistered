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
 * Rowboat; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package minifyregistered
 * @subpackage build
 *
 * Properties for the minifyRegistered plugin.
 */
$properties = array(
	array(
		'name' => 'excludeJs',
		'desc' => 'prop_minifyregistered.excludeJs',
		'type' => 'textfield',
		'options' => '',
		'value' => '',
		'lexicon' => 'minifyregistered:properties',
	),
	array(
		'name' => 'groupFolder',
		'desc' => 'prop_minifyregistered.groupFolder',
		'type' => 'textfield',
		'options' => '',
		'value' => 'assets/js',
		'lexicon' => 'minifyregistered:properties',
	),
	array(
		'name' => 'groupJs',
		'desc' => 'prop_minifyregistered.groupJs',
		'type' => 'combo-boolean',
		'options' => '',
		'value' => true,
		'lexicon' => 'minifyregistered:properties',
	),
	array(
		'name' => 'minPath',
		'desc' => 'prop_minifyregistered.minPath',
		'type' => 'textfield',
		'options' => '',
		'value' => '/manager/min/',
		'lexicon' => 'minifyregistered:properties',
	)
);

return $properties;