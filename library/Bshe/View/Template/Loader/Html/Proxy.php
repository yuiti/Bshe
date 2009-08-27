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

/** Bshe_View_Template_Loader_Proxy */
require_once 'Bshe/View/Template/Loader/Proxy.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Bshe_Viewのテンプレート読み込みクラス
 * Proxy HTML型の別URLから読み込みを実施する。
 *
 *
 * @author Yuichiro Abe
 * @created 2009.08.18 <bshe@bshe.org>
 * @license LGPL
 * @todo アサインクラスが見つからない場合も処理を停止しないようにする
 */
class Bshe_View_Template_Loader_Html_Proxy extends Bshe_View_Template_Loader_Proxy
{
   
    /**
     * テンプレートURIの読み込み
     * baseurlは設定情報から読み替える
     * 
     *
     * @param string $fileName テンプレートファイル（指定されない場合はパラメーター設定されている内容を利用）
     * @return string 読み込まれたテンプレート文字列
     */
    public function readTemplateFile($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み開始', Zend_Log::DEBUG, array('target_request' => $arrayParams['target_request']));

            // リクエスト生成
            if (isset($arrayParams['target_request'])) {
                $this->_request = $arrayParams['target_request'];
            }
            $response = $this->_request->request();
            //Bshe_Log::logWithFileAndParamsWrite('読み込まれたリクエスト', Zend_Log::DEBUG, array('target_request' => $arrayParams['target_request']));
            
            // content-typeがtext/htmlでない場合は例外とする
            if (strpos($response->getHeader('Content-Type'), 'text/html') === false)
            {
                // HTMLではない
                throw new Bshe_View_Template_Loader_Html_Proxy_Exception_NoHtml('Content-Type: ' . $response->getHeader('Content-Type'), $response);
            }

            return $response->getBody();

        } catch (Exception $e) {
            throw $e;
        }
    }
}