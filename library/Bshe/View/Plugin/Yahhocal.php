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
 * Bshe_View_Plugin_Yahhocal
 *
 * Yahhocalのタグを生成するプラグイン
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshe_View_Plugin_Yahhocal extends Bshe_View_Plugin_Abstract
{
    /**
     * templateクラスへYahhocalを設置するためのcss,jsの読み込みを挿入する
     *
     * @param $template
     * @return unknown_type
     */
    static public function setYahhocal($template)
    {
        try{
            // pluginFlagチェック
            $arrayPluginFlags = $template->getParam('pluginFlags');
            if (!isset($arrayPluginFlags['Bshe_View_Plugin_Yahhocal']) or
                ($arrayPluginFlags['Bshe_View_Plugin_Yahhocal'] == false)) {
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
            $strTmp = '<script src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/js/YahhoCal.js" type="text/javascript"></script>';
            $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');
            $strTmp = '<script src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/js/GCalHolidays.js" type="text/javascript"></script>';
            $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');
            $strTmp = '<script type="text/javascript">YahhoCal.loadYUI();</script>';
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