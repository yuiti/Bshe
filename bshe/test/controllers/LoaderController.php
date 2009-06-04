<?php
/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';
/** Models_Test */
require_once 'models/Test.php';
/** Test_Models_Test */
require_once 'test/models/Test.php';


/**
 * Bshe_Loaderテスト
 *
 * @author Yuichiro Abe
 * @created 2008.09.06
 * @license LGPL
 */
class Test_LoaderController extends Zend_Controller_Action
{
    /**
     * application/controllers/models以下のファイルのautoloadテスト
     *
     */
    public function modelAction()
    {
        $clsTest1 = New Models_Test();
        $strTmp = $clsTest1->loaderTest1();

        $view = $this->initView();
        $view->msg = $strTmp;

        $this->render();
    }

    /**
     * application/controllers/test/models以下のファイルのautoloadテスト
     *
     */
    public function testmodelAction()
    {
        $clsTest2 = New Test_Models_Test();
        $strTmp = $clsTest2->loaderTest2();

        $view = $this->initView();
        $view->msg = $strTmp;

        $this->render();
    }

}