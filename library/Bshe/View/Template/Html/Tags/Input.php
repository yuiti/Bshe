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
 * Bshe_View_Template_Html_Tags_Input
 *
 * Inputタグ用に用意されたデフォルトのHTMLTAGクラス
 * 通常の値セットがvalueになるなどの特殊な処置を行っている
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
class Bshe_View_Template_Html_Tags_Input extends Bshe_View_Template_Html_Tags_Abstract
{

    /**
     * 値、属性単純セット
     * 通常のブロック要素として値をセット
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
                if ($params['params'][0] == 'innerHTML' or $params['params'][0] == 'bshe_innerHTML') {
                    $strType = $templateClass->getAttribute($element, 'type');
                    // typeにより処理を分岐する
                    switch ($strType) {
                        case 'checkbox':
                        case 'radio':
                            $strValue = $templateClass->getAttribute($element, 'value');
                            if ($strValue == $strTmp) {
                                $templateClass->setAttribute($element, 'checked', null);
                            } else {
                                $templateClass->removeAttribute($element, 'checked');
                            }
                            break;
                        case 'image':
                            $templateClass->setAttribute($element, 'src', $strTmp);
                            break;
                        case 'file':
                            // 何もしない
                            break;
                        case 'text':
                        case 'password':
                        case 'hidden':
                        case 'submit':
                        case 'reset':
                        case 'button':
                        default:
                            // value属性にセット
                            $templateClass->setAttribute($element, 'value', $strTmp);
                            break;
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


