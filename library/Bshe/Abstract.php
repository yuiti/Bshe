<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Abstract
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Bshe_のクラス全体の抽象クラス
 * 　パラメータの保持管理などの共通事項を処理する
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.11.18
 * @license LGPL
 */
abstract class Bshe_Abstract
{
    /**
     * 設定用配列
     *
     * @var array
     */
    protected $_params = array();

    /**
     * 各種設定情報を配列から設定
     *
     * @param array $arrayParams
     */
    public function setParams($arrayParams = array(), $all = false)
    {
        try {
            foreach ($arrayParams as $key => $value) {
                if (array_key_exists($key, $this->_params) or $all) {
                    if (is_array( $this->_params[$key]) and !is_array($value)) {
                        // 配列化して登録
                        $this->_params[$key][] = $value;
                        Bshe_Log::logWithFileAndParamsWrite('パラメーター設定', Zend_Log::DEBUG, array($key => var_export( $value, TRUE)));
                    } else {
                        // そのまま登録
                        $this->_params[$key] = $value;
                        Bshe_Log::logWithFileAndParamsWrite('パラメーター設定', Zend_Log::DEBUG, array($key => var_export( $value, TRUE)));
                    }
                }
            }

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * パラメーター配列取得
     *
     * @return array
     */
    public function getParams($key = null)
    {
        if ($key == null) {
            return $this->_params;
        } else {
            return $this->_params[$key];
        }
    }

    /**
     * パラメーターのgetter
     *
     * @param string $key
     * @return
     */
    public function getParam($key)
    {
        return $this->_params[$key];
    }

    /**
     * パラメーターのセット
     *
     * @param $key
     * @param $value
     * @return unknown_type
     */
    public function setParam($key, $value)
    {
        $this->_params[$key] = $value;
    }
}
