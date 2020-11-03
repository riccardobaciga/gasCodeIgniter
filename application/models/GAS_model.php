<?php
class GAS_Model extends CI_Model {
    public function require($key, $className, $keyName)
	{
        if ($key === FALSE)
        {
                die ('{"result":"KO","description":"'.$className.': '.$keyName.' required"}');
        }
		return TRUE;
	}

}