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
 * Bshe_View_Resource_Html_Table
 *
 * データを1方向に繰り返し表示するリソースクラス
 * <table>タグなどで利用
 * 設定用の配列を生成する機能も有する
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.11.05
 * @license LGPL
 */
class Bshe_View_Resource_Html_Table extends Bshe_View_Resource_Html_Abstract
{
    /**
     * データ配列内部保持用配列
     *
     * @var array
     */
    protected $_arrayResource = array();

    /**
     * 配列を返す
     *
     * @return unknown
     */
    public function getDataArray()
    {
        return $this->_arrayResource;
    }

    /**
     * 1次元の配列をセットする
     * 多重構造の場合は、Bshe_View_Resource_Html_Tableを配列の引数にしておくことで多重処理される
     *
     * @param array $arrayData セットする配列
     */
    public function setDataArray($arrayData)
    {
        try {
            $this->_arrayResource = $this->_setDataArray($arrayData);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 属性をデータ配列へ付加
     *
     * @param array $arrayData
     */
    public function setAtribute($arrayData)
    {
        try {
            $this->_arrayResource = $this->_setAtribute($arrayData, $this->_arrayResource);
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 属性をデータ配列へ付加
     *
     * @param array $arrayData
     */
    public function _setAtribute($arrayData, $arrayResult)
    {
        try {
            foreach ($arrayData as $key => $values) {
                if ($key !== '_assigns') {
                    foreach ($values as $celKey => $value) {
                        if (!is_array($arrayResult['_values'][$key][$celKey]) and ($arrayResult['_values'][$key][$celKey] != null)) {
                            $valTmp = $arrayResult['_values'][$key][$celKey];
                            $arrayResult['_values'][$key][$celKey] = array();
                            $arrayResult['_values'][$key][$celKey][] = array( 'a' => array('innerHTML' ,$valTmp));
                        }
                        if (is_a($value, __CLASS__)) {
                            // サブテーブル
                            $arrayResult['_values'][$key]['_values'][$celKey] = $value->getDataArray();
                        } else {
                            // 通常のアサイン
                            $arrayResult['_values'][$key][$celKey][] = $value;
                        }
                    }
                } else {
                    // 親自体にアサイン
                    $arrayResult['_assigns'][] = $values;
                }
            }
            return $arrayResult;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 再帰呼び出し用配列セット関数
     *
     * @param array $arrayData 設定するデータ配列
     */
    protected function _setDataArray($arrayData)
    {
        try {
            $arrayResult = array();
            foreach ($arrayData as $key => $values) {
                foreach ($values as $celKey => $value) {
                    if (is_a($value, __CLASS__)) {
                        // サブテーブル
                        $arrayResult['_values'][$key]['_values'][$celKey] = $value->getDataArray();
                    } else {
                        // 通常のアサイン
                        $arrayResult['_values'][$key][$celKey][] = $value;
                    }
                }
            }
            return $arrayResult;
        } catch (Exception $e) {
            throw $e;
        }
    }



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
            foreach ($arrayTableValues['_values'] as $key => $values) {
                $elementRow = $arrayTableElements['row'][$i];
                $arrayRowParams = $arrayTablePasedIds['row'][$i];

                // 行へのアサインがない場合は、Before/afterのみ実施
                if (!isset ($values['_assigns'])) {
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
                    if ($values['_assigns'] != array()) {
                        $arrayTargetParams['presetValues'] = $values;
                    } else {
                        $arrayTargetParams['presetValues']['_assigns'] = array();
                    }

                    $arrayTargetParams['params']['arrayMethodParams']['valuekey'] = '_assigns';
                    $arrayTargetParams['templateClass'] = $arrayParams['templateClass'];
                    $arrayTargetParams['element'] = $elementRow;
                    $arrayParams['templateClass'] =
                        Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTargetParams);
                }

                // 行内の値に対するループ
                foreach ($arrayTableElements['cols'][$i] as $celKey => $arrayTargetCols) {
                    if ($values[$celKey] != array() and isset($values[$celKey])) {
                        // アサインデータあり
                        // 該当element抽出
                        foreach ($arrayTargetCols as $colSeq => $targetCol) {
                            // 該当パース文字列取得
                            $arrayTargetParams = $arrayTablePasedIds['cols'][$i][$celKey][$colSeq];
                            // アサイン配列の有無
                            // 各種アサイン
                            $arrayTmpParams['presetValues'][$celKey] = $values[$celKey];
                            $arrayTmpParams['params'] = $arrayTargetParams;
                            $arrayTmpParams['params']['arrayMethodParams']['valuekey'] = $celKey;
                            $arrayTmpParams['element'] = $targetCol;
                            $arrayTmpParams['templateClass'] = $arrayParams['templateClass'];
                            $arrayParams['templateClass'] =
                                Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTmpParams);
                            $arrayTmpParams=array();
                        }
                    } else {
                        // アサインデータなし
                        foreach ($arrayTargetCols as $colSeq => $targetCol) {
                            // 該当パース文字列取得
                            $arrayTargetParams = $arrayTablePasedIds['cols'][$i][$celKey][$colSeq];
                            // アサイン配列の有無
                            // 各種アサイン
                            $arrayTmpParams['presetValues'][$celKey] = array();
                            $arrayTmpParams['params'] = $arrayTargetParams;
                            $arrayTmpParams['params']['arrayMethodParams']['valuekey'] = $celKey;
                            $arrayTmpParams['element'] = $targetCol;
                            $arrayTmpParams['templateClass'] = $arrayParams['templateClass'];
                            $arrayParams['templateClass'] =
                                Bshe_View_Engine::callAssingFunction('Bshe_View_Resource_Html_Show', 'assignValuesPreassignvalue', $arrayTmpParams);
                            $arrayTmpParams=array();
                        }
                    }
                }

                // サブテーブル処理
                foreach ($arrayTableChildren[$key] as $subTableKey => $subTableElements) {
                    // サブテーブルあり
                    foreach ($subTableElements as $subTableSeq => $subTableElement) {
                        // サブテーブル処理
                        $arraySubTablePasedIds = $arrayParams['templateClass']->getArrayTablePasedIds($subTableKey, $subTableSeq);
                        $arraySubTablePasedIds['table']['seq'] = $subTableSeq;
                        $arrayTableValues[$subTableKey][] = $values['_values'][$subTableKey];
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
                $i++;
            }

            return $arrayParams['templateClass'];

        } catch (Exception $e) {
            throw $e;
        }
    }
}
