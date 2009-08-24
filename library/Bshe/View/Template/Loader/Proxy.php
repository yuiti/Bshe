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

/** Bshe_View_Template_Loader_Abstract */
require_once 'Bshe/View/Template/Loader/Abstract.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Bshe_Viewのテンプレート読み込みクラス
 * Proxy型の別URLから読み込みを実施する。
 *
 *
 * @author Yuichiro Abe
 * @created 2009.08.18 <bshe@bshe.org>
 * @license LGPL
 * @todo アサインクラスが見つからない場合も処理を停止しないようにする
 */
class Bshe_View_Template_Loader_Proxy extends Bshe_View_Template_Loader_Abstract
{
    /**
     * http client
     * 
     * @var unknown_type
     */
    protected $_request = null;
    
    /**
     * リクエスト用クラスセット
     * 
     * @param $request
     * @return unknown_type
     */
    public function __construct($request = null)
    {
        try {
            if ($request != null) {
                $this->_request = $request;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    
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

            return $response->getBody();

        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * テンプレートファイル名の生成、検証
     * Proxyの場合、実ファイルはないため検証はしない、
     * target_uriがなければエラー
     * 
     * @param $fileName
     * @return unknown_type
     */
    public function getFileName($arrayParams)
    {
        try {
            if (!isset($arrayParams['target_request']) and ($this->_request == null)) {
                Bshe_Log::logWithFileAndParamsWrite('テンプレートURI設定されていません', Zend_Log::ERR);
                throw New Bshe_View_Template_Exception_NoTemplateFile($arrayParams);
            }
            return $arrayParams['templateFile'];
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * キャッシュなし
     * 
     * @see library/Bshe/View/Template/Loader/Bshe_View_Template_Loader_Abstract#checkCache($arrayParams)
     */
    public function checkCache($arrayParams = array())
    {
        return false;
    }
    
    /**
     * コンパイルキャッシュ機構
     * なし
     *
     */
    public function saveCompileCache($arrayParams = array(), $template)
    {
        return ;
    }
}
