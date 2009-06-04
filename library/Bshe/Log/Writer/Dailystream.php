<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Log
 * @subpackage Log
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_Log_Writer_Stream */
require_once 'Zend/Log/Writer/Stream.php';

/**
 * フォルダを指定して、yyyymmdd.log形式のログを出力するwriter
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.06
 * @license LGPL
 */
class Bshe_Log_Writer_Dailystream extends Zend_Log_Writer_Stream
{
    /**
     * Class Constructor
     *
     * @param string $path ログファイルを保存するパス
     * @param string $suffix ログファイルの頭にセットする文字列
     * @param mode $mode
     */
    public function __construct($path, $suffix = '', $mode = 'a')
    {
        try {
            if(!is_dir($path)) {
                throw New Zend_Log('ログ保存先はディレクトリで指定してください。');
            }

            parent::__construct($path . '/' . $suffix . date('Ymd') . '.log', $mode);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
