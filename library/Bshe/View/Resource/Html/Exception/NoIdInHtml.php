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

/** Bshe_View_Resource_Html_Exception */
require_once 'Bshe/View/Resource/Html/Exception.php';

/**
 * Bshe_View_Resource_Html_Exception_NoIdInHtml
 *
 * Keyを指定しての処理で、対象のKeyがHTMLテンプレート上に見つからない場合
 *
 * @todo アサイン失敗ログを詳細作成する
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_View_Resource_Html_Exception_NoIdInHtml extends Bshe_View_Resource_Html_Exception
{
    /**
     * テンプレート名を引数にしたコンストラクタ
     *
     * @param string $message
     * @param string $templateName
     */
    public function __construct($templateName = '', $key = '', $seq=0)
    {
        $message = 'タグが見つかりません';

        parent::__construct($message, $templateName, $key, $seq);
    }

}




