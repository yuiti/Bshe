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
 * JqueryのTreeviewプラグ引用の抽象クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.05.18
 * @license LGPL
 */
abstract class Bshe_View_Plugin_Jquery_Treeview_Abstract extends Bshe_View_Plugin_Abstract
{
    /**
     * リスト構造の配列
     * $_arrayTree = array(
     *     'サブフォルダ名|ファイル名' => Bshe_View_Plugin_Abstract | <li>タグ内の文字列
     * )
     *
     * @var unknown_type
     */
    protected $_arrayTree = array();

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
            // パラメーター項目設定
            $this->_params['self'] = '';

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
        $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'][] = 'treeview/jquery.treeview.css';
        $arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'][] = 'treeview/jquery.treeview.js';

        return $arrayPluginFlags;
    }

    /**
     * $this->_arrayTreeにセットされた情報をHTMLにて返す
     *
     * @return unknown_type
     */
    public function getHtml()
    {
        try {
            $strHTML = "";
            $strHTML .= '<li>' . $this->getParam('self');

            // 子供がいるかで処理が変わる
            if (count($this->_arrayTree) == 0) {
                // 子供はいないためそのまま
            } else {

                $strHTML .= '<ul>';
                // 子供がいるため再帰
                foreach ($this->_arrayTree as $key => $child) {
                    if (is_a($child, __CLASS__)) {
                        // サブTree
                        $strHTML .= $child->getHtml();
                    } else {
                        // 末端
                        $strHTML .= '<li>' . $child . '</li>';
                    }
                }
                $strHTML .= '</ul>';
            }
            $strHTML .= '</li>';

            return $strHTML;
        } catch (Exception $e) {
            throw $e;
        }
    }
}