<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Registry
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Abstract */
require_once 'Bshe/Abstract.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * 設定ファイルのコンフィグクラスをレジストリ保持するために
 * 拡張されたクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.19
 * @license LGPL
 */
class Bshe_Registry_Config extends Zend_Registry
{
    /**
     * INIファイルのフルパス情報
     *
     * @var string
     */
    static protected $bsheInitPath = null;

    /**
     * INIファイルのフルパスを保存
     *
     * @param string $initPath
     */
    public static function setInitPath($initPath)
    {
        self::$bsheInitPath = $initPath;
    }

    /**
     * 呼び出すコンフィグクラスを変更する場合クラス名をセット
     *
     * @var string
     */
    static protected $bsheConfigClass = '';

    /**
     * INIファイルのフルパスを保存
     *
     * @param string $initPath
     */
    public static function setConfigClass($configClass)
    {
        self::$bsheConfigClass = $configClass;
    }

    /**
     * コンフィグクラスを返す
     *
     * @return unknown_type
     */
    public static function getConfigClass()
    {
        return self::$bsheConfigClass;
    }


    /**
     * コンフィグクラスをレジストリから取得する
     * コンフィグクラスがレジストリに登録されていない場合は、予め決められたコンフィグファイルから
     * コンフィグクラスを取得する。
     *
     * @param string $index
     * @return mixed
     */
    public static function getConfig($index)
    {
        try {
            if (self::isRegistered('bshe_config_' . $index)) {
                return parent::get('bshe_config_' . $index);
            } else {
                // コンフィグクラス名確定
                if (self::$bsheConfigClass == '') {
                    // デフォルト
                    $confClassName = 'Bshe_Config_Ini';
                } else {
                    $confClassName = self::$bsheConfigClass;
                }


                // コンフィグパスがないため設定から取得
                if (substr($index, 0, 5) != 'Bshe_') {
                    // アプリケーション独自のINI
                    $arrayIndex = split('_', $index);
                    $tmpConfig = New $confClassName(Bshe_Controller_Init::getMainPath() . '/application/' . strtolower($arrayIndex[0]) . '/init/param.ini', $index, array('allowModifications' => true));
                    parent::set('bshe_config_' . $index, $tmpConfig);
                    return $tmpConfig;
                } else {
                    if (self::$bsheInitPath != null) {
                        // コンフィグパスセット済み
                        $tmpConfig = New $confClassName(self::$bsheInitPath, $index, array('allowModifications' => true));
                        parent::set('bshe_config_' . $index, $tmpConfig);
                        return $tmpConfig;
                    } else {
                        $tmpConfig = New $confClassName(Bshe_Controller_Init::getMainPath() . '/init/bshe.ini', $index, array('allowModifications' => true));
                        parent::set('bshe_config_' . $index, $tmpConfig);
                        return $tmpConfig;
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * レジストリ上のコンフィグクラスを上書きする
     *
     * @param $index
     * @param $config
     * @return unknown_type
     */
    public static function setConfig($index, $config)
    {
        try {
            parent::set('bshe_config_' . $index, $config);
            return true;
        } catch (Exceptio $e) {
            throw $e;
        }
    }

}