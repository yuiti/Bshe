<?php
/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: 2009,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_Specializer
 * @copyright  2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

/** Bshe_Specializer_Cms_Cache_Text */
require_once 'Bshe/Specializer/Cms/Cache/Text.php';

/** Bshe_Specializer_Xajax_Abstract */
require_once 'Bshe/Specializer/Xajax/Abstract.php';

/**
 * Bshe_Specializer_Xajax_Cms_Text
 *
 * Bshe_SpecializerのCMSでテキストに関する処理をするXajax
 *
 * @author Yuichiro Abe <bshe@bshe.org>
 * @created 2009.02.11
 * @license LGPL
 */
class Bshe_Specializer_Xajax_Cms_Image extends Bshe_Specializer_Xajax_Abstract
{


    static public function publishImage($pageId, $targetElement)
    {
        try {
            $cache = New Bshe_Specializer_Cms_Cache_Image($targetElement, null, null, null, urldecode($pageId), true);
            $cache->publishContents();
        } catch (Exception $e) {
            throw $e;
        }
    }

    static public function undoImage($pageId, $targetElement)
    {
        try {
            $cache = New Bshe_Specializer_Cms_Cache_Text($targetElement, null, null, null, urldecode($pageId), true);
            $html = $cache->undoContents();

            // 元のelementの値も戻す
            $response = new xajaxResponse();
            $response->assign($targetElement, 'innerHTML', $cache->getContents());
            return $response;

        } catch (Exception $e) {
            throw $e;
        }
    }
}