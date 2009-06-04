<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/**
 * Bshe_Viewのテスト用PHP
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.18
 * @license LGPL
 */
    // PHP.iniの修正定義
    ini_set('default_charset','UTF-8');
    ini_set('mbstring.http_output', 'UTF-8');
    ini_set('mbstring.internal_encoding', 'UTF-8');
    ini_set('mbstring.http_input', 'auto');

    // path設定（そのまま動かすためにつけていますが、$mainPathを手で記入するならカットできます）
        require_once 'setPath.inc';

    // logger設定（不要な場合はカット）
    //    require_once 'setLogger.inc';

            // アプリケーションパス取得
            $mainPath = Bshe_Controller_Init::getMainPath();
            // Viewクラスインスタンス化
            $params =
                array(
                    'templatePath' => $mainPath . '/template/test',
                    'templateFile' => 'viewtest.html',
                    'assignClassPath' => array($mainPath . '/application/test/views/resource'),
                    'assignClassPrefix' => 'Test_Resource_',
                    'helperClassPath' => array($mainPath . '/application/test/views/helper'),
                    'helperClassPrefix' => 'Test_Helper_'
                );
            $view = New Bshe_View( $params);

            // ロギングクラスセット
/*            require_once 'Bshe/Log.php';
            $logger = new Bshe_Log();
            require_once 'Bshe/Log/Writer/Dailystream.php';
            $writer = new Bshe_Log_Writer_Dailystream( ここにログのパスを入れる, ログファイルの頭につける文字列);
            $logger->addWriter($writer);
            $view->setLogger($logger);
*/

            // 単純表示テスト
            $view->a = 'a text test';
            $view->c = 'http://bshe.itassist.info/themes/cube_default/images/logo.gif';
            $view->d = 123456.925;

            // 指定表示テスト
            $view->b = array( 'a' => array( 'style' ,'color: #FF0000;'));
            $view->b = '赤色表示';


            // テーブル表示テスト
            $view->tablevalues =
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
            $view->tablevalues2 =
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
            $view->tablevalues4 =
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
            $view->tablevalues3 =
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
            $view->e = array( 'd' => 'aaa');

            // タグ削除
            $view->f = array( 'r' => '');

            // select生成（optiongroupなし）
            $view->selecttbl =
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
            $view->selecttbl2 =
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
            $view->tablevalues_ = $tablevalues_->getDataArray();

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
            $view->tablevalues2_ = $tablevalues_->getDataArray();
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
            $view->tablevalues4_ = $tablevalues_->getDataArray();

            $view->render();