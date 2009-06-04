<?php
/*
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Application
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */


/** Bshe_Datapack_List_Log */
require_once 'Bshe/Datapack/List/Log.php';


/**
 * エラーログ画面用リストデータパック
 *
 * @copyright  since 2009 Abatous inc. All Rights Reserved
 * @version    $Id:$
 * @link       社内APIドキュメントへのURL
 * @since      2009.03.13
 * @todo       残作業を長期に残す場合の内容
 */
class Bshe_Cms_Models_Errorlog_Datapack_List extends Bshe_Datapack_List_Log
{

    /**
     * 必要に応じてオーバーライドする
     * 出力対象のログかどうかを判別するロ
     *
     * @param $strLog
     * @return unknown_type
     */
    protected function checkLogString($strLog)
    {
        if ($strLog == '') {
            return false;
        }

        if ($this->arrayFilter['target_files'] != '') {
            if (mb_ereg($this->arrayFilter['target_files'], $strLog) !== false) {
                // 一致
                return true;
            }
        } else {
            return true;
        }

        return false;
    }


    /**
     * 全データの数量に関する配列を返す
     *
     */
    public function getAllLineCount()
    {
        try
        {
            $min = 0;
            $max = 99999999;
            if (isset($this->arrayFilter['term_min'])) {
                $min = $this->arrayFilter['term_min'];
            }
            if (isset($this->arrayFilter['term_max'])) {
                $max = $this->arrayFilter['term_max'];
            }
            $this->getArrayLog($min, $max);
            $this->totalRecordsCount = count($this->_arrayLogData);
            $this->totalPagesCount = ceil(count($this->_arrayLogData) / $this->listCount);

            // 指定したログパスの全ログファイル
            return array(
                'all_line_count' => $this->totalRecordsCount,
                'all_page_count' => $this->totalPagesCount
                );
        }
        catch( Exception $e)
        {
            throw $e;
        }
    }


    /**
     * 全データの数量に関する配列を返す
     *
     */
    public function getArrayResults()
    {
        try
        {
            $configLanguage = Bshe_Registry_Config::getConfig('Bshe_Language');

            if ($this->_arrayLogData == array()) {
                // データがないため再検索
                $this->getAllLineCount();
            }

            // データパース
            $arrayResult = array();
            for ($i=0; $i<($this->listCount*($this->pageNum-1)); $i++) {
                unset($this->readLogFile[$i]);
            }
            $cnt = 0;
            foreach ($this->_arrayLogData as $key => $strVal) {
                if ($cnt < $this->listCount*($this->pageNum-1)) {

                } elseif ($cnt >= $this->listCount*($this->pageNum+1)) {

                } else {
                    $arrayRecord = array();
                    $arrayVal = mb_split($this->_sep, $strVal);
                    // 年月日
                    $arrayTmp = mb_split(' ', $arrayVal[0]);
                    $arrayRecord['date'] = $arrayTmp[0];
                    // ログレベル
                    $arrayRecord['loglevel'] = $arrayTmp[1];
                    // テンプレート名
                    $arrayRecord['template'] = mb_substr($arrayVal[4], mb_strlen($configLanguage->Bshe_View_Exception->template_name . ' => '));
                    // のこり
                    $arrayRecord['remarks'] = $arrayVal[5];
                    $arrayResult[] = $arrayRecord;
                }
                $cnt++;
            }

            return $arrayResult;
        }
        catch( Exception $e)
        {
            throw $e;
        }
    }
}