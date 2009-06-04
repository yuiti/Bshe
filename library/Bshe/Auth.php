<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Auth
 * @subpackage View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_Auth */
require_once 'Zend/Auth.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Controller_Init */
require_once 'Bshe/Controller/Init.php';


/**
 * Bshe_Auth
 *
 * 認証空間を用途別に制御する機能を付加した
 * Zend_Authクラス拡張版
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.28
 * @license LGPL
 */
class Bshe_Auth extends Zend_Auth
{
    /**
     * 保持しているAuthインスタンスのキー配列を返す
     *
     * @return array
     */
    static public function getInstanceNames()
    {
        if (is_array(self::$_instance)) {
            return array_keys(self::$_instance);
        } else {
            return array();
        }
    }

    /**
     * Returns an instance of Zend_Auth
     *
     * Singleton pattern implementation
     *
     * @return Zend_Auth Provides a fluent interface
     */
    public static function getInstance($nameSpaceName = 'bshe')
    {
        if (null === self::$_instance[$nameSpaceName]) {
            self::$_instance[$nameSpaceName] = new Bshe_Auth();

            // Bshe_Sessionコンフィグ
            $configSession = Bshe_Registry_Config::getConfig('Bshe_Session');
            $pathString = str_replace('/', '_', Bshe_Controller_Init::getUrlPath());

            // インスタンス作成時に、ストレージをセット
            self::$_instance[$nameSpaceName]->setStorage(new Zend_Auth_Storage_Session($configSession->session_prefix . $pathString . $nameSpaceName));
        }

        return self::$_instance[$nameSpaceName];
    }

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    public function __construct()
    {}
}
