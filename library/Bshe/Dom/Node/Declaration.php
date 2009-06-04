<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Dom
 * @subpackage Dom
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Dom_Node_Text */
require_once 'Bshe/Dom/Node/Text.php';

/**
 * HTMLのXML宣言部
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.02
 * @license LGPL
 */
class Bshe_Dom_Node_Declaration extends Bshe_Dom_Node_Text
{

    /**
     * 自身のタグを含んだ文字列を引数にしたコンストラクタ
     * 子供は原則いないため登録のみ
     *
     * @param string $nodeValue
     */
    public function __construct($nodeValue)
    {
        try {
            parent::__construct($nodeValue);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
