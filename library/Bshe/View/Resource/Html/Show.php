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
/** Bshe_View_Resource_Exception_Novalue */
require_once 'Bshe/View/Resource/Exception/Novalue.php';

/**
 * Bshe_View_Resource_Html_Show
 *
 * データを単純に表示する機能を提供するresourceクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
class Bshe_View_Resource_Html_Show extends Bshe_View_Resource_Html_Abstract
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
    static public function assignValuesPreassignvalue($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('PreassignValueセット開始', Zend_Log::DEBUG, array('key' => $arrayParams['params']['arrayMethodParams']['valuekey']));

            // 値取得
            if (!isset( $arrayParams['presetValues'][$arrayParams['params']['arrayMethodParams']['valuekey']])) {
                // 値がない

                // 値がなく、HELPERが初期値メソッドを持っている場合
                if (method_exists($arrayParams['params']['helperName'], 'getDefaultValue')) {
                    $arrayParams['presetValues'][$arrayParams['params']['arrayMethodParams']['valuekey']] =
                        call_user_func(array($arrayParams['params']['helperName'], 'getDefaultValue'));
                } else {
                    return $arrayParams['templateClass'];
                }
            }

            $targetValue = $arrayParams['presetValues'][$arrayParams['params']['arrayMethodParams']['valuekey']];

            $arrayAssign = array();
            if (is_array( $targetValue)) {
                foreach ($targetValue as $key => $assigns) {
                    if (is_array( $assigns)) {
                        foreach ($assigns as $method => $assign) {

                            $arrayAssign[] =
                                array(
                                    'method' => $method,
                                    'element' => $arrayParams['element'],
                                    'params' => $assign
                                );
                        }
                    } else {
                        // 値の場合は直接セット
                        $arrayAssign[] =
                            array(
                                'method' => 'a',
                                'element' => $arrayParams['element'],
                                'params' =>
                                    array(
                                        0 => 'bshe_innerHTML',
                                        1 => $assigns,
                                        2 => $arrayParams['params']['helperName'],
                                        3 => $arrayParams['params']['helperParams']
                                    )
                            );
                    }
                }
            } else {
                // 値の場合は直接セット
                $arrayAssign[] =
                    array(
                        'method' => 'a',
                        'element' => $arrayParams['element'],
                        'params' =>
                            array(
                                0 => 'bshe_innerHTML',
                                1 => $targetValue,
                                2 => $arrayParams['params']['helperName'],
                                3 => $arrayParams['params']['helperParams']
                            )
                    );
            }

            foreach ($arrayAssign as $key => $assign) {
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);
            }

            Bshe_Log::logWithFileAndParamsWrite('PreassignValueセット終了', Zend_Log::DEBUG, array('key' => $arrayParams['params']['value'], 'value' => $targetValue));

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
