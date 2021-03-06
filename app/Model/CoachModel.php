<?php

namespace App\Model;

class CoachModel extends BaseGameModel
{
	
	//IDを元にコーチを検索する
	public function getById($uCoachId)
	{
		// SQLに接続する
$sql = <<< EOD
			SELECT *
			FROM uCoach
			WHERE id = {$uCoachId}
EOD;
		// 最初に一致したものを返す
		return $this->select($sql,'first');
	}
	
		//コーチの追加
	public function insertCoach ($newCoachState)
	{
$sql = <<< EOD
	INSERT INTO uCoach 
	VALUES (
		NULL,
		'{$newCoachState['userId']}',
		'{$newCoachState['imgId']}',
		'{$newCoachState['name']}',
		'{$newCoachState['rare']}',
		'{$newCoachState['attribute']}',
		'{$newCoachState['hp']}',
		'{$newCoachState['gooAtk']}',
		'{$newCoachState['choAtk']}',
		'{$newCoachState['paaAtk']}',
		'0',
		NULL,
		NULL
	);
EOD;
	$this->insert($sql);
	}
	
	//コーチの削除
	public function deleteCoach($uCoachId){
$sql = <<< EOD
	UPDATE uCoach
	SET state = 2
	WHERE id = {$uCoachId};
EOD;
	$result = $this->update($sql);
	}
}
