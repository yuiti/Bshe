<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Session
 * @subpackage View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_Session_Namespace */
require_once 'Zend/Session/Namespace.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';


/**
 * Bshe_Session_Namespace
 *
 * Bsheを同一ドメイン内でいくつも利用することを想定し
 * namespaceの名称の頭に自動で値をセットする
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshe_Session_Namespace extends Zend_Session_Namespace
{
    /**
     *
     *
     * @param string $namespace
     * @param boolean $singleInstance
     * @return unknown_type
     */
    public function __construct($namespace = 'Default', $singleInstance = false)
    {
        // Bshe_Sessionの設定取得
        $config = Bshe_Registry_Config::getConfig('Bshe_Session');
        // URLから文字列抽出
        $strUrlPath = mb_ereg_replace('\/', '', Bshe_Controller_Init::getUrlPath());

        parent::__construct($config->session_prefix . $strUrlPath . $namespace, $singleInstance);
    }
}