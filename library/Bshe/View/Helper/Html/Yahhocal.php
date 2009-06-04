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

/** Bshe_View_Helper_Html_Abstract */
require_once 'Bshe/View/Helper/Html/Abstract.php';
/** Bshe_View_Helper_Html_Exception_InvalidParam */
require_once 'Bshe/View/Helper/Html/Exception/InvalidParam.php';

/**
 * Bshe_View_Helper_Html_Yahhocal
 *
 * 対象のINPUTボックスをカレンダー入力にするヘルパー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2008.09.27
 * @license LGPL
 */
class Bshe_View_Helper_Html_Yahhocal extends Bshe_View_Helper_Html_Abstract
{



    /**
     * SELECTボックスに都道府県をセットする
     *
     *
     * @param array $values
     *
     * @return Bshe_View_Template_Abstract
     */
    static public function setView($values)
    {
        try {
            // assign用配列取得
            //$arrayValues = $values['templateClass']->getPresetValues();

            $arrayAssigns = array();
            // IDの有無を確認しIDがない場合は、例外を発行
            if (!$values['templateClass']->hasAttribute($values['params']['element'], 'id')) {
                $values['templateClass']->getLogger()->logWithFileAndParams('Helperエラー: YahhocalヘルパーがセットされているタグにIDがありません。', Zend_Log::WARN);
            } else {
                // 入力javascriptをonclickに追加
                $arrayAssigns[] =
                    array(
                        'method' => 'a',
                        'element' => $values['params']['element'],
                        'params' =>
                            array(
                                0 => 'onclick',
                                1 => 'YahhoCal.render(this.id);',
                                2 => null,
                                3 => null
                            )
                    );
            }

            // 値の通常セット
            $arrayAssigns[] =
                array(
                    'method' => 'a',
                    'element' => $values['params']['element'],
                    'params' =>
                        array(
                            0 => 'bshe_innerHTML',
                            1 => $values['value'],
                            2 => null,
                            3 => null
                        )
                );
            foreach ($arrayAssigns as $key => $assign) {
                $values['templateClass'] = Bshe_View_Resource_Html_Show::assign($values['templateClass'], $assign);
            }

            // プラグイン配列セット
            $arrayPluginFlags = $values['templateClass']->getParam('pluginFlags');
            $arrayPluginFlags['Bshe_View_Plugin_Yahhocal'] = true;
            $values['templateClass']->setParam('pluginFlags', $arrayPluginFlags);


            return $values['templateClass'];

        } catch (Exception $e) {
            throw $e;
        }
    }


    static public function getDefaultValue()
    {
        return '';
    }
}