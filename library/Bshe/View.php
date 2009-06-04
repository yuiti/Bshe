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
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_View_Interface */
require_once 'Zend/View/Interface.php';
/** Bshe_View_Engine */
require_once 'Bshe/View/Engine.php';

/**
 * HTMLファイルをそのまま読み込んで処理できるテンプレートエンジンを利用したViewクラス
 * HTMLのID属性を利用して各値を設定できる
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.09
 * @license LGPL
 */
class Bshe_View implements Zend_View_Interface
{
    /**
     * エンジンクラス
     *
     * @var unknown_type
     */
    protected $_engine;

    /**
     * ログクラスをスタティックに登録
     *
     * @param unknown_type $logger
     */
    public function setLogger($logger)
    {
        $this->_engine->setLogger($logger);
    }

    /**
     * ロガー取得
     *
     * @return unknown
     */
    public function getLogger()
    {
        return $this->_engine->getLogger();
    }

    /**
     * ログクラスがセットされているか確認
     *
     * @return unknown_type
     */
    public function hasLogger()
    {
        return $this->_engine->getLogger();
    }

    /**
     * コンストラクタ
     *
     */
    public function __construct( $config = array())
    {
        try {
            // Bshe_Viewクラスインスタンス化
            $this->_engine = New Bshe_View_Engine( $config);
        }
        catch( Exception $e)
        {
            throw $e;
        }
    }


    /**
     * Return the template engine object, if any
     *
     * If using a third-party template engine, such as Smarty, patTemplate,
     * phplib, etc, return the template engine object. Useful for calling
     * methods on these objects, such as for setting filters, modifiers, etc.
     *
     * @return mixed
     */
    public function getEngine()
    {
        return $this->_engine;
    }

    /**
     * Set the path to find the view script used by render()
     *
     * HTMLテンプレートのパスの設定
     *
     * @param string|array The directory (-ies) to set as the path. Note that
     * the concrete view implentation may not necessarily support multiple
     * directories.
     * @return void
     */
    public function setScriptPath($path)
    {
        try {
            if( is_array( $path))
            {
                Bshe_Log::logWithFileAndParamsWrite( 'テンプレートパス指定で配列は利用できません', Zend_Log::ERR, array( 'path' => $path));
                throw New Bshe_View_Exception( 'Bshe_Viewではテンプレートパス指定で配列は利用できません');
            }

            $this->_engine->setParams( array( 'templatePath' => $path));
        }
        catch( Exception $e)
        {
            throw $e;
        }

    }

    /**
     * Retrieve all view script paths
     *
     * HTMLテンプレートパス
     *
     * @return array
     */
    public function getScriptPaths()
    {
        return $this->_engine->getParams( 'templatePath');
    }

    /**
     * Set a base path to all view resources
     *
     * リソースクラスパスセット
     *
     * @param  string|array $path
     * @param  string $classPrefix これより後ろを_で区切ってパスとしても認識する
     * @return void
     */
    public function setBasePath($path, $classPrefix = 'Bshe_View_Resource')
    {
        try {
            // パスセット
            $this->_engine->setParams( array( 'assignClassPath' => $path));
            // prefixセット
            $this->_engine->setParams( array( 'assignClassPrefix' => $classPrefix));
        }
        catch( Exception $e)
        {
            throw $e;
        }
    }


    /**
     * Add an additional path to view resources
     *
     * リソースクラスパス追加
     *
     * @param  string $path
     * @param  string $classPrefix
     * @return void
     */
    public function addBasePath($path, $classPrefix = 'Bshe_View_Resource')
    {
        try {
            // パスセット
            $tmpPath = $this->_engine->getParams( 'assignClassPath');
            if( ( is_array( $tmpPath)) or ( $tmpPath == null))
            {
                // 配列に追加
                $tmpPath[] = $path;
            }
            else
            {
                // 配列として保存し直し
                $tmpPath[] = $tmpPath;
                $tmpPath[] = $path;
            }
            $this->_engine->setParams( array( 'assignClassPath' => $tmpPath));

            // prefixセット
            $this->_engine->setParams( array( 'assignClassPrefix' => $classPrefix));
        }
        catch( Exception $e)
        {
            throw $e;
        }
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
        $this->_engine->__set( $key, $val);
    }

    /**
     * Allows testing with empty() and isset() to work
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->_engine->__isset( $key);
    }

    /**
     * Allows unset() on object properties to work
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        if( isset( $this->_engine->_presetValues[$key]))
        {
            unset( $this->_engine->_presetValues[$key]);
        }
    }

    /**
     * Assign variables to the view script via differing strategies.
     *
     * Suggested implementation is to allow setting a specific key to the
     * specified value, OR passing an array of key => value pairs to set en
     * masse.
     *
     * @see __set()
     * @param string|array $spec The assignment strategy to use (key or array of key
     * => value pairs)
     * @param mixed $value (Optional) If assigning a named variable, use this
     * as the value.
     * @return void
     */
    public function assign($spec, $value = null)
    {
        try {
            if( is_array( $spec))
            {
                foreach( $spec as $key => $value)
                {
                    $this->__set( $key, $value);
                }
            }
            else
            {
                $this->__set( $spec, $value);
            }

        }
        catch( Exception $e)
        {
            throw $e;
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
        $this->_engine->clearVars();
    }

    /**
     * Processes a view script and returns the output.
     *
     * @param string $name The script script name to process.
     * @return string The script output.
     */
    public function render($name)
    {
        try {
            // ファイルが指定されている場合は、ファイル名をエンジンにセット
            if( $name != null)
            {
                $this->_engine->setParams( array( 'templateFile' => $name));
            }
            // レンダリング実行
            return $this->_engine->render();
        }
        catch( Exception $e)
        {
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
            return $this->_engine->getTemplatePluginFlags();
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
            return $this->_engine->setTemplatePluginFlags($arrayPluginFlags);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
