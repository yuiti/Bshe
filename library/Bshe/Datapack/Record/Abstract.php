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
 * さまざまなデータレコードを処理する抽象クラス
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.11.18
 * @license LGPL
 */
abstract class Bshe_Datapack_Record_Abstract extends Bshe_Datapack_Abstract
{
    /**
     * 処理する値のキーに関する配列
     *
     * @var array
     */
    protected $requestConfigs = array();

    /**
     * チェック時のエラーを保持した配列
     *
     * $checkErrors[][$key] = エラー文字列
     *
     * @var array
     */
    protected $checkErrors = array();

    /**
     * チェック前のリクエストデータを保持
     *
     * @var array
     */
    protected $beforeCheckDatas = array();

    /**
     * チェックレベル定義
     *
     */
    const LEVEL_TEXT_NOCHECK = 10;// テキスト：必須チェックなし
    const LEVEL_TEXT_MUST = 11;// テキスト：1文字以上のものを保持していればOK
    const LEVEL_TEXT_MUSTNOW = 12;// テキスト：1文字以上のものが流れてこないとNG
    const LEVEL_CHECKBOX_NOCHECK = 20;// チェックボックス：基本何もしない
    const LEVEL_CHECKBOX_CHECK = 21;// チェックボックス：画面にチェックボックスがあるので、NULLはチェックはずしと判別
    const LEVEL_MSTCHECKBOX_NOCHECK = 30;// チェックボックス：基本何もしない
    const LEVEL_MSTCHECKBOX_MUST = 31;// チェックボックス：保持か今回NULL以外の値が入っている
    const LEVEL_MSTCHECKBOX_MUSTNOW = 32;// チェックボックス：今回NULL以外が入っている

    /**
     * チェック前のデータ配列を取得
     * 再チェックなどの際に利用
     *
     */
    public function getBeforeCheckDatas()
    {
        return $this->beforeCheckDatas;
    }

    /**
     * $this->checkErrorsをセットする
     *
     */
    public function setCheckErrors($key, $message)
    {
        try {
            $this->checkErrors[][$key] = $message;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * requestConfigsに値をセットする
     *
     * @param string $key
     * @param string $name
     * @param integer $isMst
     * 　テキスト：10:必須チェックなし、11:1文字以上のものを保持していればOK、12:1文字以上のものが流れてこないとNG
     * 　チェックボックス：20:基本何もしない、21:画面にチェックボックスがあるので、NULLはチェックはずしと判別
     * 　必須チェックボックス：30:基本何もしない、31:保持か今回NULL以外の値が入っている、32:今回NULL以外が入っている
     */
    public function setRequestConfigs($key, $name, $isMst)
    {
        try {
            $this->setRequestConfig($key, 'name', $name);
            $this->setRequestConfig($key, 'isMst', $isMst);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * リクエスト設定を返す
     *
     * @return unknown
     */
    public function getAllRequestConfig()
    {
        return $this->requestConfigs;
    }

    /**
     * requestConfigsに値をセットする
     *
     * @param string $key
     * @param string $subkey
     * @param mixed $value
     */
    public function setRequestConfig($key, $subkey, $value)
    {
        try {
            $this->requestConfigs[$key][$subkey] = $value;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * requestConfigsにの値を取得する
     *
     * @param string $key
     * @param string $subkey
     */
    public function getRequestConfig($key, $subkey)
    {
        return $this->requestConfigs[$key][$subkey];
    }

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        try {
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * データのチェック実行を行う。
     *
     * @param unknown_type $values
     */
    public function checkDatas($values)
    {
        try {
            // チェック前データ保持
            $this->beforeCheckDatas = $values;

            $this->checkErrors = array();
            // 処理対象リクエストでループ
            foreach($this->requestConfigs as $key => $arrayConfigs) {
                // 必須チェック
                    $checkFunction = false;

                    switch ($arrayConfigs['isMst']) {
                        case 10:
                            // テキスト：必須チェックなし
                            if (isset($values[$key])) {
                                $checkFunction = true;
                            }
                            break;
                        case 11:
                            // テキスト：1文字以上のものを保持していればOK
                            if (mb_strlen($values[$key]) > 0) {
                                // 送られてきている
                                $checkFunction = true;
                            } elseif(mb_strlen($arrayConfigs['value'])>1) {
                                // 保持している
                            } else {
                                // 保持していないからエラー
                                $this->checkErrors[][$key] = '必須項目：「' . $arrayConfigs['name'] . '」を入力してください。';
                            }
                            break;
                        case 12:
                            // テキスト：1文字以上のものが流れてこないとNG
                            if (mb_strlen($values[$key]) > 0) {
                                // 送られてきている
                                $checkFunction = true;
                            } else {
                                // 必須が抜けている
                                $this->checkErrors[][$key] = '必須項目：「' . $arrayConfigs['name'] . '」を入力してください。';
                            }
                            break;
                        case 20:
                        case 30:
                            // チェックボックス：基本何もしない

                            break;
                        case 21:
                            // チェックボックス：画面にチェックボックスがあるので、NULLはチェックはずしと判別
                            $checkFunction = true;
                            break;
                        case 31:
                            // チェックボックス：保持か今回NULL以外の値が入っている
                            if (mb_strlen($values[$key]) > 0) {
                                // 送られてきている
                                $checkFunction = true;
                            } elseif(mb_strlen($arrayConfigs['value'])>1) {
                                // 保持している

                            } else {
                                // 保持していないからエラー
                                $this->checkErrors[][$key] = '必須項目：「' . $arrayConfigs['name'] . '」をチェックしてください。';
                            }
                            break;
                        case 32:
                            // チェックkボックス：今回NULL以外が入っている
                            if (mb_strlen($values[$key]) > 0) {
                                // 送られてきている
                                $checkFunction = true;
                            } else {
                                // 保持していないからエラー
                                $this->checkErrors[][$key] = '必須項目：「' . $arrayConfigs['name'] . '」をチェックしてください。';
                            }
                    }

                // $checkFunctionがtrue場合、チェック関数呼び出し
                if ($checkFunction and method_exists($this, '_check_' . $key)) {
                    // チェック関数を呼び出してデータ保持
                    $funcName = '_check_' . $key;
                    $rsl = $this->$funcName($values[$key], $arrayConfigs);
                    if ($rsl !== false) {
                        $this->requestConfigs[$key]['value'] = $rsl;
                    }
                } elseif ($checkFunction) {
                    // データを保持
                    $this->requestConfigs[$key]['value'] = $values[$key];
                } else {
                    // 何もしない

                }
            }

            if ($this->getCheckErrors() == array()) {
                return true;
            } else {
                return $this->getCheckErrors();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * エラー配列を返す
     *
     * @return unknown
     */
    public function getCheckErrors()
    {
        return $this->checkErrors;
    }
}