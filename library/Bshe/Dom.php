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
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_View_Template_Html_Exception_HtmlParseError */
require_once 'Bshe/View/Template/Html/Exception/HtmlParseError.php';
/** Bshe_Dom_Node_Declaration */
require_once 'Bshe/Dom/Node/Declaration.php';
/** Bshe_Dom_Node_Comment */
require_once 'Bshe/Dom/Node/Comment.php';
/** Bshe_Dom_Node_Cdata */
require_once 'Bshe/Dom/Node/Cdata.php';
/** Bshe_Dom_Node_SingleElement */
require_once 'Bshe/Dom/Node/SingleElement.php';
/** Bshe_Dom_Node_Element */
require_once 'Bshe/Dom/Node/Element.php';
/** Bshe_Dom_Node_Text */
require_once 'Bshe/Dom/Node/Text.php';

/**
 * Bshe_ViewのHTML全体を保持するクラス
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.12.02
 * @license LGPL
 */
class Bshe_Dom
{

    /**
     * 終了タグを必要としないタグ
     *
     * @var array
     */
    protected $_arrayNoMustFineTag =
        array(
            'input',
            'img',
            'br',
            'hr',
            'meta',
            'link',
            'frame'
        );


    /**
     * 子ノードの単純配列
     *
     * キーが0がトップノード
     * 並び変わったりしても、この配列は順序変化なく
     * 各ノードクラスの実態を保持する
     *
     * @var array
     */
    public $elements = array();


    /**
     * $this->elementsの中ですでに削除済みの番号を保持する
     *
     * @var unknown_type
     */
    public $deletedElements = array();


    /**
     * ノードの階層構造を再現する配列
     *
     * @var array
     */
    protected $_domTree = array();

    /**
     * 読み込まれた元HTML
     *
     * @var string
     */
    protected $_nodeValue;

    /**
     * 自身のタグを含んだ文字列を引数にしたコンストラクタ
     * 子供は原則いないため登録のみ
     *
     * @param string $nodeValue
     */
    public function __construct($nodeValue = '', $template = null)
    {
        try {
            if ($nodeValue != '') {
                $this->_nodeValue = $nodeValue;
                $this->parseHtml($nodeValue, 0, $template);
            }

        } catch (Exception $e) {
            throw $e;
        }
    }



    public function parseHtml($contents, $offset =0, $template = null, $parent = null)
    {
        try {
            $arrayTree = array();

            while (true) {
                if ($strNext = mb_substr($contents, $offset, 1) == '<') {
                    // 「<」の次の文字の判別
                    $strNext = mb_substr($contents, $offset+1, 1);
                    switch ($strNext) {
                        case '?':
                            // declaration
                            // 終りの「>」検索
                            if (($endPos = mb_strpos( $contents, '?>', $offset)) === false) {
                                throw New Bshe_View_Template_Html_Exception_HtmlParseError('「<?」に対応する「>」が見つかりません。', $template, $offset);
                            }
                            // Domクラスインスタンス化
                            $nodeValue = mb_substr($contents, $offset, $endPos+2 - $offset);
                            $this->elements[] = New Bshe_Dom_Node_Declaration($nodeValue);
                            $arrayTree[]['node'] = count($this->elements)-1;
                            $offset = $offset + $endPos+2 - $offset;
                            break;
                        case '!':
                            if (mb_substr($contents, $offset,4) == '<!--') {
                                // コメント
                                // 終りの「-->」検索
                                if (($endPos = mb_strpos($contents, '-->', $offset)) === false) {
                                    throw New Bshe_View_Template_Html_Exception_HtmlParseError('「<!--」に対応する「-->」が見つかりません。', $template, $offset);
                                }
                                // Domクラスインスタンス化
                                $nodeValue = mb_substr($contents, $offset, $endPos+3 - $offset);
                                $this->elements[] = New Bshe_Dom_Node_Comment($nodeValue);
                                $arrayTree[]['node'] = count($this->elements)-1;

                                $offset = $offset + $endPos+3 - $offset;
                            } elseif (mb_substr( $contents, $offset,9) == '<![CDATA[') {
                                // CDATA
                                // 終りの「]>」検索
                                if (($endPos = mb_strpos($contents, ']>', $offset)) === false) {
                                    throw New Bshe_View_Template_Html_Exception_HtmlParseError('「<[CDATA」に対応する「]>」が見つかりません。', $template, $offset);
                                }
                                // Domクラスインスタンス化
                                $nodeValue = mb_substr($contents, $offset, $endPos+3 - $offset);
                                $this->elements[] = New Bshe_Dom_Node_Cdata($nodeValue);
                                $arrayTree[]['node'] = count($this->elements)-1;
                                $offset = $offset + $endPos+9 - $offset;
                            } elseif (mb_substr($contents, $offset,9) == '<!DOCTYPE') {
                                // DOCTYPE
                                // 終りの「>」検索
                                if (($endPos = mb_strpos($contents, '>', $offset)) === false) {
                                    throw New Bshe_View_Template_Html_Exception_HtmlParseError('「<!」に対応する「>」が見つかりません。', $template, $offset);
                                }
                                // Domクラスインスタンス化
                                $nodeValue = mb_substr($contents, $offset, $endPos+1 - $offset);
                                $this->elements[] = New Bshe_Dom_Node_Declaration($nodeValue);
                                $arrayTree[]['node'] = count($this->elements)-1;
                                $offset = $offset + $endPos+1 - $offset;
                            }
                            break;
                        case '/':
                            // </ ノードの終了
                            // 終りの「>」検索
                            if (($endPos = mb_strpos($contents, '>', $offset)) === false) {
                                throw New Bshe_View_Template_Html_Exception_HtmlParseError('「</」に対応する「>」が見つかりません。', $template, $offset);
                            }
                            // 終了のオフセットを返す
                            $endName = strtolower(trim(mb_substr($contents, $offset+2, $endPos -2 - $offset)));
                            $offset = $offset + $endPos+1 - $offset;
                            return array('nextOffset' => $offset, 'children' => $arrayTree, 'endName' => $endName);
                        default:
                            // 一般のタグ

                            // 最初の「>」検索
                            if (($endPos = mb_strpos($contents, '>', $offset)) === false) {
                                throw New Bshe_View_Template_Html_Exception_HtmlParseError( '「<」に対応する「>」が見つかりません。', $template, $offset);
                            }
                            // タグ名
                            $strTmp = mb_substr($contents, $offset);
                            if ( preg_match('/^<[a-zA-Z0-9]*/', $strTmp, $matches) !== 1) {
                                throw New Bshe_View_Template_Html_Exception_HtmlParseError('タグ名の抽出に失敗しました。:' . mb_substr($strTmp, 0, 10) . '...', $template, $offset);
                            }
                            $tagName = strtolower(mb_substr($matches[0], 1));

                            if (array_search(strtolower($tagName), $this->_arrayNoMustFineTag) !== false) {
                                // 終了タグなしでもOK
                                // 「>」で終了できるタグで終りが「>」
                                $nodeValue = mb_substr($contents, $offset, $endPos+1 - $offset);
                                $tmpNode = New Bshe_Dom_Node_SingleElement($nodeValue, strtolower($tagName));
                                $tmpNode->setParent($parent);
                                $this->elements[] = $tmpNode;
                                $arrayTree[]['node'] = count($this->elements)-1;
                                $offset = $offset + $endPos+1 - $offset;

                            } elseif (mb_substr($contents, $endPos -1, 2) == '/>') {
                                // 終りが「/>」
                                $nodeValue = mb_substr($contents, $offset, $endPos+1 - $offset);
                                $tmpNode = New Bshe_Dom_Node_SingleElement($nodeValue, $tagName);
                                $this->elements[] = $tmpNode;
                                $tmpNode->setParent($parent);
                                $arrayTree[]['node'] = count($this->elements)-1;
                                $offset = $offset + $endPos+1 - $offset;
                            } else {
                                // 終りが「>」続いてinnerHTML
                                // 内容空の本クラスイスタンス化
                                $this->elements[] = New Bshe_Dom_Node_Element('', $tagName);
                                $tmpNode = count($this->elements)-1;
                                $this->elements[$tmpNode]->setParent($parent);
                                $arrayTree[]['node'] = $tmpNode;
                                // 本メソッドを再帰呼び出し
                                $arrayResult = $this->parseHtml($contents, $endPos +1, $template, $tmpNode);
                                if ($tagName != $arrayResult['endName']) {
                                	// 終了タグがちぐはぐ
                                	throw New Bshe_View_Template_Html_Exception_HtmlParseError($tagName . 'に終了タグが見つかりません。', $template, $arrayResult['nextOffset']);
                                }
                                $arrayTree[count($arrayTree)-1]['children'] = $arrayResult['children'];
                                foreach ($arrayResult['children'] as $key => $val) {
                                    $this->elements[$tmpNode]->addChild($val['node']);
                                }
                                // 内容文字列セット
                                $this->elements[$tmpNode]->setNodeValue(mb_substr($contents, $offset, $arrayResult['nextOffset'] - $offset), true, false);
                                $offset = $arrayResult['nextOffset'];
                            }

                            // メタタグの場合情報を保持

                            break;
                    }
                }
                else{
                    // 「<」以外の文字で始まる
                    // 「<」を検索
                    if( ( $endPos = mb_strpos( $contents, '<', $offset)) === false) {
                        // 最後の可能性
                        $nodeValue = mb_substr( $contents, $offset);
                        $tmpNode = New Bshe_Dom_Node_Text($nodeValue);
                        $tmpNode->setParent($parent);
                        $this->elements[] = $tmpNode;

                        $arrayTree[]['node'] = count($this->elements)-1;
                        $offset = $offset + $endPos - $offset;
                        break;
                        //throw New Bshe_View_Template_Html_Exception_HtmlParseError( '次のノード「<」が見つかりません。', $template, $offset);
                    }
                    $nodeValue = mb_substr( $contents, $offset, $endPos - $offset);
                    $tmpNode = New Bshe_Dom_Node_Text($nodeValue);
                    $tmpNode->setParent($parent);
                    $this->elements[] = $tmpNode;
                    $arrayTree[]['node'] = count($this->elements)-1;
                    $offset = $offset + $endPos - $offset;
                }
                // 最後まで来た場合は終了
                if ($offset>=mb_strlen($contents)) {
                    break;
                }
            }

            $this->_domTree = $arrayTree;

        } catch (Exception $e) {
            throw $e;
        }

        return ;
    }

    /**
     * ノードを指定して子ノードの有無
     *
     * @return boolean
     */
    public function hasChildNodes($nodeNumber = null)
    {
        if ($nodeNumber === null) {
            // 親ノード
            if (count($this->_domTree) == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            // 子ノード
            if ($this->elements[$nodeNumber]->hasChildNodes() != 0) {
                return true;
            } else {
                return false;
            }
        }

    }

    /**
     * ノードを指定して子ノード番号の配列を取得
     *
     * @param integer $nodeNumber
     */
    public function getChildNumbers($nodeNumber = null)
    {
        try {
            if ($nodeNumber === null) {
                // 親ノード
                $arrayTmp = array();
                foreach ($this->_domTree as $key => $val) {
                    $arrayTmp[] = $val['node'];
                }
                return $arrayTmp;
            } else {
                // 子ノード
                return $this->elements[$nodeNumber]->getChildNumbers();
            }
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
            // 各子ノード生成
            for ($i=0; $i<count($this->_domTree); $i++) {
                $strHtml .= $this->elements[$this->_domTree[$i]['node']]->saveHTML($this);
            }

            return $strHtml;

        } catch (Exception $e)
        {
            throw $e;
        }
    }

    /**
     * ノードの挿入
     *
     * @param unknown_type $arrayNodes
     * @param unknown_type $beforeNodeNumber
     */
    public function insertBefore($arrayNodes, $beforeNodeNumber, $domTree = null)
    {
        try {
            $isTop = false;
            if ($domTree === null) {
                $isTop = true;
                $domTree = $this->_domTree;
            }
            foreach ($domTree as $key => $subDomTree) {
                if ($subDomTree['node'] == $beforeNodeNumber) {
                    // このノードの前に挿入する
                    for ($i=count($domTree)-1; $i>=$key; $i--) {
                        // ノードを後ろへ移動
                        $domTree[$i+1] = $domTree[$i];
                    }
                    // 親ノードセット
                    $this->elements[$arrayNodes['node']]->setParent($this->elements[$beforeNodeNumber]->getParent());

                    // ノードtreeの挿入
                    $domTree[$key] = $arrayNodes;
                    $domTree['fine'] = true;
                    return $domTree;
                } else {
                    // 対象ではない、子ノードがある場合はそちらを呼び出し
                    if ($this->elements[$subDomTree['node']]->hasChildNodes()) {
                        // 子ノードあり
                        $domTree[$key]['children'] = $this->insertBefore($arrayNodes, $beforeNodeNumber, $domTree[$key]['children']);
                        if ($domTree[$key]['children']['fine'] == true) {
                            $this->elements[$domTree[$key]['node']]->insertBefore($arrayNodes['node'], $beforeNodeNumber);

                            unset ($domTree[$key]['children']['fine']);
                            if ($isTop) {
                                $this->_domTree = $domTree;
                                return ;
                            } else {
                                return $domTree;
                            }
                        }
                    }
                }
            }
            if ($isTop) {
                // treeセット
                $this->_domTree = $domTree;
                return ;
            } else {
                return $domTree;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }



    /**
     * 対象ノードを子ノードも含めて削除
     * （treeからのみ削除し実態は削除しない）
     *
     * @param integer $targetNodeNumber
     * @param array $domTree
     */
    public function removeNode($targetNodeNumber, $domTree = null)
    {
        try {
            $isTop = false;
            if ($domTree === null) {
                $isTop = true;
                $domTree = $this->_domTree;
            }
            foreach ($domTree as $key => $subDomTree) {
                if ($subDomTree['node'] == $targetNodeNumber) {
                    // このノードを削除する
                    for ($i=$key; $i<count($domTree)-1; $i++) {
                        // ノードを前へ移動
                        $domTree[$i] = $domTree[$i+1];
                    }
                    // 最後のノードの削除
                    $this->deletedElements[] = $targetNodeNumber;
                    unset ($domTree[count($domTree)-1]);
                    $domTree['fine'] = true;
                    return $domTree;
                } else {
                    // 対象ではない、子ノードがある場合はそちらを呼び出し
                    if ($this->elements[$subDomTree['node']]->hasChildNodes()) {
                        // 子ノードあり
                        $domTree[$key]['children'] = $this->removeNode($targetNodeNumber, $domTree[$key]['children']);
                        if ($domTree[$key]['children']['fine'] == true) {
                            // ノードクラスからも削除
                            $this->elements[$domTree[$key]['node']]->removeChildNode($targetNodeNumber);

                            unset ($domTree[$key]['children']['fine']);
                            if ($isTop) {
                                $this->_domTree = $domTree;
                                return ;
                            } else {
                                return $domTree;
                            }
                        }
                    }
                }
            }
            if ($isTop) {
                // treeセット
                $this->_domTree = $domTree;
                return ;
            } else {
                return $domTree;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * $this->elements配列を返す
     *
     * 注意として、この配列は削除されたDOMnodeも含まれている
     *
     * @return unknown
     */
    public function getElements()
    {
        return $this->elements;
    }


    /**
     * $this->elements配列からNodeを返す
     *
     * 注意として、この配列は削除されたDOMnodeも含まれている
     *
     * @return unknown
     */
    public function getElement($key)
    {
        if (isset($this->elements[$key])) {
            return $this->elements[$key];
        } else {
            return null;
        }
    }


    /**
     * 新しいNodeクラスをelements配列へ追加し
     * そのノードIDを返す
     *
     * @param Bshe_Dom_Node_Abstract $nodeClass
     * @return integer
     */
    public function newNode($nodeClass)
    {
        $this->elements[] = $nodeClass;
        return count($this->elements)-1;
    }

    /**
     * 子ノードを指定したノードに追加する
     *
     * @param integer $parentNumber
     * @param integer $childNumber
     */
    public function addChild($childNodeClass, $parentNumber)
    {
        $this->elements[] = $childNodeClass;
        $this->elements[$parentNumber]->addChild(count($this->elements)-1);
        $this->elements[count($this->elements)-1]->setParent($parentNumber);

        return count($this->elements)-1;
    }


    /**
     * 特定のタグから親をたどり、
     * 親に指定されたタグがあるかを検索し、
     * そのnodeidを返す
     *
     * @param $elementId
     * @param $targetTagName
     * @return integer|false 対象タグがあればNODE番号、なければfalseを返す
     */
    public function searchParentTag($elementId, $targetTagName)
    {
        try {
            // 親タグがNULLの場合、親はなし
            if ($this->elements[$elementId]->getParent() === null) {
                return false;
            }
            // 親タグのタグ名チェック
            if ($this->elements[$this->elements[$elementId]->getParent()]->nodeName == $targetTagName) {
                return $this->elements[$elementId]->getParent();
            } else {
                // 親ノードチェック
                return $this->searchParentTag($this->elements[$elementId]->getParent(), $targetTagName);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

}
