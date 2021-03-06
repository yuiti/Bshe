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

/** Bshe_View_Exception */
require_once 'Bshe/View/Exception.php';

/**
 * Bshe_View_Exception
 *
 * テンプレートエンジン用例外クラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.23
 * @license LGPL
 */
class Bshe_View_Template_Exception extends Bshe_View_Exception
{

    /**
     * テンプレート名を引数にしたコンストラクタ
     *
     * @param string $message
     * @param string $templateName
     */
    public function __construct($message = '', $templateName = '')
    {
        $configLanguage = Bshe_Registry_Config::getConfig('Bshe_Language');

    	$message = $configLanguage->Bshe_View_Exception->template_name . ' => ' . $templateName . ': ' . $message;
        parent::__construct($message);
    }
}
