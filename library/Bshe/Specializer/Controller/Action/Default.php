<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * Copyright(c) since 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Application
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Specializer_Controller_Action_Abstract */
require_once 'Bshe/Specializer/Controller/Action/Abstract.php';

/**
 * Bshe_Specializer_Controller_Action_Abstractを継承した
 * Specializerデフォルトのコントローラー
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.01.29
 * @license LGPL
 */
class Bshe_Specializer_Controller_Action_Default extends Bshe_Specializer_Controller_Action_Abstract
{


    /**
     * Retrieve base path based on location of current action controller
     *
     * @return string
     */
    public function getBasePath()
    {
        try {
            $config = Bshe_Registry_Config::getConfig('Bshe_Specializer');
            $mainPath = Bshe_Controller_Init::getMainPath();


            return $mainPath . $config->alias_path . $config->template_path;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Bshe_Specializerの基本処理アクション
     * 基本はヘルパーで処理される
     *
     */
    public function indexAction()
    {
        try {

        } catch (Exception $e) {
            throw $e;
        }
    }

}