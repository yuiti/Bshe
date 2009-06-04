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


/** Zend_Controller_Plugin_Abstract */
require_once 'Zend/Controller/Plugin/Abstract.php';

/**
 * アクセスログを保存するプラグイン
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.05.18
 * @license LGPL
 */
class Bshe_Specializer_Controller_Plugin_Accesslog extends Zend_Controller_Plugin_Abstract
{
    /**
     * ログクラス
     */
    protected $logger = null;

    /**
     * ログクラスセット
     *
     * @param $logger
     * @return unknown_type
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function __construct($accessLogger)
    {
        $this->setLogger($accessLogger);
    }

    /**
     * アクセスログセットプラグイン
     *
     * ・URL
     * ・セッションのUID
     * を淡々とログに保存する
     *
     *
     * @param $request
     * @return unknown_type
     */
    public function postDispatch($request)
    {
        try {
            $pathInfo = $request->getPathInfo();
            $this->logger->logWithFileAndParams($message, Zend_Log::INFO, array('pathinfo' => $pathInfo));

        } catch (Exception $e) {
            throw $e;
        }
    }
}