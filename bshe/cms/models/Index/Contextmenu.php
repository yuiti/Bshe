<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Application
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @needs      PEAR:Net_UserAgent_Mobile 1.0.0RC1
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Plugin_Jquery_Contextmenu_Default */
require_once "Bshe/View/Plugin/Jquery/Contextmenu/Default.php";

/**
 * サイトマップ用のFiletreeクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.05.18
 * @license LGPL
 */
class Bshe_Cms_Models_Index_Contextmenu extends Bshe_View_Plugin_Jquery_Contextmenu_Default
{

    /**
     * Javascriptの関数を返す
     * 必ずオーバーライドする
     *
     * @return unknown_type
     */
    protected function _getFunctionString()
    {
        $strJavascript =
/*            "function(action, el, pos) {" . "\n" .
                "if (action == 'edit') {" . "\n" .
                    "xajax_goEdit();" . "\n" .
                "} else if (action == 'copy') {" . "\n" .
                    "e=new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:'" .
                        Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/sexy-lightbox-2/sexyimages'});" . "\n" .
                    "e.show('ページをコピー', '" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/cms/admin/sitemap/copy.html?height=160&width=350', 'sexylightbox');" . "\n" .
                "} else if (action == 'delete') {" . "\n" .
                    "e=new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:'" .
                        Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/sexy-lightbox-2/sexyimages'});" . "\n" .
                    "e.show('ページを削除', '" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/cms/admin/sitemap/delete.html?height=160&width=350', 'sexylightbox');" . "\n" .
                "} else if (action == 'editproperty') {" . "\n" .
                    "e=new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:'" .
                        Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/sexy-lightbox-2/sexyimages'});" . "\n" .
                    "e.show('タイトルなどの編集', '" . Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . "/cms/admin/sitemap/delete.html?height=160&width=350', 'sexylightbox');" . "\n" .
                "}}" . "\n";*/
        $strJavascript = "function(action, el, pos) { alert('aaa');}";
        return $strJavascript;
    }


    /**
     * サイト編集画面へ移動
     *
     * @return unknown_type
     */
    static public function goEdit()
    {

    }

    /**
     * ページのコピーを実行
     *
     * @return unknown_type
     */
    static public function doCopy()
    {

    }

    /**
     * ページの削除を実行
     *
     * @return unknown_type
     */
    static public function doDelete()
    {

    }

    /**
     * ページプロパティの編集
     *
     * @return unknown_type
     */
    static public function doEditProperty()
    {

    }
}
