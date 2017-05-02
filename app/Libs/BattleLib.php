<?php

namespace App\Libs;

class BattleLib extends BaseGameLib
{

	// 敵の各属性を出す確率ステータスに基づいた手をランダムに選択する処理
	public static function setEnmHand($EnemyData, $typeData)
	{
		$result = [];   //データ返却用変数の初期化

		// ランダムに1～99の数値を選択し格納
		$Hand = rand(1, 99);

		// Hand の数値が EnemyData の 'goo' の確率値 'gooPer' 以下の場合
		if ($Hand <= $EnemyData['gooPer'])
		{
			// result に 'goo' を格納
			$result = $typeData['goo'];
		}
		// Hand の数値が EnemyData の 'goo' の確率値 'gooPer' と 'cho' の確率値 'choPer' を足した数以下の場合
		else if ($Hand <= $EnemyData['gooPer'] + $EnemyData['choPer'])
		{
			// result に 'cho' を格納
			$result = $typeData['cho'];
		}
		// Hand の数値が EnemyData の 'goo' の確率値 'gooPer' と 'cho' の確率値 'choPer' を足した数より大きいの場合
		else {
			// result に 'paa' を格納
			$result = $typeData['paa'];
		}

		return $result;
	}

	// 攻撃の処理を格納する処理
	public static function AtackResult($pcHand, $enmHand, $typeData, $resultData)
	{
		
		$result = [];   // データ返却用変数の初期化

		// Chara の 'hand' によって処理を行う
		switch ($pcHand)
		{
			// 'goo' の場合
			case $typeData['goo']:
				// Enemy の 'hand' によって処理を行う
				switch ($enmHand)
				{
					// 'goo' の場合
					case $typeData['goo']:
						// result に 'draw' を格納
						$result = $resultData['draw'];
						break;

					// 'cho' の場合
					case $typeData['cho']:
						// result に 'win' を格納
						$result = $resultData['win'];
						break;

					// 'paa' の場合
					case $typeData['paa']:
						// result に 'lose' を格納
						$result = $resultData['lose'];
						break;

					default:
						echo 'エラー';
						exit;
				}
				break;

			// 'cho' の場合
			case $typeData['cho']:
				// Enemy の 'hand' によって処理を行う
				switch ($enmHand)
				{
					// 'goo' の場合
					case $typeData['goo']:
						// result に 'lose' を格納
						$result = $resultData['lose'];
						break;

					// 'cho' の場合
					case $typeData['cho']:
						// result に 'draw' を格納
						$result = $resultData['draw'];
						break;

					// 'paa' の場合
					case $typeData['paa']:
						// result に 'win' を格納
						$result = $resultData['win'];
						break;

					default:
						echo 'エラー';
						exit;
				}
				break;

			// 'paa' の場合
			case $typeData['paa']:
				// Enemy の 'hand' によって処理を行う
				switch ($enmHand)
				{
					// 'goo' の場合
					case $typeData['goo']:
						// result に 'win' を格納
						$result = $resultData['win'];
						break;

					// 'cho' の場合
					case $typeData['cho']:
						// result に 'lose' を格納
						$result = $resultData['lose'];
						break;

					// 'paa' の場合
					case $typeData['paa']:
						// result に 'draw' を格納
						$result = $resultData['draw'];
						break;

					default:
						echo 'エラー';
						exit;
				}
				break;

			default:
				echo 'エラー';
				exit;
		}

		return $result;
	}

	// ダメージ量の計算処理
	public static function damageCalc($winner, $typeData)
	{
		// config にあるダメージ割合の変化量の最小値と最大値を格納
		$randData = \Config::get('battle.damagePer');

		// ダメージ割合を格納
		$damagePer = mt_rand($randData['min'], $randData['max']) * 0.01;

		// 勝った方の 'hand' によって処理を行う
		// ダメージ量計算式
		// ダメージ量 = 勝った方の攻撃力 * ダメージ割合
		switch ($winner['hand'])
		{
			// 'goo' の場合
			case $typeData['goo']:
				// 'bGooAtk' に 元データ 'cGooAtk' と ダメージ割合 'damagePer' を掛けた結果を格納
				$winner['bGooAtk'] = (int) ($winner['gooAtk'] * $damagePer);
				break;

			// 'cho' の場合
			case $typeData['cho']:
				// 'bChoAtk' に 元データ 'cChoAtk' と ダメージ割合 'damagePer' を掛けた結果を格納
				$winner['bChoAtk'] = (int) ($winner['choAtk'] * $damagePer);
				break;

			// 'paa' の場合
			case $typeData['paa']:
				// 'bPaaAtk' に 元データ 'cPaaAtk' と ダメージ割合 'damagePer' を掛けた結果を格納
				$winner['bPaaAtk'] = (int) ($winner['paaAtk'] * $damagePer);
				break;

			default;
				exit;
		}

		return $winner;
	}

	// ダメージ計算を行う処理
	public static function hpCalc($winner, $loser, $typeData)
	{
		// 勝った方の 'hand' によって処理を行う
		switch ($winner['hand'])
		{
			// 'goo' の場合
			case $typeData['goo']:
				// 負けた方の 'hp' を勝った方の 'gooAtk' 分減らす
				$loser['bHp'] = $loser['bHp'] - $winner['bGooAtk'];
				break;

			// 'cho' の場合
			case $typeData['cho']:
				// 負けた方の 'hp' を勝った方の 'choAtk' 分減らす		
				$loser['bHp'] = $loser['bHp'] - $winner['bChoAtk'];
				break;

			// 'paa' の場合
			case $typeData['paa']:
				// 負けた方の 'hp' を勝った方の 'paaAtk' 分減らす		
				$loser['bHp'] = $loser['bHp'] - $winner['bPaaAtk'];
				break;

			default;
				exit;
		}
		
		// HPが0より下回った場合、HPを0に戻す処理
		if( $loser['bHp'] < 0 )
		{
			$loser['bHp'] = 0;
		}
		
		return $loser['bHp'];
	}
	
	// 賞金計算
	public static function prizeCalc($EnemyData, $Commission, $prizeRatio)
	{
		// 賞金額計算
		$result = ($EnemyData['hp'] * $Commission['Commission']) * ( $prizeRatio[$EnemyData['level']] * 0.01);
		
		return $result;

	}

}
