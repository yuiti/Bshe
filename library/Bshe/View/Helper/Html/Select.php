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

/** Bshe_View_Helper_Html_Abstract */
require_once 'Bshe/View/Helper/Html/Abstract.php';
/** Bshe_View_Helper_Html_Exception_InvalidParam */
require_once 'Bshe/View/Helper/Html/Exception/InvalidParam.php';

/**
 * Bshe_View_Helper_Html_Select
 *
 * SELECTボックスのリストに変数として与えられている値を展開表示する
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
class Bshe_View_Helper_Html_Select extends Bshe_View_Helper_Html_Abstract
{



    /**
     * SELECTボックスに都道府県をセットする
     *
     *
     * @param array $values
     *
     * @return Bshe_View_Template_Abstract
     */
    static public function setView($values)
    {
        try {
            // assign用配列取得
            $arrayValues = $values['templateClass']->getPresetValues();

            // 変数名チェック
            if ($values['helperParams'][0] != '') {
                // リスト配列名セットあり
                $arrayOptions = $arrayValues[$values['helperParams'][0]][count($arrayValues[$values['helperParams'][0]])-1];
            } else {
                // リスト配列名セットなし
                return $values['value'];
            }

            // optionタグ生成
            foreach ($arrayOptions as $key => $value) {


                // selected判別
                if ($values['value'] == $key) {
                    $strTmp = '<option value="' .  $key . '" selected="true">' . $value ."</option>";
                } else {
                    $strTmp = '<option value="' .  $key . '">' . $value ."</option>";
                }
                $node = New Bshe_Dom_Node_Element($strTmp, 'option');
                $values['templateClass']->addChild($node, $values['params']['element']);
            }

            return $values['templateClass'];

        } catch (Exception $e) {
            throw $e;
        }
    }

    static public function getDefaultValue()
    {
        return '';
    }
}