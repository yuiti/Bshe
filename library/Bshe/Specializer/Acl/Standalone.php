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
/** Bshe_Specializer_Acl_Abstract */
require_once "Bshe/Specializer/Acl/Abstract.php";

/**
 * 単独動作用のACLクラス
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.19
 * @license LGPL
 */
class Bshe_Specializer_Acl_Standalone extends Bshe_Specializer_Acl_Abstract
{
    /**
     * アクセス管理role情報をINIファイルから取得
     *
     * @return void
     */
    protected function _registRole()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $pwdConfig = New Bshe_Config_Ini(Bshe_Controller_Init::getMainPath() . $config->cms_auth_ini, 'Bshe_Specializer_Auth', array('allowModifications' => true));

            $arrayRoles = $pwdConfig->users->toArray();

            $this->addRole(new Zend_Acl_Role('admins'));
            $this->addRole(new Zend_Acl_Role('guest'));
            foreach ($arrayRoles as $key => $role) {
                $this->addRole(new Zend_Acl_Role($key), 'admins');
            }

            return;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * リソースセット（nullのみ）
     *
     * @return unknown_type
     */
    protected function _registResource()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $this->add(new Zend_Acl_Resource($config->indexphp_path . '/cms/login.html'));
            $this->allow('guest', $config->indexphp_path . '/cms/login.html', null);
            $this->allow('admins', null, null);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
