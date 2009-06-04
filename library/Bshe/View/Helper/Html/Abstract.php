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

/** Bshe_View_Helper_Abstract */
require_once 'Bshe/View/Helper/Abstract.php';

/**
 * Bshe_View_Helper_Abstract
 *
 * HTMLテンプレート用ヘルパーの抽象クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
abstract class Bshe_View_Helper_Html_Abstract extends Bshe_View_Helper_Abstract
{

    /**
     * ヘルパー処理
     *
     *
     * @param array $values
     */
    abstract static public function setView($values);

}
