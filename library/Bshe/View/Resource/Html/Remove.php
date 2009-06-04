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
/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Bshe_View_Resource_Html_Remove
 *
 * HTML生成時に該当タグを削除する
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.30
 * @license LGPL
 */
class Bshe_View_Resource_Html_Remove extends Bshe_View_Resource_Html_Abstract
{

    /**
     * テンプレートへpresetValuesの指定された値をセットする
     * presetValuesの中の対象データを見つけるためのキーは$paramsのキーがvalueの値にて指定される
     * 引数配列は以下の形式
     *  array(
     *       'templateClass' => テンプレートクラスのインスタンス,
     *       'element' => 処理対象のelement,
     *       'params' => 識別文字列をパースした配列,
     *       'presetValues' => preset引数（配列の場合は、指定された属性に対してあサインセットする
     *       )
     *
     * @param array $arrayParams Resourceクラス向けパラメーター
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValuesRemove($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Remove開始', Zend_Log::DEBUG, array('key' => $arrayParams['params']['arrayMethodParams']['valuekey']));

            $assign =
                array(
                    'method' => 'r',
                    'element' => $arrayParams['element'],
                    'params' => null
                );
            $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * メソッド未指定の場合
     *
     * @param array $arrayParams
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValues($arrayParams)
    {
        try {
            return Bshe_View_Resource_Html_Remove::assignValuesRemove($arrayParams);
        } catch (Exception $e) {
            throw $e;
        }
    }

}