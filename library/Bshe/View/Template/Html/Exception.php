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

/** Bshe_View_Template_Exception */
require_once 'Bshe/View/Template/Exception.php';

/**
 * Ashe_View_Template_Html_Exception
 *
 * HTMLテンプレート独自の例外
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_View_Template_Html_Exception extends Bshe_View_Template_Exception
{
    /**
     * テンプレート名、offsetを引数にしたコンストラクタ
     *
     * @param string $message
     * @param string $templateName
     */
    public function __construct($message = '', $templateName = '')
    {
        parent::__construct($message, $templateName);
    }
}




