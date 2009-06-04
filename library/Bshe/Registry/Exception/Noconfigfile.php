<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Registry
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Registry_Exception */
require_once 'Bshe/Registry/Exception.php';

/**
 * Bsheレジストリ用のExceptionクラス
 * コンフィグファイルが指定されていない場合の例外
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.19
 * @license LGPL
 */
class Bshe_Registry_Exception_Noconfigfile extends Bshe_Registry_Exception
{

}