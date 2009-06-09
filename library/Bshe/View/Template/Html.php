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

/** Bshe_View_Template_Abstract */
require_once 'Bshe/View/Template/Abstract.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Exception */
require_once 'Bshe/View/Exception.php';
/** Bshe_Dom */
require_once 'Bshe/Dom.php';
/** Bshe_View_Template_Html_Exception_NoIdInHtml */
require_once 'Bshe/View/Template/Html/Exception/NoIdInHtml.php';

/**
 * Bshe_ViewのHTMLテンプレート用のクラス
 *
 * HTMLテンプレート固有の処理を実装する。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.09
 * @license LGPL
 */
class Bshe_View_Template_Html extends Bshe_View_Template_Abstract
{

    /**
     * パースされたXMLオブジェクト
     *
     * @var array
     */
    protected $_xml = array();


    /**
     * 各処理対象Elementのタグ名
     *
     * @var array
     */
    protected $_arrayElementsTagName = array();

    /**
     * HTMLへのデータのアサインは最後にまとめて実施する。
     * ここのResourceクラスからはこの配列に処理内容を登録する形で対応する。
     * （例外的に直接先にテンプレートを処理するものもある）
     *
     * @var array
     */
    protected $_arrayAssigns = array();

    /**
     * HTMLのエンコーディング
     *
     * @var unknown_type
     */
    protected $_encoding = 'utf-8';

    /**
     * HTMLの円コーディング
     */
    protected $_metaTags = array();

    /**
     * キー文字列をパースした配列を保持
     *
     * @var array
     */
    protected $_arrayParsedId = array();


    /**
     * テーブルとして処理される各種情報配列
     *
     * @var array
     */
    protected $_arrayTablesElements = array();

    /**
     * テーブル用のパースした配列を保持
     *
     * @var array
     */
    protected $_arrayTablesPasedIds = array();

    /**
     * テーブルの親テーブルIDリスト
     *
         * @var array
     */
    protected $_arrayTableParents = array();

    /**
     * このクラスがコンパイルされた日時
     *
     * @var int
     */
    protected $_compiledFileTimestamp = null;

     /**
      * コンストラクタ
      *
      * @param array $param 設定配列
      */
     public function __construct($param = array())
     {
         try {
             // 設定配列へ追加
             // HTML上のIDのBshe_View利用頭文字
             $this->_params['id_suffix'] = 'bshe';
             // HTML上のID文字列内でキーワードの境目ashe:aaaの「:」の部分の文字
             $this->_params['id_separator'] = ':';
             // HTML上のID文字列内でHelperキーワードと引数の境目「-」の部分の文字
             $this->_params['helper_separator'] = '-';
             // アサインクラスprefix
             $this->_params['builtinAssignClassPrefix'] = 'Bshe_View_Resource_Html_';
             // ヘルパークラスprefix
             $this->_params['builtinHelperClassPrefix'] = 'Bshe_View_Helper_Html_';
             // キー属性
             $this->_params['key'] = 'key';
             // デフォルトクラスを利用しないタグの配列
             $this->_params['noDefaultTags'] = array(
                 'input' => 'Bshe_View_Template_Html_Tags_Input',
                 'select' => 'Bshe_View_Template_Html_Tags_Select',
                );
             // テーブル扱いクラス
             $this->_params['tableClass'] =
                array(
                    'Bshe_View_Resource_Html_Table',
                    'Bshe_View_Resource_Html_Xtable',
                    'Bshe_View_Resource_Html_Select'
                );
             // テーブル扱いクラス
             $this->_params['templateCompilePath'] = null;

             parent::__construct($param);
         } catch (Exception $e) {
             throw $e;
         }
     }


    /**
     * テンプレートファイルの読み込み
     *
     * テンプレートを読み込んだのち、DOM解析を行う。
     *
     * @param string $fileName テンプレートファイル（指定されない場合はパラメーター設定されている内容を利用）
     * @return string 読み込まれたテンプレート文字列
     */
    public function readTemplateFile($fileName = null)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み開始', Zend_Log::DEBUG,
                array('fileName' => $fileName));

            $fileName = $this->getFileName($fileName);

            // ファイルのコンテンツキャッシュの有無確認
            if ($this->getParam('templateCachePath') != null) {
                $fileDir = dirname($fileName);
                if (!file_exists($this->_params['templateCachePath'] . '/' . $fileDir)) {
                    Bshe_Log::logWithFileAndParamsWrite('キャッシュフォルダ作成', Zend_Log::DEBUG, $this->_params['templateCachePath'] . '/' . $fileDir);
                    if (!mkdir($this->_params['templateCachePath'] . '/' . $fileDir, 0777, true)) {
                        // フォルダ作成失敗
                        Bshe_Log::logWithFileAndParamsWrite('キャッシュフォルダ作成失敗', Zend_Log::ERR, $this->_params['templateCachePath'] . '/' . $fileDir);
                        throw New Bshe_View_Exception('キャッシュフォルダ作成失敗', Zend_Log::ERR);
                    }
                }

                $frontendOptions = array(
                    'lifetime' => $this->getParam( 'templateCacheLifeTime'), // キャッシュの有効期限をなしにする
                    'automatic_serialization' => true,
                    'ignore_user_abort' => true
                    );
                $backendOptions = array(
                    'cache_dir' => $this->_params['templateCachePath'] . '/' . $fileDir // キャッシュファイルを書き込むディレクトリ
                    );
                $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

                if ($template = $cache->load(self::getCacheKeyFromFile(basename($fileName)))) {
                    // キャッシュ読み込み
                    Bshe_Log::logWithFileAndParamsWrite('キャッシュファイル読込み', Zend_Log::DEBUG,
                        array('fileName' => $fileName));
                    $template->_isCached = true;
                    $template->setParams($this->_params);
                    return $template;
                }
            }


            // ファイルのコンパイルキャッシュの有無確認
            if ($this->getParam('templateCompilePath') != null) {
                $fileDir = dirname($fileName);
                if (!file_exists($this->_params['templateCompilePath'] . '/' . $fileDir)) {
                    Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュフォルダ作成', Zend_Log::DEBUG, $this->_params['templateCompilePath'] . '/' . $fileDir);
                    if (!mkdir($this->_params['templateCompilePath'] . '/' . $fileDir, 0777, true)) {
                        // フォルダ作成失敗
                        Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュフォルダ作成失敗', Zend_Log::ERR, $this->_params['templateCompilePath'] . '/' . $fileDir);
                        throw New Bshe_View_Exception('コンパイルキャッシュフォルダ作成失敗', Zend_Log::ERR);
                    }
                }
                $frontendOptions = array(
                    'master_file' => $this->_params['templatePath'] . '/' . $fileName,
//                    'lifetime' => $this->getParam( 'templateCacheLifeTime'), // キャッシュの有効期限をなしにする
                    'automatic_serialization' => true,
                    'ignore_user_abort' => true
                    );
                $backendOptions = array(
                    'cache_dir' => $this->_params['templateCompilePath'] . '/' . $fileDir // キャッシュファイルを書き込むディレクトリ
                    );
                $cache = Zend_Cache::factory('File', 'File', $frontendOptions, $backendOptions);

                if ($template = $cache->load(self::getCacheKeyFromFile(basename($fileName)))) {
                    // キャッシュ読み込み
                    Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュファイル読込み', Zend_Log::DEBUG,
                        array('fileName' => $fileName));
//                    $template->_isCached = true;
                    $template->setParams($this->_params);
                    return $template;
                }
            }

            // テンプレートファイル読み込み
            if (!$this->_isCached) {
                $this->_contents = parent::readTemplateFile($fileName);
            }

            // DOM解析
            $this->parseTemplate($this->_contents);
            // コンパイルキャッシュ保存
            $this->saveCompileCache();

            Bshe_Log::logWithFileAndParamsWrite('テンプレートファイル読込み終了', Zend_Log::DEBUG,
                array('fileName' => $fileName));

            return $this;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * コンパイルキャッシュ機構
     *
     */
    public function saveCompileCache()
    {
        try {
            if ($this->getParam( 'templateCompilePath') != null) {
                $fileName = $this->getFileName();
                $fileDir = dirname($fileName);

                // フォルダ生成
                if (!file_exists($this->_params['templateCompilePath'] . '/' . $fileDir)) {
                    if (!mkdir( $this->_params['templateCompilePath'] . '/' . $fileDir, 0777, true)) {
                        // フォルダの作成失敗
                        Bshe_Log::logWithFileAndParamsWrite('フォルダの作成に失敗しました', Zend_Log::ERR,
                            array('dir' => $this->_params['templateCompilePath'] . '/' . $fileDir));
                        throw New Bshe_View_Exception('フォルダの作成に失敗しました。:: ' . $this->_params['templateCompilePath'] . '/' . $fileDir);
                    }
                }

                $frontendOptions = array(
                    'master_file' => $this->_params['templatePath'] . '/' . $fileName,
                    'automatic_serialization' => true,
                    'ignore_user_abort' => true
                    );
                $backendOptions = array(
                    'cache_dir' => $this->_params['templateCompilePath'] . '/' . $fileDir // キャッシュファイルを書き込むディレクトリ
                    );
                $cache = Zend_Cache::factory('File', 'File', $frontendOptions, $backendOptions);

                $cache->save($this, self::getCacheKeyFromFile(basename($fileName)));
                Bshe_Log::logWithFileAndParamsWrite('コンパイルキャッシュファイル保存', Zend_Log::DEBUG, array('fileName' => $fileName));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * キャッシュ機構がある場合は、この関数をオーバーライドする。
     *
     */
    public function saveCache()
    {
        try {
            if ($this->getParam( 'templateCachePath') != null) {
                $fileName = $this->getFileName();
                $fileDir = dirname($fileName);

                // フォルダ生成
                if (!file_exists($this->_params['templateCachePath'] . '/' . $fileDir)) {
                    if (!mkdir( $this->_params['templateCachePath'] . '/' . $fileDir, 0777, true)) {
                        // フォルダの作成失敗
                        Bshe_Log::logWithFileAndParamsWrite('フォルダの作成に失敗しました', Zend_Log::ERR,
                            array('dir' => $this->_params['templateCachePath'] . '/' . $fileDir));
                        throw New Bshe_View_Exception('フォルダの作成に失敗しました。:: ' . $this->_params['templateCachePath'] . '/' . $fileDir);
                    }
                }

                $frontendOptions = array(
                    'lifetime' => $this->getParam( 'templateCacheLifeTime'), // キャッシュの有効期限をセット
                    'automatic_serialization' => true,
                    'ignore_user_abort' => true
                    );
                $backendOptions = array(
                    'cache_dir' => $this->_params['templateCachePath'] . '/' . $fileDir // キャッシュファイルを書き込むディレクトリ
                    );
                $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

                $cache->save($this, self::getCacheKeyFromFile($fileName));
                Bshe_Log::logWithFileAndParamsWrite('キャッシュファイル保存', Zend_Log::DEBUG, array('fileName' => $fileName));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * $this->_xmlをパースし、テーブルなどの階層構造については
     * 必要な情報を保持する
     *
     */
    public function parseHtml($nodeNumber = null, $currentTableSeq = null, $parentTableName = null)
    {
        try {
            // 子ノードでループして処理
            if ($this->_xml->hasChildNodes($nodeNumber)) {
                $childNumbers = $this->_xml->getChildNumbers($nodeNumber);
            } else {
                // 子ノードがない場合は何もせず戻る
                return;
            }

            $nextParentTableName = $parentTableName;

            foreach ($childNumbers as $elementNumber) {

                // DomTextの場合はスキップ
                if ($this->_xml->elements[$elementNumber]->hasAttribute($this->getParam('key'))) {
                    $id = $this->_xml->elements[$elementNumber]->getAttribute($this->getParam('key'));
                    if (strpos($id, $this->_params['id_suffix']) === 0) {
                        try {
                            // IDパース
                            if (!isset($this->_arrayParsedId[$id])) {
                                $this->parseId($id);
                            }

                            $this->_arrayElements[$id][] = $elementNumber;
                            $this->_arrayElementsTagName[$id][] = $this->_xml->elements[$elementNumber]->nodeName;
                            $seq = count( $this->_arrayElements[$id]) -1;
                            $arrayParsedId = $this->getParsedId($id);
                            // テーブル構造対応処理
                            if (array_search($arrayParsedId['className'], $this->_params['tableClass']) !== false) {
                                // テーブル処理対象クラス
                                switch ($arrayParsedId['methodName']) {
                                    case 'table':
                                    case 'subtable':
                                    case 'select':
                                        // メインのテーブルメソッド
                                        if ($parentTableName != null) {
                                            $parentTableSeq = $currentTableSeq;
                                            $currentTableSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]]);
                                            $curentRowSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row']) -1;
                                            $this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['table'] = $elementNumber;
                                            $this->_arrayTablesPasedIds[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['table'] = $arrayParsedId;
                                            $parentRowSeq = count($this->_arrayTablesElements[$parentTableName][$parentTableSeq]['row']) -1;
                                            $this->_arrayTableParents[$parentTableName][$parentTableSeq][$parentRowSeq][$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]
                                                = $elementNumber;
                                        } else {
                                            $currentTableSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]]);
                                            $curentRowSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row']) -1;
                                            $this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['table'] = $elementNumber;
                                            $this->_arrayTablesPasedIds[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['table'] = $arrayParsedId;
                                        }
                                        $nextParentTableName = $arrayParsedId['arrayMethodParams'][0];
                                        break;
                                    case 'row':
                                    case 'optgroup':
                                        // 1行を示すメソッド
                                        $curentRowSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row']);
                                        $this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row'][$curentRowSeq] = $elementNumber;
                                        $this->_arrayTablesPasedIds[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row'][$curentRowSeq] = $arrayParsedId;
                                        break;
                                    case 'col':
                                    case 'option':
                                        // 1列を示すメソッド
                                        $curentRowSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row']) -1;
                                        if ($arrayParsedId['arrayMethodParams'][1] == '') {
                                            $colName = '_xtable';
                                        } else {
                                            $colName = $arrayParsedId['arrayMethodParams'][1];
                                        }
                                        $this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['cols'][$curentRowSeq][$colName][$seq] = $elementNumber;
                                        $this->_arrayTablesPasedIds[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['cols'][$curentRowSeq][$colName][$seq] = $arrayParsedId;
                                        break;
                                    case 'val':
                                        // 項目を示すメソッド（マトリクス型のみで利用)
                                        $curentRowSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row']) -1;
                                        $curentColSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['cols'][$curentRowSeq]['_xtable']) -1;
                                        $this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['vals'][$curentRowSeq][$curentColSeq][$arrayParsedId['arrayMethodParams'][1]][$seq] = $elementNumber;
                                        $this->_arrayTablesPasedIds[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['vals'][$curentRowSeq][$curentColSeq][$arrayParsedId['arrayMethodParams'][1]][$seq] = $arrayParsedId;
                                        break;
                                    default:
                                        // その他表内の表示を処理するメソッド（将来用）
                                        $curentRowSeq = count($this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['row']) -1;
                                        $this->_arrayTablesElements[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['vals'][$curentRowSeq][$arrayParsedId['methodName']][$seq] = $elementNumber;
                                        $this->_arrayTablesPasedIds[$arrayParsedId['arrayMethodParams'][0]][$currentTableSeq]['vals'][$curentRowSeq][$arrayParsedId['methodName']][$seq] = $arrayParsedId;
                                        break;
                                }
                            }
                        } catch (Bshe_View_Template_Exception_Noassignclass $e) {
                            // パースエラー、次の処理
                            $this->getLogger()->logWithFileAndParams($e->getMessage(), Zend_Log::INFO);
                        } catch (Exception $e) {
                            throw $e;
                        }
                    }
                }
                // メタタグ保持
                if ($this->_xml->elements[$elementNumber]->nodeName == 'meta') {
                    $this->_metaTags[$elementNumber] = $this->_xml->elements[$elementNumber]->getAttribute('http-equiv');
                }
                // 子ノードがある場合は、再帰呼び出し
                if ($this->_xml->hasChildNodes($elementNumber)) {
                    $this->parseHtml($elementNumber, $currentTableSeq, $nextParentTableName);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 再パースするために再帰的にテーブル配列を削除
     *
     * @param integer $tableSeq
     */
    public function clearTable($tableName, $tableSeq, $main = false)
    {
        try {
            if (isset($this->_arrayTablesElements[$tableName][$tableSeq])) {
                // table クリア
                foreach ($this->_arrayTablesElements[$tableName][$tableSeq] as $key => $arrayTable) {
                    if (!($main and ( $key == 'table'))) {
                        // 呼び出しのメインテーブルの場合はテーブルの定義は残す
                        unset($this->_arrayTablesElements[$tableName][$tableSeq][$key]);
                        unset($this->_arrayTablesPasedIds[$tableName][$tableSeq][$key]);
                    }
                }
            }

            // サブテーブルがあればそれをクリア
            if (isset($this->_arrayTableParents[$tableName][$tableSeq]))
            {
                foreach($this->_arrayTableParents[$tableName][$tableSeq] as $arrayTables) {
                    $this->clearTable($arrayTables[0], $arrayTables[1]);
                }
            }

            // parent情報をクリア
            unset($this->_arrayTableParents[$tableName][$tableSeq]);

        } catch(Exception $e) {
            throw $e;
        }
    }


    /**
     * テンプレート文字列のDOM解析
     *
     * @param string $contents HTML文字列
     * @return array Element配列
     */
    protected function parseTemplate($contents)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('テンプレート解析開始', Zend_Log::DEBUG);

            // XML読み込み
            $this->_encoding = mb_detect_encoding($contents, 'ASCII, JIS, UTF-8, EUC-JP, SJIS-win, SJIS');
            $contents = mb_convert_encoding($contents, ini_get('mbstring.internal_encoding'), $this->_encoding);
            $this->_xml = New Bshe_Dom($contents, $this);

            $this->parseHtml();

            Bshe_Log::logWithFileAndParamsWrite('テンプレート解析終了', Zend_Log::DEBUG, array('elements' => count( $this->_arrayElements)));

            return $this->_arrayElements;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テーブル用情報配列をテーブル名を指定して取得する
     *
     * @param string $tableName
     */
    public function getArrayTableElements($tableName, $seq)
    {
        try {
            if (isset($this->_arrayTablesElements[$tableName][$seq])) {
                return $this->_arrayTablesElements[$tableName][$seq];
            } else {
                // 対象テーブル定義なし（通常は起こりえない）
                return array();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * テーブル用パースID配列をテーブル名を指定して取得する
     *
     * @param string $tableName
     */
    public function getArrayTablePasedIds($tableName, $seq)
    {
        try {
            if (isset($this->_arrayTablesPasedIds[$tableName][$seq])) {
                return $this->_arrayTablesPasedIds[$tableName][$seq];
            } else {
                // 対象テーブル定義なし（通常は起こりえない）
                return array();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * テーブルを指定して、子テーブルの配列を取得
     *
     * @param string $tableName
     * @param integer $seq
     * @return array
     */
    public function getArrayTableChildren($tableName, $seq)
    {
        try {
            if (isset($this->_arrayTableParents[$tableName][$seq])) {
                return $this->_arrayTableParents[$tableName][$seq];
            } else {
                return array();
            }

        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * IDからTAG名称取得
     *
     * @param string $id
     * @return string タグ名
     */
    public function getTagNameById($id, $seq)
    {
        try {
            if (isset($this->_arrayElementsTagName[$id][$seq])) {
                return $this->_arrayElementsTagName[$id][$seq];
            } else {
                Bshe_Log::logWithFileAndParamsWrite('対象のIDがHTMLテンプレート上に見つかりません。', Zend_Log::DEBUG,
                    array('対象' . $this->getParam( 'key') => $id, '対象seq' => $seq));
                throw New Bshe_View_Template_Html_Exception_NoIdInHtml($this->getTemplateFileName(), $id, $seq);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * パースされたキー文字列の
     * 情報配列を取得する。
     *
     * @param unknown_type $id
     */
    public function getParsedId($id)
    {
        try {
            if (!isset( $this->_arrayParsedId[$id])) {
                return $this->parseId( $id);
            } else {
                return $this->_arrayParsedId[$id];
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 処理対象Elementのキー文字列から処理クラス名、処理メソッド名、引数配列を生成する
     *
     * @param string $id キー文字列
     */
    public function parseId($id)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('ID解析開始', Zend_Log::DEBUG, array('id' => $id));

            // ID分割
            $arraySplitId = split($this->_params['id_separator'], $id);
            // 戻り値配列
            $arrayResult = array();
            $arrayResult['originalId'] = $id;

            // 項目が3つしかない場合、単純出力として処理
            if (count( $arraySplitId) == 3) {
                // クラス、メソッド、引数
                $arrayResult['className'] = $this->getAssingClass('show');
                $arrayResult['methodName'] = 'preassignvalue';
                $arrayResult['arrayMethodParams']['valuekey'] = $arraySplitId[2];
                // ヘルパー
                if ($arraySplitId[1] != '') {
                    $arraySplitHelper = split( $this->_params['helper_separator'], $arraySplitId[1]);
                    $arrayResult['helperName'] = $this->getHelperClass( $arraySplitHelper[0]);
                    for ($i=1; $i<count($arraySplitHelper); $i++) {
                        $arrayResult['helperParams'][] = $arraySplitHelper[$i];
                    }
                }
            } else {
                // その他の場合

                // 0は識別文字のため無視
                // 1はHelper
                if ($arraySplitId[1] != '') {
                    $arraySplitHelper = split($this->_params['helper_separator'], $arraySplitId[1]);
                    $arrayResult['helperName'] = $this->getHelperClass($arraySplitHelper[0]);
                    $arrayResult['helperParams'] = $arraySplitHelper;
                }
                // 2は処理クラス
                $arrayResult['className'] = $this->getAssingClass($arraySplitId[2]);
                // 3は処理メソッド
                $arrayResult['methodName'] = $arraySplitId[3];
                // 4以降は引数
                if (count( $arraySplitId) >4) {
                    for ($i=4; $i<count( $arraySplitId); $i++) {
                        $arrayResult['arrayMethodParams'][] = $arraySplitId[$i];
                    }
                }
            }
            Bshe_Log::logWithFileAndParamsWrite('ID解析終了', Zend_Log::DEBUG,
                array('id' => $id, 'className' => $arrayResult['className'], 'methodName' => $arrayResult['methodName'], 'arrayMethodParams' => $arrayResult['arrayMethodParams']));

            $this->_arrayParsedId[$id] = $arrayResult;

            return $arrayResult;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * タグ名から、処理するタグクラスを取得する
     *
     * @param string $tagName タグ名
     * @return string タグクラス名
     */
    public function getTagClass( $tagName)
    {
        if (isset($this->_params['noDefaultTags'][ $tagName])) {
            return $this->_params['noDefaultTags'][ $tagName];
        } else {
            return 'Bshe_View_Template_Html_Tags_Default';
        }
    }

    /**
     * 要素IDから、DOMElementクラスを取得
     *
     * @param string $id
     * @return DOMElement
     */
    public function getElementById($id, $seq)
    {
        try {

            if (isset($this->_arrayElements[$id][$seq])) {
                return $this->_arrayElements[$id][$seq];
            } else {
                Bshe_Log::logWithFileAndParamsWrite('対象のIDがHTMLテンプレート上に見つかりません。', Zend_Log::DEBUG,
                    array('対象' . $this->getParam( 'key') => $id, '対象seq' => $seq));
                throw New Bshe_View_Template_Html_Exception_NoIdInHtml($this->getTemplateFileName(), $id, $seq);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * assign配列へ値を追加
     *
     * @param array $arrayAssign
     */
    public function addArrayAssigns($arrayAssign)
    {
        try {
            $this->_arrayAssigns[] = $arrayAssign;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 出力文字列生成
     *
     */
    public function output($elementId = null)
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('html生成開始', Zend_Log::DEBUG);

            // HTML文字列生成
            if ($elementId == null) {
                $strHTML = $this->_xml->saveHTML();
            } else {
                // DOM Elementを指定してのHTML生成
                $strHTML = $this->_xml->getElement($elementId)->saveHTML($this->_xml);
            }

            // 文字列変換
            $strHTML = mb_convert_encoding($strHTML, $this->_encoding);
            foreach ($this->_metaTags as $element => $val) {
                if ($val == 'content-type') {
                    if ($this->_xml->elements[$element]->hasAttribute('content')) {
                        // コンテントタイプ出力
                        header('Content-Type: ' . $this->_xml->elements[$element]->getAttribute('content'));
                    }
                }
            }

            // 後方互換モード回避
            // libxml2.62移行はオプションで対応可能
            $ua = $_SERVER['HTTP_USER_AGENT'];
            if (!(ereg("Windows",$ua) && ereg("MSIE",$ua)) || ereg("MSIE 7",$ua)) {
                // IE以外はそのまま
            } else {
                $strHTML = mb_ereg_replace('^\<\?xml.*\?\>\n', '', $strHTML);
            }

            Bshe_Log::logWithFileAndParamsWrite('html生成終了', Zend_Log::DEBUG);

            return $strHTML;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * javascript設定など、最後にしか実施できないものについて
     * 対応する。
     *
     */
    public function afterAssigns()
    {
        try {
            Bshe_Log::logWithFileAndParamsWrite('afterアサイン開始', Zend_Log::DEBUG);

            return parent::afterAssigns();


            Bshe_Log::logWithFileAndParamsWrite('afterアサイン終了', Zend_Log::DEBUG);
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * ファイル名から、キャッシュ用の文字列を生成する
     * aaaa/BbbBb.htmlをaaaaBbbbbHtmlと変化させる
     *
     * @param unknown_type $file
     */
    public static function getCacheKeyFromFile($file)
    {
        try {
            $strResult = '';
            $splited = split('[^a-zA-Z0-9]', $file);
            foreach ($splited as $val) {
                $strResult .= ucfirst($val);
            }
            return $strResult;

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * DOM内のノードからノード名を取得する
     *
     * @param integer $nodeNumber
     */
    public function getNodeName($nodeNumber)
    {
        try {

            if (isset($this->_xml->elements[$nodeNumber])) {
                return $this->_xml->elements[$nodeNumber]->nodeName;
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $nodeNumber);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードのinnerHTMLをセットする
     *
     * @param integer $nodeNumber
     * @param string $nodeValue
     */
    public function setNodeValue($nodeNumber, $nodeValue, $reParse = false, $clearChild = true)
    {
        try {

            if (isset($this->_xml->elements[$nodeNumber])) {
                $this->_xml->elements[$nodeNumber]->setNodeValue($nodeValue, $reParse, $clearChild);
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $nodeNumber);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードの属性をセットする
     *
     * @param integer $nodeNumber
     * @param string $key
     * @param string $value
     */
    public function setAttribute($nodeNumber, $key, $value)
    {
        try {

            if (isset($this->_xml->elements[$nodeNumber])) {
                $this->_xml->elements[$nodeNumber]->setAttribute($key, $value);
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $nodeNumber);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードの属性を取得する
     *
     * @param integer $nodeNumber
     * @param string $key
     * @param string $value
     */
    public function getAttribute($nodeNumber, $key)
    {
        try {

            if (isset($this->_xml->elements[$nodeNumber])) {
                return $this->_xml->elements[$nodeNumber]->getAttribute($key);
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $nodeNumber);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードの属性を取得する
     *
     * @param integer $nodeNumber
     * @param string $key
     * @param string $value
     */
    public function hasAttribute($nodeNumber, $key)
    {
        try {

            if (isset($this->_xml->elements[$nodeNumber])) {
                return $this->_xml->elements[$nodeNumber]->hasAttribute($key);
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $nodeNumber);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * ノードの属性を削除
     *
     * @param integer $nodeNumber
     * @param string $key
     */
    public function removeAttribute($nodeNumber, $key)
    {
        try {
            if (isset($this->_xml->elements[$nodeNumber])) {
                $this->_xml->elements[$nodeNumber]->removeAttribute($key);
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $nodeNumber);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードをコピーして特定のノードの前に挿入
     *
     * @param integer $copyNodeNumber
     * @param integer $insertBeforeNodeNumber
     */
    public function cloneNodeBefore($copyNodeNumber, $beforeNodeNumber)
    {
        try {
            if (!isset($this->_xml->elements[$copyNodeNumber])) {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $copyNodeNumber);
            }
            if (!isset($this->_xml->elements[$beforeNodeNumber])) {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $beforeNodeNumber);
            }
            // ノードコピークラス取得
            $arrayNodes = $this->_xml->elements[$copyNodeNumber]->cloneNode($this->_xml);
            // ノードをinsert
            $this->_xml->insertBefore($arrayNodes, $beforeNodeNumber);

            return $arrayNodes;

        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * 新しいノードを登録
     *
     * @param $node
     * @return integer ノード番号
     */
    public function newNode($node)
    {
        try {
            return $this->_xml->newNode($node);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードを指定したノードの前に登録
     * ※既存設定の削除には対応していないため、
     * 　すでに何かの子ノードとなっている場合は、
     * 　再帰的に利用される
     *
     * @param $arrayNodes
     * @param $beforeNodeNumber
     * @return unknown_type
     */
    public function insertBefore($arrayNodes, $beforeNodeNumber)
    {
        try {
            if (is_array($arrayNodes)) {
                $this->_xml->insertBefore($arrayNodes, $beforeNodeNumber);
            } else {
                $arrayTmp = array();
                $arrayTmp['node'] = $arrayNodes;
                $this->_xml->insertBefore($arrayTmp, $beforeNodeNumber);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードの新規追加
     *
     * @param Bshe_Dom_Node_Abstract $insertNode
     * @param integer $afterNodeNumber
     */
    public function addChild($insertNodeClass, $parentNumber)
    {
        try {
            return $this->_xml->addChild($insertNodeClass, $parentNumber);
        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * 指定のノードを削除
     *
     * @param integer $targetNodeNumber
     */
    public function removeNode($targetNodeNumber)
    {
        try {
            if (!isset($this->_xml->elements[$targetNodeNumber])) {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $targetNodeNumber);
            }
            $this->_xml->removeNode($targetNodeNumber);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * DOMのelementリストを取得する
     *
     * 速度対処のため、$fromTreeがfalseの場合は、_treeではなく、
     * 削除されたノードも含む$elementsが返される
     *
     */
    public function getElementNumbers($fromTree = false)
    {
        try {
            return $this->_xml->getElements();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * DOMのelementを取得する
     *
     */
    public function getElementNumber($key)
    {
        try {
            return $this->_xml->getElement($key);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードの値を取得する
     * （子ノードはsaveHTMLする）
     *
     * @param $elementId
     * @return unknown_type
     */
    public function getNodeValue($elementId)
    {
        try {
            if (isset($this->_xml->elements[$elementId])) {
                $element = $this->getElementNumber($elementId);
                return $this->_xml->elements[$elementId]->getNodeValue($this->_xml);
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $elementId);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Nodeを指定してHTMLを出力
     *
     * @param $elementId
     * @return unknown_type
     */
    public function saveHTML($elementId)
    {
        try {
        if (isset($this->_xml->elements[$elementId])) {
                $element = $this->getElementNumber($elementId);
                return $this->_xml->elements[$elementId]->saveHTML($this->_xml);
            } else {
                throw New Bshe_View_Template_Html_Exception_NoNode($this->getTemplateFileName(), $elementId);
            }
        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * 特定のタグから親をたどり、
     * 親に指定されたタグがあるかを検索する。
     *
     * @param $elementId
     * @param $targetTagName
     * @return unknown_type
     */
    public function searchParentTag($elementId, $targetTagName)
    {
        try {
            return $this->_xml->searchParentTag($elementId, $targetTagName);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * タグ名からElementを取得
     * 同じタグが複数ある場合は最初に見つかったタグを返す
     *
     * @param $tagName
     * @return unknown_type
     */
    public function getElementByName($tagName)
    {
        try {
            $elements = $this->getElementNumbers();
            $targetElement = null;
            foreach ($elements as $key => $element) {
                $nodeName = $element->nodeName;
                if ($nodeName == $tagName) {
                    // header発見
                    $targetElement = $key;
                    break;
                }
            }
            if ($targetElement === null) {
                // headerが見つからない
                $this->getLogger()->logWithFileAndParams($tagName . 'タグが見つかりません。', Zend_Log::INFO);
                return false;
            } else {
                return $targetElement;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * タグ名からElementを取得
     * 同じタグが複数ある場合は最初に見つかったタグを返す
     *
     * @param $tagName
     * @return unknown_type
     */
    public function getElementsByName($tagName)
    {
        try {
            $elements = $this->getElementNumbers();
            $targetElement = array();
            foreach ($elements as $key => $element) {
                $nodeName = $element->nodeName;
                if ($nodeName == $tagName) {
                    // header発見
                    $targetElement[] = $key;
                }
            }
            if ($targetElement == array()) {
                // headerが見つからない
                $this->getLogger()->logWithFileAndParams($tagName . 'タグが見つかりません。', Zend_Log::INFO);
                return false;
            } else {
                return $targetElement;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
