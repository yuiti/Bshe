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

/** Bshe_View_Resource_Html_Abstract */
require_once 'Bshe/View/Resource/Html/Abstract.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Resource_Exception_Novalue */
require_once 'Bshe/View/Resource/Exception/Novalue.php';

/**
 * Application_Resource_Helloworld
 *
 * サンプルresourceクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.05.02
 * @license LGPL
 */
class Application_Resource_Helloworld extends Bshe_View_Resource_Html_Abstract
{

    static public function assignValuesTest($arrayParams)
    {
        try {
            $arrayParams =
                array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'bshe_innerHTML',
                            1 => 'hello world',
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
            $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $assign);

            return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
