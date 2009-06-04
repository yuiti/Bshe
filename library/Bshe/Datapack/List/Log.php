<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Datapack
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Datapack_List_Abstract */
require_once 'Bshe/Datapack/List/Abstract.php';

/**
 * ログファイルの内容を出力するリストデータパック
 *
 * ログファイル形式は　[接頭文字]yyyymmdd.log　とする。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.22
 * @license LGPL
 */
abstract class Bshe_Datapack_List_Log extends Bshe_Datapack_List_Abstract
{

    /**
     * ログ記録パス
     * @var string
     */
    protected $_logPath = "";

    /**
     * ログファイル名の接頭文字
     *
     * @var string
     */
    protected $_prefix = "";

    /**
     * ログを分割する文字列
     *
     */
    protected $_sep = "";

    /**
     * 取得されたログデータ
     *
     * @var array
     */
    protected $_arrayLogData = array();

    /**
     * データを取得するリソースの定義などを行う。
     *
     * @return unknown_type
     */
    public function __construct($logPath = null, $prefix = 'BsheView_', $sep = ': ')
    {
        try {
            // $logPathがnullの場合は、デフォルトのログパス
            if ($logPath == null) {
                $this->_logPath = Bshe_Controller_Init::getMainPath() . '/logs';
            } else {
                $this->_logPath = $logPath;
            }
            $this->_prefix = $prefix;
            $this->_sep = $sep;

        } catch (Exception $e) {
            throw $e;
        }
    }



    public function clearList()
    {
        $this->_arrayLogData = array();
    }


    protected function getArrayLog($minYmd = 0, $maxYmd = 99999999)
    {
        try {

            $arrayTargetFiles = array();
            $dirs = scandir($this->_logPath, 1);
            foreach ($dirs as $key => $fileName) {
                if(ereg ($this->_prefix . "([0-9]{8})\.log", $fileName, $regs)) {
                    // 一致
                    if (($minYmd <= $regs[1]) and ($maxYmd >= $regs[1])) {
                        $this->readLogFile($fileName);
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ログファイルを読み込みチェックして
     * 通ったもののみを配列として返す
     * データは「:」で分割する
     *
     * @param $fileName
     * @return unknown_type
     */
    protected function readLogFile($fileName)
    {
        try {
            $fp = fopen($this->_logPath . '/' . $fileName, 'r');
            if ($fp) {
                while (!feof($fp)) {
                    $strBuf = fgets($fp);
                    if ($this->checkLogString($strBuf)) {
                        // 対象
                        $this->_arrayLogData[] = $strBuf;
                    }
                }
            }
            fclose($fp);

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 必要に応じてオーバーライドする
     * 出力対象のログかどうかを判別するロ
     *
     * @param $strLog
     * @return unknown_type
     */
    protected function checkLogString($strLog)
    {
        return true;
    }
}