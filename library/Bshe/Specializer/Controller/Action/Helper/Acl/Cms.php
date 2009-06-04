<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */
/** Bshe_Exception */
require_once "Bshe/Exception.php";

/**
 * Bshe_Specializer用ACLヘルパー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.04.19
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Helper_Acl_Cms extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Aclのインスタンス生成
     *
     * @return unknown_type
     */
    public function __construct()
    {
        try {
            Bshe_Specializer_Acl::setAcl(new Bshe_Acl_Standalone());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * リソースに対して権限がアルカチェックする
     * $isExit === false の時はアクセス可・不可を返す。
     */
    public function check( $resource, $priv, $isExit = true)
    {
        $flag = FALSE;

        if(Bshe_Specializer_Acl::isAllowedByUserid($resource, $priv)) {
            $flag = TRUE ;
        }

        Bshe_Log::logWrite( __METHOD__. ":resource[$resource] priv[$priv] isExit[" . var_export( $isExit, TRUE) . "] result : [" . var_export( $flag, TRUE) . "]", Zend_Log::DEBUG);

        if ( false === $isExit  ) {
            // exit 指定でない時は、アクセス可・不可を返す。
            return $flag ;
        }

        if( ! $flag ){
            $auth = Bshe_Auth::getInstance();
            $userId =  $auth->getIdentity()->userid;

            $res = $this->_actionController->getResponse();

            $res->setBody( "userid:$userid<BR> " ) ;
            $res->appendBody( "resource:$resource<BR> " ) ;
            $res->appendBody( "priv:    $priv<BR> " ) ;
            $res->appendBody('アクセスが拒否されました。');
            $res->sendResponse();
            exit();
        }

        return $flag;
    }

    /**
     * ヘルパーとして呼ばれる関数
     *
     */
    public function direct( $resource, $priv, $isExist = true ) {
        return $this->check( $resource, $priv, $isExist );
    }
}
