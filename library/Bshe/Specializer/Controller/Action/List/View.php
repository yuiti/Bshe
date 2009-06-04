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

/** Bshe_Specializer_Controller_Action_Default **/
require_once "Bshe/Specializer/Controller/Action/Default.php";


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
class Bshe_Specializer_Controller_Action_List_View extends Bshe_Specializer_Controller_Action_Default
{

    /**
     * 一覧表示用コントローラー
     *
     * 基本的な設定のみ実施するが基本はテンプレートをそのまま表示する。
     * その他Ajax用の処理を実装している。
     *
     */
    public function indexAction()
    {
        try {
            // Xajax用の関数設置処理
            $arrayPluginFlags = $this->view->getTemplatePluginFlags();

            // すでにセッションにフィルタがあれば、再検索
            $datapackFilter = Zend_Registry::get('Bshe_Specializer_Controller_Action_List_View_Filter');
            $datapackFilter = $datapackFilter->getDatapackSession();
            $values = $datapackFilter->getBeforeCheckDatas();
            if ($values != array()) {
                // 読み込み時に検索を実行するように設定
                $this->view->body = array('a' => array('onload', "javascript:xajax_showList(xajax.getFormValues('form_filter')); return false;"));
                // 前の検索結果があるため、それを反映
                foreach ($values as $key => $value) {
                    $this->view->{$key} = htmlspecialchars($value);
                }
            }


            // showlist,deleteRecordチェック
            $isRegist = array();
            foreach ($arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'] as $key => $func) {
                if ($func[1] == 'showList') {
                    $isRegist['showList'] = true;
                } elseif ($func[1] == 'deleteRecord') {
                    $isRegist['deleteRecord'] = true;
                }
            }
            if ($isRegist['showList'] !== true) {
                $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] = array(
                	'Bshe_Specializer_Controller_Action_Xajax_List_View', 'showList'
                );
            }
            if ($isRegist['deleteRecord'] !== true) {
                $arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist'][] = array(
                	'Bshe_Specializer_Controller_Action_Xajax_List_View', 'deleteRecord'
                );
            }
            $this->view->setTemplatePluginFlags($arrayPluginFlags);


        } catch (Exception $e) {
            throw $e;
        }
    }
}