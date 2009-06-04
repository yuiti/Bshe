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
 * Bshe_View_Helper_Html_Selectpref
 *
 * SELECTボックスに都道府県をセットするヘルパー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
class Bshe_View_Helper_Html_Selectpref extends Bshe_View_Helper_Html_Abstract
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
            $configView = Bshe_Registry_Config::getConfig('Bshe_View');

            // 都道府県リスト取得
            $configPref = New Bshe_Config_Ini(Bshe_Controller_Init::getMainPath() . $configView->helper->html->selectpref->pref_list_file,
            	'preflist', array('allowModifications' => true));
            $arrayPref = $configPref->pref->toArray();

            // optionタグ生成
            foreach ($arrayPref as $key => $pref) {


                if ($values['helperParams'][0] == 'name') {
                    // selected判別
                    if ($values['value'] == $pref) {
                        $strTmp = '<option value="' .  $pref . '" selected="true">' . $pref ."</option>";
                    } else {
                        $strTmp = '<option value="' .  $pref . '">' . $pref ."</option>";
                    }
                } else {
                    if ($values['value'] == $key) {
                        $strTmp = '<option value="' .  $key . '" selected="true">' . $pref ."</option>";
                    } else {
                        $strTmp = '<option value="' .  $key . '">' . $pref ."</option>";
                    }
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