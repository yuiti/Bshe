<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Function
 * @subpackage
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

class Bshe_Function_ArrayFunction
{

}

/**
 * 配列に対して再帰的に文字コード変換を行うための関数
 * @param $item
 * @param $key
 * @param $arrayConfig
 * @return unknown_type
 */
function arrayMbConvertEncoding(&$item, $key, $arrayConfig)
{
    $toEncoding = $arrayConfig[0];
    $fromEncoding = $arrayConfig[1];
    if (is_string($item)) {
        $item = mb_convert_encoding($item, $toEncoding, $fromEncoding);
    }

}