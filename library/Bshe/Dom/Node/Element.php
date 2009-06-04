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
 * HTMLのElement
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.02
 * @license LGPL
 */
class Bshe_Dom_Node_Element extends Bshe_Dom_Node_Abstract
{

    /**
     * 自身のタグを含んだ文字列を引数にしたコンストラクタ
     *
     * @param string $nodeValue
     */
    public function __construct($nodeValue='', $tagName)
    {
        try {
            $this->nodeName = $tagName;
            if ($nodeValue != '') {
                $this->parseAttribute($nodeValue);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * タグ文字列から属性を配列にして保持
     *
     * @param string $nodeValue
     */
    public function parseAttribute($nodeValue)
    {
        try {
            // innerHTML抽出
            if (preg_match('/^<[^>]*>([^<>]*)</', $nodeValue, $matches) == 1) {
                // inerHTMLがある
                $this->_nodeValue = $matches[1];
            } else {
                $this->_nodeValue = '';
            }

            parent::parseAttribute($nodeValue);
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
            $strHtml .= '>';

            if (count($this->_children) == 0) {
                // nodeValue設定
                $strHtml .= $this->_nodeValue;
            } elseif ($dom == null) {
                // domクラスが引数に入っていない
                Bshe_Log::logWithFileAndParamsWrite('引数にDOMクラスが必要です。', Zend_Log::ERR, array('nodename' => $this->nodeName));
                throw New Bshe_Exception('DOMクラスが引数に必要です。');
            } else {
                // 各子ノード生成
                for ($i=0; $i<count($this->_children); $i++) {
                    $strHtml .= $dom->elements[$this->_children[$i]]->saveHTML($dom);
                }
            }

            // 自身の終了ヘッダー生成
            $strHtml .= '</' . $this->nodeName . '>';

            return $strHtml;

        } catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * 自身を除いた
     * @param $dom
     * @return unknown_type
     */
    public function getNodeValue($dom = '')
    {
        try {
            if ($dom == '' and count($this->_children) != 0) {
                // 子ノードがある場合は引数にDOMが必要
                Bshe_Log::logWithFileAndParamsWrite('引数にDOMクラスが必要です。', Zend_Log::ERR, array('nodename' => $this->nodeName));
                throw New Bshe_Dom_Exception('引数にDOMクラスが必要です。');
            }
            $strHTML = '';

            if (count($this->_children) == 0) {
                // nodeValue設定
                $strHtml .= $this->_nodeValue;
            } else {
                // 各子ノード生成
                for ($i=0; $i<count($this->_children); $i++) {
                    $strHtml .= $dom->elements[$this->_children[$i]]->saveHTML($dom);
                }
            }

            return $strHtml;

        } catch (Exception $e)
        {
            throw $e;
        }
    }

}
