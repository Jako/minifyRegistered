<?php
/**
 * minifyRegistered
 *
 * Copyright 2011-2019 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package minifyregistered
 * @subpackage plugin
 */

$groupJs = $modx->getOption('minifyregistered.groupJs', null, false);
$groupFolder = $modx->getOption('minifyregistered.groupFolder', null, 'assets/js');
$minPath = $modx->getOption('minifyregistered.minPath', null, '/assets/min/');
$excludeJs = $modx->getOption('minifyregistered.excludeJs', null, '');

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
			if ($clientStartupScripts) {
				$output = str_replace($clientStartupScripts . "\n", '', $output);
			}
			if ($clientScripts) {
				$output = str_replace($clientScripts . "\n", '', $output);
			}

			// any cached minified scripts?
			$minifiedScripts = $modx->cacheManager->get('mfr_' . md5($clientStartupScripts . $clientScripts));

			// if minified scripts not cached, collect them
			if (!is_array($minifiedScripts) || empty($minifiedScripts)) {
				$startupScripts = explode("\n", $clientStartupScripts);
				$scripts = explode("\n", $clientScripts);

				$conditional = FALSE;
				// startup scripts
				foreach ($startupScripts as $scriptSrc) {
					if (preg_match('/<!--\[if /', trim($scriptSrc), $tag) || $conditional) {
						// don't touch conditional css/scripts
						$registeredScripts['head'][] = $scriptSrc;
						$conditional = TRUE;
						if ($conditional && preg_match('/endif\]-->/', trim($scriptSrc), $tag)) {
							$conditional = FALSE;
						}
					} else {
						preg_match('/^<(script|link)[^>]+>/', trim($scriptSrc), $tag);
						if (preg_match('/(src|href)=\"([^\"]+)/', $tag[0], $src)) {
							// if there is a filename referenced in the registered line
							if (substr(trim($src[2]), -3) == '.js') {
								// the registered chunk is a separate javascript
								if (substr($src[2], 0, 4) == 'http' || substr($src[2], 0, 2) == '//') {
									// do not minify scripts with an external url
									$registeredScripts['head_external'][] = $src[2];
								} elseif (in_array($src[2], $excludeJs)) {
									// do not minify scripts in excludeJs
									$registeredScripts['head_nomin'][] = $src[2];
								} elseif ($groupJs && (trim(dirname(trim($src[2])), '/') == $groupFolder)) {
									// group minify scripts in assets/js
									$registeredScripts['head_jsmingroup'][] = trim(str_replace($groupFolder, '', $src[2]), '/');
								} else {
									// minify scripts
									$registeredScripts['head_jsmin'][] = $src[2];
								}
							} elseif ((substr(trim($src[2]), -4) == '.css') || (strpos($src[2], '/css?') !== FALSE)) {
								if (substr($src[2], 0, 4) == 'http' || substr($src[2], 0, 2) == '//') {
									// do not minify css with an external url
									$registeredScripts['head_cssexternal'][] = $src[2];
								} else {
									// minify css
									$registeredScripts['head_cssmin'][] = $src[2];
								}
							} else {
								// do not minify any other file
								$registeredScripts['head_nomin'][] = $src[2];
							}
						} else {
							// if there is no filename referenced in the registered line leave it alone
							$registeredScripts['head'][] = $scriptSrc;
						}
					}
				}

				$conditional = FALSE;
				// body scripts
				foreach ($scripts as $scriptSrc) {
					if (preg_match('/<!--\[if /', trim($scriptSrc), $tag) || $conditional) {
						// don't touch conditional css/scripts
						$registeredScripts['body'][] = $scriptSrc;
						$conditional = TRUE;
						if ($conditional && preg_match('/endif\]-->/', trim($scriptSrc), $tag)) {
							$conditional = FALSE;
						}
					} else {
						preg_match('/^<(script|link)[^>]+>/', trim($scriptSrc), $tag);
						if (preg_match('/(src|href)=\"([^\"]+)/', $tag[0], $src)) {
							// if there is a filename referenced in the registered line
							if (substr(trim($src[2]), -3) == '.js') {
								// the registered chunk is a separate javascript
								if (substr($src[2], 0, 4) == 'http' || substr($src[2], 0, 2) == '//') {
									// do not minify scripts with an external url
									$registeredScripts['body_external'][] = $src[2];
								} elseif (in_array($src[2], $excludeJs)) {
									// do not minify scripts in excludeJs
									$registeredScripts['body_nomin'][] = $src[2];
								} elseif ($groupJs && (trim(dirname(trim($src[2])), '/') == $groupFolder)) {
									// group minify scripts in assets/js
									$registeredScripts['body_jsmingroup'][] = trim(str_replace($groupFolder, '', $src[2]), '/');
								} else {
									// minify scripts
									$registeredScripts['body_jsmin'][] = $src[2];
								}
							} elseif (substr(trim($src[2]), -4) == '.css') {
								// minify css
								$registeredScripts['body_cssmin'][] = $src[2];
							} else {
								// do not minify any other file
								$registeredScripts['body_nomin'][] = $src[2];
							}
						} else {
							// if there is no filename referenced in the registered line leave it alone
							$registeredScripts['body'][] = $scriptSrc;
						}
					}
				}

				// prepare the output of the registered blocks
				if (isset($registeredScripts['head_cssexternal']) && count($registeredScripts['head_cssexternal'])) {
					$minifiedScripts['head'] .= '<link href="' . implode('" rel="stylesheet" type="text/css" />' . "\r\n" . '<link href="', $registeredScripts['head_cssexternal']) . '" rel="stylesheet" type="text/css" />' . "\r\n";
				}
				if (isset($registeredScripts['head_cssmin']) && count($registeredScripts['head_cssmin'])) {
					$minifiedScripts['head'] .= '<link href="' . $minPath . '?f=' . implode(',', $registeredScripts['head_cssmin']) . '" rel="stylesheet" type="text/css" />' . "\r\n";
				}
				if (isset($registeredScripts['head_external']) && count($registeredScripts['head_external'])) {
					$minifiedScripts['head'] .= '<script src="' . implode('" type="text/javascript"></script>' . "\r\n" . '<script src="', $registeredScripts['head_external']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['head_jsmingroup']) && count($registeredScripts['head_jsmingroup'])) {
					$minifiedScripts['head'] .= '<script src="' . $minPath . '?b=' . $groupFolder . '&amp;f=' . implode(',', $registeredScripts['head_jsmingroup']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['head_jsmin']) && count($registeredScripts['head_jsmin'])) {
					$minifiedScripts['head'] .= '<script src="' . $minPath . '?f=' . implode(',', $registeredScripts['head_jsmin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['head_nomin']) && count($registeredScripts['head_nomin'])) {
					$minifiedScripts['head'] .= '<script src="' . implode('" type="text/javascript"></script>' . "\r\n" . '<script src="', $registeredScripts['head_nomin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['head']) && count($registeredScripts['head'])) {
					$minifiedScripts['head'] .= implode("\r\n", $registeredScripts['head']) . "\r\n";
				}
				if (isset($registeredScripts['body_external']) && count($registeredScripts['body_external'])) {
					$minifiedScripts['body'] .= '<script src="' . implode('" type="text/javascript"></script>' . "\r\n" . '<script src="', $registeredScripts['body_external']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['body_jsmingroup']) && count($registeredScripts['body_jsmingroup'])) {
					$minifiedScripts['body'] .= '<script src="' . $minPath . '?b=' . $groupFolder . '&amp;f=' . implode(',', $registeredScripts['body_jsmingroup']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['body_jsmin']) && count($registeredScripts['body_jsmin'])) {
					$minifiedScripts['body'] .= '<script src="' . $minPath . '?f=' . implode(',', $registeredScripts['body_jsmin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['body_nomin']) && count($registeredScripts['body_nomin'])) {
					$minifiedScripts['body'] .= '<script src="' . implode('" type="text/javascript"></script>' . "\r\n" . '<script src="', $registeredScripts['body_nomin']) . '" type="text/javascript"></script>' . "\r\n";
				}
				if (isset($registeredScripts['body']) && count($registeredScripts['body'])) {
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
