<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Controller
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** ajax読み込み **/
$config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
require_once( Bshe_Controller_Init::getMainPath() . $config->alias_path . $config->indexphp_path . "/xajax/xajax_core/xajax.inc.php");

/** Bshe_Specializer_Controller_Action_List_View **/
require_once "Bshe/Specializer/Controller/Action/List/View.php";


/**
 * DBからリストを取得してそのリストを画面表示するコントローラー
 *
 * Ashe_Datapack_Listと連携して処理を行う。
 * 継承されて利用されることを想定したクラス。
 *
 * @copyright  since 2009 Abatous inc. All Rights Reserved
 * @version    $Id:$
 * @link       https://del.abatous.jp/projects/ashe_001/wiki/docs/Ashe_Controller_Action_List
 * @since      2009.03.12
 * @todo       設定ファイルには、未対応
 */
class Bshe_Specializer_Controller_Action_Bshe_List_View extends Bshe_Specializer_Controller_Action_List_View
{
    /**
     * Retrieve base path based on location of current action controller
     *
     * @return string
     */
    public function getBasePath()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $mainPath = Bshe_Controller_Init::getMainPath();


            return $mainPath . $config->alias_path;
        } catch (Exception $e) {
            throw $e;
        }
    }
}