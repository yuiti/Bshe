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
/** Bshe_View_Template_Exception */
require_once 'Bshe/View/Template/Exception.php';
/** Bshe_View_Template_Exception_NoTemplateFile */
require_once 'Bshe/View/Template/Exception/NoTemplateFile.php';
/** Bshe_View_Template_Exception_Nostop_Noassignclass */
require_once 'Bshe/View/Template/Exception/Nostop/Noassignclass.php';

/**
 * Bshe_Viewのテンプレート用クラスの抽象クラス
 *
 *
 * @author Yuichiro Abe
 * @created 2008.09.09 <bshe@bshe.org>
 * @license LGPL
 * @todo アサインクラスが見つからない場合も処理を停止しないようにする
 */
abstract class Bshe_View_Template_Abstract extends Bshe_Abstract
{
    /**
     * 各種設定情報配列
     *
     * templatePath: テンプレートHTMLのパス
     * templateFile: テンプレートHTMLファイル名
     * assignClassPath: ユーザー定義クラスの設置パス（NULLの場合はautoloader経由で探す）
     * assignClassPrefix: ユーザー定義クラスのprefix（NULLの場合はユーザー定義クラスを検索しない、autoloaderのほかに対象のクラス名＝ファイル名としてassignClassPathからも探す）
     * builtinAssignClassPrefix: ビルトインクラスのprefix（autoloader経由で探す）
     *
     * @var array
     *
     */
    protected $_params =
        array(
            'templatePath' => null,
            'templateFile' => null,
            'assignClassPath' => array(),
            'assignClassPrefix' => null,
            'builtinAssignClassPrefix' => null,
            'helperClassPath' => array(),
            'helperClassPrefix' => null,
            'builtinHelperClassPrefix' => 'Bshe_View_Helper_',
            'templateCachePath' => null,
            'afterAssigns' => array(),
            'beforeAssigns' => array(),
            'pluginFlags' => array()
        );

    /**
     * 読み込まれたテンプレート文字列
     *
     * @var string
     */
    protected $_contents;

    /**
     * HTMLテンプレート内の処理対象の配列
     *
     * @var array
     */
    protected $_arrayElements = array();

    /**
     * テンプレートがキャッシュから読み込まれたかどうか
     *
     * @var boolean
     */
    protected $_isCached = false;

    /**
     * シリアライズのsleep,wakeup用の読み込みクラスファイル
     *
     * @var array
     */
    protected $_requiredClass = array();

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
    }

    /**
     * ロガー取得
     *
     * @return unknown
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     * assign用値（エンジン経由で直接指定したもの用）
     *
     * @var array
     */
    protected $_presetValues = array();

    /**
     * assign用一括変数セット
     *
     * @param array $values
     * @return void
     */
    public function setPresetValues($values)
    {
        $this->_presetValues = $values;
    }

    /**
     * assign用変数配列取得
     *
     * @return array
     */
    public function getPresetValues()
    {
        return $this->_presetValues;
    }


    /**
     * 処理配列から指定のエレメントを削除する
     *
     * @param string $key
     * @param integer $seq
     */
    public function unsetElement($key, $seq)
    {
        if (isset($this->_arrayElements[$key][$seq])) {
            unset($this->_arrayElements[$key][$seq]);
        }
    }

    /**
     * 処理配列内に指定のエレメントが存在するかをチェックする。
     *
     * @param string $key
     * @param integer $seq
     * @return boolean
     */
    public function issetElement($key, $seq)
    {
        if (isset($this->_arrayElements[$key][$seq])) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * コンストラクタ
     *
     * テンプレートパスなどの設定情報を保存
     *
     * @param unknown_type $arrayParams
     */
    public function __construct($param = array())
    {
        try {
            // パラメーターセット
            $this->setParams($param);
        } catch (Exception $e) {
            throw $e;
        }
    }



    /**
     * テンプレートファイル名取得
     * 　設定されたテンプレートルートから下のフルパス
     *
     * @return string
     */
    public function getTemplateFileName()
    {
        return $this->_params['templateFile'];
    }

    /**
     * テンプレートファイルの読み込み
     *
     * @param string $fileName テンプレートファイル（指定されない場合はパラメーター設定されている内容を利用）
     * @return string 読み込まれたテンプレート文字列
     */
    public function readTemplateFile($fileName = null)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み開始', Zend_Log::DEBUG, array('fileName' => $fileName));

            $file = $this->_params['templatePath'] . '/' . $this->getFileName($fileName);

            // ファイルオープン
            $fp = fopen($file, 'r');
            $this->_contents = fread($fp, filesize($file));
            fclose($fp);

            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み終了', Zend_Log::DEBUG, array('file' => $file));

            return $this->_contents;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ファイル名を取得（NULLの場合は、パラメータから取得
     *
     * @param unknown_type $fileName
     */
    public function getFileName($fileName = null)
    {
        try {
            if ($fileName === null) {
                // ファイルの指定がない場合は、パラメーターから取得する
                if ($this->_params['templateFile'] != null) {
                    $fileName = $this->_params['templateFile'];
                } else {
                    Bshe_Log::logWithFileAndParamsWrite('対象のテンプレートが指定されておりません', Zend_Log::ERR);
                    throw New Bshe_View_Template_Exception('対象のテンプレートが指定されておりません。');
                }
            }

            // ファイルフルパス生成
            $this->_params['templateFile'] = $fileName;
            $file = $this->_params['templatePath'] . '/' . $this->_params['templateFile'];
            if (!file_exists( $file)) {
                Bshe_Log::logWithFileAndParamsWrite('テンプレートファイルが見つかりません', Zend_Log::ERR, array('file' => $file));
                throw New Bshe_View_Template_Exception_NoTemplateFile($fileName);
            }

            return $fileName;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テンプレートファイルのタイムスタンプを確認します。
     *
     */
    protected function getTemplateFileTimestamp($fileName = null)
    {
        try {
            $fileName = $this->getFileName($fileName);

            $mtime = filemtime($this->_params['templatePath'] . '/' . $fileName);

            return $mtime;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 処理対象となるIDのElementの配列を返す
     *
     * @return array 処理対象Element
     */
    public function getElements()
    {
        return $this->_arrayElements;
    }

    /**
     * assingクラスキーワード（クラス名のprefixより後ろの文字）からクラス名を生成
     * ユーザー定義クラスが存在する場合そちらを優先
     *
     * @param string $classKeyWord クラスキーワード（クラス名のprefixより後ろの文字）
     * @return string|false クラス名（見つからない場合はfalse）
     */
    protected function getAssingClass($classKeyWord)
    {
        try {
            return $this->getClassName(
                $this->_params['assignClassPrefix'],
                $this->_params['assignClassPath'],
                $this->_params['builtinAssignClassPrefix'],
                $classKeyWord);
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Helperクラスキーワード（クラス名のprefixより後ろの文字）からクラス名を生成
     * ユーザー定義クラスが存在する場合そちらを優先
     *
     * @param string $classKeyWord クラスキーワード（クラス名のprefixより後ろの文字）
     * @return string|false クラス名（見つからない場合はfalse）
     */
    protected function getHelperClass($classKeyWord)
    {
        try {
            return $this->getClassName(
                $this->_params['helperClassPrefix'],
                $this->_params['helperClassPath'],
                $this->_params['builtinHelperClassPrefix'],
                $classKeyWord);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * シリアライズ後に、必要なクラスをrequireする
     *
     */
    public function __wakeup()
    {
        try {
            foreach ($this->_requiredClass as $classFile) {
                require_once $classFile;
            }
        } catch (Exception $e) {
            throw $e;
        }

    }


    private function getClassName($prefix, $paths, $builtinPrefix, $classKeyWord)
    {
        try {
            // クラスキーワードの1文字目を大文字に変換
            $ucClassKeyWord = ucfirst(strtolower($classKeyWord));
            // ユーザークラスの有無を確認
            if ($prefix  !== null) {
                if (class_exists( $prefix . $ucClassKeyWord, false)) {
                    return $prefix . $ucClassKeyWord;
                } else {
                    // ユーザーパスにて対応
                    foreach ($paths as $key => $path) {
                        // ユーザーファイル検索
                        $file = $path . '/' .  ucfirst($classKeyWord) . '.php';
                        if (file_exists($file)) {
                            require_once $file;
                            $this->_requiredClass[] = $file;
                            if (class_exists( $prefix . $ucClassKeyWord)) {
                                return $prefix . $ucClassKeyWord;
                            }
                        }
                    }
                }
            }

            // ビルトインクラスの有無を確認
            if (class_exists( $builtinPrefix . $ucClassKeyWord)) {
                return $builtinPrefix . $ucClassKeyWord;
            }

            Bshe_Log::logWithFileAndParamsWrite('アサイン用クラスが見つかりません', Zend_Log::DEBUG,
                array('classKeyword' => $classKeyWord, 'prefix' => $prefix, 'path' => $paths, 'builtinPrefix' => $builtinPrefix));
            throw New Bshe_View_Template_Exception_Noassignclass($this->_params['templateFile'], $classKeyWord);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * このテンプレートがキャッシュから取得されたかどうか
     *
     * @return boolean
     */
    public function fromCache()
    {
        return $this->_isCached;
    }

    /**
     * キャッシュ機構がある場合は、この関数をオーバーライドする。
     *
     */
    public function saveCache()
    {
        return ;
    }

    /**
     * 処理対象Elementのキー文字列から処理クラス名、処理メソッド名、引数配列を生成する
     *
     * @param string $id キー文字列
     */
    abstract public function parseId($id);


    /**
     * パースされたIDの配列を取得する
     *
     * @param unknown_type $id
     */
    public function getPasedId($id)
    {
        try {
            if (!isset($this->_arrayParsedId[$id])) {
                $this->parseId($id);
            }
            return $this->_arrayParsedId[$id];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 出力文字列生成
     *
     */
    abstract public function output();


    /**
     * afterAssign処理配列をセット
     *
     * @param unknown_type $arrayAfterAssigns
     */
    public function setAfterAssigns($arrayAfterAssigns = array())
    {
        $this->arrayAfterAssigns = $arrayAfterAssigns;
    }

    /**
     * テンプレート置換処理一式完了後に実施する処理
     *
     * モバイル向けセッション処理などを実装する。
     *
     *
     */
    public function afterAssigns()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_View');

            $template = $this;

            // plugin実行
            if (isset($config->plugin)) {
                $arrayPlugins = $config->plugin->toArray();
            } else {
                $arrayPlugins = array();
            }
            if (isset($arrayPlugins['after'])) {
                foreach ($arrayPlugins['after'] as $pluginClass => $plugins) {
                    foreach ($plugins as $pluginMethod => $yn) {
                        if ($yn == true) {
                            // プラグイン実行
                            $template = call_user_func(array($pluginClass, $pluginMethod), $template);
                        }
                    }
                }
            }

            foreach ($this->_params['afterAssigns'] as $arrayAfterAssign) {
                // アサインクラス実行
                $template = call_user_func(array($arrayAfterAssign['className'], $arrayAfterAssign['methodName']), $template);
            }

            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テンプレート置換処理一式完了前に実施する処理
     *
     * モバイル向けセッション処理などを実装する。
     *
     *
     */
    public function beforeAssigns()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_View');
            $template = $this;

            foreach ($this->_params['beforeAssigns'] as $arrayBeforeAssign) {
                // アサインクラス実行
                $template = call_user_func(array($arrayBeforeAssign['className'], $arrayBeforeAssign['methodName']), $template);
            }

            // plugin実行
            if (isset($config->plugin)) {
                $arrayPlugins = $config->plugin->toArray();
            } else {
                $arrayPlugins = array();
            }
            if (isset($arrayPlugins['before'])) {
                foreach ($arrayPlugins['before'] as $pluginClass => $plugins) {
                    foreach ($plugins as $pluginMethod => $yn) {
                        if ($yn == true) {
                            // プラグイン実行
                            $template = call_user_func(array($pluginClass, $pluginMethod), $template);
                        }
                    }
                }
            }


            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * オフセット値から、テンプレート内の行番号を取得する
     * エラー出力などで利用する。
     *
     * @param integer $offset
     * @return integer
     */
    public function getLineNumber($offset = 0)
    {
    	try{
    		// offset間での文字列を取得
    	    $strTarget = mb_substr ($this->_contents, 0, $offset +1);
    		// strTarget内の改行数をチェック
    		$count = preg_match_all('/\n/', $strTarget, $matches);

    		return $count;
    	} catch (Exception $e) {
    		throw $e;
    	}
    }

}
