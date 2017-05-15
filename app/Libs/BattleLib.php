<?php

namespace App\Libs;

class BattleLib extends BaseGameLib
{

	// 敵の各属性を出す確率ステータスに基づいた手をランダムに選択する処理
	public static function setEnmHand($EnemyData)
	{
		$result = [];   //データ返却用変数の初期化

		// ランダムに1～99の数値を選択し格納
		$Hand = rand(1, 99);

		// Hand の数値が EnemyData の 1(グー) の確率値 'gooPer' 以下の場合
		if ($Hand <= $EnemyData['gooPer'])
		{
			// result に 1(グー) を格納
			$result = 1;
		}
		// Hand の数値が EnemyData の 1(グー) の確率値 'gooPer' と 2(チョキ) の確率値 'choPer' を足した数以下の場合
		else if ($Hand <= $EnemyData['gooPer'] + $EnemyData['choPer'])
		{
			// result に 2(チョキ) を格納
			$result = 2;
		}
		// Hand の数値が EnemyData の 1(グー) の確率値 'gooPer' と 2(チョキ) の確率値 'choPer' を足した数より大きいの場合
		else {
			// result に 3(パー) を格納
			$result = 3;
		}

		return $result;
	}

	// 攻撃の処理を格納する処理
	public static function AtackResult($pcHand, $enmHand)
	{

		$result = [];   // データ返却用変数の初期化
		
		// 勝ち条件の場合
		if(	($pcHand == 1 && $enmHand == 2) ||
			($pcHand == 2 && $enmHand == 3) ||
			($pcHand == 3 && $enmHand == 1))
		{
			// result に 1(勝ち) を格納
			$result = 1;
		}
		// 負け条件の場合
		else if(($pcHand == 1 && $enmHand == 3) ||
				($pcHand == 2 && $enmHand == 1) ||
				($pcHand == 3 && $enmHand == 2))
		{
			// result に 2(負け) を格納
			$result = 2;
		}
		// あいこ条件の場合
		else if(($pcHand == 1 && $enmHand == 1) ||
				($pcHand == 2 && $enmHand == 2) ||
				($pcHand == 3 && $enmHand == 3))
		{
			// result に 3(あいこ) を格納
			$result = 3;
		}
		else
		{	
			echo 'エラー';
			exit;
		}

		return $result;
	}

	// ダメージ量の計算処理
	public static function damageCalc($winner)
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
			// 1(グー) の場合
			case 1:
				// 'battleGooAtk' に 元データ 'cGooAtk' と ダメージ割合 'damagePer' を掛けた結果を格納
				$winner['battleGooAtk'] = (int) ($winner['gooAtk'] * $damagePer);
				break;

			// 2(チョキ) の場合
			case 2:
				// 'battleChoAtk' に 元データ 'cChoAtk' と ダメージ割合 'damagePer' を掛けた結果を格納
				$winner['battleChoAtk'] = (int) ($winner['choAtk'] * $damagePer);
				break;

			// 3(パー) の場合
			case 3:
				// 'battlePaaAtk' に 元データ 'cPaaAtk' と ダメージ割合 'damagePer' を掛けた結果を格納
				$winner['battlePaaAtk'] = (int) ($winner['paaAtk'] * $damagePer);
				break;

			default;
				echo 'エラー';
				exit;
		}

		return $winner;
	}

	// ダメージ計算を行う処理
	public static function hpCalc($winner, $loser)
	{
		// 勝った方の 'hand' によって処理を行う
		switch ($winner['hand'])
		{
			// 1(グー) の場合
			case 1:
				// 負けた方の 'hp' を勝った方の 'gooAtk' 分減らす
				$loser['battleHp'] = $loser['battleHp'] - $winner['battleGooAtk'];
				break;

			// 2(チョキ) の場合
			case 2:
				// 負けた方の 'hp' を勝った方の 'choAtk' 分減らす
				$loser['battleHp'] = $loser['battleHp'] - $winner['battleChoAtk'];
				break;

			// 3(パー) の場合
			case 3:
				// 負けた方の 'hp' を勝った方の 'paaAtk' 分減らす
				$loser['battleHp'] = $loser['battleHp'] - $winner['battlePaaAtk'];
				break;

			default;
				echo 'エラー';
				exit;
		}

		// HPが0より下回った場合、HPを0に戻す処理
		if( $loser['battleHp'] < 0 )
		{
			$loser['battleHp'] = 0;
		}

		return $loser['battleHp'];
	}

	// 賞金計算
	public static function prizeCalc($EnemyData, $Commission, $DifficulutyData)
	{
		// 賞金額計算
		$result = ($EnemyData['hp'] * $Commission['Commission']) * ( $DifficulutyData[$EnemyData['difficulty']]['prizeRatio'] * 0.01);

		return $result;

	}

	// 対戦データを生成する
	public static function createMatchData($arenaData,$uCharaData,$enemyApp,$enemyName,$enemyStatus)
	{
		// 対戦データの作成をする
		$matchData					= array();
		// 大会難易度のを格納する
		$matchData['difficulty']	= $arenaData["arenaDifficulty"];
		// ユーザーキャラクターのデータを格納する
		$matchData['uCharaId']		= $uCharaData['id'];
		$matchData['uHp']			= $uCharaData['hp'];
		$matchData['uGooAtk']		= $uCharaData['gooAtk'];
		$matchData['uChoAtk']		= $uCharaData['choAtk'];
		$matchData['uPaaAtk']		= $uCharaData['paaAtk'];
		// エネミーのデータを格納する
		$matchData['eImgId']		= $enemyApp['imgId'];
		$matchData['eFirstName']	= $enemyName['firstname']['name'];
		$matchData['eLastName']		= $enemyName['lastname']['familyname'];
		$matchData['eHp']			= $enemyStatus['hp'];
		$matchData['eGooAtk']		= $enemyStatus['gooAtk'];
		$matchData['eChoAtk']		= $enemyStatus['choAtk'];
		$matchData['ePaaAtk']		= $enemyStatus['paaAtk'];

		return $matchData;
	}
}