<?php
/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';
/** Zend_Registry */
require_once 'Zend/Registry.php';
/** Bshe_View */
require_once 'Bshe/View.php';

/**
 * Bshe_Viewテスト
 *
 * @author Yuichiro Abe
 * @created 2008.09.06
 * @license LGPL
 */
class Test_ViewController extends Bshe_Specializer_Controller_Action_Default
{

    /**
     * selectprefヘルパーテスト
     * @return unknown_type
     */
    public function selectprefAction()
    {
        try {
            $this->view->selectval = '東京都';
            $this->view->selectval2 = '03';

        } catch (Exception $e) {
            throw $e;
        }
    }



    /**
     * selectヘルパーテスト
     * @return unknown_type
     */
    public function selectAction()
    {
        try {
            $this->view->selectval = '3';
            $this->view->selectvals =
                array(
                    '0' => 'a',
                    '1' => 'b',
                    '2' => 'c',
                    '3' => 'd',
                    '4' => 'e'
                );

        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * テンプレートへの基本出力テスト
     *
     */
    public function indexAction()
    {
        try {
            // アプリケーションパス取得
            $mainPath = Bshe_Controller_Init::getMainPath();

            // 単純表示テスト
            $this->view->a = 'a text test';
            $this->view->c = 'http://bshe.itassist.info/themes/cube_default/images/logo.gif';
            $this->view->d = 123456.925;

            // 指定表示テスト
            $this->view->b = array( 'a' => array( 'style' ,'color: #FF0000;'));
            $this->view->b = '赤色表示';


            // テーブル表示テスト
            $this->view->tablevalues =
                array(
                    '_values' =>
                        array(
                            0 => array(
                                'cel1' => 'a0',
                                'cel2' =>
                                    array(
                                        // 色を指定
                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                        array( 'a' => array( 'innerHTML' ,'a1'))
                                    ),
                                'cel3' => 'a2'
                                ),
                            1 => array(
                                'cel1' => 'b0',
                                'cel2' => 'b1',
                                'cel3' => 'b2',
                                '_assigns' =>
                                    array(
                                        // 色を指定
                                        array( 'a' => array( 'bordercolor' ,'#00FF00'))
                                    )
                                ),
                            2 => array(
                                'cel1' => 'c0',
                                'cel2' => 'c1',
                                'cel3' => 'c2'
                                ),
                            3 => array(
                                'cel1' => 'd0',
                                'cel2' =>
                                    array(
                                        // 色を指定
                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                        array( 'a' => array( 'innerHTML' ,'d1'))
                                    ),
                                'cel3' => 'd2'
                                ),
                            4 => array(
                                'cel1' => 'e0',
                                'cel2' => 'e1',
                                'cel3' => 'e2',
                                '_assigns' =>
                                    array(
                                        // 色を指定
                                        array( 'a' => array( 'bordercolor' ,'#00FF00'))
                                    )
                                ),
                            5 => array(
                                'cel1' => 'f0',
                                'cel2' => 'f1',
                                'cel3' => 'f2'
                                )
                        ),
                    '_assigns' =>
                        array(
                            // 色を指定
                            array( 'a' => array( 'bordercolor' ,'#FFFFFF'))
                        )
                );

            // 多階層テーブル
            $this->view->tablevalues2 =
                array(
                    '_values' =>
                        array(
                            0 => array(
                                    'cel1' => 'a0',
                                    'cel2' =>
                                        array(
                                            array( 'a' => array( 'bordercolor' ,'#FF0000'))
                                        ),
                                    '_values' =>
                                        array(
                                            'sub1' =>
                                                array(
                                                    '_values' => array(
                                                        0 =>array(
                                                            'cel1' => 'aa0',
                                                            'cel2' => 'aa1',
                                                            'cel3' => 'aa2'
                                                        ),
                                                        1 =>array(
                                                            'cel1' => 'aaa0',
                                                            'cel2' => 'aaa1',
                                                            'cel3' => 'aaa2'
                                                        )
                                                    ),
                                                     '_assigns' =>
                                                        array(
                                                            // 色を指定
                                                            array( 'a' => array( 'bordercolor' ,'#0000FF'))
                                                        )

                                                )

                                        )
                                ),
                            1 => array(
                                    'cel1' => 'b0',
                                    '_assigns' =>
                                        array(
                                            // 色を指定
                                            array( 'a' => array( 'bordercolor' ,'#00FF00'))
                                        ),
                                    '_values' =>
                                        array(
                                            'sub1' =>
                                                array(
                                                    '_values' => array(
                                                        0 =>array(
                                                            'cel1' => 'ab0',
                                                            'cel2' => 'ab1',
                                                            'cel3' => 'ab2'
                                                        ),
                                                        1 =>array(
                                                            'cel1' => 'aba0',
                                                            'cel2' => 'aba1',
                                                            'cel3' => 'aba2'
                                                        )
                                                    )
                                                )
                                        )
                                ),
                            2 => array(
                                    'cel1' => 'c0',
                                    '_values' =>
                                        array(
                                            'sub1' =>
                                                array(
                                                    '_values' =>
                                                        array(
                                                            0 =>array(
                                                                'cel1' => 'ac0',
                                                                'cel2' => 'ac1',
                                                                'cel3' => 'ac2'
                                                            ),
                                                            1 =>array(
                                                                'cel1' => 'aca0',
                                                                'cel2' => 'aca1',
                                                                'cel3' =>
                                                                     array(
                                                                        // 色を指定
                                                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                                                        array( 'a' => array( 'innerHTML' ,'aca2'))
                                                                     )
                                                            )
                                                        )
                                                )
                                        )
                                )
                        ),
                    '_assigns' =>
                        array(
                            // 色を指定
                            array( 'a' => array( 'bordercolor' ,'#FFFFFF'))
                        )
                );
            $this->view->tablevalues4 =
                array(
                    '_values' =>
                        array(
                            0 => array(
                                    'cel2' =>
                                        array(
                                            array( 'a' => array( 'bordercolor' ,'#FF0000'))
                                        ),
                                    '_values' =>
                                        array(
                                            'sub1' =>
                                                array(
                                                    '_values' => array(
                                                        0 =>array(
                                                            'cel1' => 'aa0',
                                                            'cel2' => 'aa1',
                                                            'cel3' => 'aa2'
                                                        ),
                                                        1 =>array(
                                                            'cel1' => 'aaa0',
                                                            'cel2' => 'aaa1',
                                                            'cel3' => 'aaa2'
                                                        )
                                                    ),
                                                     '_assigns' =>
                                                        array(
                                                            // 色を指定
                                                            array( 'a' => array( 'bordercolor' ,'#0000FF'))
                                                        )

                                                )

                                        )
                                ),
                            1 => array(
                                    '_assigns' =>
                                        array(
                                            // 色を指定
                                            array( 'a' => array( 'bordercolor' ,'#00FF00'))
                                        ),
                                    '_values' =>
                                        array(
                                            'sub1' =>
                                                array(
                                                    '_values' => array(
                                                        0 =>array(
                                                            'cel1' => 'ab0',
                                                            'cel2' => 'ab1',
                                                            'cel3' => 'ab2'
                                                        ),
                                                        1 =>array(
                                                            'cel1' => 'aba0',
                                                            'cel2' => 'aba1',
                                                            'cel3' => 'aba2'
                                                        )
                                                    )
                                                )
                                        )
                                ),
                            2 => array(
                                    '_values' =>
                                        array(
                                            'sub1' =>
                                                array(
                                                    '_values' =>
                                                        array(
                                                            0 =>array(
                                                                'cel1' => 'ac0',
                                                                'cel2' => 'ac1',
                                                                'cel3' => 'ac2'
                                                            ),
                                                            1 =>array(
                                                                'cel1' => 'aca0',
                                                                'cel2' => 'aca1',
                                                                'cel3' =>
                                                                     array(
                                                                        // 色を指定
                                                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                                                        array( 'a' => array( 'innerHTML' ,'aca2'))
                                                                     )
                                                            )
                                                        )
                                                )
                                        )
                                )
                        ),
                    '_assigns' =>
                        array(
                            // 色を指定
                            array( 'a' => array( 'bordercolor' ,'#FFFFFF'))
                        )
                );
            // マトリクス内に通常テーブル
            $this->view->tablevalues3 =
                array(
                    '_values' =>
                        array(
                            0 => array(
                                '_values' => array(
                                    'cels' => array(
                                        '_values' => array(
                                            0 => array(
                                                'cel1' => 'a0',
                                                'cel2' =>
                                                    array(
                                                        // 色を指定
                                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                                        array( 'a' => array( 'innerHTML' ,'a1'))
                                                    ),
                                                'cel3' => 'a2'
                                                ),
                                            1 => array(
                                                'cel1' => 'aa0',
                                                'cel2' =>
                                                    array(
                                                        // 色を指定
                                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                                        array( 'a' => array( 'innerHTML' ,'aa1'))
                                                    ),
                                                'cel3' => 'aa2'
                                                )
                                            )
                                        )
                                    )
                                ),
                            1 => array(
                                '_values' => array(
                                    'cels' => array(
                                        '_values' => array(
                                            0 => array(
                                                'cel1' => 'b0',
                                                'cel2' => 'b1',
                                                'cel3' => 'b2',
                                                '_assigns' =>
                                                    array(
                                                        // 色を指定
                                                        array( 'a' => array( 'bordercolor' ,'#00FF00'))
                                                    )
                                                )
                                            )
                                        )
                                    )
                                ),
                            2 => array(
                                '_values' => array(
                                    'cels' => array(
                                        '_values' => array(
                                            0 => array(
                                                'cel1' => 'c0',
                                                'cel2' => 'c1',
                                                'cel3' => 'c2'
                                                )
                                            )
                                        )
                                    )
                                ),
                            3 => array(
                                '_values' => array(
                                    'cels' => array(
                                        '_values' => array(
                                            0 => array(
                                                'cel1' => 'd0',
                                                'cel2' =>
                                                    array(
                                                        // 色を指定
                                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                                        array( 'a' => array( 'innerHTML' ,'d1'))
                                                    ),
                                                'cel3' => 'd2'
                                                ),
                                            1 => array(
                                                'cel1' => 'dd0',
                                                'cel2' =>
                                                    array(
                                                        // 色を指定
                                                        array( 'a' => array( 'bordercolor' ,'#FF0000')),
                                                        array( 'a' => array( 'innerHTML' ,'dd1'))
                                                    ),
                                                'cel3' => 'dd2'
                                                )
                                            )
                                        )
                                    )
                                ),
                            4 => array(
                                '_values' => array(
                                    'cels' => array(
                                        '_values' => array(
                                            0 => array(
                                                'cel1' => 'e0',
                                                'cel2' => 'e1',
                                                'cel3' => 'e2',
                                                '_assigns' =>
                                                    array(
                                                        // 色を指定
                                                        array( 'a' => array( 'bordercolor' ,'#00FF00'))
                                                    )
                                                )
                                            )
                                        )
                                    )
                                ),
                            5 => array(
                                '_values' => array(
                                    'cels' => array(
                                        '_values' => array(
                                            0 => array(
                                                'cel1' => 'f0',
                                                'cel2' => 'f1',
                                                'cel3' => 'f2'
                                                )
                                            )
                                        )
                                    )
                                )
                        ),
                    '_assigns' =>
                        array(
                            // 色を指定
                            array( 'a' => array( 'bordercolor' ,'#FFFFFF'))
                        )
                );
            // 属性削除
            $this->view->e = array( 'd' => 'aaa');

            // タグ削除
            $this->view->f = array( 'r' => '');

            // select生成（optiongroupなし）
            $this->view->selecttbl =
                array(
                    '_values' => array(
                        0 => array(
                            array('a' => array( 'value' ,'cel1')),
                            array('a' => array( 'innerHTML' ,'cel1text'))
                        ),
                        1 => array(
                            0 => array('a' => array( 'value' ,'cel2')),
                            1 => array('a' => array( 'innerHTML' ,'cel2text'))
                        ),
                        2 => array(
                            0 => array('a' => array( 'value' ,'cel3')),
                            1 => array('a' => array( 'innerHTML' ,'cel3text'))
                        )
                    ),
                    '_assigns' => array(
                        array( 'a' => array( 'size' ,'2'))
                    )
                );

            // select生成（optiongroupあり）
            $this->view->selecttbl2 =
                array(
                    '_values' => array(
                        0 => array(
                            '_assigns' => array(
                                    array('a' => array('label', 'グループ1'))
                                ),
                            '_values' => array(
                                    0 => array(
                                            array('a' => array( 'value' ,'cel1')),
                                            array('a' => array( 'innerHTML' ,'cel1text')
                                        )
                                    ),
                                    1 => array(
                                            array('a' => array( 'value' ,'cel2')),
                                            array('a' => array( 'innerHTML' ,'cel2text')
                                        )
                                    ),
                                    2 => array(
                                            array('a' => array( 'value' ,'cel3')),
                                            array('a' => array( 'innerHTML' ,'cel3text')
                                        )
                                    )
                                )
                        ),
                        1 => array(
                            '_assigns' => array(
                                    array('a' => array('label', 'グループ2'))
                                ),
                            '_values' => array(
                                    0 => array(
                                            array('a' => array( 'value' ,'cel4')),
                                            array('a' => array( 'innerHTML' ,'cel4text')
                                        )
                                    )
                                )
                        )
                    ),
                    '_assigns' => array(
                        array( 'a' => array( 'size' ,'2'))
                    )
                );


            // テーブル表示テスト（配列生成メソッド利用）
            $tablevalues_ = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 => array(
                        'cel1' => 'a0',
                        'cel2' => 'a1',
                        'cel3' => 'a2'
                        ),
                    1 => array(
                        'cel1' => 'b0',
                        'cel2' => 'b1',
                        'cel3' => 'b2'
                        ),
                    2 => array(
                        'cel1' => 'c0',
                        'cel2' => 'c1',
                        'cel3' => 'c2'
                        ),
                    3 => array(
                        'cel1' => 'd0',
                        'cel2' => 'd1',
                        'cel3' => 'd2'
                        ),
                    4 => array(
                        'cel1' => 'e0',
                        'cel2' => 'e1',
                        'cel3' => 'e2'
                        ),
                    5 => array(
                        'cel1' => 'f0',
                        'cel2' => 'f1',
                        'cel3' => 'f2'
                        )
                );
            $tablevalues_->setDataArray($arrayData);
            $arrayData = array(
                    0 => array(
                        'cel2' => array( 'a' => array( 'bordercolor' ,'#FF0000'))
                        ),
                    1 => array(
                        'cel3' => array( 'a' => array( 'bordercolor' ,'#00FF00'))
                        ),
                    3 => array(
                        'cel2' => array( 'a' => array( 'bordercolor' ,'#FF0000'))
                        ),
                    4 => array(
                        '_assigns' => array( 'a' => array( 'bordercolor' ,'#FF0000'))
                        ),

                );
            $tablevalues_->setAtribute($arrayData);
            $this->view->tablevalues_ = $tablevalues_->getDataArray();

            // 多階層テーブル（配列生成メソッド利用）
            $subtablevalues_[0] = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'cel1' => 'aa0',
                        'cel2' => 'aa1',
                        'cel3' => 'aa2'
                    ),
                    1 =>array(
                        'cel1' => 'aaa0',
                        'cel2' => 'aaa1',
                        'cel3' => 'aaa2'
                    )
                );
            $subtablevalues_[0]->setDataArray($arrayData);
            $subtablevalues_[1] = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'cel1' => 'ab0',
                        'cel2' => 'ab1',
                        'cel3' => 'ab2'
                    ),
                    1 =>array(
                        'cel1' => 'aba0',
                        'cel2' => 'aba1',
                        'cel3' => 'aba2'
                    )
                );
            $subtablevalues_[1]->setDataArray($arrayData);
            $subtablevalues_[2] = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'cel1' => 'ac0',
                        'cel2' => 'ac1',
                        'cel3' => 'ac2'
                    ),
                    1 =>array(
                        'cel1' => 'aca0',
                        'cel2' => 'aca1',
                        'cel3' => 'aca2'
                    )
                );
            $subtablevalues_[2]->setDataArray($arrayData);
            $arrayData = array(
                    0 => array(
                        'cel3' => array( 'a' => array( 'bordercolor' ,'#FF0000'))
                        )
                    );
            $subtablevalues_[2]->setAtribute($arrayData);
            $tablevalues_ = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'cel1' => 'a0',
                        'sub1' => $subtablevalues_[0]
                    ),
                    1 =>array(
                        'cel1' => 'b0',
                        'sub1' => $subtablevalues_[1]
                    ),
                    2 =>array(
                        'cel1' => 'c0',
                        'sub1' => $subtablevalues_[2]
                    )
                );
            $tablevalues_->setDataArray($arrayData);
            $arrayData = array(
                    0 => array(
                        'cel2' => array( 'a' => array( 'bordercolor' ,'#FF0000'))
                        ),
                    1 => array(
                        '_assigns' => array( 'a' => array( 'bordercolor' ,'#00FF00'))
                        ),
                    '_assigns' => array( 'a' => array( 'bordercolor' ,'#FFFFFF'))
                );
            $tablevalues_->setAtribute($arrayData);
            $arrayData = array(
                    0 => array(
                        'sub1' => array( 'a' => array( 'bordercolor' ,'#0000FF'))
                        )
                );
            $tablevalues_->setAtribute($arrayData);
            $this->view->tablevalues2_ = $tablevalues_->getDataArray();
            $subtablevalues_[0] = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'cel1' => 'aa0',
                        'cel2' => 'aa1',
                        'cel3' => 'aa2'
                    ),
                    1 =>array(
                        'cel1' => 'aaa0',
                        'cel2' => 'aaa1',
                        'cel3' => 'aaa2'
                    )
                );
            $subtablevalues_[0]->setDataArray($arrayData);
            $subtablevalues_[1] = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'cel1' => 'ab0',
                        'cel2' => 'ab1',
                        'cel3' => 'ab2'
                    ),
                    1 =>array(
                        'cel1' => 'aba0',
                        'cel2' => 'aba1',
                        'cel3' => 'aba2'
                    )
                );
            $subtablevalues_[1]->setDataArray($arrayData);
            $subtablevalues_[2] = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'cel1' => 'ac0',
                        'cel2' => 'ac1',
                        'cel3' => 'ac2'
                    ),
                    1 =>array(
                        'cel1' => 'aca0',
                        'cel2' => 'aca1',
                        'cel3' => 'aca2'
                    )
                );
            $subtablevalues_[2]->setDataArray($arrayData);
            $arrayData = array(
                    0 => array(
                        'cel3' => array( 'a' => array( 'bordercolor' ,'#FF0000'))
                        )
                    );
            $subtablevalues_[2]->setAtribute($arrayData);
            $tablevalues_ = New Bshe_View_Resource_Html_Table();
            $arrayData = array(
                    0 =>array(
                        'sub1' => $subtablevalues_[0]
                    ),
                    1 =>array(
                        'sub1' => $subtablevalues_[1]
                    ),
                    2 =>array(
                        'sub1' => $subtablevalues_[2]
                    )
                );
            $tablevalues_->setDataArray($arrayData);
            $arrayData = array(
                    0 => array(
                        'cel2' => array( 'a' => array( 'bordercolor' ,'#FF0000'))
                        ),
                    1 => array(
                        '_assigns' => array( 'a' => array( 'bordercolor' ,'#00FF00'))
                        ),
                    '_assigns' => array( 'a' => array( 'bordercolor' ,'#FFFFFF'))
                );
            $tablevalues_->setAtribute($arrayData);
            $arrayData = array(
                    0 => array(
                        'sub1' => array( 'a' => array( 'bordercolor' ,'#0000FF'))
                        )
                );
            $tablevalues_->setAtribute($arrayData);
            $this->view->tablevalues4_ = $tablevalues_->getDataArray();

//            $this->view->render();
        }
        catch( Exception $e)
        {
            throw $e;
        }
    }

    /**
     * syslogへのログ出力テスト
     *
     */
    public function notemplateAction()
    {
        try {
            // アプリケーションパス取得
            $mainPath = Bshe_Controller_Init::getMainPath();
            // Viewクラスインスタンス化
            $params =
                array(
                    'templatePath' => $mainPath . '/template',
                    'templateFile' => 'aaaa.html',
                    'key' => 'key'
                );
            $this->view = New Bshe_View( $params);

            // 単純表示テスト
            $this->view->a = 'a text test';


            $this->view->render();
        }
        catch( Exception $e)
        {
            throw $e;
        }
    }
}