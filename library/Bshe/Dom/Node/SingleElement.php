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
 * HTMLの終了タグのない単独element
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.02
 * @license LGPL
 */
class Bshe_Dom_Node_SingleElement extends Bshe_Dom_Node_Abstract
{



    /**
     * 自身のタグを含んだ文字列を引数にしたコンストラクタ
     *
     *
     * @param string $nodeValue
     */
    public function __construct($nodeValue, $tagName)
    {
        try {
            // タグ名
            $this->nodeName = $tagName;
            // 属性抽出
            if ($nodeValue != '') {
                $this->parseAttribute($nodeValue);
            }
            $this->_nodeValue = '';
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * HTML文字列生成
     *
     */
    public function saveHTML($dom = null)
    {
        try {
            // 自身の開始ヘッダー生成
            $strHtml = '<' . $this->nodeName . ' ';
            // 属性生成
            foreach ($this->_attributes as $key => $value) {
                $strHtml .= $key . '=' . '"' . $value . '" ';
            }
            // nodeValueの有無確認
            if ($this->_nodeValue ==  '') {
                $strHtml = trim($strHtml) . ' />';
            } else {
                $strHtml = trim($strHtml) . ' >' . $this->_nodeValue . '/' . $this->nodeName . '>';
            }


            return $strHtml;

        } catch (Exception $e) {
            throw $e;
        }
    }
}
