<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/**
 * Bshe_Specializer_Xajax_Auth_Cms
 *
 * Bshe_SpecializerのCMSで認証を処理するXajax
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.02.03
 * @license LGPL
 */
class Bshe_Specializer_Xajax_Auth_Cms extends Bshe_Specializer_Xajax_Abstract
{
    /**
     * ログイン処理
     *
     * @param $formValues
     * @return unknown_type
     */
    static public function authCmsLogin($formValues)
    {
        // xajax読込
        $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
        require_once Bshe_Controller_Init::getMainPath() . $config->alias_path . $config->indexphp_path . '/xajax/xajax_core/xajax.inc.php';

        // 言語ファイル読込
        $configLanguage = Bshe_Registry_Config::getConfig('Bshe_Language');

        // レスポンスクラス生成
        $objResponse = new xajaxResponse();

        // 認証
        $authResult = Bshe_Specializer_Auth_Cms::login($formValues['userid'], $formValues['password']);
        switch ($authResult->getCode()) {
            case Zend_Auth_Result::FAILURE :
                $objResponse->assign('bsheloginformresult', 'innerHTML', $configLanguage->Bshe_Specializer_Xajax_Auth_Cms->login_failure);
                break;
            case Zend_Auth_Result::SUCCESS :
                // ページをリロード
                $url = str_replace('bshe_specializer_auth=login&', '', $_SERVER['REQUEST_URI']);
                $url = str_replace('bshe_specializer_auth=login', '', $url);
                if (substr($url, -1, 1) == '?') {
                    $url = substr($url, 0, -1) ;
                }
                $objResponse->redirect($url);

                break;
            default:
                $objResponse->assign('bsheloginformresult', 'innerHTML', $configLanguage->Bshe_Specializer_Xajax_Auth_Cms->login_unknown);
                break;
        }

        return $objResponse;

    }

}