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

/** Zend_Log_Writer_Abstract */
require_once 'Zend/Log/Writer/Abstract.php';
/** Zend_Exception */
require_once 'Zend/Exception.php';

/** Bshe_Log_Formatter_Syslog */
require_once 'Bshe/Log/Formatter/Syslog.php';

/**
 * Syslogへログ保存するZend_Log用writer
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.06
 * @license LGPL
 */
class Bshe_Log_Writer_Syslog extends Zend_Log_Writer_Abstract
{
    /**
     * ログ接続処理用設定値
     *
     * @var unknown_type
     */
    protected $arrayOptions = array();

    /**
     * syslog facility
     *
     * @var unknown_type
     */
    protected $facility = null;

    /**
     * syslog option
     *
     * @var unknown_type
     */
    protected $option = 0;

    /**
     * syslog ident
     *
     * @var unknown_type
     */
    protected $ident = 'Bshe_Log_Writer_Syslog';

    /**
     * Class Constructor
     *
     * openlogの引数や、facilityの設定などを行う。
     *
     * @param string $ident syslog関数のident（未設定時は'Bshe_Log_Writer_Stream'）
     * @param int $option syslog関数のoption
     * @param int $facility sylog関数のfacility（未設定時はLOG_USER）
     */
    public function __construct($ident = 'Bshe_Log_Writer_Syslog', $option = 0, $facility = null)
    {
        // syslog初期化
        define_syslog_variables();
        // $facilityNULL時に初期値を設定
        if( $facility === null) {
            $this->facility = LOG_USER;
        } else {
            $this->facility = $facility;
        }

        $this->option = $option;
        $this->ident = $ident;


        // openlog
        if (!openlog($ident, $option, $facility)) {
            // ログエラー
            throw new Zend_Log_Exception("syslogを開けません");
        }

        // syslogへ_writeする専用のformatterを用意
        $this->_formatter = new Bshe_Log_Formatter_Syslog();
    }

    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        define_syslog_variables();
        $line = $this->_formatter->format($event);

        if (!openlog($this->ident, $this->option, $this->facility)) {
            // ログエラー
            throw new Zend_Log_Exception("syslogを開けません");
        }

        if (false === syslog($event['priority'] , $line)) {
            throw new Zend_Log_Exception("syslogへ書き込みできません: ");
        }

        closelog();
    }
}
