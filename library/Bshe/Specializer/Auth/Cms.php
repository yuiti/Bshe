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
/** Bshe_Specializer_Auth_Abstract */
require_once "Bshe/Specializer/Auth/Abstract.php";
/** Bshe_Specializer_Auth_Cms */
require_once "Bshe/Specializer/Auth/Cms.php";
/** Bshe_Auth */
require_once "Bshe/Auth.php";

/**
 * Bshe_Specializer_Auth_Cms
 *
 * Bshe_SpecializerのCMS機能にて認証を担当するクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshe_Specializer_Auth_Cms extends Bshe_Specializer_Auth_Abstract
{

    static public function getInstance()
    {
        return Bshe_Auth::getInstance('Bshe_Specializer_Auth_Cms');
    }

    /**
     * ログイン実行
     *
     * @param $userId
     * @param $password
     * @return Zend_Auth_Result
     */
    static public function login($userId, $password)
    {
        try {
            // 認証
            $auth = self::getInstance();
            $adapter = new Bshe_Specializer_Auth_Adapter_Cms($userId, $password);
            return $auth->authenticate($adapter);
        } catch (Exception $e) {
            throw $e;
        }

    }


    /**
     * ログインアウト処理
     *
     * @return void
     */
    static public function logout()
    {
        try {
            // 認証
            $auth = self::getInstance();
            $auth->clearIdentity();
        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * ログイン中かどうかをチェックする。
     *
     * @return boolean
     */
    static public function isLogined()
    {
        try {
            $auth = self::getInstance();
            if ($auth->hasIdentity()) {
                // ログイン中
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }




}
