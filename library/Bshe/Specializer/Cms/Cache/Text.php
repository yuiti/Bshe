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
 * Bshe_Specializer_Cms_Cache_Text
 *
 * CMS機能でのテキストキャッシュのクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.02.02
 * @license LGPL
 */
class Bshe_Specializer_Cms_Cache_Text extends Bshe_Specializer_Cms_Cache_Abstract
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
    public function __construct($id, $elementId = null, $template, $templateFilename = null)
    {
        try {
            $cachePath = self::getCachePath($template, $templateFilename);

            parent::__construct($cachePath . '/' . $id);

            // publishedファイルチェック（なければ表示は最初のまま）
            if (!file_exists($this->_cachePath . '/published.html') and ($elementId != null)) {
                // なければ表示は最初のまま、最初の値をdefaultとpublishedへ保存
                $element = $template->getElementNumber($elementId);
                $this->_contents = $template->getNodeValue($elementId);

                $this->_saveCmsCache($this->_cachePath . '/published.html', $this->_contents);
                //$this->_saveCmsCache($this->_cachePath . '/' . date('Y-m-d-H-i-s') . '.html', $this->_contents);
                $this->_saveCmsCache($this->_cachePath . '/default.html', $this->_contents);
            } elseif (file_exists($this->_cachePath . '/preview.html')) {
                $this->_contents = $this->_loadCmsCache($this->_cachePath . '/preview.html');
            } else {
                $this->_contents = $this->_loadCmsCache($this->_cachePath . '/published.html');
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * コンテンツをセーブする
     *
     * @param $contents
     * @return unknown_type
     */
    public function saveContents($contents)
    {
        try {
            $this->_contents = $contents;
            $this->_saveCmsCache($this->_cachePath . '/preview.html', $this->_contents);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * コンテンツをpublishする
     *
     * @param $contents
     * @return unknown_type
     */
    public function publishContents($contents)
    {
        try {
            $this->_contents = $contents;
            $this->_saveCmsCache($this->_cachePath . '/published.html', $this->_contents);
            $this->_saveCmsCache($this->_cachePath . '/' . date('Y-m-d-H-i-s') . '.html', $this->_contents);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * publishをpreviewに戻す
     *
     * @param $contents
     * @return unknown_type
     */
    public function undoContents()
    {
        try {
            $contents = $this->_loadCmsCache($this->_cachePath . '/published.html');
            $this->_saveCmsCache($this->_cachePath . '/preview.html', $contents);
            $this->_contents = $contents;

            return true;
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
     * 保存された履歴のリストを取得する
     *
     * @return unknown_type
     */
    public function getRevisionList()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            $dirScan = scandir($this->_cachePath, 1);
            $maxFileRead = count($dirScan)-2 > $config->cms_cache_max_revisions ? $config->cms_cache_max_revisions : count($dirScan)-2;

            $arrayRevisions = array();

            for($i=0;$i<$maxFileRead+2;$i++) {
                if(is_numeric(substr($dirScan[$i],0,1))) {
                    // 対象のフォルダ
                    $arrayRevision = array();
                    $arrayRevision['time'] = substr($dirScan[$i], 0, -4);
                    $arrayRevision['contents'] = $this->_loadCmsCache($this->_cachePath . '/' . $dirScan[$i]);
                    $arrayRevisions[] = $arrayRevision;
                }
            }

            return $arrayRevisions;
        } catch (Exception $e) {
            throw $e;
        }

    }

}
