<?php
class Application_Resource_Helloworld extends Bshe_View_Resource_Html_Abstract
{

    public function assignValuesTest($arrayParams)
    {
        try {
                $arrayAssign = array(
                    'method' => 'a',
                    'element' => $arrayParams['element'],
                    'params' =>
                        array(
                            0 => 'innerHTML',
                            1 => 'hello world',
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