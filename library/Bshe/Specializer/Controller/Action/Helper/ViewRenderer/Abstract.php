<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Zend_Controller_Action_Helper_ViewRenderer */
require_once 'Zend/Controller/Action/Helper/ViewRenderer.php';

/**
 * Bshe_Specializerを利用するためのコントローラー用ヘルパー
 * Viewの自動生成やパス、ログの設定を行う。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.03.11
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Helper_ViewRenderer_Abstract extends Zend_Controller_Action_Helper_ViewRenderer
{
    /**
     * 追加Viewアサインクラスプラグイン
     */
    protected $_addViewAssign = array();

    /**
     * init - initialize view
     *
     * @return void
     */
    public function init($options = array())
    {
        if ($this->getFrontController()->getParam('noViewRenderer')) {
            return;
        }

        $this->initView(null, null, $options);
    }

    /**
     * Initialize the view object
     *
     * @param  string $path
     * @param  string $prefix
     * @param  array  $options
     * @throws Zend_Controller_Action_Exception
     * @return void
     */
    public function initView($path = null, $prefix = null, array $options = array())
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $viewConfig = Bshe_Registry_Config::getConfig('Bshe_View');
            $mainPath = Bshe_Controller_Init::getMainPath();

            if (null === $this->view) {

                // リクエストクラスのパラメーターにView用のパラメーターがある場合はそれに追記
                $params = array();
                if (isset($options['bshe_view_params'])) {
                    $params = $options['bshe_view_params'];
                }

                // View用パラメーターセット
                if (!isset($params['templatePath'])) {
                    $params['templatePath'] = $this->_actionController->getBasePath();
                }
                if (!is_array($params['afterAssigns'])) {
                    $params['afterAssigns'] = array();
                }
                if (!is_array($params['beforeAssigns'])) {
                    $params['beforeAssigns'] = array();
                }
                if (!isset($params['templateCompilePath'])) {
                    $params['templateCompilePath'] = $mainPath . '/template_c';
                }
                if (!isset($params['templateFile'])) {
                    // テンプレートがディレクトリで終わる場合対策
                    $pathinfo = $this->getRequest()->getPathInfo();
                    if (strtolower(substr($this->getRequest()->getPathInfo(), -5)) != '.html') {
                        // 末尾が.htmlではないため、index.htmlで保管する
                        if (substr($this->getRequest()->getPathInfo(), -1) == '/') {
                            $pathinfo = $pathinfo . 'index.html';
                        } else {
                            $pathinfo = $pathinfo . '/index.html';
                        }
                    }
                    $params['templateFile'] = $pathinfo;
                }

                $params['assignClassPath'][] = $mainPath . '/application/' . $this->_actionController->getRequest()->getModuleName() . '/views/resources';
                $params['helperClassPath'][] = $mainPath . '/application/' . $this->_actionController->getRequest()->getModuleName() . '/views/helpers';
                $params['assignClassPath'][] = $mainPath . '/application/views/resources';
                $params['helperClassPath'][] = $mainPath . '/application/views/helpers';
                if (!isset($params['assignClassPrefix'])) {
                    $params['assignClassPrefix'] = 'Application_Resource_';
                }
                if (!isset($params['helperClassPrefix'])) {
                    $params['helperClassPrefix'] = 'Application_Helper_';
                }


                // 追加Viewプラグイン
                if (isset($this->_addViewAssign['afterAssigns'])) {
                    foreach ($this->_addViewAssign['afterAssigns'] as $key => $arrayAdd) {
                        $params['afterAssigns'][] = $arrayAdd;
                    }
                }
                if (isset($this->_addViewAssign['beforeAssigns'])) {
                    foreach ($this->_addViewAssign['beforeAssigns'] as $key => $arrayAdd) {
                        $params['beforeAssigns'][] = $arrayAdd;
                    }
                }

                // logger
                $templateLogger = new Bshe_Log();
                $writer = new Bshe_Log_Writer_Dailystream( Bshe_Controller_Init::getMainPath() . '/logs', 'BsheView_');
                $templateLogger->addWriter($writer);
                $params['logger'] = $templateLogger;

                $this->setView(New Bshe_View($params));

                // テンプレートログセット
                $response = $this->getResponse();

                $response->clearHeaders();
                $response->setHeader("Expires", gmdate("D, d M Y H:i:s",time() - 3600 * 24 * 365) . " GMT");
                $response->setHeader("Last-Modified", gmdate("D, d M Y H:i:s") . " GMT");
                $response->setHeader("Cache-Control", "no-cache");
                $response->setHeader("Pragma", "no-cache");
                $response->setHeader("Expires", "Thu, 01 Dec 1994 16:00:00 GMT");


                // ロギングクラスセット
                //if (!$this->view->hasLogger()) {
                    $templateLogger = new Bshe_Log();
                    $writer = new Bshe_Log_Writer_Dailystream( Bshe_Controller_Init::getMainPath() . '/logs', 'BsheView_');
                    $templateLogger->addWriter($writer);
                    $this->view->setLogger($templateLogger);
                //}

            }

            // Reset some flags every time
            $options['noController'] = (isset($options['noController'])) ? $options['noController'] : false;
            $options['noRender']     = (isset($options['noRender'])) ? $options['noRender'] : false;
            $this->_scriptAction     = null;
            $this->_responseSegment  = null;

            // Set options first; may be used to determine other initializations
            $this->_setOptions($options);

            // Get base view path
            if (empty($path)) {
                $path = $this->_actionController->getBasePath();
                if (empty($path)) {
                    /**
                     * @see Zend_Controller_Action_Exception
                     */
                    throw new Zend_Controller_Action_Exception('ViewRenderer initialization failed: retrieved view base path is empty');
                }
            }


            // Register view with action controller (unless already registered)
            if ((null !== $this->_actionController)) {
                $this->_actionController->view       = $this->view;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render a view script (optionally to a named response segment)
     *
     * Sets the noRender flag to true when called.
     *
     * @param  string $script
     * @param  string $name
     * @return void
     */
    public function renderScript($script = null, $name = null)
    {
        $this->getResponse()->appendBody(
            $this->view->render($name),
            $name
        );

        $this->setNoRender();
    }

    /**
     * Render a view based on path specifications
     *
     * Renders a view based on the view script path specifications.
     *
     * @param  string  $action
     * @param  string  $name
     * @param  boolean $noController
     * @return void
     */
    public function render($action = null, $name = null, $noController = null)
    {
        try {
            $this->renderScript();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * postDispatch - auto render a view
     *
     * Only autorenders if:
     * - _noRender is false
     * - action controller is present
     * - request has not been re-dispatched (i.e., _forward() has not been called)
     * - response is not a redirect
     *
     * @return void
     */
    public function postDispatch()
    {
        if ($this->_shouldRender()) {
            $this->render();
        }
    }
    /**
     * Should the ViewRenderer render a view script?
     *
     * @return boolean
     */
    protected function _shouldRender()
    {
        return (!$this->getFrontController()->getParam('noViewRenderer')
            && !$this->_neverRender
            && !$this->_noRender
            && $this->getRequest()->isDispatched()
            && !$this->getResponse()->isRedirect()
        );
    }

    /**
     * Set the view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_Controller_Action_Helper_ViewRenderer Provides a fluent interface
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }


    /**
     * Set options
     *
     * @param  array $options
     * @return Zend_Controller_Action_Helper_ViewRenderer Provides a fluent interface
     */
    protected function _setOptions(array $options)
    {
        foreach ($options as $key => $value)
        {
            switch ($key) {
                case 'neverRender':
                case 'neverController':
                case 'noController':
                case 'noRender':
                    $property = '_' . $key;
                    $this->{$property} = ($value) ? true : false;
                    break;
                case 'responseSegment':
                case 'scriptAction':
                case 'viewBasePathSpec':
                case 'viewScriptPathSpec':
                case 'viewScriptPathNoControllerSpec':
                case 'viewSuffix':
                    $property = '_' . $key;
                    $this->{$property} = (string) $value;
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

}