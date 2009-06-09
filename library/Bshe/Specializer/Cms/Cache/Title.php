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
 * CMS機能でのページのタイトルやdescriptionのキャッシュを保持する
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.06.09
 * @license LGPL
 */
class Bshe_Specializer_Cms_Cache_Title extends Bshe_Specializer_Cms_Cache_Abstract
{

    /**
     * headタグの各種情報
     *
     * @var unknown_type
     */
    protected $_headerValues = array();

    /**
     * コンストラクタにて見つけた
     * titleやmetaタグの番号
     *
     * @var unknown_type
     */
    protected $_tagsNumber = array(
        'title' => false,
        'description' => false,
        'keywords' => false
    );

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

            parent::__construct($cachePath . '/bshe_title');

            // publishedファイルチェック（なければ表示は最初のまま）
            if (!file_exists($this->_cachePath . '/published.array')) {
                // なければ表示は最初のまま、最初の値をdefaultとpublishedへ保存

                $this->getTagNumbers($template);
                // titleタグ
                if ($this->_tagsNumber['title'] === false) {
                    // titleタグがない
                    $this->_headerValues['title'] = '';
                } else {
                    // title情報
                    $this->_headerValues['title'] = $template->getNodeValue($this->_tagsNumber['title']);
                }

                // keywords,description
                if ($this->_tagsNumber['keywords'] === false) {
                    // titleタグがない
                    $this->_headerValues['keywords'] = '';
                } else {
                    // title情報
                    $this->_headerValues['keywords'] = $template->getNodeValue($this->_tagsNumber['keywords']);
                }
                if ($this->_tagsNumber['description'] === false) {
                    // titleタグがない
                    $this->_headerValues['description'] = '';
                } else {
                    // title情報
                    $this->_headerValues['description'] = $template->getNodeValue($this->_tagsNumber['description']);
                }

                $this->_saveCmsCache($this->_cachePath . '/published.array', serialize($this->_headerValues));
                $this->_saveCmsCache($this->_cachePath . '/default.array', serialize($this->_headerValues));
            } else {
                $this->_headerValues = unserialize($this->_loadCmsCache($this->_cachePath . '/published.array'));
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * title,metaの各種タグの番号を取得する
     *
     * @param $template
     * @return unknown_type
     */
    public function getTagNumbers($template)
    {
        try {
            $this->_tagsNumber['title'] = $template->getElementByName('title');
            $elements = $template->getElementsByName('meta');
            foreach($elements as $meta) {
                if ($template->getAttribute($meta, 'name') == 'keywords') {
                    // キーワード
                    $this->_tagsNumber['keywords'] = $meta;
                } elseif($template->getAttribute($meta, 'name') == 'description') {
                    // description
                    $this->_tagsNumber['description'] = $meta;
                }
            }

            return $this->_tagsNumber;
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
            $this->_headerValues = $contents;
            $this->_saveCmsCache($this->_cachePath . '/' . date('Y-m-d-H-i-s') . '.array', serialize($this->_headerValues));
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
            $this->_headerValues = $contents;
            $this->_saveCmsCache($this->_cachePath . '/published.array', serialize($this->_headerValues));
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
    public function getArrayContents()
    {
        try {
            return $this->_headerValues;
        } catch (Exception $e) {
            throw $e;
        }
    }


}
