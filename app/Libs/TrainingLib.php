<?php
namespace App\Libs;

class TrainingLib extends BaseGameLib
{
	/*
	 * 訓練が終了しているか確認する
	 */
	public function endCheck($nowTime, $userId ,$isTrainingPage = false)
	{
		//訓練終了時刻を過ぎているデータを取得
		$endTraining = $this->Model->exec('Training', 'getEndDate', array($nowTime, $userId));
		
		//訓練終了時刻を過ぎているものがあり、かつ訓練所ページから参照された場合は強化結果を反映する
		if(isset($endTraining) && $isTrainingPage == true)
		{
			foreach($endTraining as $val)
			{
				$trainingState = 2;
				TrainingLib::uCharaAtkUp($val['id']);
				$this->Model->exec('Training', 'uCharaStateChange', array($val['uCharaId'],0));
				$this->Model->exec('Training', 'uCoachStateChange', array($val['uCoachId'],0));
				$this->Model->exec('Training', 'stateChange', array($val['id'], $trainingState), $this->user['id']);
			}
			return $endTraining;
		}else if(isset($endTraining) && $isTrainingPage == false){
			foreach($endTraining as $val)
			{
				$trainingState = 1;
				$this->Model->exec('Training', 'stateChange', array($val['id'], $trainingState), $this->user['id']);
			}
			return $endTraining;
		}
	}

	/*
	 * 訓練したキャラクターの攻撃力とコーチの攻撃力から成功確率を算出し
	 * 成功していた場合は攻撃力を上昇させる
	 */
	public function uCharaAtkUp($trainingId)
	{
		//uTrainingテーブルの情報取得
		$trainingInfo = $this->Model->exec('Training','getInfo',$trainingId);
		//コーチの攻撃力取得
		$uCoachAtk = $this->Model->exec('Training','getUCoachAtk', $trainingInfo['uCoachId']);
		//キャラの攻撃力取得
		$uCharaStatus = $this->Model->exec('Training','getUCharaStatus', $trainingInfo['uCharaId']);
		
		$gooResult = TrainingLib::atkUpProbability($uCoachAtk['gooAtk'],$uCharaStatus['gooAtk'],$uCharaStatus['gooUpCnt']);
		$choResult = TrainingLib::atkUpProbability($uCoachAtk['choAtk'],$uCharaStatus['choAtk'],$uCharaStatus['choUpCnt']);
		$paaResult = TrainingLib::atkUpProbability($uCoachAtk['paaAtk'],$uCharaStatus['paaAtk'],$uCharaStatus['paaUpCnt']);
		
		//コーチのグー、チョキ、パーそれぞれの攻撃力とキャラのグー、チョキ、パーそれぞれの攻撃力から上昇率を算出
		$statusResult = TrainingLib::atkUpJudge($gooResult,$choResult,$paaResult,$trainingInfo['time']);
		
		$upDateStatus = [
			'hp'		 => $uCharaStatus['hp']			 + $statusResult['statusUpCnt'],
			'gooAtk'	 => $uCharaStatus['gooAtk']		 + $statusResult['gooUpCnt'],
			'choAtk'	 => $uCharaStatus['choAtk']		 + $statusResult['choUpCnt'],
			'paaAtk'	 => $uCharaStatus['paaAtk']		 + $statusResult['paaUpCnt'],
			'gooUpCnt'	 => $uCharaStatus['gooUpCnt']	 + $statusResult['gooUpCnt'],
			'choUpCnt'	 => $uCharaStatus['choUpCnt']	 + $statusResult['choUpCnt'],
			'paaUpCnt'	 => $uCharaStatus['paaUpCnt']	 + $statusResult['paaUpCnt']
		];
		
		$this->Model->exec('Chara', 'updateStatus', array($upDateStatus, $trainingInfo['uCharaId']));
		return $upDateStatus;
	}
	
	/*
	 * 攻撃力の上昇確率の計算処理
	 */
	public function atkUpProbability($uCoachAtk,$uCharaAtk,$atkUpCnt)
	{
		//基本確率をconfigから取得
		$baseProbability = \Config::get('training.baseProbability');
		
		$result = $uCoachAtk / $uCharaAtk * $baseProbability * (100 - $atkUpCnt);

		//$result(確率の計算結果)が101(％)以上だったら$resultに100を入れる
		if($result >= 101)
		{
			$result = 100;
		}
		return round($result,2);
	}
	
	/*
	 * 攻撃力が上昇するか判定する
	 */
	public function atkUpJudge($gooResult,$choResult,$paaResult,$time = 1)
	{
		//ステータスの上昇回数を格納する変数
		$gooUpCnt	 = 0;
		$choUpCnt	 = 0;
		$paaUpCnt	 = 0;
		
		for($i = 0; $i < $time; $i++)
		{
			$gooJudgeValue = rand(1, 100);
			if($gooResult >= $gooJudgeValue)
			{
				$gooUpCnt++;
			}
			$choJudgeValue = rand(1, 100);
			if($choResult >= $choJudgeValue)
			{
				$choUpCnt++;
			}
			$paaJudgeValue = rand(1, 100);
			if($paaResult >= $paaJudgeValue)
			{
				$paaUpCnt++;
			}
			
			$statusUpCnt = $gooUpCnt + $choUpCnt + $paaUpCnt;
		}
		
		$result = [
			'gooUpCnt'		=> $gooUpCnt,
			'choUpCnt'		=> $choUpCnt,
			'paaUpCnt'		=> $paaUpCnt,
			'statusUpCnt'	=> $statusUpCnt
		];
		return $result;
	}
}