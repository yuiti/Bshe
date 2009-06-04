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

/** Bshe_View_Template_Exception_Nostop */
require_once 'Bshe/View/Template/Exception/Nostop.php';

/**
 * Ashe_View_Template_Exception_Noassignclass
 *
 * テンプレートエンジン用例外クラス
 * アサイン用クラスが見つからない場合の例外
 *
 * @todo アサイン失敗ログを詳細作成する
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_View_Template_Exception_Nostop_Noassignclass extends Bshe_View_Template_Exception_Nostop
{
    /**
     * テンプレート名を引数にしたコンストラクタ
     *
     * @param string $message
     * @param string $templateName
     */
    public function __construct($templateName = '', $classKeyWord = '')
    {
        $message = 'アサイン用クラスが見つかりません: クラスキーワード => ' . $classKeyWord;
        parent::__construct($message, $templateName);
    }
}




