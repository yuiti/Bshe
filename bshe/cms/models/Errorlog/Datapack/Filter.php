<?php
/**
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


/** Bshe_Datapack_Record_Abstract */
require_once 'Bshe/Datapack/Record/Abstract.php';

/**
 * エラー表示フィルタ用データパック
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.21
 * @license LGPL
 */
class Bshe_Cms_Models_Errorlog_Datapack_Filter extends Bshe_Datapack_Record_Filter
{
    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        try {
            $this->init();
            parent::__construct();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 各設定情報を初期化する。
     *
     */
    public function init()
    {
        try {
            // 項目設定
            $this->setRequestConfigs('target_files', '検索対象テンプレートファイル', self::LEVEL_TEXT_NOCHECK);
            $this->setRequestConfigs('term_min', '対象期間', self::LEVEL_TEXT_NOCHECK);
            $this->setRequestConfigs('term_max', '対象期間', self::LEVEL_TEXT_NOCHECK);

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * チェック：検索対象テンプレートファイル
     *
     * ・前方一致
     *
     */
    protected function _check_target_files($value, $arrayConfig)
    {
        // 「..」が含まれていないこと
        if (strpos($value, '..') !== false) {
            $this->setCheckErrors('target_files', 'ファイルに「..」を含めることはできません。');
            return false;
        }

        return $value;
    }

    /**
     * チェック：対象期間
     *
     * ・YYYYMMDD形式に変換する
     *
     */
    protected function _check_term_min($value, $arrayConfig)
    {
        // 「..」が含まれていないこと
        $arrayYmd = explode('/', $value);
        if (count($arrayYmd) != 3) {
            // */*/*形式ではない
            $this->setCheckErrors('term_min', 'yyyy/m/d形式で指定してください。');
            return false;
        }
        if(!is_numeric($arrayYmd[0]) or !is_numeric($arrayYmd[1]) or !is_numeric($arrayYmd[2])) {
            // 数値以外が利用されている
            $this->setCheckErrors('term_min', '数字と「/」で指定してください。');
            return false;
        }
        return $arrayYmd[0]*10000+$arrayYmd[1]*100+$arrayYmd[2];
    }


    /**
     * チェック：対象期間
     *
     * ・YYYYMMDD形式に変換する
     *
     */
    protected function _check_term_max($value, $arrayConfig)
    {
        // 「..」が含まれていないこと
        $arrayYmd = explode('/', $value);
        if (count($arrayYmd) != 3) {
            // */*/*形式ではない
            $this->setCheckErrors('term_min', 'yyyy/m/d形式で指定してください。');
            return false;
        }
        if(!is_numeric($arrayYmd[0]) or !is_numeric($arrayYmd[1]) or !is_numeric($arrayYmd[2])) {
            // 数値以外が利用されている
            $this->setCheckErrors('term_min', '数字と「/」で指定してください。');
            return false;
        }
        $max = $arrayYmd[0]*10000+$arrayYmd[1]*100+$arrayYmd[2];
        $min = $this->getRequestConfig('bshe_term_min', 'value');
        if ($min > $max) {
            // 逆にする
            $tmp = $max;
            $max = $min;
            $min = $max;
            $this->setRequestConfig('bshe_term_min', 'value', $min);
        }

        return $max;
    }
}