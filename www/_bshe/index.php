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
 * Bsheライブラリを利用する際のIndex.php
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.09
 * @license LGPL
 */
    // PHP.iniの修正定義
    ini_set('default_charset','UTF-8');
    ini_set('mbstring.http_output', 'UTF-8');
    ini_set('mbstring.internal_encoding', 'UTF-8');
    ini_set('mbstring.http_input', 'auto');

    // sslでのダウンロード対策
    session_cache_limiter('public');

    // path設定
        require_once 'setPath.inc';

    // logger設定
        require_once 'setLogger.inc';

    // HTMLファイル分割MVC用ルート生成
/*        require_once 'Bshe/Specializer/Controller/Router.php';
        $arrayRoutes = array(
            'test/specializertest(\d+)_(\d+).html' => array(
                'module' => 'test',
                'controller' => 'specializer',
                'action' => 'test2'
                )
        );
*/
    $router = Bshe_Specializer_Controller_Router::createRouter();

    require_once 'Zend/Controller/Front.php';
    require_once 'Bshe/Controller/Init.php';
    $front = Zend_Controller_Front::getInstance();
    $front->setRouter($router);
    $front->setBaseUrl(Bshe_Controller_Init::getUrlPath());
    $controllerPaths = array(
        'default' => Bshe_Controller_Init::getMainPath() . '/application/controllers',
        'bshe' => Bshe_Controller_Init::getMainPath() . '/bshe/controllers',
        'bshecms' => Bshe_Controller_Init::getMainPath() . '/bshe/cms/controllers'
    );

    // application以下で、models,views,controllers出ないものはmodule
    $dirScan = scandir(Bshe_Controller_Init::getMainPath() . '/application', 1);
    foreach ($dirScan as $key => $dirName) {
        if(($dirName != 'models') and ($dirName != 'controllers') and ($dirName != 'views') and ($dirName != '..') and ($dirName != '.')) {
            // パスに追加
            $controllerPaths[$dirName] = Bshe_Controller_Init::getMainPath() . '/application/' . $dirName . '/controllers';
        }
    }
    $front->setControllerDirectory($controllerPaths);

    // アクセスログ
    $configSpecializer = Bshe_Registry_Config::getConfig('Bshe_Specializer');
    if ($configSpecializer->access_log == true) {
        $accessLogger = new Bshe_Log();
        $accessWriter = new Bshe_Log_Writer_Dailystream(Bshe_Controller_Init::getMainPath() . '/logs', 'AccessLog_');
        $accessLogger->addWriter($accessWriter);
        $front->registerPlugin(new Bshe_Specializer_Controller_Plugin_Accesslog($accessLogger));
    }
    // 例外を表示
    $front->throwExceptions(true);
    try
    {
        $front->dispatch();
    }
    catch( Exception $e)
    {
        // 例外を表示
        print_r($e);
    }
