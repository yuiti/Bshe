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

/** Bshe_Dom_Exception */
require_once 'Bshe/Dom/Exception.php';

/**
 * Bshe_ViewのDOMオブジェクトの抽象クラス
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.02
 * @license LGPL
 */
abstract class Bshe_Dom_Node_Abstract
{



    /**
     * 属性の配列
     *
     * @var array
     */
    protected $_attributes = array();

    /**
     * ノード文字列
     *
     * @var string
     */
    protected $_nodeValue = '';


    /**
     * タグ名
     *
     * @var string
     */
    public $nodeName = "";

    /**
     * 子ノード番号の配列
     *
     * @var unknown_type
     */
    protected $_children = array();

    /**
     * 親ノードID
     *
     * @var unknown_type
     */
    protected $_parent = null;
    
    /**
     * attributeの記載方式
     * 
     * @var unknown_type
     */
    protected $_attributeType = array();

    /**
     * 自身のタグを含んだ文字列を引数にしたコンストラクタ
     * 子供がいる場合は、ここから処理が行われる
     *
     * @param string $nodeValue
     */
    public function __construct($nodeValue = '')
    {
        try {
            $this->setNodeValue($nodeValue, false, false);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * nodeValueをセット
     * 引数により、再度パースを行うこともできる
     *
     * @todo $reParse == trueの場合の処理
     *
     * @param string $nodeValue
     */
    public function setNodeValue($nodeValue = '', $reParse = false, $clearChild = true)
    {
        try {
            if ($clearChild) {
                $this->unsetChildren();
            }
            $this->_nodeValue = $nodeValue;
            if ($reParse === true) {
               $this->parseAttribute($nodeValue);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ノードの値を返す
     *
     * @return string
     */
    public function getNodeValue($dom = '')
    {
        return $this->_nodeValue;
    }

    /**
     * 子ノードの追加
     *
     * @param Bshe_View_Template_Html_Dom_Abstract $domChild
     * @param unknown_type $targetNumber
     */
    public function addChild($childNumber)
    {
        try {
                $this->_children[] = $childNumber;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 子ノード配列再セット
     *
     * @param array $arrayChildren
     */
    public function setChiledren($arrayChildren)
    {
        try {
                $this->_children = $arrayChildren;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 親IDセット
     *
     * @param $parentId
     * @return unknown_type
     */
    public function setParent($parentId)
    {
        try {
            $this->_parent = $parentId;
        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * 親ノードを返す
     *
     * @return unknown_type
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * 子ノードの定義をクリアする
     *
     */
    public function unsetChildren()
    {
        $this->_children = array();
    }

    /**
     * 子ノード番号の配列を返す
     *
     */
    public function getChildNumbers()
    {
        return $this->_children;
    }


    /**
     * タグ文字列から属性を配列にして保持
     *
     * @param string $nodeValue
     */
    public function parseAttribute($nodeValue)
    {
        try {
            // タグ内の文字列を取得
            if (preg_match('/^<[^>]*>/', $nodeValue, $matches) !== 1) {
                throw New Bshe_Dom_Exception( 'タグ名の抽出に失敗しました。');
            }
            $strInnerTag = $matches[0];
            // 前後の「<」「>」を取り除く
            $strInnerTag = mb_substr($strInnerTag, 1, -1);

            // 一番後ろが「/」の場合取り除く
            if (mb_substr($strInnerTag, -1) == '/') {
                $strInnerTag = mb_substr($strInnerTag, 0, -1);
            }

            // 1つ目はタグ名
            $arrayAttributeStrings = mb_split(' ', trim($strInnerTag));
            $this->nodeName = strtolower($arrayAttributeStrings[0]);
            $strInnerTag = trim(mb_substr(trim($strInnerTag), mb_strlen($arrayAttributeStrings[0])));
            // "内の「 」「=」対策

            while (mb_strlen($strInnerTag) != 0) {
                if (preg_match('/^([^\=]+)\s*=\s*\"([^\"]*)\"+/', $strInnerTag, $matches) == 1) {
                    // ""で囲まれたタイプ
                    $this->_attributes[strtolower($matches[1])] = $matches[2];
                    $this->_attributeType[strtolower($matches[1])] = '"';
                } elseif (preg_match('/^([^\=]+)\s*=\s*\'([^\']*)\'+/', $strInnerTag, $matches) == 1) {
                    // ''で囲まれたタイプ
                    $this->_attributes[strtolower($matches[1])] = $matches[2];
                    $this->_attributeType[strtolower($matches[1])] = "'";
                } elseif (preg_match('/^([^\=]+)\s*=\s*([^\s]*)/', $strInnerTag, $matches) == 1) {
                    // 「 」で区切られたタイプ
                    $this->_attributes[strtolower($matches[1])] = $matches[2];
                    $this->_attributeType[strtolower($matches[1])] = "";
                } elseif (preg_match('/^([^\=\s]+)/', $strInnerTag, $matches) == 1) {
                    // =のないタイプ
                    $this->_attributes[strtolower($matches[1])] = null;
                    $this->_attributeType[strtolower($matches[1])] = "";
                } else {
                    // 認識不能
                    break;
                }

                $strInnerTag = trim(mb_substr($strInnerTag, mb_strlen($matches[0])));
            }

        } catch (Exception $e) {
            throw $e;
        }

    }

    /**
     * 属性有無チェック
     *
     * @param string $key
     * @return boolean
     */
    public function hasAttribute($key)
    {
        if (isset($this->_attributes[$key])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 属性取得
     *
     * @param string $key
     * @return string
     */
    public function getAttribute($key)
    {
        if (isset($this->_attributes[$key])) {
            return $this->_attributes[$key];
        } else {
            return null;
        }
    }

    /**
     * 子ノードの有無
     *
     * @return boolean
     */
    public function hasChildNodes()
    {
        if (count($this->_children) != 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 属性文字列セット
     * エスケープ等は一切行わない
     *
     * @param string $key
     * @param string $value
     */
    public function setAttribute($key, $value)
    {
        try {
            $this->_attributes[strtolower($key)] = strval($value);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 属性削除
     *
     * @param string $key
     */
    public function removeAttribute($key)
    {
        if (isset($this->_attributes[$key])) {
            unset ($this->_attributes[$key]);
        }
    }

    /**
     * ノードをコピーする
     *
     * @param unknown_type $dom
     */
    public function cloneNode($dom)
    {
        try {
            $arrayNodes = array();
            // 自身をクローン
            $dom->elements[] = clone $this;
            $nodeNumber = count($dom->elements) -1;
            $arrayNodes['node'] = $nodeNumber;
            $dom->elements[$nodeNumber]->unsetChildren();

            // 子ノードの有無確認
            if ($this->hasChildNodes()) {
                // 子ノードコピー
                $arrayChildNodeNumbers = $this->getChildNumbers();
                for ($i=0; $i<count($arrayChildNodeNumbers); $i++) {
                    $arrayNodes['children'][] = $dom->elements[$arrayChildNodeNumbers[$i]]->cloneNode($dom);
                    $dom->elements[$nodeNumber]->addChild($arrayNodes['children'][count($arrayNodes['children'])-1]['node']);
                }
            }

            return $arrayNodes;

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 子ノードを削除し配列を整理
     *
     * @param unknown_type $targetNodeNumber
     */
    public function removeChildNode($targetNodeNumber)
    {
        try {
            if (($key = array_search($targetNodeNumber, $this->_children)) !== false) {
                for ($i=$key; $i<count($this->_children)-1; $i++) {
                    $this->_children[$i] = $this->_children[$i+1];
                }
                // 最期をクリア
                unset ($this->_children[count($this->_children)-1]);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 子ノードを削除し配列を整理
     *
     * @param unknown_type $targetNodeNumber
     */
    public function insertBefore($targetNodeNumber, $beforeNodeNumber)
    {
        try {
            if (($key = array_search($beforeNodeNumber, $this->_children)) !== false) {
                for ($i=count($this->_children)-1; $i>=$key; $i--) {
                    $this->_children[$i+1] = $this->_children[$i];
                }
                // 挿入
                $this->_children[$key] = $targetNodeNumber;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


//    public function removeChild($targetNode)
//    {
//        try {
//            // 削除対象セット
//            if (($elementRow->getChildNumber() !== null) and ($elementRow->getChildNumber() < count($this->childNodes))) {
//                $targetNodeNumber = $elementRow->getChildNumber();
//            } else {
//                // 対象ノードは子ノードではない
//                throw New Bshe_Dom_Exception('指定されたノードは子ノードではないか、一番後ろのノードよりも大きい番号を持っています。:' . $targetNodeNumber);
//            }
//            // 対象より後ろにある子ノードを１つ後ろにずらす
//            $intNodeCount = count($this->childNodes);
//            for ($i = $targetNodeNumber +1; $i < $intNodeCount; $i++) {
//                // ノード番号移動
//                $this->childNodes[$i]->setChildNumber($this->childNodes[$i]->getChildNumber() -1);
//                // ノード移動
//                $this->childNodes[$i-1] =& $this->childNodes[$i];
//            }
//            unset ()
//            // やはり、対応の数値配列を利用しないと削除できないか、、、、
//
//        } catch (Exception $e) {
//            throw $e;
//        }
//    }
}
