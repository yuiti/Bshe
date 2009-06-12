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
 * Bshe_View_Plugin_Sexylightbox
 *
 * Sexylightboxのタグを生成するプラグイン
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshe_View_Plugin_Jquery extends Bshe_View_Plugin_Abstract
{
    /**
     * templateクラスへsexylightboxを設置するためのcss,jsの読み込みを挿入する
     *
     * @param $template
     * @return unknown_type
     */
    static public function setJquery($template)
    {
        try{
            // pluginFlagチェック
            $arrayPluginFlags = $template->getParam('pluginFlags');
            if (!isset($arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJquery']) or
                ($arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJquery'] == false)) {
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
            $strTmp = '<script src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/jquery/jquery.js" type="text/javascript"></script>';
            $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');
            // 共存
            $strTmp = '<script type="text/javascript">jQuery.noConflict();</script>';
            $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');

            foreach ($arrayPluginFlags['Bshe_View_Plugin_Jquery']['setJqueryPlugin'] as $key => $target) {
                if (substr($target, 0, 1) == '/') {
                    // フルパスで指定されている
                    if (substr($target, -3) == 'css') {
                        $strTmp = '<link rel="stylesheet" type="text/css" href="' . $target . '" />';
                        $insertNodeClasses[] = New Bshe_Dom_Node_SingleElement($strTmp, 'link');
                    } elseif (substr($target, -2) == 'js') {
                        $strTmp = '<script src="' . $target . '" type="text/javascript"></script>';
                        $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');
                    }
                } else {
                    if (substr($target, -3) == 'css') {
                        $strTmp = '<link rel="stylesheet" type="text/css" href="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/jquery/plugins/' . $target . '" />';
                        $insertNodeClasses[] = New Bshe_Dom_Node_SingleElement($strTmp, 'link');
                    } elseif (substr($target, -2) == 'js') {
                        $strTmp = '<script src="' . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/jquery/plugins/' . $target . '" type="text/javascript"></script>';
                        $insertNodeClasses[] = New Bshe_Dom_Node_Element($strTmp, 'script');
                    }
                }
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