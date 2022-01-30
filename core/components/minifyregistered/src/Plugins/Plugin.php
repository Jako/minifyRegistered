<?php
/**
 * Abstract plugin
 *
 * @package minifyregistered
 * @subpackage plugin
 */

namespace TreehillStudio\MinifyRegistered\Plugins;

use modX;
use MinifyRegistered;

/**
 * Class Plugin
 */
abstract class Plugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var MinifyRegistered $minifyregistered */
    protected $minifyregistered;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * Plugin constructor.
     *
     * @param $modx
     * @param $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties = &$scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('minifyregistered.core_path', null, $this->modx->getOption('core_path') . 'components/minifyregistered/');
        $this->minifyregistered = $this->modx->getService('minifyregistered', 'MinifyRegistered', $corePath . 'model/minifyregistered/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * Run the plugin event.
     */
    public function run()
    {
        $init = $this->init();
        if ($init !== true) {
            return;
        }

        $this->process();
    }

    /**
     * Initialize the plugin event.
     *
     * @return bool
     */
    public function init()
    {
        return true;
    }

    /**
     * Process the plugin event code.
     *
     * @return mixed
     */
    abstract public function process();
}