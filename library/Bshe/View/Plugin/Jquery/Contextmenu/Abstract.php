<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Plugin_Abstract */
require_once 'Bshe/View/Plugin/Abstract.php';

/**
 * JqueryのContextmenuプラグイン用の抽象クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.06.01
 * @license LGPL
 */
abstract class Bshe_View_Plugin_Jquery_Contextmenu_Abstract extends Bshe_View_Plugin_Abstract
{

    /**
     * 初期設定を行うコンストラクタ
     * $param = array(
     *     'path' => treeview表示するパス
     * )
     *
     * @param $params 設定配列
     * @return unknown_type
     */
    public function __construct($params = array())
    {
        try {
            // パラメーターセット
            $this->setParams($params);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Bshe_Viewのパラメーター配列を取得して、
     * 配列に必要なJavascriptをセットする。
     *
     * @param $arrayPluginFlags
     * @return unknown_type
     */
    static public function setJavascript($arrayPluginFlags)
    {
        $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJquery'] = true;
        $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'][] = 'contextMenu/jquery.contextMenu.js';
        $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'][] = 'contextMenu/jquery.contextMenu.css';

        return $arrayPluginFlags;
    }

}