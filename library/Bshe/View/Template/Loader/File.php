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
 * テキストファイルから読み込みを実施する。
 *
 *
 * @author Yuichiro Abe
 * @created 2009.08.18 <bshe@bshe.org>
 * @license LGPL
 * @todo アサインクラスが見つからない場合も処理を停止しないようにする
 */
class Bshe_View_Template_Loader_File extends Bshe_View_Template_Loader_Abstract
{
   
    /**
     * テンプレートファイルの読み込み
     *
     * @param string $fileName テンプレートファイル（指定されない場合はパラメーター設定されている内容を利用）
     * @return string 読み込まれたテンプレート文字列
     */
    public function readTemplateFile($arrayParams)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み開始', Zend_Log::DEBUG, array('fileName' => $arrayParams['filename']));

            $file = $arrayParams['templatePath'] . '/' . $arrayParams['filename'];

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
            $file = $arrayParams['templatePath'] . '/' . $arrayParams['templateFile'];
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
        try {
            if ($arrayParams['templateCompilePath'] != null) {
                $fileName = $this->getFileName($arrayParams);
                $fileDir = dirname($fileName);
                if (!file_exists($arrayParams['templateCompilePath'] . '/' . $fileDir)) {
                    Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュフォルダ作成', Zend_Log::DEBUG, $arrayParams['templateCompilePath'] . '/' . $fileDir);
                    if (!mkdir($arrayParams['templateCompilePath'] . '/' . $fileDir, 0777, true)) {
                        // フォルダ作成失敗
                        Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュフォルダ作成失敗', Zend_Log::ERR, $arrayParams['templateCompilePath'] . '/' . $fileDir);
                        throw New Bshe_View_Exception('コンパイルキャッシュフォルダ作成失敗', Zend_Log::ERR);
                    }
                }
                $frontendOptions = array(
                    'master_file' => $arrayParams['templatePath'] . '/' . $fileName,
//                    'lifetime' => $this->getParam( 'templateCacheLifeTime'), // キャッシュの有効期限をなしにする
                    'automatic_serialization' => true,
                    'ignore_user_abort' => true
                    );
                $backendOptions = array(
                    'cache_dir' => $arrayParams['templateCompilePath'] . '/' . $fileDir // キャッシュファイルを書き込むディレクトリ
                    );
                $cache = Zend_Cache::factory('File', 'File', $frontendOptions, $backendOptions);

                if ($template = $cache->load(self::getCacheKeyFromFile(basename($fileName)))) {
                    // キャッシュ読み込み
                    Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュファイル読込み', Zend_Log::DEBUG,
                        array('fileName' => $fileName));
//                    $template->_isCached = true;
                    $template->setParams($arrayParams);
                    return $template;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    
    /**
     * コンパイルキャッシュ機構
     *
     */
    public function saveCompileCache($arrayParams = array(), $template)
    {
        try {
            if ($arrayParams['templateCompilePath'] != null) {
                $fileName = $this->getFileName($arrayParams);
                $fileDir = dirname($fileName);

                // フォルダ生成
                if (!file_exists($this->_params['templateCompilePath'] . '/' . $fileDir)) {
                    if (!mkdir( $this->_params['templateCompilePath'] . '/' . $fileDir, 0777, true)) {
                        // フォルダの作成失敗
                        Bshe_Log::logWithFileAndParamsWrite('フォルダの作成に失敗しました', Zend_Log::ERR,
                            array('dir' => $this->_params['templateCompilePath'] . '/' . $fileDir));
                        throw New Bshe_View_Exception('フォルダの作成に失敗しました。:: ' . $arrayParams['templateCompilePath'] . '/' . $fileDir);
                    }
                }

                $frontendOptions = array(
                    'master_file' => $arrayParams['templatePath'] . '/' . $fileName,
                    'automatic_serialization' => true,
                    'ignore_user_abort' => true
                    );
                $backendOptions = array(
                    'cache_dir' => $arrayParams['templateCompilePath'] . '/' . $fileDir // キャッシュファイルを書き込むディレクトリ
                    );
                $cache = Zend_Cache::factory('File', 'File', $frontendOptions, $backendOptions);

                $cache->save($template, self::getCacheKeyFromFile(basename($fileName)));
                Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュファイル保存', Zend_Log::DEBUG, array('fileName' => $fileName));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    
    /**
     * ファイル名から、キャッシュ用の文字列を生成する
     * aaaa/BbbBb.htmlをaaaaBbbbbHtmlと変化させる
     *
     * @param unknown_type $file
     */
    public static function getCacheKeyFromFile($file)
    {
        try {
            $strResult = '';
            $splited = split('[^a-zA-Z0-9]', $file);
            foreach ($splited as $val) {
                $strResult .= ucfirst($val);
            }
            return $strResult;

        } catch (Exception $e) {
            throw $e;
        }
    }
    
}