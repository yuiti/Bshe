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

/**
 * サイトマップ用のFiletreeクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.05.18
 * @license LGPL
 */
class Bshe_Cms_Models_Index_Sitemap extends Bshe_View_Plugin_Jquery_Treeview_Filetree
{
    /**
     * 子供がある場合のTreeに対する文字列を取得する。
     * 実装の際にaタグや文字列を出す際はオーバーライドする。
     *
     * @param $file
     * @return unknown_type
     */
    protected function getParentString($file)
    {
        try {
            // arrayTreeへ、ファイル名、URLのAタグを表示
            return '<span class="folder">' . $file . '</span>';
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ファイルパスから、$_arrayTreeにセットする文字列を取得する。
     * 実装の際にaタグや文字列を出す際はオーバーライドする。
     *
     * @param $file
     * @return unknown_type
     */
    protected function setFileString($file)
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $targetPath = Bshe_Controller_Init::getMainPath() . $config->alias_path . $config->template_path;

            // arrayTreeへ、ファイル名、URLのAタグを表示
            if ((substr($file, -3) == 'htm') or (substr($file, -4) == 'html')) {
                $this->_arrayTree[$file] = '<span class="file">' .
                    '<a href="' . Bshe_Controller_Init::getUrlPath() .
                    substr($this->getParam('path'), strlen($targetPath)) .
                    '/' . $file . '">' .
                    $file . '</a></span>';
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}