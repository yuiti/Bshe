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
 * @subpackage View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_View_Resource_Html_Abstract */
require_once 'Bshe/View/Resource/Html/Abstract.php';
/** Bshe_View_Engine */
require_once 'Bshe/View/Engine.php';

/**
 * Bshe_View_Resource_Html_Select
 *
 * データを1方向に繰り返し表示するリソースクラス
 * <table>タグなどで利用
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.29
 * @license LGPL
 */
class Bshe_View_Resource_Html_Select extends Bshe_View_Resource_Html_Abstract
{

    /**
     * selectタグに対する処理
     * 引数配列は以下の形式
     *  array(
     *       'templateClass' => テンプレートクラスのインスタンス,
     *       'key' => seq
     *       'element' => 処理対象のelement,
     *       'params' => 識別文字列をパースした配列,
     *       'presetValues' => preset引数
     *       )
     *  preset引数は、
     *   固定の値の場合、「a」処理される。
     *   配列の場合、対象が_tableキーを持っている場合は、再帰的にテーブル生成を呼び出す。
     * 処理可能なタグに制限があるため、タグ種別のチェックが行われる。
     *
     * @param array $arrayParams Resourceクラス向けパラメーター
     * @param boolean $before beforeメソッドの呼び出し（Engineから呼ばれた場合はEngine側で呼ばれるため不要）
     * @param boolean $after afterメソッドの呼び出し（Engineから呼ばれた場合はEngine側で呼ばれるため不要）
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValuesSelect($arrayParams, $before = false, $after = false)
    {
        try {
            // テーブル名称
            $tableName = $arrayParams['params']['arrayMethodParams'][0];
            // テーブル用Element配列
            $arrayTableElements = $arrayParams['templateClass']->getArrayTableElements($tableName, $arrayParams['params']['seq']);
            $arrayTablePasedIds = $arrayParams['templateClass']->getArrayTablePasedIds($tableName, $arrayParams['params']['seq']);
            // 子テーブル配列
            $arrayTableChildren = $arrayParams['templateClass']->getArrayTableChildren($tableName, $arrayParams['params']['seq']);

            // テーブル用データ
            $tableValuesName = $arrayParams['params']['arrayMethodParams'][1];
            if ($tableValuesName == '') {
                $tableValuesName = $tableName;
            }

            // row定義がある場合は、optiongroupを利用したselectと判断、通常のテーブルとして処理
            if (isset($arrayTableElements['row'])) {
                return Bshe_View_Resource_Html_Select::assignValuesSelectwithgroup($arrayParams, $before, $after);
            }

            // 複数の配列登録がある場合は、最後の配列を利用する
            $tmp = count( $arrayParams['presetValues'][$tableValuesName]) -1;
            $arrayTableValues = $arrayParams['presetValues'][$tableValuesName][$tmp];

            // テーブル用Elementクラス
            $elementTable = $arrayParams['element'];

            // table自身のタグに対する処理を実施（before/afterはエンジンから呼ばれるため不要）
            if ($arrayTableValues['_assigns'] != array()) {
                $arrayParams['presetValues'][$tableValuesName] = $arrayTableValues['_assigns'];
                $arrayParams['params']['arrayMethodParams']['valuekey'] = $tableValuesName;

                Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayParams, $before, $after);
            } else {
                $arrayParams['presetValues'][$tableValuesName] = array();
                $arrayParams['params']['arrayMethodParams']['valuekey'] = $tableValuesName;

                Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayParams, $before, $after);
            }

            // テーブル構造上必要なタグの有無を確認
            // row
            if (!isset( $arrayTableElements['cols'][-1]['_xtable'])) {
                // row設定がない
                throw New Bshe_View_Resource_Html_Exception_NoTableElementInHtml($arrayParams['templateClass']->getTemplateFileName(),
                    $arrayParams['params']['originalId'], $arrayParams['seq'], $tableName, 'option');
            }
            // rowElement
            $arrayTmp = array_keys($arrayTableElements['cols'][-1]['_xtable']);
            $elementRow = $arrayTableElements['cols'][-1]['_xtable'][$arrayTmp[0]];
            $arrayRowParams = $arrayTablePasedIds['cols'][-1]['_xtable'][$arrayTmp[0]];

            // 行数分だけ行をコピー
            foreach ($arrayTableValues['_values'] as $key => $values) {
                // 行をコピー
                $arrayParams['templateClass']->cloneNodeBefore($elementRow, $elementRow);
            }
            // 最終行削除
            $arrayParams['templateClass']->removeNode($elementRow);

            // 再パース
            $arrayParams['templateClass']->clearTable($tableName, $arrayParams['params']['seq'], true);
            $arrayParams['templateClass']->parseHTML($elementTable, $arrayParams['params']['seq'], $tableName);

            // テーブル用Element配列再取得
            $arrayTableElements = $arrayParams['templateClass']->getArrayTableElements($tableName, $arrayParams['params']['seq']);
            $arrayTablePasedIds = $arrayParams['templateClass']->getArrayTablePasedIds($tableName, $arrayParams['params']['seq']);
            $arrayTableChildren = $arrayParams['templateClass']->getArrayTableChildren($tableName, $arrayParams['params']['seq']);


            // データ配列にてループ
            $i=0;
            foreach ($arrayTableElements['cols'][-1]['_xtable'] as $rowSeq => $elementRow) {
                $arrayRowParams = $arrayTablePasedIds['cols'][-1]['_xtable'][$rowSeq];

                // optionに対する処理

                // 行に対する処理
                $arrayTargetParams = $arrayRowParams;
                $arrayTargetParams['presetValues'] = $arrayTableValues['_values'];

                $arrayTargetParams['params']['arrayMethodParams']['valuekey'] = $i;
                $arrayTargetParams['templateClass'] = $arrayParams['templateClass'];
                $arrayTargetParams['element'] = $elementRow;
                $arrayParams['templateClass'] =
                    Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTargetParams);
                $i++;
            }

            return $arrayParams['templateClass'];

        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * selectタグに対する処理
     * optgroupタグが利用されているselectタグに対する処理
     *
     * 引数配列は以下の形式
     *  array(
     *       'templateClass' => テンプレートクラスのインスタンス,
     *       'key' => seq
     *       'element' => 処理対象のelement,
     *       'params' => 識別文字列をパースした配列,
     *       'presetValues' => preset引数
     *       )
     *  preset引数は、
     *   固定の値の場合、「a」処理される。
     *   配列の場合、対象が_tableキーを持っている場合は、再帰的にテーブル生成を呼び出す。
     * 処理可能なタグに制限があるため、タグ種別のチェックが行われる。
     *
     * @param array $arrayParams Resourceクラス向けパラメーター
     * @param boolean $before beforeメソッドの呼び出し（Engineから呼ばれた場合はEngine側で呼ばれるため不要）
     * @param boolean $after afterメソッドの呼び出し（Engineから呼ばれた場合はEngine側で呼ばれるため不要）
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValuesSelectwithgroup($arrayParams, $before = false, $after = false)
    {
        try {
            // テーブル名称
            $tableName = $arrayParams['params']['arrayMethodParams'][0];
            // テーブル用Element配列
            $arrayTableElements = $arrayParams['templateClass']->getArrayTableElements($tableName, $arrayParams['params']['seq']);
            $arrayTablePasedIds = $arrayParams['templateClass']->getArrayTablePasedIds($tableName, $arrayParams['params']['seq']);
            // 子テーブル配列
            $arrayTableChildren = $arrayParams['templateClass']->getArrayTableChildren($tableName, $arrayParams['params']['seq']);

            // テーブル用データ
            $tableValuesName = $arrayParams['params']['arrayMethodParams'][1];
            if ($tableValuesName == '') {
                $tableValuesName = $tableName;
            }

            // 複数の配列登録がある場合は、最後の配列を利用する
            $tmp = count( $arrayParams['presetValues'][$tableValuesName]) -1;
            $arrayTableValues = $arrayParams['presetValues'][$tableValuesName][$tmp];

            // テーブル用Elementクラス
            $elementTable = $arrayParams['element'];

            // table自身のタグに対する処理を実施（before/afterはエンジンから呼ばれるため不要）
            if ($arrayTableValues['_assigns'] != array()) {
                $arrayParams['presetValues'][$tableValuesName] = $arrayTableValues['_assigns'];
                $arrayParams['params']['arrayMethodParams']['valuekey'] = $tableValuesName;

                Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayParams, $before, $after);
            } else {
                $arrayParams['presetValues'][$tableValuesName] = array();
                $arrayParams['params']['arrayMethodParams']['valuekey'] = $tableValuesName;

                Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayParams, $before, $after);
            }

            // テーブル構造上必要なタグの有無を確認
            // col
            if (!isset( $arrayTableElements['cols'][0]['_xtable'])) {
                // col設定がない
                throw New Bshe_View_Resource_Html_Exception_NoTableElementInHtml($arrayParams['templateClass']->getTemplateFileName(),
                    $arrayParams['params']['originalId'], $arrayParams['seq'], $tableName, 'option');
            }
            // row
            if (!isset($arrayTableElements['row'])) {
                throw New Bshe_View_Resource_Html_Exception_NoTableElementInHtml($arrayParams['templateClass']->getTemplateFileName(),
                    $arrayParams['params']['originalId'], $arrayParams['seq'], $tableName, 'optgroup');
            }


            // rowElement
            $elementRow = $arrayTableElements['row'][0];
            $arrayRowParams = $arrayTablePasedIds['row'][0];

            // optgroup数分だけ行をコピー
            foreach ($arrayTableValues['_values'] as $key => $values) {
                // 行をコピー
                $arrayParams['templateClass']->cloneNodeBefore($elementRow, $elementRow);
            }
            // 最終行削除
            $arrayParams['templateClass']->removeNode($elementRow);

            // 再パース
            $arrayParams['templateClass']->clearTable($tableName, $arrayParams['params']['seq'], true);
            $arrayParams['templateClass']->parseHTML($elementTable, $arrayParams['params']['seq'], $tableName);

            // テーブル用Element配列再取得
            $arrayTableElements = $arrayParams['templateClass']->getArrayTableElements($tableName, $arrayParams['params']['seq']);
            $arrayTablePasedIds = $arrayParams['templateClass']->getArrayTablePasedIds($tableName, $arrayParams['params']['seq']);
            $arrayTableChildren = $arrayParams['templateClass']->getArrayTableChildren($tableName, $arrayParams['params']['seq']);

            // option数分だけ行をコピー
            foreach ($arrayTableElements['row'] as $key => $rowId) {
                $arrayTmp = array_keys($arrayTableElements['cols'][$key]['_xtable']);
                $elementCol = $arrayTableElements['cols'][$key]['_xtable'][$arrayTmp[0]];
                foreach ($arrayTableValues['_values'][$key]['_values'] as $values) {
                    // elementコピー
                    $arrayParams['templateClass']->cloneNodeBefore($elementCol, $elementCol);
                }
                // 最終element削除
                $arrayParams['templateClass']->removeNode($elementCol);

            }

            // 再パース
            $arrayParams['templateClass']->clearTable($tableName, $arrayParams['params']['seq'], true);
            $arrayParams['templateClass']->parseHTML($elementTable, $arrayParams['params']['seq'], $tableName);

            // テーブル用Element配列再取得
            $arrayTableElements = $arrayParams['templateClass']->getArrayTableElements($tableName, $arrayParams['params']['seq']);
            $arrayTablePasedIds = $arrayParams['templateClass']->getArrayTablePasedIds($tableName, $arrayParams['params']['seq']);
            $arrayTableChildren = $arrayParams['templateClass']->getArrayTableChildren($tableName, $arrayParams['params']['seq']);


            // optgroupにてループ

            foreach ($arrayTableElements['row'] as $rowSeq => $elementRow) {
                $arrayRowParams = $arrayTablePasedIds['row'][$rowSeq];

                // optgroupに対する処理
                // アサインがない場合は、Before/afterのみ実施
                if (!isset ($arrayTableValues['_values'][$rowSeq]['_assigns'])) {
                    $arrayTargetParams = $arrayRowParams;
                    $arrayTargetParams['presetValues']['_assigns'] = array();
                    $arrayTargetParams['params']['arrayMethodParams']['valuekey'] = '_assigns';
                    $arrayTargetParams['templateClass'] = $arrayParams['templateClass'];
                    $arrayTargetParams['element'] = $elementRow;
                    $arrayParams['templateClass'] =
                        Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTargetParams);
                } else {
                    // 行に対する処理
                    $arrayTargetParams = $arrayRowParams;
                    if ($arrayTableValues['_values'][$rowSeq]['_assigns'] != array()) {
                        $arrayTargetParams['presetValues'] = $arrayTableValues['_values'][$rowSeq];
                    } else {
                        $arrayTargetParams['presetValues']['_assigns'] = array();
                    }

                    $arrayTargetParams['params']['arrayMethodParams']['valuekey'] = '_assigns';
                    $arrayTargetParams['templateClass'] = $arrayParams['templateClass'];
                    $arrayTargetParams['element'] = $elementRow;
                    $arrayParams['templateClass'] =
                        Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTargetParams);
                }


                // optionに対する処理
                $i=0;
                foreach ($arrayTableElements['cols'][$rowSeq]['_xtable'] as $celKey => $targetCol) {
                    if ($arrayTableValues['_values'][$rowSeq]['_values'][$i] != array()) {
                        // アサインデータあり
                        // 該当パース文字列取得
                        $arrayTargetParams = $arrayTablePasedIds['cols'][$rowSeq]['_xtable'][$celKey];
                        // アサイン配列の有無
                        // 各種アサイン
                        $arrayTmpParams['presetValues'][$i] = $arrayTableValues['_values'][$rowSeq]['_values'][$i];
                        $arrayTmpParams['params'] = $arrayTargetParams;
                        $arrayTmpParams['params']['arrayMethodParams']['valuekey'] = $i;
                        $arrayTmpParams['element'] = $targetCol;
                        $arrayTmpParams['templateClass'] = $arrayParams['templateClass'];
                        $arrayParams['templateClass'] =
                            Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTmpParams);
                        $arrayTmpParams=array();
                    } else {
                        // アサインデータなし
                        // 該当パース文字列取得
                        $arrayTargetParams = $arrayTablePasedIds['cols'][$rowSeq]['_xtable'][$celKey];
                        // アサイン配列の有無
                        // 各種アサイン
                        $arrayTmpParams['presetValues'][$i] = array();
                        $arrayTmpParams['params'] = $arrayTargetParams;
                        $arrayTmpParams['params']['arrayMethodParams']['valuekey'] = $i;
                        $arrayTmpParams['element'] = $targetCol;
                        $arrayTmpParams['templateClass'] = $arrayParams['templateClass'];
                        $arrayParams['templateClass'] =
                            Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTmpParams);
                        $arrayTmpParams=array();
                    }
                    $i++;
                }
            }

            return $arrayParams['templateClass'];

        } catch (Exception $e) {
            throw $e;
        }
    }
}
