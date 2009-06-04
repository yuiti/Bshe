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
/** Bshe_Abstract */
require_once 'Bshe/Abstract.php';

/**
 * Bsheにてデータをパッケージングする抽象クラス
 *
 *
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.11.18
 * @license LGPL
 */
abstract class Bshe_Datapack_Abstract extends Bshe_Abstract
{
    /**
     * セッション変数名
     *
     * @var string
     */
    protected $sessionName;

    /**
     * セッション名前空間
     *
     * @var Zend_Session_Namespace
     */
    protected $sessionNamespace;


    /**
     * 自身をセッションへ保存する
     * セッション名は、アプリケーションコード＋クラス名または$sessionName
     *
     * @param string $sessionName
     */
    public function saveDatapackSession($sessionName = null)
    {
        try {
            if ($sessionName === null) {
                // デフォルト
                $this->sessionName = get_class($this);
            } else {
                // 指定したセッションnamespace
                $this->sessionName = $sessionName;
            }
            $this->sessionNamespace = new Bshe_Session_Namespace($this->sessionName);

            $this->sessionNamespace->datapack = serialize($this);

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * セッションから自分自身をロードする
     *
     * @param unknown_type $sessionName
     */
    public function getDatapackSession($sessionName = null)
    {
        try {
            if ($sessionName === null) {
                // デフォルト
                $this->sessionName = get_class($this);
            } else {
                // 指定したセッションnamespace
                $this->sessionName = $sessionName;
            }
            $this->sessionNamespace = new Bshe_Session_Namespace($this->sessionName);

            $rsl = $this->sessionNamespace->datapack;
            if ($rsl == null) {
                return $this;
            } else {
                return unserialize($rsl);
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

}