<?php
class Test_Resource_Myclass extends Bshe_View_Resource_Html_Abstract
{

    public function assignValuesMymethod($arrayParams)
    {
        try {
                $arrayAssign = array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'innerHTML',
                            1 => $arrayParams['params']['arrayMethodParams'][0],
                            2 => $arrayParams['params']['helperName'],
                            3 => $arrayParams['params']['helperParams']
                        )
                );
                $arrayParams['templateClass'] = self::assign($arrayParams['templateClass'], $arrayAssign);

                return $arrayParams['templateClass'];
        } catch (Exception $e) {
            throw $e;
        }
    }
}