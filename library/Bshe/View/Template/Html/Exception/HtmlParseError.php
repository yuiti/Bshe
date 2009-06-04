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

/** Bshe_View_Template_Html_Exception */
require_once 'Bshe/View/Template/Html/Exception.php';

/**
 * Bshe_View_Template_Html_Dom_Exception
 *
 * HTMLパーサーのエラー
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_View_Template_Html_Exception_HtmlParseError extends Bshe_View_Template_Html_Exception
{
    /**
     * テンプレート名、offsetを引数にしたコンストラクタ
     *
     * @param string $message
     * @param string $templateName
     */
    public function __construct($message = '', $template = null, $offset = 0)
    {
        // offsetから行番号を入手して例外文字列を生成する。
        if ($template !== null) {
            $lineNumber = $template->getLineNumber($offset);
            $message = '行番号 => ' . $lineNumber . ': HTMLの解析に失敗: ' . $message;
            parent::__construct($message, $template->getTemplateFileName());
        } else {
            $message = 'HTMLの解析に失敗: ' . $message;
            parent::__construct($message, $template);
        }

    }
}




