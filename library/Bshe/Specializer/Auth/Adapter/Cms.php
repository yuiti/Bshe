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
/** Bshe_Registry_Config */
require_once "Bshe/Registry/Config.php";
/** Zend_Auth_Result */
require_once "Zend/Auth/Result.php";

/**
 * Bshe_Specializer_Auth_Adapter_Cms
 *
 * CMSの認証に利用するAuthアダプタ
 *
 * @author Yuichiro Abe <bsh2.031.29
 * @license LGPL
 */
class Bshe_Specializer_Auth_Adapter_Cms implements Zend_Auth_Adapter_Interface
{

    /**
     * 認証用のUserID
     *
     * @var string
     */
    protected $_userId;

    /**
     * 認証用のパスワード
     *
     * @var string
     */
    protected $_password;

    /**
     * 認証用のユーザーとパスワードをセット
     *
     * @param string $userId ログインID
     * @param string $password パスワード
     */
    public function __construct($userId, $password)
    {
        $this->_userId = $userId;
        $this->_password = $password;
    }

    /**
     * 認証実行
     *
     * @throws Zend_Auth_Adapter_Exception
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            Bshe_Log::logWithFileAndParamsWrite('認証INIファイルパス', Zend_Log::DEBUG, array( Bshe_Controller_Init::getMainPath() . $config->cms_auth_ini));
            // コンフィグ読み込み
            $pwdConfig = New Bshe_Config_Ini(Bshe_Controller_Init::getMainPath() . $config->cms_auth_ini, 'Bshe_Specializer_Auth', array('allowModifications' => true));


            $arrayUsers = $pwdConfig->users->toArray();
            if (isset($arrayUsers[$this->_userId])) {
                // userIdあり
                if ($arrayUsers[$this->_userId] == $this->_password) {
                    // パスワードOK

                    // role取得
                    $arrayIdent = array(
                        'userid' => $this->_userId
                    );
                    return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $arrayIdent);
                }
            }
            // 認証失敗
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE, $this->_userId);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
