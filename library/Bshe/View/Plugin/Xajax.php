<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Plugin_Abstract */
require_once 'Bshe/View/Plugin/Abstract.php';

/**
 * Bshe_View_Plugin_Xajax
 *
 * Bshe_View_Plugin_Xajaxのタグをセットするプラグイン
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshe_View_Plugin_Xajax extends Bshe_View_Plugin_Abstract
{
    /**
     * templateクラスへxajax用の各種処理を実装する。
     * 処理速度を考慮してbeforeとして呼ばれるため
     * registしたいものは、ユーザー定義beforeプラグインで
     * 適宜templateクラスのパラメータへ追加する
     *
     * @param $template
     * @return unknown_type
     */
    static public function setXajaxInc($template)
    {
        try{
            // pluginFlagチェック
            $arrayPluginFlags = $template->getParam('pluginFlags');
            if (!isset($arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'])) {
                // フラグがないため何もしない
                return $template;
            }

            // セットするAjaxのクラスをインスタンス化
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $configView = Bshe_Registry_Config::getConfig('Bshe_View');
            require_once Bshe_Controller_Init::getMainPath() . $config->alias_path . $config->indexphp_path . '/xajax/xajax_core/xajax.inc.php';
            $xajax = new xajax();
            $xajax->configure('javascript URI', Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/xajax');
            $xajax->configure('requestURI', $_SERVER['REQUEST_URI']);
            if ($configView->xajax_debug == 'debug') {
                $xajax->configure("debug", true);
            }
            $xajax->setDefaultMethod('POST');
            Bshe_Log::logWithFileAndParamsWrite('Xajax関数登録', Zend_Log::DEBUG, $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist']);
            foreach ($arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'] as $key => $target) {
                $xajax->registerFunction($target);
            }
            // Ajax関数実行
            $xajax->processRequest();

            // Javascript出力

            // headerエレメント取得
            if (($headerElement = $template->getElementByName('head')) === false) {
                return $template;
            }

            // headerへ必要なlinkを挿入
            $insertNodeClasses = array();
            $strTmp = $xajax->getJavascript();
            $insertNodeClasses[] = New Bshe_Dom_Node_Text($strTmp);

            foreach ($insertNodeClasses as $key => $node) {
                $template->addChild($node, $headerElement);
            }

            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }



}