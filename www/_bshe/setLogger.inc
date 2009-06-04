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
 * Asheライブラリを利用する際のログ記録を設定
 *
 * 必要なログライタをBshe_Logクラスのインスタンスへ設定し
 * Zend_Registryにてloggerという名称のレジストリに登録する
 *
 * @author Yuichiro Abe
 * @created 2008.09.09
 * @license LGPL
 */

require_once 'Bshe/Log.php';

$logger = new Bshe_Log();

/**
 * メールログ設定例
 *
 */
/**
    $writer = new Bshe_Log_Writer_Maillog( array('logadmin@itassist.info'), $subject = "Asheログメール[%priorityName%]", array( 'logadmin@itassist.info', 'テストログFROM'));
    // ログフォーマット設定
    $fomatter = New Bshe_Log_Formatter_Maillog( "ログメッセージ\r\nこのメールはAsheテスト環境プログラムから送信されています\r\n" .
        '%timestamp% %priorityName% (%priority%): %message%');
    $writer->setFormatter( $fomatter);
    // メール設定
    $mail = $writer->getMail();
    $mail->IsSMTP();
    $mail->Host = 'localhost';
    $writer->setMail( $mail);
    $logger->addWriter($writer);
*/

/**
 * ファイルログ設定例
 *
 *
 */
    require_once 'Bshe/Log/Writer/Dailystream.php';
    $writer = new Bshe_Log_Writer_Dailystream(Bshe_Controller_Init::getMainPath() . '/logs', 'Bshe_');
    $logger->addWriter($writer);


// フィルターの設定
//$filters[] = new Zend_Log_Filter_Priority( Zend_Log::INFO);
//$logger->addWriterWithFilter( $writer, $filters);

/**
 * syslog設定例
 *
 */
/**
    $writer = new Bshe_Log_Writer_Syslog( 'AsheTest');
    $logger->addWriter($writer);
*/
Bshe_Log::setLogger( $logger);

