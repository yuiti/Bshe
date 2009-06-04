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

/** Bshe_View_Template_Html_Tags_Abstract */
require_once 'Bshe/View/Template/Html/Tags/Abstract.php';
/** Bshe_View_Template_Html_Exception */
require_once 'Bshe/View/Template/Html/Exception.php';
/** Bshe_View_Template_Html_Exception */
require_once 'Bshe/View/Template/Html/Exception.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Bshe_View_Template_Html_Tags_Select
 *
 * Selectタグ用に用意されたデフォルトのHTMLTAGクラス
 * 通常の値セットがvalueになるなどの特殊な処置を行っている
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
class Bshe_View_Template_Html_Tags_Select extends Bshe_View_Template_Html_Tags_Abstract
{

    /**
     * 値、属性単純セット
     * SELECT内のoptionを検索し、対象がある場合はselectedをセットする。
     *
     * 引数配列の形式
     * array(
     *     [0] => 'a',
     *     [1] => 対象ID
     *     [2] => シーケンス番号
     *     [3] => array(
     *         [0] => 対象の属性（innerHTMLの場合、typeにより処理を分岐する）
     *         [1] => 投入する値
     *         [2] => Helperクラス名（省略可能：省略時はhtmlspecialcharsだけ実施してそのまま出力）
     *         [3] => Helper引数配列（省略可能）
     * )
     *
     * @param Ashe_View_Template_Html $templateClass
     * @param array $params 引数配列
     * @return Ashe_View_Template_Html
     */
    static public function assignA($templateClass, $params)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('assignA開始', Zend_Log::DEBUG, $params);

            // セットするelementクラスを取得
            $element = $params['element'];
            // Helperチェック
            $strTmp = '';
            $isAssign = true;
            if (isset( $params['params'][2])  and $params['params'][2] !== null) {
                // HELPER
                $strTmp =
                    call_user_func(array($params['params'][2], 'setView'),
                        array(
                            'value' => $params['params'][1],
                            'helperParams' => $params['params'][3],
                            'templateClass' => $templateClass,
                            'params' => $params
                        )
                    );
                // 戻りがテンプレートクラスの場合、テンプレートを保存して何もしない
                if (is_subclass_of($strTmp, 'Bshe_View_Template_Abstract')) {
                    $templateClass = $strTmp;
                    $isAssign = false;
                }
            } else {
                $strTmp = $params['params'][1];
            }

            // 置換処理
            if ($isAssign) {
                if ($params['params'][0] == 'bshe_innerHTML') {
                    // optionタグを抽出して、ループ
                    $selectNode = $templateClass->getElementById($params[1], $params[2]);
                    if ($selectNode->hasChildNodes()) {
                        $childrenNumbers = $selectNode->getChildNumbers();
                        foreach ($childrenNumbers as $key => $childNumber) {
                            $childNode = $templateClass->getElementNumber($childNumber);
                            if ($childNode->nodeName == 'optgroup' and $childNode->hasChildNodes()) {
                                // optgroupタグの場合はその下も検索する
                                $optGroupChildrenNumbers = $childNode->getChildNumbers();
                                foreach ($optGroupChildrenNumbers as $key => $optGroupChildNumber) {
                                    $targetNode = $templateClass->getElementNumber($optGroupChildNumber);
                                    if ($targetNode->hasAttribute('value')) {
                                        if ($targetNode->getAttribute('value') == $params[3][1]) {
                                            // 一致
                                            $templateClass->setAttribute($optGroupChildNumber, 'selected', 'true');
                                        } else {
                                            $templateClass->removeAttribute($optGroupChildNumber, 'selected');
                                        }
                                    }
                                }
                            }
                            $targetNode = $templateClass->getElementNumber($childNumber);
                            if ($targetNode->hasAttribute('value')) {
                                if ($targetNode->getAttribute('value') == $params[3][1]) {
                                    // 一致
                                    $templateClass->setAttribute($childNumber, 'selected', 'true');
                                } else {
                                    $templateClass->removeAttribute($childNumber, 'selected');
                                }
                            }
                        }
                    }
                } else {
                    // 対象属性指定あり
                    $templateClass->setAttribute($element, $params['params'][0], $strTmp);
                }
            }

            Bshe_Log::logWithFileAndParamsWrite('assignA終了', Zend_Log::DEBUG, $params);
            return $templateClass;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

