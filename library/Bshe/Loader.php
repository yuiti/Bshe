<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Loader
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_Loader */
require_once 'Zend/Loader.php';

/**
 * オートローダークラス
 *
 * library以下に加え、application以下のmodelsからも処理できるように拡張したクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.08.16
 * @license LGPL
 */
class Bshe_Loader extends Zend_Loader
{
    /**
     * 追加するpathのリスト
     *
     * @var array
     */
    protected static $applicationDir = null;

    public static function setApplicationDir($applicationDir = null)
    {
        if($applicationDir !==null) {
            self::$applicationDir = $applicationDir;
        }
    }

    /**
     * loadClassメソッド
     *
     * application/models
     * application/module名/models
     * 以下にあるクラスを読み込む
     *
     * @param string $class      - The full class name of a Zend component.
     * @param string|array $dirs - OPTIONAL Either a path or an array of paths
     *                             to search.
     * @return void
     * @throws Zend_Exception
     */
    public static function loadClassFromApplication($class)
    {
        if (self::$applicationDir === null) {
            // applicationパスがセットされていないため処理なし
            require_once 'Zend/Exception.php';
            throw new Zend_Exception("application dir not set class \"$class\" was not found");
        }

        // 親のloadClassで見つけられなかった場合application以下から探す
        if (strpos( $class, 'Models_') === 0) {
            // application/models以下
            // autodiscover the path from the class name
            $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
            // 頭のMをmへ変更
            $file = 'm' . substr($file, 1);
            // loadFile呼び出し
            $file = basename($file);
            self::loadFile($file, self::$applicationDir . '/models', true);
        } else {
            // application/module名/models以下として処理
            // moduleフォルダの有無を確認
            $pathes = explode('_', $class);
            if (file_exists(self::$applicationDir . '/' . strtolower($pathes[0]) )) {
                // 対象moduleあり
                $strHeadDir = strtolower($pathes[0]) . '/models';
                $file = $strHeadDir . substr(str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php', strlen($strHeadDir));
                // loadFile呼び出し
                $strDir = dirname($file);
                $file = basename($file);
                self::loadFile($file, self::$applicationDir . '/' . $strDir, true);
            } else {
                // 対象moduleなし
                require_once 'Zend/Exception.php';
                throw new Zend_Exception("module " . strtolower( $pathes[0]) . " is not found, class \"$class\" was not found");
            }

        }

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            require_once 'Zend/Exception.php';
            throw new Zend_Exception("File \"$file\" does not exist or class \"$class\" was not found in the file");
        }
    }

    /**
     * オートロード
     *
     * @param string $class
     * @return string|false Class name on success; false on failure
     */
    public static function autoload($class)
    {
        try {
            self::loadClass($class);
            return $class;

        } catch (Exception $e) {
            // 親のロードクラスで見つからない場合、applicationから検索
            try {
                self::loadClassFromApplication($class);
                return $class;
            } catch (Exception $e) {
                return false;
            }
        }

    }
}
