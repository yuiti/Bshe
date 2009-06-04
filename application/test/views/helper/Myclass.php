<?php
class Test_Helper_Myclass extends Bshe_View_Helper_Html_Abstract
{

    static public function setView($arrayParams)
    {
        try {
            if (is_numeric($arrayParams['value'])) {
                return number_format($arrayParams['value']);
            } else {
                return $arrayParams['value'];
            }

        } catch (Exception $e) {
            throw $e;
        }
    }
}
