<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Datapack
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Datapack_Abstract */
require_once 'Bshe/Datapack/Abstract.php';

/**
 * さまざまなリストを処理する抽象クラス
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.11.18
 * @license LGPL
 */
abstract class Bshe_Datapack_List_Abstract extends Bshe_Datapack_Abstract
{

    /**
     * Where配列
     *
     * @var array
     */
    protected $arrayFilter;

    /**
     * 並び順文字列
     *
     * @var string
     */
    protected $strOrder = '';

    /**
     * 1ページ行数
     * 0の場合はすべて表示
     *
     */
    public $listCount = 30;

    /**
     * 表示ページ番号
     *
     * @var unknown_type
     */
    protected $pageNum = 1;

    /**
     * 検索された総レコード数
     *
     * @var integer
     */
    protected $totalRecordsCount;

    /**
     * 検索された総ページ数
     *
     * @var integer
     */
    protected $totalPagesCount;


    /**
     * ページコントローラーの前後表示数
     *
     * @var unknown_type
     */
    protected $pageCtlCnt = 5;

    /**
     * 1ページ行数
     *
     * @param $pageCltCnt
     * @return void
     */
    public function setPageCtlCnt($pageCltCnt)
    {
        $this->pageCtlCnt = $pageCltCnt;
    }

    public function setListCnt($listCount)
    {
        $this->listCount= $listCount;
    }


    public function setPage($pageNum)
    {
        try {
            $this->pageNum = $pageNum;
            // limit句として登録
            if ($this->listCount != 0) {

            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * 並べ替え順をセット
     *
     * @param unknown_type $strOrder
     */
    public function setOrder($strOrder)
    {
        try {
            // メンバ変数へ保存
            $this->strOrder = $strOrder;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * where条件配列を受け取る
     *
     * @param unknown_type $array
     */
    public function setFilter($arrayWhere)
    {
        try {
            // メンバ変数へ保持
            $this->arrayFilter = $arrayWhere;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * データを取得する
     *
     */
    abstract public function getArrayResults();


    /**
     * 全データの数量に関する配列を返す
     *
     */
    abstract public function getAllLineCount();


    /**
     * 取得されているデータをクリアして再取得する場合に
     * クリアを実行する。
     * 必要に応じてオーバーライド
     *
     * @return void
     */
    public function clearList()
    {
        return ;
    }

    /**
     * ページコントロールタグ生成
     * <pre>
     * valueMstRequestにpage_numberを定義すること
     * </pre>
     *
     * @param string $pageURL ページコントロールのGET先
     */
    public function getPageCtlAjaxHtml()
    {
        $rsl = "";
        // 1ページへ移動
        $rslTmp = '<a href="#" onclick="javascript:' . "xajax_showList(xajax.getFormValues('form_filter'),'',1)" . '">&lt;&lt;' . '</a>&nbsp;&nbsp;' . "\n";
        $rsl .= $rslTmp;

        // 前のページへ
        if ( $this->pageNum > 1)
        {
            $rslTmp = '<a href="#" onclick="javascript:' . "xajax_showList(xajax.getFormValues('form_filter'),''," . ($this->pageNum -1) . ")" . '">&lt;' . '</a>&nbsp;&nbsp;' . "\n";
            $rsl .= $rslTmp;
        }

        // ページリスト
            // 前のページ
            for( $i = ($this->pageNum - $this->pageCtlCnt); $i < $this->pageNum; $i++)
            {
                if ( $i > 0)
                {
                    $rslTmp = '<a href="#" onclick="javascript:' . "xajax_showList(xajax.getFormValues('form_filter'),''," . $i . ")" . '">' . $i . '</a>&nbsp;&nbsp;' . "\n";
                    $rsl .= $rslTmp;
                }
            }
            // 現在のページ
            $rslTmp = $this->pageNum . '&nbsp;&nbsp;' . "\n";
            $rsl .= $rslTmp;
            // 後のページ
            for( $i = ($this->pageNum + 1); $i <= ( $this->pageNum + $this->pageCtlCnt); $i++)
            {
                if ( $i <=  $this->totalPagesCount)
                {
                    $rslTmp = '<a href="#" onclick="javascript:' . "xajax_showList(xajax.getFormValues('form_filter'),''," . $i . ")" . '">' . $i . '</a>&nbsp;&nbsp;' . "\n";
                    $rsl .= $rslTmp;
                }
            }

        // 次のページへ
        if ( $this->pageNum <  $this->totalPagesCount)
        {
            $rslTmp = '<a href="#" onclick="javascript:' . "xajax_showList(xajax.getFormValues('form_filter'),''," . ($this->pageNum +1) . ")" . '">&gt;' . '</a>&nbsp;&nbsp;' . "\n";
            $rsl .= $rslTmp;
        }

        // 最後のページへ
        $rslTmp = '<a href="#" onclick="javascript:' . "xajax_showList(xajax.getFormValues('form_filter'),''," . $this->totalPagesCount . ")" . '">&gt;&gt;' . '</a>&nbsp;&nbsp;' . "\n";
        $rsl .= $rslTmp;

        return $rsl;
    }

}