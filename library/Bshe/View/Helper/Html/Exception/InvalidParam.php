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

/** Bshe_View_Helper_Html_Exception */
require_once 'Bshe/View/Helper/Html/Exception.php';

/**
 * Bshe_View_Helper_Html_Exception
 *
 * テンプレートエンジンのHTMLヘルパークラス用例外
 * 通常この例外はキャッチされ、ログの記録が行われるのみで処理は停止しない。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 *
 */
class Bshe_View_Helper_Html_Exception_InvalidParam extends Bshe_View_Helper_Html_Exception
{
    /**
     * テンプレート名を引数にしたコンストラクタ
     *
     * @param string $message
     * @param string $templateName
     */
    public function __construct($templateName = '', $helperName='', $paramNum=1, $paramValue = '')
    {
        $message = 'ヘルパーパラメーターの指定が不正です。パラメーター番号: ' . $paramNum . '/ パラメーター値: ' . $paramValue;
        parent::__construct($message, $templateName, $helperName);
    }
}
