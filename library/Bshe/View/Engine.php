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
 * @subpackage View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Abstract */
require_once 'Bshe/Abstract.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Exception */
require_once 'Bshe/View/Exception.php';
/** Bshe_View_Resource_Exception */
require_once 'Bshe/View/Resource/Exception.php';
/*: Bshe_View_Template_Exception */
require_once 'Bshe/View/Template/Exception.php';
/** Bshe_View_Helper_Exception */
require_once 'Bshe/View/Helper/Exception.php';

/**
 * HTMLファイルをそのまま読み込んで処理できるテンプレートエンジン
 * HTMLのID属性を利用して各値を設定できる
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.09
 * @license LGPL
 */
class Bshe_View_Engine extends Bshe_Abstract
{

    /**
     * ログクラス保持用
     *
     * @var unknown_type
     */
    protected $_logger = null;


    /**
     * ログクラスをスタティックに登録
     *
     * @param unknown_type $logger
     */
    public function setLogger($logger)
    {
        $this->_logger = $logger;
        $this->_templateClass->setLogger($logger);
    }

    /**
     * ロガー取得
     *
     * @return unknown
     */
    public function getLogger()
    {
        // loggerが登録されていない場合は、メインログへ記録
        if ($this->_logger === null) {
            if ($this->getParam('logger') == null) {
                return Bshe_Log::getLogger();
            } else {
                $this->_logger = $this->getParam('logger');
                return $this->_logger;
            }
        } else {
            return $this->_logger;
        }
    }

    /**
     * ログクラスがセットされているか確認
     *
     * @return unknown_type
     */
    public function hasLogger()
    {
        if ($this->_logger === null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 各種設定情報配列
     *
     * templatePath: テンプレートHTMLのパス
     * templateFile: テンプレートHTMLファイル名
     * assignClassPath: ユーザー定義のアサイン用メソッド用のクラスのパス（配列でも可、nullの場合はautoLoaderを利用してクラスを探す）
     * assignClassPrefix: ユーザー定義のアサイン用クラスの頭の文字列
     * 　クラス名のこの文字列の後ろ+.phpをクラスファイルとしてassignClassPathからファイルを検索する。
     * helperClassPath: ユーザー定義のHELPER用メソッド用のクラスのパス（配列でも可、nullの場合はautoLoaderを利用してクラスを探す）
     * helperClassPrefix: ユーザー定義のHELPER用クラスの頭の文字列
     * 　クラス名のこの文字列の後ろ+.phpをクラスファイルとしてassignClassPathからファイルを検索する。
     *
     * @var array
     *
     */
    protected $_params =
        array(
            'templatePath' => null,
            'templateFile' => null,
            'templateCompilePath' => null,
            'assignClassPath' => array(),
            'assignClassPrefix' => null,
            'helperClassPath' => array(),
            'helperClassPrefix' => null,
            'logger' => null
        );


    protected $_arraySuffix2Class =
        array(
            'html' => 'Bshe_View_Template_Html',
            'htm' => 'Bshe_View_Template_Html'
        );

    /**
     * テンプレートクラス
     *
     * @var Bshe_View_Template_Abstract
     */
    protected $_templateClass;

    /**
     * assign用値（エンジン経由で直接指定したもの用）
     *
     * @var array
     */
    protected $_presetValues = array();


    /**
     * コンストラクタ
     *
     * 初期設定配列を読み込みセットする。
     *
     * @param unknown_type $param
     */
    public function __construct($param = array())
    {
        try {
            // パラメーターセット
            $this->setParams($param, true);

            $config = Bshe_Registry_Config::getConfig('Bshe_View');

            // テンプレート未設定の場合
            if (!isset($this->_templateClass)) {
                // 必須パラメーターチェック
                if (($this->_params['templatePath'] == null) or ($this->_params['templateFile'] == null)) {
                    Bshe_Log::logWithFileAndParamsWrite('テンプレートパスまたはテンプレートファイルが指定されておりません。', Zend_Log::ERR,
                        array('params' => $this->_params));
                    throw New Bshe_View_Exception('テンプレートパスまたはテンプレートファイルが指定されておりません。');
                }

                // ファイル名の拡張子からクラスを判別
                $suffix = substr(strrchr($this->_params['templateFile'], "."), 1);


                // テンプレートクラスインスタンス化
                if (isset($this->_arraySuffix2Class[strtolower($suffix)])) {
                    $className = $this->_arraySuffix2Class[strtolower($suffix)];
                    $this->_templateClass = New $className();
                } else {
                    Bshe_Log::logWithFileAndParamsWrite('テンプレートクラスが指定されておりません', Zend_Log::ERR,
                        array('suffix' => $suffix));
                    throw New Bshe_View_Exception('テンプレートクラスが指定されておりません');
                }
            }

            // loggerセット
            $this->_templateClass->setLogger($this->getLogger());

            // 設定ファイルテンプレートへセット
            $this->_templateClass->setParams($this->_params);

            // テンプレートファイル読み込み
            try {
                if ($this->_params['templateFile'] != null) {
            	    $this->_templateClass = $this->_templateClass->readTemplateFile();
                }
            } catch (Bshe_View_Template_Exception $e) {
            	// テンプレート上の例外そのまま処理
                $this->getLogger()->logWithFileAndParams($e->getMessage(), Zend_Log::INFO);
            }


        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テンプレートクラスをセット
     *
     * ここでセットしない場合、テンプレートファイルの拡張子で自動判別される
     *
     * @param Bshe_View_Template_Abstract $template
     */
    public function setTemplate($template)
    {
        try {
            $this->_templateClass = $template;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テンプレートクラスを返す
     * 
     * @return unknown_type
     */
    public function getTemplate()
    {
        return $this->_templateClass;
    }


    /**
     * Assign a variable to the view
     *
     * @param string $key The variable name.
     * @param mixed $val The variable value.
     * @return void
     */
    public function __set($key, $val)
    {
        try {
            $this->_presetValues[$key][] = $val;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->_presetValues[$key]);
    }

    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        if (isset($this->_presetValues[$key])) {
            unset($this->_presetValues[$key]);
        }
    }

    /**
     * Clear all assigned variables
     *
     * Clears all variables assigned to Zend_View either via {@link assign()} or
     * property overloading ({@link __get()}/{@link __set()}).
     *
     * @return void
     */
    public function clearVars()
    {
        $this->_presetValues = array();
    }

    /**
     * Processes a view script and returns the output.
     *
     * @param string $name The script script name to process.
     * @return string The script output.
     */
    public function render()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_View');
//
//            // テンプレート未設定の場合
//            if (!isset($this->_templateClass)) {
//                // 必須パラメーターチェック
//                if (($this->_params['templatePath'] == null) or ($this->_params['templateFile'] == null)) {
//                    Bshe_Log::logWithFileAndParamsWrite('テンプレートパスまたはテンプレートファイルが指定されておりません。', Zend_Log::ERR,
//                        array('params' => $this->_params));
//                    throw New Bshe_View_Exception('テンプレートパスまたはテンプレートファイルが指定されておりません。');
//                }
//
//                // ファイル名の拡張子からクラスを判別
//                $suffix = substr(strrchr($this->_params['templateFile'], "."), 1);
//
//
//                // テンプレートクラスインスタンス化
//                if (isset($this->_arraySuffix2Class[strtolower($suffix)])) {
//                    $className = $this->_arraySuffix2Class[strtolower($suffix)];
//                    $this->_templateClass = New $className();
//                } else {
//                    Bshe_Log::logWithFileAndParamsWrite('テンプレートクラスが指定されておりません', Zend_Log::ERR,
//                        array('suffix' => $suffix));
//                    throw New Bshe_View_Exception('テンプレートクラスが指定されておりません');
//                }
//            }
//
            // loggerセット
            $this->_templateClass->setLogger($this->getLogger());
//
//            // 設定ファイルテンプレートへセット
//            $this->_templateClass->setParams($this->_params);
//
//             テンプレートファイル読み込み
//            try {
//            	$this->_templateClass = $this->_templateClass->readTemplateFile();
//            } catch (Bshe_View_Template_Exception $e) {
//            	 テンプレート上の例外そのまま処理
//                $this->getLogger()->logWithFileAndParams($e->getMessage(), Zend_Log::INFO);
//            }

            // templateクラスのassign前処理
            if (method_exists($this->_templateClass, 'beforeAssigns')) {
                $this->_templateClass->beforeAssigns();
            }



            if (!$this->_templateClass->fromCache()) {
                // データアサイン（通常）
                $this->_templateClass = $this->assignValuesToTemplate();
                // キャッシュ機構がある場合はキャッシュ保存
                $this->_templateClass->saveCache();
            }

            // キャッシュ外アサイン
            $this->_templateClass = $this->assignValuesToTemplate('noChacheAssignValues');

            // templateクラスのassign後処理
            if (method_exists($this->_templateClass, 'afterAssigns')) {
                $this->_templateClass = $this->_templateClass->afterAssigns();
            }

            // plugin実行


            // HTML出力
            echo $this->_templateClass->output();

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テンプレートへ値をセットする
     *
     */
    protected function assignValuesToTemplate($functionPrefix = 'assignValues')
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('テンプレートへ値セット開始', Zend_Log::DEBUG);

            // 値配列セット
            $this->_templateClass->setPresetValues($this->_presetValues);

            // IDでループしての処理
            $arrayElement = $this->_templateClass->getElements();
            foreach ($arrayElement as $id => $elements) {
                // 各種チェック対応
                // _で分割
                $arrayPaseId = $this->_templateClass->getParsedId($id);
                $methodName = $functionPrefix . ucfirst(strtolower($arrayPaseId['methodName']));

                // methodがあれば実行する。
                if (method_exists($arrayPaseId['className'], $methodName)) {
                    try {
                        foreach ($elements as $key => $element) {
                            // まだ未処理のelementに対してのみ処理を実行
                            if ($this->_templateClass->issetElement( $id, $key)) {
                                $arrayPaseIdTemp = $arrayPaseId;
                                $arrayPaseIdTemp['seq'] = $key;
                                $arrayFuncParams = array(
                                            'templateClass' => $this->_templateClass,
                                            'element' => $element,
                                            'params' => $arrayPaseIdTemp,
                                            'presetValues' => $this->_presetValues
                                        );

                                $this->_templateClass = self::callAssingFunction($arrayPaseId['className'], $methodName, $arrayFuncParams);
                            }
                        }
                    } catch (Bshe_View_Resource_Exception $e) {
                        // リソースの例外はそのまま処理
                        $this->getLogger()->logWithFileAndParams($e->getMessage(), Zend_Log::INFO);
                    } catch (Bshe_View_Template_Exception $e) {
                        // テンプレート上の例外そのまま処理
                        $this->getLogger()->logWithFileAndParams($e->getMessage(), Zend_Log::INFO);
                    } catch (Bshe_View_Helper_Exception $e) {
                        // ヘルパーの例外はそのまま処理
                        $this->getLogger()->logWithFileAndParams($e->getMessage(), Zend_Log::INFO);
                    } catch (Bshe_View_Template_Exception_Nostop $e) {
                        // テンプレートの継続例外はそのまま処理
                        $this->getLogger()->logWithFileAndParams($e->getMessage(), Zend_Log::INFO);
                    } catch (Exception $e) {
                        throw $e;
                    }
                }
            }


            Bshe_Log::logWithFileAndParamsWrite('テンプレートへ値セット終了', Zend_Log::DEBUG);
            return $this->_templateClass;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 対象のElementに対するアサイン処理を実施する
     * before,after処理もメソッドがあれば実行する。
     *
     * @param string $className クラス名
     * @param array $arrayFuncParams 呼出パラメーター
     * @param boolean $before 事前処理呼出制御
     * @param boolean $after 事後処理呼出制御
     */
    public static function callAssingFunction($className, $methodName, $arrayFuncParams, $before = true, $after = true)
    {
        try {
            // templateクラスのassign前処理
            if (method_exists($className, 'beforeAssign') and $before) {
                $arrayFuncParams['templateClass'] = call_user_func(array($className, 'beforeAssign'),
                    $arrayFuncParams
                );
            }

            // assign処理
            $arrayFuncParams['templateClass'] = call_user_func(array($className, $methodName),
                $arrayFuncParams
            );

            // templateクラスのassign後処理
            if (method_exists($className, 'afterAssign') and $after) {
                $arrayFuncParams['templateClass'] = call_user_func(array($className, 'afterAssign'),
                    $arrayFuncParams
                );
            }

            return $arrayFuncParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * テンプレートクラスのプラグインフラグ配列を取得する。
     *
     * @return unknown_type
     */
    public function getTemplatePluginFlags()
    {
        try {
            return $this->_templateClass->getParam('pluginFlags');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テンプレートクラスのプラグインフラグ配列を取得する。
     *
     * @return unknown_type
     */
    public function setTemplatePluginFlags($arrayPluginFlags)
    {
        try {
            return $this->_templateClass->setParam('pluginFlags', $arrayPluginFlags);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
