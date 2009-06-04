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
 * Bshe_View_Helper_Html_Numberformat
 *
 * 数値をフォーマットするヘルパー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
class Bshe_View_Helper_Html_Numberformat extends Bshe_View_Helper_Html_Abstract
{

    /**
     * 数値のフォーマットを行う。
     *
     * 数字ではない場合は何もしない
     * 引数は
     * 第一：この桁まで小数を表示する
     * 　指定しあい場合は少数以下を表示しない
     *
     * @param array $values
     *
     * @return string
     */
    static public function setView($values)
    {
        try {
            $result = $values['value'];
            if (is_numeric($values['value']))
            {
                // 少数処理
                if (isset ($values['helperParams'][0])) {
                    if (is_numeric($values['helperParams'][0])) {
                        return number_format($result, $values['helperParams'][0]);
                    } else {
                        throw New Bshe_View_Helper_Html_Exception_InvalidParam
                            ($values['templateClass']->getTemplateFileName(), get_class($this), 2, $values['helperParams'][1]);
                    }
                } else {
                    return number_format($result);
                }

            } else {
                return $result;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
