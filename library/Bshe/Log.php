<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Log
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_Log */
require_once 'Zend/Log.php';
/** Zend_Session */
require_once 'Zend/Session.php';
/** Zend_Auth */
require_once 'Zend/Auth.php';

/**
 * Bshe_Log
 *
 * writer別にフィルターをセットできるように拡張したログクラス
 * HTMLアプリケーション用に、session_idやuidを自動で追加する
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.10
 * @license LGPL
 */
class Bshe_Log extends Zend_Log
{

    /**
     * ログクラス保持用
     *
     * @var unknown_type
     */
    static protected $_logger = null;

    /**
     * ログクラスをスタティックに登録
     *
     * @param unknown_type $logger
     */
    static public function setLogger($logger)
    {
        self::$_logger = $logger;
    }

    /**
     * logクラスのインスタンスを返す。
     *
     * @return Bshe_Log
     */
    static public function getLogger()
    {
        return self::$_logger;
    }

    /**
     * スタティックに保存されたログクラスを利用してログを記録
     * ログクラスがセットされていない場合は何もしない
     *
     * @param unknown_type $message
     * @param unknown_type $priority
     */
    static public function logWrite($message= '', $priority = Zend_Log::INFO)
    {
        try {
            if (self::$_logger !== null) {
                $logger = self::$_logger;
                $logger->log($message, $priority);

                return;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * スタティックに保存されたログクラスを利用してパラメーターつきログを記録
     *
     * @param unknown_type $message
     * @param unknown_type $params
     * @param unknown_type $priority
     */
    static public function logWithFileAndParamsWrite($message= '', $priority = Zend_Log::INFO, $params = array())
    {
        try {
            if (self::$_logger !== null) {
                $logger = self::$_logger;
                $logger->logWithFileAndParams($message, $priority, $params, 1);

                return;
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ログ記録
     *
     * ログを出力する。
     * ・sessionがZend_Sessionで開始されていれば、セッションIDも出力する。
     * ・Zend_Authで認証されている場合、identityのuidがセットされていれば出力する。
     *
     */
    public function log($message= '', $priority = Zend_Log::INFO)
    {
        // ユーザー、セッション情報
        $additionalString = '';
        if (Zend_Session::isStarted()) {
            $uid = "nologin";
            $arrayAuthKeys = Bshe_Auth::getInstanceNames();
            foreach ($arrayAuthKeys as $key => $keyName) {
                $auth = Bshe_Auth::getInstance($keyName);
                if ($auth->hasIdentity()) {
                    $identity = $auth->getIdentity();
                    if (is_a($identity)) {
                    }
                    elseif (isset($identity['uid'])) {
                        $uid .= $keyName . '(' . $identity['uid'] . ')';
                    }
                }
            }

            $sessionId = Zend_Session::getId();
        } else {
            $uid = "nologin";
            $sessionId = "nosession";
        }
        $additionalString = $uid . ': ' .  $sessionId;
        $message = $additionalString . ': ' . $message;

        parent::log($message, $priority);
        return;
    }


    /**
     * パラメーターつきログ
     * 呼出元のファイル・メソッド情報も併せて記録する
     *
     * @param unknown_type $message
     * @param unknown_type $params
     * @param unknown_type $priority
     */
    public function logWithFileAndParams($message= '', $priority = Zend_Log::INFO, $params = array(), $traceLevel = 0)
    {
        $strAdditionalString = "";

        // トレース取得
        $trace = debug_backtrace();
        if (count($trace) >= 2) {
            // 呼出元ファイル、行数、メソッド取得
            $file = $trace[$traceLevel]['file'];
            $line = $trace[$traceLevel]['line'];
            $method = $trace[$traceLevel+1]['function'];
            // メッセージの頭にセット
            $strAdditionalString = $file . '(' . $line . ')::' . $method . ': ';
        }
        // パラメーター
        foreach ($params as $key => $value) {
            if (is_array( $value)) {
                $strAdditionalString .= $key . ' => ' . print_r($value, true) . ': ';
            } else {
                $strAdditionalString .= $key . ' => ' . $value . ': ';
            }
        }

        $this->log($strAdditionalString . $message, $priority);
    }
}
