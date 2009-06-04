<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Log
 * @subpackage Log
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Zend_Log_Writer_Abstract */
require_once 'Zend/Log/Writer/Abstract.php';
/** Zend_Exception */
require_once 'Zend/Exception.php';

/** Bshe_Log_Formatter_Maillog */
require_once 'Bshe/Log/Formatter/Maillog.php';

/** jphpmailer */
require_once 'Jphpmailer/jphpmailer.php';

/**
 * ログをMailするZend_Log用writer
 * メール送信にはJphpmailerを利用している。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.06
 * @license LGPL
 */
class Bshe_Log_Writer_Maillog extends Zend_Log_Writer_Abstract
{
    /**
     * ログメール送付先
     *
     * @var array
     */
    protected $mailTo = array();

    /**
     * メールのFROM
     *
     * @var array array( FROMアドレス, FROMNAME)
     */
    protected $mailFrom = array();

    /**
     * ログメールCC
     *
     * @var array
     */
    protected $mailCc = array();

    /**
     * ログメールBCC
     *
     * @var array
     */
    protected $mailBcc = array();

    /**
     * 件名の頭につける文字列
     *
     * @var string
     */
    protected $subject = "";

    /**
     * Jphpmailerクラス
     *
     * @var JPHPMailer
     */
    protected $mail;


    /**
     * Class Constructor
     *
     * メールの宛先や、件名の頭につける文字列を記載する
     *
     * @param array $mailTo
     * @param string $subject
     * @param array $mailFrom
     * @param array $mailCc
     * @param array $mailBcc
     */
    public function __construct($mailTo = array(), $subject = "", $mailFrom = array(), $mailCc = array(), $mailBcc = array())
    {
        // 宛先などアドレス情報設定
        if ($mailTo == array()) {
            // 宛先がセットされていない
            throw New Zend_Exception('ログメールの宛先が設定されていません。', Zend_Log::ERR);
        }
        if ($mailFrom == array()) {
            // Fromがセットされていない
            throw New Zend_Exception('ログメールのFromが設定されていません。', Zend_Log::ERR);
        }

        $this->mailTo = $mailTo;
        $this->subject = $subject;
        $this->mailFrom = $mailFrom;
        $this->mailCc = $mailCc;
        $this->mailBcc = $mailBcc;

        // Jphpmailerインスタンス化
        $this->mail = New JPHPMailer();
        $this->mail->PluginDir = 'Jphpmailer/phpmailer/';

        // メールの本文のフォーマットは必要な場合はFormatterにて設定する
        $this->_formatter = new Bshe_Log_Formatter_Maillog();
    }

    /**
     * 各種オプションを設定するためにメールクラスを取得する際に利用
     *
     * @return JPHPMailer
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * メールクラスをセット
     *
     * @param JPHPMailer $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }


    /**
     * Write a message to the log.
     *
     * @param  array  $event  event data
     * @return void
     */
    protected function _write($event)
    {
        // 本文生成
        $line = $this->_formatter->format($event);
        // 件名生成
        $subject = $this->subject;
        foreach ($event as $name => $value) {
            $subject = str_replace("%$name%", $value, $subject);
        }

        // to設定
        foreach ($this->mailTo as $strMailTo) {
            $this->mail->addTo( $strMailTo);
        }
        // cc設定
        foreach ($this->mailCc as $strMailCc) {
            $this->mail->addCc($strMailCc);
        }
        // bcc設定
        foreach ($this->mailBcc as $strMailBcc) {
            $this->mail->addBcc($strMailBcc);
        }
        // From設定
        $this->mail->setFrom($this->mailFrom[0], $this->mailFrom[1]);
        // Subject
        $this->mail->setSubject($subject);
        $this->mail->setBody($line);

        // メール送信
        if (!$this->mail->send()) {
            throw New Zend_Exception("メールが送信できませんでした。エラー:" . $mail->getErrorMessage());
        }


    }
}
