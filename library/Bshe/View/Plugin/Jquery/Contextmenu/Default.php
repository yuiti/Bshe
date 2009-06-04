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
/** Bshe_View_Plugin_Jquery_Contextmenu_Abstract */
require_once 'Bshe/View/Plugin/Jquery/Contextmenu/Abstract.php';

/**
 * デフォルトのJqueryのContextmenuプラグイン用のクラス
 * HTMLテンプレート上にコンテキストメニューが設置されている想定
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.06.01
 * @license LGPL
 */
class Bshe_View_Plugin_Jquery_Contextmenu_Default extends Bshe_View_Plugin_Jquery_Contextmenu_Abstract
{
    /**
     * 初期設定を行うコンストラクタ
     * $param = array(
     *     'menu_tag_id' => menuのタグのID
     * )
     *
     * @param $params 設定配列
     * @return unknown_type
     */
    public function __construct($params = array())
    {
        try {
            // パラメーターセット
            parent::__construct($params);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Bshe_Viewのpluginとして、コンテキストメニューをセットする。
     *
     * @return unknown_type
     */
    static public function setContextmenu($template)
    {
        try {
            // pluginFlagチェック
            $arrayPluginFlags = $template->getParam('pluginFlags');
            if (!isset($arrayPluginFlags['Bshe_View_Plugin_Jquery_Contextmenu_Default']['javascript']) or
                ($arrayPluginFlags['Bshe_View_Plugin_Jquery_Contextmenu_Default']['javascript'] == false)) {
                // フラグがないため何もしない
                return $template;
            }
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // headerエレメント取得
            if (($headerElement = $template->getElementByName('head')) === false) {
                return $template;
            }

            // headerへ必要なlinkを挿入
            $insertNodeClasses = array();
            // メインのjquery
            foreach ($arrayPluginFlags['Bshe_View_Plugin_Jquery_Contextmenu_Default']['javascript'] as $key => $target) {
                $insertNodeClasses[] = New Bshe_Dom_Node_Element($target, 'script');
            }

            foreach ($insertNodeClasses as $key => $node) {
                $template->addChild($node, $headerElement);
            }

            return $template;

        } catch (Exception $e) {
            throw $e;
        }
    }


}