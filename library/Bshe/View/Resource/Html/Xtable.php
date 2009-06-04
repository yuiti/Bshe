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
/** Bshe_View_Resource_Html_Exception_NoTableElementInHtml */
require_once 'Bshe/View/Resource/Html/Exception/NoTableElementInHtml.php';

/**
 * Bshe_View_Resource_Html_XTable
 *
 * データを2方向に繰り返し表示するリソースクラス
 * <table>タグなどで利用
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.29
 * @license LGPL
 */
class Bshe_View_Resource_Html_XTable extends Bshe_View_Resource_Html_Abstract
{

    /**
     * テーブル領域自体に対する処理（対象のテーブル内のデータ生成もこのメソッドから実行する。
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
    static public function assignValuesTable($arrayParams, $before = false, $after = false)
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
            // row
            if (!isset( $arrayTableElements['row'][0])) {
                // row設定がない
                throw New Bshe_View_Resource_Html_Exception_NoTableElementInHtml($arrayParams['templateClass']->getTemplateFileName(),
                    $arrayParams['params']['originalId'], $arrayParams['seq'], $tableName, 'row');
            }
            // rowElement
            $elementRow = $arrayTableElements['row'][0];
            $arrayRowParams = $arrayTablePasedIds['row'][0];

            // 必要行数算出
            $colCountInRow = count($arrayTableElements['cols'][0]['_xtable']);
            $rowCount = ceil(floatval(count($arrayTableValues['_values']))/floatval($colCountInRow));
            for ($i=0; $i<$rowCount; $i++) {
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


            // 行・列にてループ
            $valCount = 0; // データ番号
            foreach ($arrayTableElements['row'] as $row => $elementRow) {
                $arrayRowParams = $arrayTablePasedIds['row'][$row];
                // 行へのアサインは起こりえないため、Before/afterのみ実施
                $arrayTargetParams = $arrayRowParams;
                $arrayTargetParams['presetValues']['_assigns'] = array();
                $arrayTargetParams['params']['arrayMethodParams']['valuekey'] = '_assigns';
                $arrayTargetParams['templateClass'] = $arrayParams['templateClass'];
                $arrayTargetParams['element'] = $elementRow;
                $arrayParams['templateClass'] =
                    Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTargetParams);

                $colCount = 0;
                foreach ($arrayTableElements['cols'][$row]['_xtable'] as $col => $elementCol) {
                    $arrayColParams = $arrayTablePasedIds['cols'][$row]['_xtable'][$col];

                    // セルへのアサインがない場合は、Before/afterのみ実施
                    if (!isset ($arrayTableValues['_values'][$valCount]['_assigns'])) {
                        $arrayTargetParams = $arrayRowParams;
                        $arrayTargetParams['presetValues']['_assigns'] = array();
                        $arrayTargetParams['params']['arrayMethodParams']['valuekey'] = '_assigns';
                        $arrayTargetParams['templateClass'] = $arrayParams['templateClass'];
                        $arrayTargetParams['element'] = $elementCol;
                        $arrayParams['templateClass'] =
                            Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTargetParams);
                    } else {
                        // 行に対する処理
                        $arrayTargetParams = $arrayRowParams;
                        if ($values['_assigns'] != array()) {
                            $arrayTargetParams['presetValues'] = $arrayTableValues['_values'][$valCount];
                        } else {
                            $arrayTargetParams['presetValues']['_assigns'] = array();
                        }

                        $arrayTargetParams['params']['arrayMethodParams']['valuekey'] = '_assigns';
                        $arrayTargetParams['templateClass'] = $arrayParams['templateClass'];
                        $arrayTargetParams['element'] = $elementCol;
                        $arrayParams['templateClass'] =
                            Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTargetParams);
                    }

                    // セル内の処理
                    foreach ($arrayTableElements['vals'][$row][$colCount] as $valKey => $arrayTargetVals) {  //ここが
                        if ($arrayTableValues['_values'][$valCount][$valKey] != array()) {
                            // アサインデータあり
                            // 該当element抽出
                            foreach ($arrayTableElements['vals'][$row][$colCount][$valKey] as $valSeq => $targetVal) {
                                // 該当パース文字列取得
                                $arrayTargetParams = $arrayTablePasedIds['vals'][$row][$colCount][$valKey][$valSeq];
                                // アサイン配列の有無
                                // 各種アサイン
                                $arrayTmpParams['presetValues'] = $arrayTableValues['_values'][$valCount];
                                $arrayTmpParams['params'] = $arrayTargetParams;
                                $arrayTmpParams['params']['arrayMethodParams']['valuekey'] = $valKey;
                                $arrayTmpParams['element'] = $targetVal;
                                $arrayTmpParams['templateClass'] = $arrayParams['templateClass'];
                                $arrayParams['templateClass'] =
                                    Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTmpParams);
                                $arrayTmpParams=array();
                            }
                        } else {
                            // アサインデータなし
                            foreach ($arrayTargetCols as $colSeq => $targetCol) {
                                // 該当パース文字列取得
                                $arrayTargetParams = $arrayTablePasedIds['vals'][$row][$colCount][$valKey][$valSeq];
                                // アサイン配列の有無
                                // 各種アサイン
                                $arrayTmpParams['presetValues'] = array();
                                $arrayTmpParams['params'] = $arrayTargetParams;
                                $arrayTmpParams['params']['arrayMethodParams']['valuekey'] = $valKey;
                                $arrayTmpParams['element'] = $targetVal;
                                $arrayTmpParams['templateClass'] = $arrayParams['templateClass'];
                                $arrayParams['templateClass'] =
                                    Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTmpParams);
                                $arrayTmpParams=array();
                            }
                        }
                    }

                    // サブテーブル処理
                    foreach ($arrayTableChildren[$valCount] as $subTableKey => $subTableElements) {
                        // サブテーブルあり
                        foreach ($subTableElements as $subTableSeq => $subTableElement) {
                            // サブテーブル処理
                            $arraySubTablePasedIds = $arrayParams['templateClass']->getArrayTablePasedIds($subTableKey, $subTableSeq);
                            $arraySubTablePasedIds['table']['seq'] = $subTableSeq;
                            $arrayTableValues[$subTableKey][] = $arrayTableValues['_values'][$valCount]['_values'][$subTableKey];
                            $arrayTargetParams =
                                array (
                                    'element' => $subTableElement,
                                    'params' => $arraySubTablePasedIds['table'],
                                    'presetValues' => $arrayTableValues,
                                    'templateClass' => $arrayParams['templateClass']
                                );
                            $arrayFuncParams['templateClass'] = call_user_func(array($arraySubTablePasedIds['table']['className'], 'assignValuesTable'),
                                $arrayTargetParams, true, true
                            );
                        }
                    }
                    $colCount++;
                    $valCount++;

                }
            }
            return $arrayParams['templateClass'];

        } catch (Exception $e) {
            throw $e;
        }
    }
}
