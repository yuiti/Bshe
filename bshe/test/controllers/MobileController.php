<?php
/** Bshe_Specializer_Controller_Action_Abstract */
require_once 'Bshe/Specializer/Controller/Action/Abstract.php';
/** Bshe_Mobile_Session */
require_once 'Bshe/Mobile/Session.php';
/**
 * Bshe_Mobileテスト
 *
 * @author Yuichiro Abe
 * @created 2008.09.06
 * @license LGPL
 */
class Test_MobileController extends Bshe_Specializer_Controller_Action_Abstract
{
    /**
     * キャリア別セッション判別テスト
     *
     */
    public function sessionAction()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $mainPath = Bshe_Controller_Init::getMainPath();

            $params =
                array(
                    'templatePath' => $mainPath . $config->template_path,
                    'afterAssigns' => array(
                        array(
                            'className' => 'Bshe_View_Plugin_Mobile',
                            'methodName' => 'setSessionIdForDocomo'
                        )
                    )
                );
            $this->view = New Bshe_View($params);

            // セッション処理
            Bshe_Mobile_Session::start();

            $this->view->sessionid = Bshe_Mobile_Session::getId();

            $this->view->render($this->getRequest()->getPathInfo());
        } catch (Exception $e) {
            throw $e;
        }
    }

}