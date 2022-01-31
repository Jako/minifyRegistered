<?php
/**
 * @package minifyregistered
 * @subpackage plugin
 */

namespace TreehillStudio\MinifyRegistered\Plugins\Events;

use modChunk;
use TreehillStudio\MinifyRegistered\Plugins\Plugin;

class OnWebPagePrerender extends Plugin
{
    private $registeredScripts = [];

    public function process()
    {
        $this->registeredScripts = [
            'head' => [],
            'body' => []
        ];

        // Get output and registered scripts
        $output = &$this->modx->resource->_output;
        $clientStartupScripts = $this->modx->getRegisteredClientStartupScripts();
        $clientScripts = $this->modx->getRegisteredClientScripts();

        // Remove inserted registered scripts
        if ($clientStartupScripts) {
            $output = str_replace($clientStartupScripts . "\n", '', $output);
        }
        if ($clientScripts) {
            $output = str_replace($clientScripts . "\n", '', $output);
        }

        // Any cached minified scripts?
        $minifiedScripts = $this->modx->cacheManager->get('mfr_' . md5($clientStartupScripts . $clientScripts));

        // If minified scripts are not cached, collect them
        if (!is_array($minifiedScripts) || empty($minifiedScripts)) {
            $startupScripts = ($clientStartupScripts) ? explode("\n", $clientStartupScripts) : [];
            $scripts = ($clientScripts) ? explode("\n", $clientScripts) : [];

            // Collect the registered scripts a
            $this->collectRegisted($startupScripts, 'head');
            $this->collectRegisted($scripts, 'body');

            // Prepare the output of the registered blocks
            $minifiedScripts = [
                'head' => [],
                'body' => []
            ];
            $minifiedScripts['head'][] = $this->registerBlock($this->registeredScripts['head']['cssexternal'], '<link href="[[+script]]" rel="stylesheet" type="text/css">');
            $minifiedScripts['head'][] = $this->registerMinBlock($this->registeredScripts['head']['cssmin'], '<link href="[[+minPath]]?f=[[+scripts]]" rel="stylesheet" type="text/css">');
            $minifiedScripts['head'][] = $this->registerBlock($this->registeredScripts['head']['external'], '<script src="[[+script]]" type="text/javascript"></script>');
            $minifiedScripts['head'][] = $this->registerMinBlock($this->registeredScripts['head']['jsmingroup'], '<script src="[[+minPath]]?b=[[+groupFolder]]&amp;f=[[+scripts]]" type="text/javascript"></script>');
            $minifiedScripts['head'][] = $this->registerMinBlock($this->registeredScripts['head']['jsmin'], '<script src="[[+minPath]]?f=[[+scripts]]" type="text/javascript"></script>');
            $minifiedScripts['head'][] = $this->registerBlock($this->registeredScripts['head']['nomin'], '<script src="[[+script]]" type="text/javascript"></script>');
            $minifiedScripts['head'][] = $this->registerBlock($this->registeredScripts['head']['untouched'], '[[+script]]');
            $minifiedScripts['body'][] = $this->registerBlock($this->registeredScripts['body']['external'], '<script src="[[+script]]" type="text/javascript"></script>');
            $minifiedScripts['body'][] = $this->registerMinBlock($this->registeredScripts['body']['jsmingroup'], '<script src="[[+minPath]]?b=[[+groupFolder]]&amp;f=[[+scripts]]" type="text/javascript"></script>');
            $minifiedScripts['body'][] = $this->registerMinBlock($this->registeredScripts['body']['jsmin'], '<script src="[[+minPath]]?f=[[+scripts]]" type="text/javascript"></script>');
            $minifiedScripts['body'][] = $this->registerBlock($this->registeredScripts['body']['nomin'], '<script src="[[+script]]" type="text/javascript"></script>');
            $minifiedScripts['body'][] = $this->registerBlock($this->registeredScripts['body']['untouched'], '[[+script]]');

            $minifiedScripts['head'] = array_filter($minifiedScripts['head']);
            $minifiedScripts['body'] = array_filter($minifiedScripts['body']);

            // Cache the result
            $this->modx->cacheManager->set('mfr_' . md5($clientStartupScripts . $clientScripts), $minifiedScripts);
        }

        // Insert minified scripts
        if ($minifiedScripts['head']) {
            $output = str_replace('</head>', implode("\r\n", $minifiedScripts['head']) . '</head>', $output);
        }
        if ($minifiedScripts['body']) {
            $output = str_replace('</body>', implode("\r\n", $minifiedScripts['body']) . '</body>', $output);
        }
    }

    /**
     * Collect the registered scripts into sections
     *
     * @param array $scripts
     * @param string $section
     */
    private function collectRegisted($scripts, $section)
    {
        $conditional = false;
        $this->registeredScripts[$section] = [
            'cssexternal' => [],
            'cssmin' => [],
            'external' => [],
            'jsmingroup' => [],
            'jsmin' => [],
            'nomin' => [],
            'untouched' => [],
        ];
        foreach ($scripts as $scriptSrc) {
            if (preg_match('/<!--\[if /', trim($scriptSrc), $tag) || $conditional) {
                // don't touch conditional css/scripts
                $this->registeredScripts[$section]['untouched'][] = $scriptSrc;
                $conditional = true;
                if (preg_match('/endif]-->/', trim($scriptSrc), $tag)) {
                    $conditional = false;
                }
            } else {
                preg_match('/^<(script|link)[^>]+>/', trim($scriptSrc), $tag);
                if ($tag && preg_match('/(src|href)=\"(.*?)(\?v=.*?)?"/', $tag[0], $src)) {
                    // if there is a filename referenced in the registered line
                    if (substr(trim($src[2]), -3) == '.js') {
                        // the registered chunk is a separate javascript
                        if (substr($src[2], 0, 4) == 'http' || substr($src[2], 0, 2) == '//') {
                            // do not minify scripts with an external url
                            $this->registeredScripts[$section]['external'][] = $src[2];
                        } elseif (in_array($src[2], $this->minifyregistered->getOption('excludeJs'))) {
                            // do not minify scripts in excludeJs
                            $this->registeredScripts[$section]['nomin'][] = $src[2];
                        } elseif ($this->minifyregistered->getOption('groupJs') && (trim(dirname(trim($src[2])), '/') == $this->minifyregistered->getOption('groupFolder'))) {
                            // group minify scripts in assets/js
                            $this->registeredScripts[$section]['jsmingroup'][] = trim(str_replace($this->minifyregistered->getOption('groupFolder'), '', $src[2]), '/');
                        } else {
                            // minify scripts
                            $this->registeredScripts[$section]['jsmin'][] = $src[2];
                        }
                    } elseif ((substr(trim($src[2]), -4) == '.css') || (strpos($src[2], '/css?') !== false)) {
                        if (substr($src[2], 0, 4) == 'http' || substr($src[2], 0, 2) == '//') {
                            // do not minify css with an external url
                            $this->registeredScripts[$section]['cssexternal'][] = $src[2];
                        } else {
                            // minify css
                            $this->registeredScripts[$section]['cssmin'][] = $src[2];
                        }
                    } else {
                        // do not minify any other file
                        $this->registeredScripts[$section]['nomin'][] = $src[2];
                    }
                } else {
                    // if there is no filename referenced in the registered line leave it alone
                    $this->registeredScripts[$section]['untouched'][] = $scriptSrc;
                }
            }
        }
        foreach ($this->registeredScripts[$section] as &$scriptSection) {
            $scriptSection = array_unique($scriptSection);
        }
    }

    /**
     * @param array $scripts
     * @param string $template
     * @return string
     */
    private function registerBlock($scripts, $template)
    {
        $block = [];
        foreach ($scripts as $script) {
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk', array('name' => 'block' . uniqid()));
            $chunk->setCacheable(false);
            $block[] = $chunk->process([
                'script' => $script,
            ], $template);
            break;
        }
        return implode("\r\n", $block);
    }

    /**
     * @param array $scripts
     * @param string $template
     * @return string
     */
    private function registerMinBlock($scripts, $template)
    {
        $minPath = $this->minifyregistered->getOption('minPath');
        $groupFolder = $this->minifyregistered->getOption('groupFolder');

        if ($scripts) {
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk', array('name' => 'block' . uniqid()));
            $chunk->setCacheable(false);
            return $chunk->process([
                'scripts' => implode(',', $scripts),
                'minPath' => $minPath,
                'groupFolder' => $groupFolder
            ], $template);
        } else {
            return '';
        }
    }
}
