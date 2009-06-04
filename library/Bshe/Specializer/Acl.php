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
 * Bshe_Specializer_Acl
 *
 * Bshe_Specializer用のAclのスタティックメソッド集
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.19
 * @license LGPL
 */
class Bshe_Specializer_Acl
{
    static protected $_acl;

    /**
     * Aclクラスを返す
     *
     * @return unknown_type
     */
    static public function getAcl()
    {
        if (self::$_acl == null) {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $cmsMode = $config->cms_mode;
            $className = 'Bshe_Specializer_Acl_' . ucfirst($cmsMode);
            self::$_acl = New $className();
        }
        return self::$_acl;
    }


    /**
     * Bshe_Specializer_Authの認証情報から
     * 対象のアクセス権を確認する。
     *
     * @param $resource
     * @param $pri
     * @return unknown_type
     */
    static public function isAllowedByUserid($resource = null, $priv = null)
    {
        try {
            $auth = Bshe_Specializer_Auth_Cms::getInstance();
            if ($auth->hasIdentity()) {
                $acl = self::getAcl();
                // ログイン中
                $arrayIdentitys = $auth->getIdentity();
                $role = $arrayIdentitys['userid'];
                if ($acl->has($resource)) {
                    return $acl->isAllowed($role, $resource, $priv);
                } else {
                    return $acl->isAllowed($role, null, null);
                }
            } else {
                // ログインしていない
                $acl = self::getAcl();
                if ($acl->has($resource)) {
                    return $acl->isAllowed('guest', $resource, $priv);
                } else {
                    return $acl->isAllowed('guest', null, null);
                }
                return false;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
