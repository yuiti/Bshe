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

/** Bshe_Dom_Node_Abstract */
require_once 'Bshe/Dom/Node/Abstract.php';

/**
 * HTMLのText
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.02
 * @license LGPL
 */
class Bshe_Dom_Node_Text extends Bshe_Dom_Node_Abstract
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


    /**
     * 子ノード追加
     * 子ノードを持たないため処理スキップ
     *
     * @param unknown_type $domChiled
     * @param unknown_type $targetNumber
     */
    public function addChiled($domChiled, $targetNumber = null)
    {
    }


    /**
     * 指定のNodeの前に子ノードを挿入
     *
     * 子ノードを持たないため処理スキップ
     *
     * @param Bshe_View_Template_Html_Dom_Abstract $newNode
     * @param integer $targetNode
     */
    public function insertBefore($newNode, $targetNode = null)
    {
    }

    /**
     * HTML文字列生成
     *
     */
    public function saveHTML($dom)
    {
        try {
            return $this->_nodeValue;
        } catch (Exception $e)
        {
            throw $e;
        }
    }

}
