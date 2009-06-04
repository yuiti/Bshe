<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */
/** Bshe_Exception */
require_once "Bshe/Exception.php";

/**
 * Bshe_Specializer_Auth_Abstract
 *
 * Bshe_Specializerにて認証を担当するクラスの抽象クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
abstract class Bshe_Specializer_Auth_Abstract
{
    /**
     * ログイン処理を実施し、
     * 結果を返すメソッド
     *
     * @param string $userId ログインID
     * @param string $password パスワード
     */
    abstract static public function login($userId, $password);

    /**
     * ログアウト処理
     * @return void
     */
    abstract static public function logout();
}
