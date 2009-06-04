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
 * Bshe_View_Resource_Html_User
 *
 * ログインログアウト等をはじめとする、ユーザー関連の機能を実現するリソースクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.28
 * @license LGPL
 */
class Bshe_View_Resource_Html_User extends Bshe_View_Resource_Html_Abstract
{

    /**
     * ユーザーのログインの有無を判別し、ログインエリアの表示非表示を切り替える
     *
     * @param array $arrayParams Resourceクラス向けパラメーター
     * @return Bshe_View_Template_Abstract
     */
    static public function assignValuesLoginarea($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('Loginareaセット開始', Zend_Log::DEBUG, array());

            // ログインチェック


            // ログイン中の場合、ログインエリアを表示



            // ログインしていない場合ログインエリアを削除


            Bshe_Log::logWithFileAndParamsWrite('Loginareaセット終了', Zend_Log::DEBUG, array('status' => ''));

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
