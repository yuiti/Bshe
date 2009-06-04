<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */


/**
 * リストを取得してそのリストを画面表示するXajax関数群
 *
 * Ashe_Datapack_Listと連携して処理を行う。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.21
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Xajax_List_View extends Bshe_Specializer_Controller_Action_Xajax_Abstract
{

    /**
     * 選択したレコードを削除する。
     *
     * @param unknown_type $uid
     */
    static public function deleteRecord($uid, $values = array(), $strOrder = "", $pageNum = "")
    {
        try {
            // データパッククラス取得
            $datapackList = Zend_Registry::get('Bshe_Controller_Xajax_List_View_Filter');
            if (method_exists($datapackList, 'deleteRecord')) {
                // 削除実行
                $datapackList->deleteRecord($uid);
            }

            $response = Bshe_Specializer_Controller_Xajax_List_View::showList($values, $strOrder, $pageNum);

            return $response;

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * フィルタがあればフィルタを適用して、リストを生成表示する。
     * Ajaxメソッド
     *
     * @param unknown_type $values
     */
    static public function showList($values = array(), $strOrder = "", $pageNum = "")
    {
        try {
            $response = new xajaxResponse();

            // フィルタ、リストのインスタンスを取得
            $datapackFilter = Zend_Registry::get('Bshe_Specializer_Controller_Action_List_View_Filter');
            $datapackList = Zend_Registry::get('Bshe_Specializer_Controller_Action_List_View_List');
            // テンプレートファイル名取得
            $view = Zend_Registry::get('Bshe_Specializer_Controller_Action_List_View_View');
            // 置換用のテーブルのid
            $listId = Zend_Registry::get('Bshe_Specializer_Controller_Action_List_View_ViewListId');
            // viewのキー　
            $listKey = Zend_Registry::get('Bshe_Specializer_Controller_Action_List_View_ViewKey');

            // 並べ替え
            if ($strOrder == '') {
                // 未指定、valuesから取得
                if ($values['order'] != '') {
                    // $valueで指定あり
                    $datapackFilter->setOrder($values['order']);

                } else {
                    // order条件なし、デフォルトのまま

                }
            } else {
                // 指定あり
                $datapackFilter->setOrder($strOrder);
                // Web上のhiddenへもセット
                $response->assign('order', 'value', $strOrder);
                $values['order'] = $strOrder;
            }

            // ページセット（未指定の場合は1ページ目）
            if ($pageNum == '') {
                // 未指定、values~取得
                if ($values['page'] != '') {
                    // $valueで指定あり
                    $datapackFilter->setPage($values['page']);
                } else {
                    // ページ指定なし1ページ
                    $datapackFilter->setPage(1);
                }
            } else {
                // 指定あり
                $datapackFilter->setPage($pageNum);
                // Web上のhiddenへもセット
                $response->assign('page', 'value', $pageNum);
                $values['page'] = $pageNum;
            }

            // 各項目のbackground-colorクリア
            $response->assign('formErrMsg', 'innerHTML', '');
            $response->assign('formErrMsg', 'style.display', 'none');


            // フィルタがあればフィルタの値をチェック
            if (($rsl = $datapackFilter->checkDatas($values)) !== true) {
                // エラーあり
                $strTmp = "<ul>";
                foreach ($rsl as $num => $arrayError) {
                    foreach ($arrayError as $key => $val) {
                        $strTmp .='<li>' . $datapackFilter->getRequestConfig($key, 'name') . ':' . $val . '</li>';
                    }
                }
                $strTmp .= "</ul>";
                $response->assign('formErrMsg', 'innerHTML', $strTmp);
                $response->assign('formErrMsg', 'style.display', 'block');
            } else {


                // whereデータパックセッション保存
                $datapackFilter->saveDatapackSession();

                // フィルタのWhere条件をセットしたListDatapackを生成
                $datapackList->setFilter($datapackFilter->getFilter());
                $datapackList->setOrder($datapackFilter->getOrder());
                $datapackList->setPage($datapackFilter->getPage());
                $datapackList->clearList();
                $arrayAllResults = $datapackList->getAllLineCount();
                $arrayResults = $datapackList->getArrayResults();

                // viewクラスから対象のテーブルへ値をアサインしてrender
                $view->{$listKey} = array(
                    '_values' => $arrayResults
                );

                // viewのXajaxプラグインをOFFにする（多重読み込み防止）
                $arrayPluginFlags = $view->getTemplatePluginFlags();
                unset ($arrayPluginFlags['Bshe_View_Plugin_Xajax']['setXajaxRegist']);
                $view->setTemplatePluginFlags($arrayPluginFlags);
                // 指定したDOMをレンダリングする
                $strTmp = $view->render();
                // ID取得
                $targetElementId = $view->getEngine()->getTemplate()->getArrayTableElements($listKey, 0);
                $strList = $view->getEngine()->getTemplate()->output($targetElementId['table']);

                // Responseへアサインする
                $response->assign($listId, 'outerHTML', $strList);
                $response->assign($listId, 'style.display', '');
                // ページコントローラーをアサインする
                $str =  $datapackList->getPageCtlAjaxHtml();
                $str = $str . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（計：" . number_format($arrayAllResults['all_page_count']) . "ページ／" . number_format($arrayAllResults['all_line_count']) . "行）";

                $response->assign('top_page_controller', 'innerHTML', $str);
                $response->assign('bottom_page_controller', 'innerHTML', $str);
            }


//            $response->assign('formErrMsg', 'innerHTML', 'ちゃんと届きました。');
//            $response->assign('formErrMsg', 'style.display', 'block');
//
            // たまっているキャッシュをクリアして出力しないようにする。
            ob_clean  ();

            return $response;
        } catch (Exception $e) {
            throw $e;
        }
    }
}