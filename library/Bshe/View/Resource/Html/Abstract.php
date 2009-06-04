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

/** Bshe_View_Resource_Abstract */
require_once 'Bshe/View/Resource/Abstract.php';

/**
 * Bshe_View_Resource_Html_Abstract
 *
 * HTMLへデータセットするクラスのabstract
 * 各登録処理の実メソッドはここに実装する。
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
abstract class Bshe_View_Resource_Html_Abstract extends Bshe_View_Resource_Abstract
{

    /**
     * キー属性名がidではない場合、
     * キー属性を削除する
     *
     * @param unknown_type $arrayParams
     */
    static public function afterAssign($arrayParams)
    {
        try {
            if ($arrayParams['templateClass']->getParam('key') != 'id') {
                // キー属性がIDではないため、属性自体を削除

                $arrayAssign =
                    array(
                        'method' => 'd',
                        'element' => $arrayParams['element'],
                        'params' => $arrayParams['templateClass']->getParam('key')
                    );

                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $arrayAssign);
            }
//            if($arrayParams['element'] == null) {
//                echo 'aaa';
//            }
            // 処理完了したため、処理配列から値を削除
            //$arrayParams['templateClass']->unsetElement( $arrayParams['params']['originalId'], $arrayParams['params']['seq']);

            return $arrayParams['templateClass'];

        }
        catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * タグ名を指定して、assignメソッドを呼び出す。
     *
     */
    static public function assign( $templateClass, $params = array())
    {
        try {
            // 処理コードにより、分岐
            switch ($params['method']) {
                default:
                    // 値、属性セット
                    $tagName = $templateClass->getNodeName($params['element']);
                    $tagClass = $templateClass->getTagClass($tagName);
                    break;
            }

            $templateClass =
                call_user_func(array($tagClass, 'assign' . ucfirst( $params['method'])),
                    $templateClass, $params
                );

            return $templateClass;
        } catch (Exception $e) {
            throw $e;
        }
    }



}
