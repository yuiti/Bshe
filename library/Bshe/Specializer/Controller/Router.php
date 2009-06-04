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

/** Bshe_Abstract */
require_once 'Bshe/Abstract.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Registry_Config */
require_once 'Bshe/Registry/Config.php';
/** Zend_Controller_Router_Route_Regex */
require_once 'Zend/Controller/Router/Route/Regex.php';

/**
 * BsheライブラリのHTMLファイル分割MVCを利用するための
 * ルータールーター制御クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.19
 * @license LGPL
 */
class Bshe_Specializer_Controller_Router extends Bshe_Abstract
{

    /**
     * Zend_Controller_Router_Rewriteのインスタンスを引数にとり
     * Bshe_Specializerのためのルーターをセットし、
     * Zend_Controller_Router_Rewriteのインスタンスを返すメソッド
     *
     * @param Zend_Controller_Router_Rewrite $router
     * @return Zend_Controller_Router_Rewrite
     */
    public static function createRouter($arrayRoutes = array(), $router = null)
    {
        try {
            // ルーター生成
            if ($router == null) {
                $router = new Zend_Controller_Router_Rewrite();
            }

            $routeNumber=0;

            // 引数でルート指定
            if ($arrayRoutes !== array()) {
                // 登録
                foreach ($arrayRoutes as $reg => $arrayRoute) {
                    $route = new Zend_Controller_Router_Route_Regex(
                        $reg,
                        array(
                            'module' => $arrayRoute['module'],
                            'controller' => $arrayRoute['controller'],
                            'action' => $arrayRoute['action']
                        )
                    );
                    $router->addRoute($arrayRoute['module'] . '_' . $arrayRoute['controller'] . '_' . $arrayRoute['action'] . '_' . $routeNumber, $route);
                    $routeNumber++;
                }
            }

            // コンフィグクラス取得
            try {
                $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
                if (isset ($config->router)) {
                    $arrayRoutes = $config->router->toArray();

                    // ルートセット
                    foreach ($arrayRoutes as $module => $controllers) {
                        foreach ($controllers as $controller => $actions) {
                            foreach ($actions as $action => $reg) {
                                $route = new Zend_Controller_Router_Route_Regex(
                                    $reg,
                                    array(
                                        'module' => $module,
                                        'controller' => $controller,
                                        'action' => $action
                                    )
                                );
                                $router->addRoute($module . '_' . $controller . '_' . $action . '_' . $routeNumber, $route);
                                $routeNumber++;
                            }
                        }
                    }
                }

                // 独自INIファイルから取得
                if (isset ($config->route_file)) {
                    $routeConfig = new Zend_Config_Ini(Bshe_Controller_Init::getMainPath() . $config->route_file, null, array('allowModifications' => true));
                    $arrayRoutes = $routeConfig->router->toArray();
                    // ルートセット
                    foreach ($arrayRoutes as $module => $controllers) {
                        foreach ($controllers as $controller => $actions) {
                            foreach ($actions as $action => $reg) {
                                $route = new Zend_Controller_Router_Route_Regex(
                                    $reg,
                                    array(
                                        'module' => $module,
                                        'controller' => $controller,
                                        'action' => $action
                                    )
                                );
                                $router->addRoute($module . '_' . $controller . '_' . $action . '_' . $routeNumber, $route);
                                $routeNumber++;
                            }
                        }
                    }
                }


            } catch (Bshe_Registry_Exception_Noconfigfile $e) {
                // コンフィグファイルなし、そのまま実行
            } catch (Exception $e) {
                // その他の例外
                throw $e;
            }

            return $router;
        } catch (Exception $e) {
            throw $e;
        }
    }
}