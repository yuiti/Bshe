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

/** Bshe_View_Resource_Exception */
require_once 'Bshe/View/Resource/Exception.php';

/**
 * Bshe_View_Resource_Exception_Novalue
 *
 * 設定対象となる値がない場合の例外
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_View_Resource_Exception_Novalue extends Bshe_View_Resource_Exception
{
    /**
     * テンプレート名を引数にしたコンストラクタ
     *
     * @param string $message
     * @param string $templateName
     */
    public function __construct($templateName = '', $key = '', $seq = null)
    {
        $message = 'セットするpresetvalueが登録されておりません。';
        parent::__construct($message, $templateName, $key, $seq=0);
    }
}
