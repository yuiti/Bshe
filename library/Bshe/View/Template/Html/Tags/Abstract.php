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

/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Ashe_View_Template_Html_Tags_Abstract
 *
 * HTMLの各タグ別の登録処理を実装する抽象クラス
 * 通常のブロック要素に対する処置は汎用性があるため、ここに記載
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
abstract class Bshe_View_Template_Html_Tags_Abstract
{

    /**
     * 値、属性単純セット
     * 通常のブロック要素として値をセット
     *
     * 引数配列の形式
     * array(
     *     method => 'a',
     *     element => 対象ID
     *     params => array(
     *         [0] => 対象の属性（innerHTMLの場合は'innerHTML'）
     *         [1] => 投入する値
     *         [2] => Helperクラス名（省略可能：省略時はなにもせずそのまま出力）
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
            if (isset( $params['params'][2]) and $params['params'][2] !== null) {
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
                if ($params['params'][0] == 'innerHTML' or $params['params'][0] == 'bshe_innerHTML') {
                    $templateClass->setNodeValue($element, $strTmp);
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


    /**
     * 値、属性の削除
     *
     * 引数配列の形式
     * array(
     *     method => 'd',
     *     element => 対象ID
     *     params => 属性名
     * )
     *
     * @param Ashe_View_Template_Html $templateClass
     * @param array $params 引数配列
     * @return Ashe_View_Template_Html
     */
    static public function assignD($templateClass, $params)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('assignD開始', Zend_Log::DEBUG, $params);

            // セットするelementクラスを取得
            $element = $params['element'];
            if ($params['params'] == 'innerHTML') {
                // innerHTML削除（正確には半角スペースをセット）
                $templateClass->setNodeValue($element, '');
            } else {
                $templateClass->removeAttribute($element, $params['params']);
            }

            Bshe_Log::logWithFileAndParamsWrite('assignD終了', Zend_Log::DEBUG, $params);

            return $templateClass;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 値、属性の削除
     *
     * 引数配列の形式
     * array(
     *     method => 'r',
     *     element => 対象ID
     *     params => 未使用
     * )
     *
     * @param Ashe_View_Template_Html $templateClass
     * @param array $params 引数配列
     * @return Ashe_View_Template_Html
     */
    static public function assignR($templateClass, $params = array())
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('assignD開始', Zend_Log::DEBUG, $params);

            // セットするelementクラスを取得
            $element = $params['element'];
            $templateClass->removeNode($element);

            Bshe_Log::logWithFileAndParamsWrite('assignR終了', Zend_Log::DEBUG, $params);

            return $templateClass;
        } catch (Exception $e) {
            throw $e;
        }
    }
}



