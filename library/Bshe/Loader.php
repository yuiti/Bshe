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
