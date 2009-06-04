<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Mobile
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @needs      PEAR:Net_UserAgent_Mobile 1.0.0RC1
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Session */
require_once 'Bshe/Session.php';
/** PEAR:Net_UserAgent_Mobile */
require_once('Net/UserAgent/Mobile.php');


/**
 * 携帯向けセッションを実現するためのクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.26
 * @license LGPL
 */
class Bshe_Mobile_Session extends Bshe_Session
{

    /**
     * sessionのスタート
     * 同時にキャリアチェックを行いクッキーの判別を実施
     *
     */
    static public function start()
    {
        try {
            $agent = Net_UserAgent_Mobile::singleton();
            if ($agent->isDocomo())
            {
                // DOCOMOの場合のみクッキー以外を許可
                self::setOptions(array('use_only_cookies' => false));
            }
            parent::start();
        } catch (Exception $e) {
            throw $e;
        }
    }


}