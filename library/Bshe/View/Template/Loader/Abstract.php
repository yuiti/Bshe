<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @subpackage View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Abstract */
require_once 'Bshe/Abstract.php';
/** Bshe_Log */
require_once 'Bshe/Log.php';

/**
 * Bshe_Viewのテンプレート読み込みクラスの抽象クラス
 *
 *
 * @author Yuichiro Abe
 * @created 2009.08.18 <bshe@bshe.org>
 * @license LGPL
 * @todo アサインクラスが見つからない場合も処理を停止しないようにする
 */
abstract class Bshe_View_Template_Loader_Abstract extends Bshe_Abstract
{
   
    /**
     * テンプレートファイルを読み込んで、その内容のテキストを返す。
     * @param $fileName
     * @return unknown_type
     */
    abstract public function readTemplateFile($arrayParams);
    
    /**
     * テンプレートファイル名の生成、検証
     * 
     * @param $fileName
     * @return unknown_type
     */
    abstract public function getFileName($arrayParams);
    
    /**
     * loader独自のキャッシュ処理
     * 
     */
    abstract public function checkCache($arrayParams = array());
    
    
    /**
     * loader独自のキャッシュ保存処理
     * 
     */
    abstract public function saveCompileCache($arrayParams = array(), $template);
}