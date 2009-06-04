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

/** Zend_Log_Formatter_Simple */
require_once 'Zend/Log/Formatter/Simple.php';

/**
 * Syslogへログ保存するZend_Log用formatter
 *
 * syslogではメッセージにtimestampやpriorityを出力する必要がないので
 * デフォルトのフォーマットを変更しておく
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.06
 * @license LGPL
 */
class Bshe_Log_Formatter_Syslog extends Zend_Log_Formatter_Simple
{
    const DEFAULT_FORMAT = '%message%';
}
