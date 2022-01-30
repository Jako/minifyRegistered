<?php
/**
 * MinifyRegistered
 *
 * Copyright 2015-2020 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package minifyregistered
 * @subpackage classfile
 */

namespace TreehillStudio\MinifyRegistered;

use modX;

/**
 * Class MinifyRegistered
 */
class MinifyRegistered
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'minifyregistered';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'MinifyRegistered';

    /**
     * The version
     * @var string $version
     */
    public $version = '0.4.0';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * MinifyRegistered constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');
        $modxversion = $this->modx->getVersionData();

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $options);

        // Add default options
        $this->options = array_merge($this->options, [
            'debug' => (bool)$this->modx->getOption($this->namespace . '.debug', null, '0') == 1,
            'modxversion' => $modxversion['version'],
            'groupJs' => (bool)$this->modx->getOption($this->namespace . '.groupJs', null, '0') == 1,
            'groupFolder' => $this->modx->getOption($this->namespace . '.groupFolder', null, 'assets/js'),
            'minPath' => $this->modx->getOption($this->namespace . '.minPath', null, '/assets/min/'),
            'excludeJs' => $this->modx->getOption($this->namespace . '.excludeJs', null, ''),
            'errorLogger' => (bool)$modx->getOption($this->namespace . '.errorLogger', null, false),
            'allowDebugFlag' => (bool)$modx->getOption($this->namespace . '.allowDebugFlag', null, false),
            'cachePath' => MODX_CORE_PATH . 'cache/minify',
            'documentRoot' => $modx->getOption($this->namespace . '.documentRoot', null, ''),
            'cacheFileLocking' => (bool)$modx->getOption($this->namespace . '.cacheFileLocking', null, true),
            'bubbleCssImports' => (bool)$modx->getOption($this->namespace . '.bubbleCssImports', null, false),
            'maxAge' => (int)$modx->getOption($this->namespace . '.maxAge', null, 1800),
            'allowDirs' => json_decode($modx->getOption($this->namespace . '.allowDirs', null, '["//js", "//css", "//assets"]')),
            'groupsOnly' => (bool)$modx->getOption($this->namespace . '.groupsOnly', null, false),
            'noMinPattern' => $modx->getOption($this->namespace . '.noMinPattern', null, null, true),
            'symlinks' => json_decode($modx->getOption($this->namespace . '.symlinks', null, '[]')),
            'uploaderHoursBehind' => intval($modx->getOption($this->namespace . '.uploaderHoursBehind', null, 0)),
        ]);

        $this->options['excludeJs'] = $this->options['excludeJs'] ? explode(',', $this->options['excludeJs']) : [];

        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');

        if (!file_exists($this->getOption('cachePath'))) {
            mkdir($this->getOption('cachePath'));
        }
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }
}
