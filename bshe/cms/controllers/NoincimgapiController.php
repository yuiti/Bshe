<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Application
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Specializer_Controller_Action_Api */
require_once 'Bshe/Specializer/Controller/Action/Api.php';
/** Bshe_Specializer_Exception */
require_once 'Bshe/Specializer/Exception.php';

/**
 * NoincimgのAPI処理を実施するコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.01
 * @license LGPL
 */
class Bshecms_NoincimgapiController extends Bshe_Specializer_Controller_Action_Api
{

    /**
     * ログインしていなくても画像を出力
     *
     * @return unknown_type
     */
    public function noauthshowAction()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            $pathinfo = str_replace($config->indexphp_path . '/cms/image/images', '', $this->getRequest()->getPathInfo());
            if (strpos($pathinfo, '..') === false) {
                // ..は含まれていない
                if (!file_exists(Bshe_Controller_Init::getMainPath() . $config->cms_cache_text_path . $pathinfo)) {
                    // ファイルが存在しない。404エラー
                    header('HTTP/1.0 404 Not Found');
                    return;
                } else {
                    // ファイルの拡張子でヘッダーを変更


                    // ファイル出力
                    header('Content-Length: ' . filesize(Bshe_Controller_Init::getMainPath() . $config->cms_cache_text_path . $pathinfo));
                    $fp = fopen(Bshe_Controller_Init::getMainPath() . $config->cms_cache_text_path . $pathinfo, 'rb');
                    fpassthru($fp);
                    fclose($fp);
                }
            } else {
                // ..が含まれているため動作しない
                Bshe_Log::logWithFileAndParamsWrite('画像のパス指定に「..」が含まれています。', Zend_Log::WARN);
                throw New Bshe_Specializer_Exception('画像のパス指定に「..」が含まれています。');
            }

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * ログインしていない場合、例外
     *
     */
    public function authshowAction()
    {
        try {
            // ログインしていない場合例外出力
            if (!Bshe_Specializer_Auth_Cms::isLogined()) {
                Bshe_Log::logWithFileAndParamsWrite('ログインせずにアクセスされました。', Zend_Log::WARN);
                header('HTTP/1.0 404 Not Found');
                return;
            }
            $this->noauthshowAction();
        } catch (Exception $e) {
            throw $e;
        }

    }


    /**
     * 画像公開
     *
     */
    public function publishAction()
    {
        try {
            // ログインしていない場合例外出力
            if (!Bshe_Specializer_Auth_Cms::isLogined()) {
                Bshe_Log::logWithFileAndParamsWrite('ログインせずにアクセスされました。', Zend_Log::WARN);
                header('HTTP/1.0 404 Not Found');
            }

            Bshe_Log::logWithFileAndParamsWrite('Imgrenderrslセット開始', Zend_Log::DEBUG);
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            // キャッシュクラスインスタンス化
            $cache = New Bshe_Specializer_Cms_Cache_Image($_REQUEST['elementId'], null, null, null, urldecode($_REQUEST['pageId']));
            $cache->publishContents($_REQUEST['ymd']);

            //$cache->publishContents($ymd);

            echo true;
        } catch (Exception $e) {
            throw $e;
        }

    }
}