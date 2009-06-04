<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Controller
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/**
 * 各種パス情報を取得するなどの基本情報取得クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_Controller_Init
{

    /**
     * アプリケーションのメインパス
     * （libraryの一つ上のパスを返す）
     *
     * @return string
     */
    static public function getMainPath()
    {
        $thisDirname = dirname($_SERVER['SCRIPT_FILENAME']);
        return realpath($thisDirname . '/../..');
    }

    /**
     * URLのルート（DocumentRootの場合はなし）を返す
     *
     * @return string
     */
    static public function getUrlPath()
    {
        $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
        $strTmp = str_replace($config->indexphp_path, '', substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME']) - strlen(basename($_SERVER['SCRIPT_FILENAME'])) -1));

        return $strTmp;
    }

    /**
     * libraryのメインパスを返す
     *
     * @return string
     */
    static public function getLibraryPath()
    {
        return realpath(self::getMainPath() . '/library');
    }

}
