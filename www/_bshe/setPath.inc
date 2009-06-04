<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/**
 * Asheライブラリを利用する際のパス関連を設定
 *
 * 必要なログライタをBshe_Logクラスのインスタンスへ設定し
 * Zend_Registryにてloggerという名称のレジストリに登録する
 *
 * @author Yuichiro Abe
 * @created 2008.09.09
 * @license LGPL
 */

    // ライブラリパス
        $thisDirname = dirname($_SERVER['SCRIPT_FILENAME']);
        $libraryPath = realpath($thisDirname . '/../../library');
        $applicationPath = realpath($thisDirname . '/../../application');
    // アプリケーションエリアのトップパス（下でセッションに保存する）
        ini_set("include_path",ini_get("include_path") . ':' . $libraryPath . ':' . $applicationPath);

    // Bshe_Registry_Config初期化
        /** Zend_Registry */
        require_once 'Zend/Registry.php';
        /** Bshe_Registry_Config */
        require_once 'Bshe/Registry/Config.php';
        /** Bshe_Controller_Init */
        require_once 'Bshe/Controller/Init.php';

        // コンフィグを必要に応じて自動で取得するクラスのセット
            // パスを独自レジストリクラスへセット
            Bshe_Registry_Config::setInitPath(Bshe_Controller_Init::getMainPath() . '/init/bshe.ini');
            // 独自レジストリクラスをセット
            Zend_Registry::setClassName('Bshe_Registry_Config');

    // Loader設定
        require_once 'Zend/Loader.php';
        //require_once 'Bshe/Loader.php';
        //Bshe_Loader::setApplicationDir($applicationPath);
        Zend_Loader::registerAutoload();
