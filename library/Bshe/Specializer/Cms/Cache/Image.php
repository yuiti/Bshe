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
 * Bshe_Specializer_Cms_Cache_Image
 *
 * CMS機能でのイメージキャッシュのクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.02.20
 * @license LGPL
 */
class Bshe_Specializer_Cms_Cache_Image extends Bshe_Specializer_Cms_Cache_Abstract
{
    /**
     * imgタグの各種情報
     *
     * @var unknown_type
     */
    protected $_imgValues = array();


    /**
     * キャッシュ用の
     * フォルダがない場合はフォルダを作成する。
     *
     * @param $id
     * @param $template
     * @return unknown_type
     */
    public function __construct($id, $elementId = null, $parentElementId = null, $template, $templateFilename = null, $forEdit = false)
    {
        try {
            $cachePath = self::getCachePath($template, $templateFilename);

            parent::__construct($cachePath . '/' . $id);

            // publishedファイルチェック（なければ表示は最初のまま）
            if (!file_exists($this->_cachePath . '/published.array') and ($elementId != null)) {
                // なければ表示は最初のまま、最初の値をdefaultとpublishedへ保存
                $element = $template->getElementNumber($elementId);
                // alt属性
                $this->_imgValues['alt'] = $template->getAttribute($elementId, 'alt');

                // src属性
                $this->_imgValues['src'] = $template->getAttribute($elementId, 'src');

                if ($parentElementId != null) {
                    // 親ノードにaタグがある
                    // hrefのファイルを取得してキャッシュフォルダに保存
                    $this->_imgValues['href'] = $template->getAttribute($parentElementId, 'href');
                }

                $this->_saveCmsCache($this->_cachePath . '/published.array', serialize($this->_imgValues));
                $this->_saveCmsCache($this->_cachePath . '/default.array', serialize($this->_imgValues));
            } else {
                if ($forEdit) {
                    if (!file_exists($this->_cachePath . '/preview.array')) {
                        $this->_imgValues = unserialize($this->_loadCmsCache($this->_cachePath . '/published.array'));
                    } else {
                        $this->_imgValues = unserialize($this->_loadCmsCache($this->_cachePath . '/preview.array'));
                    }
                } else {
                    $this->_imgValues = unserialize($this->_loadCmsCache($this->_cachePath . '/published.array'));
                }
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * リクエストから画像を取得し、キャッシュへ保存する。
     *
     * @return unknown_type
     */
    public function saveImageFromRequest()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');

            if(!empty($_FILES["imageFile"]) && is_uploaded_file($_FILES["imageFile"]["tmp_name"])) {
                // リクエストにファイルあり

                // 拡張子
                $imageType = substr($_FILES["imageFile"]["name"], strrpos($_FILES["imageFile"]["name"], ".")+1, 3);
                $ymd = 'preview';

                if(move_uploaded_file($_FILES["imageFile"]["tmp_name"], $this->_cachePath . "/" . $ymd . '.' . $imageType)){
                    // ファイルを移動
                    $this->_imgValues["src"] = Bshe_Controller_Init::getUrlPath() . $config->indexphp_path . '/cms/image/images' . $this->_cachePathRelative . '/preview.' . $imageType;
                    if (isset($this->_imgValues['href'])) {
                        $this->_imgValues['href'] = $_REQUEST['imageHref'];
                    }
                    $this->_imgValues['alt'] = $_REQUEST['imageTitle'];

                    $this->_imgValues['ymd'] = $ymd;

                    $this->_saveCmsCache($this->_cachePath . '/' . $ymd . '.array', serialize($this->_imgValues));


                    return true;
                } else {
                    // ファイル移動エラー
                    Bshe_Log::logWithFileAndParamsWrite('リクエストファイル移動失敗', Zend_Log::ERR, $_FILES);
                    throw New Bshe_Specializer_Cms_Cache_Exception('リクエストファイル移動失敗');
                }
            } else {
                $ymd = 'preview';
                // リクエストにファイルなし、リンクなどのみ修正
               if (isset($this->_imgValues['href'])) {
                    $this->_imgValues['href'] = $_REQUEST['imageHref'];
                }
                $this->_imgValues['alt'] = $_REQUEST['imageTitle'];

                $this->_imgValues['ymd'] = $ymd;

                $this->_saveCmsCache($this->_cachePath . '/' . $ymd . '.array', serialize($this->_imgValues));
                return true;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 画像を公開
     *
     * @param $ymd
     * @return unknown_type
     */
    public function publishContents()
    {
        try {
            $this->_imgValues = unserialize($this->_loadCmsCache($this->_cachePath . '/preview.array'));
            $imageType = substr($this->_imgValues['src'], strrpos($this->_imgValues['src'], ".")+1, 3);
            copy($this->_cachePath . '/preview.' . $imageType, $this->_cachePath . '/published.' . $imageType);
            $this->_imgValues['src'] = str_replace('preview', 'published', $this->_imgValues['src']);
            $this->_saveCmsCache($this->_cachePath . '/published.array', serialize($this->_imgValues));
            $ymd = date('Y-m-d-H-i-s');
            copy($this->_cachePath . '/preview.' . $imageType, $this->_cachePath . '/' . $ymd . '/.' . $imageType);
            $this->_imgValues['src'] = str_replace('published', $ymd, $this->_imgValues['src']);
            $this->_saveCmsCache($this->_cachePath . '/' . $ymd . '.array', serialize($this->_imgValues));
        } catch (Exception $e) {
            throw $e;
        }

    }
    
    
    /**
     * 画像を公開
     *
     * @param $ymd
     * @return unknown_type
     */
    public function undoContents()
    {
        try {
            $this->_imgValues = unserialize($this->_loadCmsCache($this->_cachePath . '/preview.array'));
            $imageType = substr($this->_imgValues['src'], strrpos($this->_imgValues['src'], ".")+1, 3);
            copy($this->_cachePath . '/preview.' . $imageType, $this->_cachePath . '/published.' . $imageType);
            $this->_imgValues['src'] = str_replace('preview', 'published', $this->_imgValues['src']);
            $this->_saveCmsCache($this->_cachePath . '/published.array', serialize($this->_imgValues));
            $ymd = date('Y-m-d-H-i-s');
            copy($this->_cachePath . '/preview.' . $imageType, $this->_cachePath . '/' . $ymd . '/.' . $imageType);
            $this->_imgValues['src'] = str_replace('published', $ymd, $this->_imgValues['src']);
            $this->_saveCmsCache($this->_cachePath . '/' . $ymd . '.array', serialize($this->_imgValues));
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
            return $this->_imgValues;
        } catch (Exception $e) {
            throw $e;
        }
    }

}