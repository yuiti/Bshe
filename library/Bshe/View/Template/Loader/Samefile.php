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

/** Bshe_View_Template_Loader_File */
require_once 'Bshe/View/Template/Loader/File.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Bshe_Viewのテンプレート読み込みクラス
 * テキストファイルから読み込みを実施する。
 * 常に同じテンプレートファイルを読み込むようになっている
 *
 *
 * @author Yuichiro Abe
 * @created 2009.08.18 <bshe@bshe.org>
 * @license LGPL
 * @todo アサインクラスが見つからない場合も処理を停止しないようにする
 */
class Bshe_View_Template_Loader_Samefile extends Bshe_View_Template_Loader_File
{
    /**
     * テンプレートファイル（パス含む）
     * 
     * @var unknown_type
     */
    protected $_fileName;
    
    /**
     * 常に同じテンプレートファイルの読み込み
     *
     * @param string $fileName テンプレートファイル（指定されない場合はパラメーター設定されている内容を利用）
     * @return string 読み込まれたテンプレート文字列
     */
    public function readTemplateFile($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み開始', Zend_Log::DEBUG, array('fileName' => $arrayParams['filename']));

            $file = $arrayParams['templatePath'] . '/' . $this->_fileName;

            // ファイルオープン
            $fp = fopen($file, 'r');
            $contents = fread($fp, filesize($file));
            fclose($fp);

            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み終了', Zend_Log::DEBUG, array('file' => $file));

            return $contents;
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * テンプレートファイル名の生成、検証
     * 
     * @param $fileName
     * @return unknown_type
     */
    public function getFileName($arrayParams)
    {
        try {
            // ファイルフルパス生成
            $file = $arrayParams['templatePath'] . '/' . $this->_fileName;
            if (!file_exists( $file)) {
                Bshe_Log::logWithFileAndParamsWrite('テンプレートファイルが見つかりません', Zend_Log::ERR, array('file' => $file));
                throw New Bshe_View_Template_Exception_NoTemplateFile($arrayParams['templateFile']);
            }

            return $arrayParams['templateFile'];
        } catch (Exception $e) {
            throw $e;
        }
    }
    
        /**
     * ローダーによるキャッシュ制御
     * 
     * @return unknown_type
     */
    public function checkCache($arrayParams = array())
    {
        return false;
    }
    
    /**
     * コンパイルキャッシュ機構
     *
     */
    public function saveCompileCache($arrayParams = array(), $template)
    {
        return true;
    }
}