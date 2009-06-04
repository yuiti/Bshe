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
 * さまざまなデータのフィルタ項目の入力を受け付けるDatapackクラス
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.21
 * @license LGPL
 */
abstract class Bshe_Datapack_Record_Filter extends Bshe_Datapack_Record_Abstract
{

    /**
     * デフォルトの並び順
     *
     * @var string
     */
    protected $strOrder = '';

    /**
     * 表示ページ番号
     *
     * @var unknown_type
     */
    protected $pageNum = 1;

    /**
     * ページ番号セット
     *
     * @param unknown_type $strPage
     */
    public function setPage($strPage)
    {
        $this->pageNum = $strPage;
    }

    /**
     * 対象ページ番号取得
     *
     * @return unknown
     */
    public function getPage()
    {
        return $this->pageNum;
    }


    /**
     * セットされたSQLのWhere配列を返す。
     *
     */
    public function getFilter()
    {
        try {
            $arrayResult = array();
            foreach ($this->requestConfigs as $key => $values) {
                $arrayResult[$key] = $values['value'];
            }
            return $arrayResult;
        } catch (Exception $e) {
            throw $e;
        }
    }



    /**
     * 並べ替え文字列をセットする。
     *
     * @param string $strOrder
     */
    public function setOrder($strOrder)
    {
        try {
            $this->strOrder = $strOrder;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * セットされている並び順を返す。
     *
     * @return unknown
     */
    public function getOrder()
    {
        return $this->strOrder;
    }
}