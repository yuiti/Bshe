<?php
/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';
/** Zend_Log */
require_once 'Zend/Log.php';
/** Bshe_Log_Writer_Syslog */
require_once 'Bshe/Log/Writer/Syslog.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_Log_Writer_Dailystream */
require_once 'Bshe/Log/Writer/Dailystream.php';
/** Bshe_Log_Writer_Maillog */
require_once 'Bshe/Log/Writer/Maillog.php';
/** Bshe_Log_Formatter_Maillog */
require_once 'Bshe/Log/Formatter/Maillog.php';



/**
 * Bshe_Logテスト
 *
 * @author Yuichiro Abe
 * @created 2008.09.06
 * @license LGPL
 */
class Test_LogController extends Zend_Controller_Action
{
    /**
     * syslogへのログ出力テスト
     *
     */
    public function syslogAction()
    {
        // ログクラスセット
        $logger = new Zend_Log();
        $writer = new Bshe_Log_Writer_Syslog( 'aaaa',0,null);
        $logger->addWriter($writer);

        // ログ記録
        $logger->log( 'ログテスト', Zend_Log::INFO);
        $logger->log( 'ログテスト2', Zend_Log::WARN);

        $view = $this->initView();
        $view->msg = 'ログ出力テスト完了';

        $this->render();
    }

    /**
     * yyyymmdd.log形式のテスト
     *
     */
    public function dailystreamAction()
    {
        // ログクラスセット
        $logger = new Bshe_Log();
        $writer = new Bshe_Log_Writer_Dailystream( '/home/abe/myframework/logs', 'Bshe_');
        $logger->addWriter($writer);

        // ログ記録
        $logger->log( 'ログテスト', Zend_Log::INFO);
        $logger->logWithFileAndParams( 'ログテスト2', Zend_Log::WARN);

        $view = $this->initView();
        $view->msg = 'yyyymmdd.log形式ログ出力テスト完了';

        $this->render();
    }



    /**
     * maillogへのログ出力テスト
     *
     */
    public function maillogAction()
    {
        // ログクラスセット
        $logger = new Zend_Log();
        $writer = new Bshe_Log_Writer_Maillog( array('mailtest@itassist.info'), $subject = "テストログメール[%priorityName%]", array( 'mailtest@itassist.info', 'テストログFROM'), array( 'mailtest@itassist.info'));
        // ログフォーマット設定
        $fomatter = New Bshe_Log_Formatter_Maillog( "テストログメッセージ\r\nこのメールはテストメールプログラムから送信されています\r\n" .
            '%timestamp% %priorityName% (%priority%): %message%');
        $writer->setFormatter( $fomatter);
        // メール設定
        $mail = $writer->getMail();
        $mail->IsSMTP();
        $mail->Host = 'localhost';

        $writer->setMail( $mail);
        $logger->addWriter($writer);

        // ログ記録
        $logger->log( 'ログテスト', Zend_Log::INFO);
        $logger->log( 'ログテスト2', Zend_Log::WARN);

        $view = $this->initView();
        $view->msg = 'メールログ出力テスト完了';

        $this->render();
    }

//    /**
//     * 複数のライターに対して
//     * 　フィルターをそれぞれセットするテスト
//     *
//     */
//    public function multifilterAction()
//    {
//        $logger = new Bshe_Log();
//        // ライター１
//        $writer1 = new Bshe_Log_Writer_Syslog( 'aaaa',0,null);
//        $filters1[] = new Zend_Log_Filter_Priority( Zend_Log::INFO);
//        // ライター２
//        $writer2 = new Zend_Log_Writer_Stream( '/var/tmp/logtest.txt', 'a');
//        $filters2[] = new Zend_Log_Filter_Priority( Zend_Log::WARN);
//        // ライター、フィルターセット
//        $logger->addWriterWithFilter( $writer1, $filters1);
//        $logger->addWriterWithFilter( $writer2, $filters2);
//
//        $logger->log( '複数ライター複数フィルターログ出力テスト　INFOログテスト', Zend_Log::INFO);
//        $logger->log( '複数ライター複数フィルターログ出力テスト　WARNログテスト', Zend_Log::WARN);
//
//        $view = $this->initView();
//        $view->msg = '複数ライター複数フィルターログ出力テスト完了';
//
//        $this->render();
//    }

}