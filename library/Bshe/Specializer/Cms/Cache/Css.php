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
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Specializer_Cms_Cache_Abstract */
require_once 'Bshe/Specializer/Cms/Cache/Abstract.php';

/**
 * Bshe_Specializer_Cms_Cache_Css
 *
 * CMS機能を利用する際の、テキスト用のCSS情報
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.02.02
 * @license LGPL
 */
class Bshe_Specializer_Cms_Cache_Css extends Bshe_Specializer_Cms_Cache_Abstract
{
    /**
     * 内部コンテンツデータ
     *
     * @var mixed
     */
    protected $_contents = '';


    /**
     * キャッシュ用の
     * フォルダがない場合はフォルダを作成する。
     *
     * @param $id
     * @param $template
     * @return unknown_type
     */
    public function __construct($template, $templateFilename = null)
    {
        try {
            $cachePath = self::getCachePath($template, $templateFilename);

            parent::__construct($cachePath);

            // cssファイルチェック（なければ表示は最初のまま）
            if (!file_exists($this->_cachePath . '/css.html')) {
                // なければ空の値をcssへ保存
                $this->_contents = '';

                $this->_saveCmsCache($this->_cachePath . '/css.html', $this->_contents);
            } else {
                $this->_contents = $this->_loadCmsCache($this->_cachePath . '/css.html');
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * キャッシュファイルへコンテンツを保存
     *
     * @param $filename
     * @param $contents
     * @return unknown_type
     */
    protected function _saveCmsCache($filename, $contents)
    {
        try {
            if (($fp = fopen($filename, 'w')) === false) {
                // 書き込みエラー発生
                Bshe_Log::logWithFileAndParamsWrite('キャッシュファイルの作成に失敗しました。', Zend_Log::INFO, array('filename' => $filename));
                throw New Bshe_Exception('キャッシュファイルの作成に失敗しました。', Zend_Log::ERR);
            }
            if (fwrite ($fp, $contents) === false) {
                // 書き込みエラー発生
                Bshe_Log::logWithFileAndParamsWrite('キャッシュファイルの出力に失敗しました。', Zend_Log::INFO, array('filename' => $filename));
                throw New Bshe_Exception('キャッシュファイルの作成に失敗しました。', Zend_Log::ERR);
            }
            fclose($fp);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * キャッシュファイルへ内容を保存
     *
     * @param $filename
     * @param $contents
     * @return unknown_type
     */
    public function saveCmsCache($contents)
    {
        try {
            $this->contents = $contents;
            $this->_saveCmsCache($this->_cachePath . '/css.html', $contents);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * キャッシュファイルからコンテンツを取得
     *
     * @param $filename
     * @param $contents
     * @return unknown_type
     */
    protected function _loadCmsCache($filename)
    {
        try {
            if (($fp = fopen($filename, 'r')) === false) {
                // 書き込みエラー発生
                Bshe_Log::logWithFileAndParamsWrite('キャッシュファイルを開くのに失敗しました。', Zend_Log::INFO, array('filename' => $filename));
                throw New Bshe_Exception('キャッシュファイルを開くのに失敗しました。', Zend_Log::ERR);
            }
            if (($this->_contents = fread ($fp, filesize($filename))) === false) {
                // 書き込みエラー発生
                Bshe_Log::logWithFileAndParamsWrite('キャッシュファイルの読み出しに失敗しました。', Zend_Log::INFO, array('filename' => $filename));
                throw New Bshe_Exception('キャッシュファイルの読み出しに失敗しました。', Zend_Log::ERR);
            }
            fclose($fp);

            return $this->_contents;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * コンテンツテキストを取得
     *
     * @return unknown_type
     */
    public function getContents()
    {
        try {
            return $this->_contents;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * キャッシュを保持するパス（ID別の部分の手前）を返す
     *
     * @param $template
     * @param $templateFilename
     * @return unknown_type
     */
    static public function getCachePath($template, $templateFilename = null)
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            if ($templateFilename == null) {
                // ファイル名が指定されていない場合はテンプレートクラスにて指定
                $templateFilename = $template->getTemplateFileName();
            }

            // ファイル名に..が含まれていないかチェック
            if (strpos($templateFilename, '..') !== false) {
                Bshe_Log::logWithFileAndParamsWrite('ファイル名に「..」が指定されたリクエストです。', Zend_Log::ERR, array('filename' => $templateFilename));
                throw New Bshe_Exception('ファイル名に「..」が指定されたリクエストです。');
            }

            // 編集ファイルフォルダチェック（なければ作成）
            $templatePath = Bshe_Controller_Init::getMainPath() . $config->cms_cache_text_path . '/' . dirname($templateFilename);
            if (substr($templatePath, -2) == '//') {
                $templatePath = substr($templatePath, 0, -2);
            }
            $templateFile = basename($templateFilename);
            // フォルダ名に.htmlが含まれている場合、誤動作を招くためログを記録しスキップ
            if ((strpos($templatePath, '.html/') !== false) or (substr($templatePath, -5) == '.html')) {
                $template->getLogger()->logWithFileAndParams('CMS機能を利用する際にフォルダ名に.htmlを利用することはできません。', Zend_Log::INFO);
                throw New Bshe_Exception('CMS機能を利用する際にフォルダ名に.htmlを利用することはできません。');

            }
            return $templatePath . '/' . $templateFile;

        } catch (Exception $e) {
            throw $e;
        }
    }
}