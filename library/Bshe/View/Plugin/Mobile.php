<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @needs      PEAR:Net_UserAgent_Mobile 1.0.0RC1
 * @license    LGPL
 */

/** Bshe_Log */
require_once 'Bshe/Log.php';
/** Bshe_View_Plugin_Abstract */
require_once 'Bshe/View/Plugin/Abstract.php';
/** PEAR:Net_UserAgent_Mobile */
require_once('Net/UserAgent/Mobile.php');


/**
 * 携帯向けセッションを実現するためのプラグインクラス
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.26
 * @license LGPL
 */
class Bshe_View_Plugin_Mobile extends Bshe_View_Plugin_Abstract
{

    /**
     * 指定されたURLが同じアプリケーション内かそうでないかを判別する
     * ・最初がhttpで始まる場合はそのhttp(s)以降がアプリケーションのルートと一致するかどうかで判別
     * ・最初がhttpでない場合は、Javascriptであるかどうかを判別（docomoではjavaは使えない仮定で実装）
     * 　・「javascript:」で始まるかどうか
     *
     * @param string $url
     * @return string in|out|java
     */
    static public function checkSameApplication($url)
    {
        if (((strstr($strTarget, 'http') === 0) and (strstr($strTarget, 'http://' . $_SERVER['SERVER_NAME'] . Bshe_Controller_Init::getUrlPath()) !== 0))
            or ((strstr($strTarget, 'https') === 0) and (strstr($strTarget, 'https://' . $_SERVER['SERVER_NAME'] . Bshe_Controller_Init::getUrlPath()) !== 0))
            or ((strstr($strTarget, 'http') !== 0) and (strstr(strtolower($strTarget), 'javascript:') !== 0))) {
                // 同じアプリケーションURL内
                return true;
            } else {
                return false;
            }

    }

    /**
     * URL文字列にsid=を追加
     *
     * @param string $url
     * @return string
     */
    static public function insertSidToUrl($url)
    {
        try {
            if (strpos($url, '?') === false) {
                // GET引数がないため新規追加
                $url .= '?'. session_name() .'=' . strip_tags(self::getId());
            } else {
                // GET引数があるため、後ろに追加
                $url .= '&'. session_name() .'=' . strip_tags(self::getId());
            }
            return $url;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Bshe_ViewのHtmlテンプレートクラスへ
     * session_id（埋め込みが必要な場合）を埋め込む
     *
     * @param unknown_type $template
     */
    static public function setSessionIdForDocomo($template)
    {
        try {
            $agent = Net_UserAgent_Mobile::singleton();
            // docomo以外の場合は何もしない
            if (!$agent->isDocomo())
            {
                return $template;
            }
            // SID埋め込み処理

            // DOMでループ
            $elements = $template->getElementNumbers();
            foreach ($elements as $key => $element) {
                $nodeName = $element->nodeName;

                switch ($nodeName) {
                    case 'a':
                        // Aタグを抽出して処理
                        $strTarget = $template->getAttribute($key, 'href');
                        if (self::checkSameApplication($strTarget)) {
                            // SID置換
                            $url = self::insertSidToUrl($strTarget);
                            $template->setAttribute($key, 'href', $url);
                        }
                        break;
                    case 'form':
                        // FORMタグを抽出して処理
                        $strTarget = $template->getAttribute($key, 'action');
                        if (self::checkSameApplication($strTarget)) {
                            // SID追加
                            // input hiddenクラス生成
                            $insertNodeClass = New Bshe_Dom_Node_SingleElement('', 'input');
                            $insertNodeClass->setAttribute('type', 'hidden');
                            $insertNodeClass->setAttribute('name', session_name());
                            $insertNodeClass->setAttribute('value', strip_tags(self::getId()));
                            $template->addChild($insertNodeClass, $key);
                        }
                        break;
                    case 'input':
                        // input type=imgを抽出して処理
                        if ($template->getAttribute($key, 'type') == 'img') {
                            $strTarget = $template->getAttribute($key, 'src');
                            if (self::checkSameApplication($strTarget)) {
                                // SID追加
                                $url = self::insertSidToUrl($strTarget);
                                $template->setAttribute($key, 'src', $url);
                            }
                        }
                    default:
                        break;
                }

            }


            return $template;
        } catch (Exception $e) {
            throw $e;
        }
    }
}