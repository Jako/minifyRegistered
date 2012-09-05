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
 * @subpackage plugin
 * 
 * @author      Thomas Jakobi (thomas.jakobi@partout.info)
 * @copyright   Copyright 2011, Thomas Jakobi
 * @version     0.2
 *
 * @internal    events: OnWebPagePrerender
 * @internal    parameter: 
 *              groupJs - Group minified files in `groupFolder` - true
 *              groupFolder - Group files in this folder with `groupJs` enabled - 'assets/js'
 *              minPath - Path to a working minify installation - '/manager/min/'
 *              excludeJs - Comma separated list of files (including pathnames) not to be minified - ''
 */

$groupJs = $modx->getOption('groupJs', $scriptProperties, true);
$groupFolder = $modx->getOption('groupFolder', $scriptProperties, 'assets/js');
$minPath = $modx->getOption('minPath', $scriptProperties, '/manager/min/');
$excludeJs = $modx->getOption('excludeJs', $scriptProperties, '');

$excludeJs = ($excludeJs != '') ? explode(',', $excludeJs) : array();

$eventName = $modx->event->name;
switch ($eventName) {
	case 'OnWebPagePrerender' : {
			$registeredScripts = array();
			$startupScripts = array();
			$scripts = array();

			// get output and registered scripts
			$output = &$modx->resource->_output;
			$clientStartupScripts = $modx->getRegisteredClientStartupScripts();
			$clientScripts = $modx->getRegisteredClientScripts();

			// remove inserted registered scripts
			$output = str_replace($clientStartupScripts . "\n", '', $output);
			$output = str_replace($clientScripts . "\n", '', $output);

			// any cached minified scripts?
			$minifiedScripts = $modx->cacheManager->get('mfr_' . md5($clientStartupScripts . $clientScripts));

			// if minified scripts not cached, collect them
			if (!is_array($minifiedScripts) || empty($minifiedScripts)) {
				preg_match_all('/(src|href)=\"([^\"]+)/', $clientStartupScripts, $startupScripts);
				preg_match_all('/(src|href)=\"([^\"]+)/', $clientScripts, $scripts);

				$startupScripts = (isset($startupScripts[2])) ? $startupScripts[2] : array();
				$scripts = (isset($scripts[2])) ? $scripts[2] : array();
				$registeredScripts = array();

				// startup scripts
				foreach ($startupScripts as $scriptSrc) {
					if (strpos($scriptSrc, '<') === FALSE) {
						// if there is no tag in the registered chunk (just a filename)
						if (substr(trim($scriptSrc), -3) == '.js') {
							// the registered chunk is a separate javascript
							if (substr($scriptSrc, 0, 4) == 'http' || in_array($scriptSrc, $excludeJs)) {
								// do not minify scripts with an external url or scripts in excludeJs
								$registeredScripts['head_nomin'][] = $scriptSrc;
							} elseif ($groupJs && (trim(dirname(trim($scriptSrc)), '/') == $groupFolder)) {
								// group minify scripts in assets/js
								$registeredScripts['head_jsmingroup'][] = trim(str_replace($groupFolder, '', $scriptSrc), '/');
							} else {
								// minify scripts
								$registeredScripts['head_jsmin'][] = $scriptSrc;
							}
						} elseif (substr(trim($scriptSrc), -4) == '.css') {
							// minify css
							$registeredScripts['head_cssmin'][] = $scriptSrc;
						} else {
							// do not minify any other file
							$registeredScripts['head_nomin'][] = $scriptSrc;
						}
					} else {
						// if there is any tag in the registered chunk leave it alone
						$registeredScripts['head'][] = $scriptSrc;
					}
				}

				// other scripts
				foreach ($scripts as $scriptSrc) {
					if (strpos($scriptSrc, '<') === FALSE) {
						// if there is no tag in the registered chunk (just a filename)
						if (substr(trim($scriptSrc), -3) == '.js') {
							// the registered chunk is a separate javascript
							if (substr($scriptSrc, 0, 4) == 'http' || in_array($scriptSrc, $excludeJs)) {
								// do not minify scripts with an external url or scripts in excludeJs
								$registeredScripts['body_nomin'][] = $scriptSrc;
							} elseif ($groupJs && (trim(dirname(trim($scriptSrc)), '/') == $groupFolder)) {
								// group minify scripts in assets/js
								$registeredScripts['body_jsmingroup'][] = trim(str_replace($groupFolder, '', $scriptSrc), '/');
							} else {
								// minify scripts
								$registeredScripts['body_jsmin'][] = $scriptSrc;
							}
						} elseif (substr(trim($scriptSrc), -4) == '.css') {
							// minify css
							$registeredScripts['head_cssmin'][] = $scriptSrc;
						} else {
							// do not minify any other file
							$registeredScripts['body_nomin'][] = $scriptSrc;
						}
					} else {
						// if there is any tag in the registered chunk leave it alone
						$registeredScripts['body'][] = $scriptSrc;
					}
				}

				// prepare the output of the registered blocks
				if (count($registeredScripts['head_cssmin'])) {
					$minifiedScripts['head'] .= '<link href="' . $minPath . '?f=' . implode(',', $registeredScripts['head_cssmin']) . '" rel="stylesheet" type="text/css" />' . "\r\n";
				}
				if (count($registeredScripts['head_jsmingroup'])) {
					$minifiedScripts['head'] .= '<script src="' . $minPath . '?b=' . $groupFolder . '&amp;f=' . implode(',', $registeredScripts['head_jsmingroup']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (count($registeredScripts['head_jsmin'])) {
					$minifiedScripts['head'] .= '<script src="' . $minPath . '?f=' . implode(',', $registeredScripts['head_jsmin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (count($registeredScripts['head_nomin'])) {
					$minifiedScripts['head'] .= '<script src="' . implode('" type="text/javascript"></script>' . "\r\n" . '<script src="', $registeredScripts['head_nomin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (count($registeredScripts['head'])) {
					$minifiedScripts['head'] .= implode("\r\n", $registeredScripts['head']) . "\r\n";
				}
				if (count($registeredScripts['body_jsmingroup'])) {
					$minifiedScripts['body'] .= '<script src="' . $minPath . '?b=' . $groupFolder . '&amp;f=' . implode(',', $registeredScripts['body_jsmingroup']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (count($registeredScripts['body_jsmin'])) {
					$minifiedScripts['body'] .= '<script src="' . $minPath . '?f=' . implode(',', $registeredScripts['body_jsmin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (count($registeredScripts['body_nomin'])) {
					$minifiedScripts['body'] .= '<script src="' . implode('" type="text/javascript"></script>' . "\r\n" . '<script src="', $registeredScripts['body_nomin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (count($registeredScripts['body'])) {
					$minifiedScripts['body'] .= "\r\n" . implode("\r\n", $registeredScripts['body']);
				}

				// cache the result
				$modx->cacheManager->set('mfr_' . md5($clientStartupScripts . $startupScripts), $minifiedScripts);
			}

			// insert minified scripts
			if (isset($minifiedScripts['head'])) {
				$output = str_replace('</head>', $minifiedScripts['head'] . '</head>', $output);
			}
			if (isset($minifiedScripts['body'])) {
				$output = str_replace('</body>', $minifiedScripts['body'] . '</body>', $output);
			}
			break;
		}
}
?>
