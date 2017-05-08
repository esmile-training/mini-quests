<?php
namespace App\Libs;

class TrainingLib extends BaseGameLib
{
    /*
     * 訓練が終了しているか確認する
     */
    public function finishCheck($test)
    {
	$trainingDateCheck = $this->Model->exec('Training', 'getTrainingDate', false, $this->user['id']);
	if(isset($trainingDateCheck))
	{
	    foreach( $trainingDateCheck as $key => $val)
	    {
		if($val['finishDate'] <= $test)
		{
		    $this->Model->exec( 'Training', 'uCharaStateChange', array($key), $this->user['id']);
		}
	    }
	}
    }
}