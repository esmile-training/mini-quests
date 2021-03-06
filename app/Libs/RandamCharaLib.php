<?php

namespace App\Libs;

class RandamCharaLib extends BaseGameLib
{
	
	public static function getGachaRatio($gachavalue = null)
	{
		
		if(is_null($gachavalue))
		{
			//ガチャの選択
			$gachavalue = (int)filter_input(INPUT_GET,"gachavalue");
		}
		//ガチャのレア度ごとの割合
		$gachaConf = \Config::get('gacha.eRate');
		
		//初期化
		$sumper=0;
		
		//パーセントの合計値
		for($i=1;$i	<= count($gachaConf[$gachavalue]['persent']);$i++)
		{
			$sumper += $gachaConf[$gachavalue]['persent'][$i];	
		}
		//０からパーセント合計値のランダム
		$hitrand = rand(0,$sumper);
		
		$per = 0;
		//合計値を低いパーセントから比較していく

		for($i = 1;$i <= 5; $i++)
		{
			$per += $gachaConf[$gachavalue]['persent'][$i];
			$hit = $i;
			if($hitrand < $per){break;}
		}
		$ratio['hit'] = $hit;
		$ratio['gachavalue'] = $gachavalue;
		
		return $ratio;
	}		
	public function getCharaImgId($sex = false, $gachavalue = null) 
	{
		if( is_null($gachavalue) )
		{
			$gachavalue = (int)filter_input(INPUT_GET,"gachavalue");
		}
		
		if($sex == 1)
		{	
			return $this->womanCharaSort();
		}else if($sex == 0 && $gachavalue == 5){
			return $this->menCharaSort();
		}else {
			
			//configからデータ取ってくる
			$charaConf = \Config::get('chara.imgId');
			//ランダム処理
			$charaId = rand(1, count($charaConf));
			//ランダムできまった数値を配列に入れる
			$charaConf['charaId'] = $charaId;
			//ランダムで決まったキャラの性別も配列に入れる
			$charaConf['sex'] = $charaConf[$charaId]['sex'];
			return $charaConf;
		}
	}

	public function getValueConf($ratio, $gachavalue = null) 
	{
		if(is_null($gachavalue))
		{
			//ガチャの種類取得
			$gachaV = (int)filter_input(INPUT_GET,"gachavalue");
		}
		//configからデータ取ってくる
		$gachaConf = \Config::get('gacha.eRate');
		//ガチャのコンフィグの中のステータスがヌルじゃないとき
		if(!$gachaConf[$gachaV]['Status'] == null)
		{
			$valueListConf = $gachaConf[$gachaV]['Status'];		
		}else{
			//ガチャのコンフィグの中のステータスがヌルのとき
			//configからデータ取ってくる
			$valueListConf = \Config::get('chara.Status');
		}
			//一つ目の攻撃力の処理
			$ratio1 = mt_rand($valueListConf[$ratio]['valueMin'], $valueListConf[$ratio]['valueMax']) * 0.01;
			$atk1 = $valueListConf[$ratio]['sumValueMax'] * $ratio1;
			//二つ目の攻撃力の処理
			if ($ratio1 * 100 < 100) 
			{
				$valueListConf[$ratio]['valueMin'] += abs($ratio1 * 100 - 100);
			}else if ($ratio1 * 100 >100){
				$valueListConf[$ratio]['valueMax'] -= abs($ratio1 * 100 - 100);
			}
		
			$ratio2 = mt_rand($valueListConf[$ratio]['valueMin'], $valueListConf[$ratio]['valueMax']) * 0.01;
			$atk2 = $valueListConf[$ratio]['sumValueMax'] * $ratio2;
			
			//三つ目の攻撃力の処理
			$atk3 = $valueListConf[$ratio]['sumValueMax'] * 3 - ($atk1 + $atk2);

			//型キャスト
			$atk['atk1'] = (int) $atk1;
			$atk['atk2'] = (int) $atk2;
			$atk['atk3'] = (int) $atk3;
			$valueListConf['hp'] = $atk['atk1'] + $atk['atk2'] + $atk['atk3'];
			if ($valueListConf['hp'] <= $valueListConf[$ratio]['sumValueMax'] * 3 - 1) 
			{
					$valueListConf['hp'] += 1;
			}
			//降順
			arsort($atk);
			//一番の大きい値を入れる
			$attack[1] = current($atk);
			//二番の大きい値を入れる
			$attack[2] = current(array_slice($atk,1));
			//三番の大きい値を入れる
			$attack[3] = current(array_slice($atk,2));

			$rand = rand(1,3);
			if($gachaV == 6)
			{
				$rand = 1;
			}else if($gachaV == 8){
				$rand = 2;
			}else if($gachaV == 10){
				$rand = 3;
			}
				
			if($rand == 1)
			{
				$attk['gu'] = $attack[1];
				$attk['choki'] = $attack[2];
				$attk['paa'] = $attack[3];
				$narrow = 1;
			}else if($rand == 2){
				$attk['gu'] = $attack[2];
				$attk['choki'] = $attack[1];
				$attk['paa'] = $attack[3];
				$narrow = 2;
			}else{
				$attk['gu'] = $attack[2];
				$attk['choki'] = $attack[3];
				$attk['paa'] = $attack[1];
				$narrow = 3;
			}
			$valueListConf['gu'] = $attk['gu'];
			$valueListConf['choki'] = $attk['choki'];
			$valueListConf['paa'] = $attk['paa'];
			$valueListConf['narrow'] = $narrow;
	
		return $valueListConf;
	}

	public static function randamCharaName($sexData) 
	{

		//configからデータ取ってくる
		$charanameConf = \Config::get('chara.allname');
		//ファーストネーム配列の中からひとつランダムで取る
		$charaFirstNameNumber = array_rand($charanameConf['firstname'][$sexData]);
		//ラストネーム
		$charaLastNameNumber = array_rand($charanameConf['lastname']);
		$charanameConf['firstname'] = $charanameConf['firstname'][$sexData][$charaFirstNameNumber];
		$charanameConf['lastname'] = $charanameConf['lastname'][$charaLastNameNumber];

		return $charanameConf;
		
	}
	public function womanCharaSort() 
	{		
		//configからデータ取ってくる
		$characonf = \Config::get('chara.imgId');
	
		$charawoman = [];
		foreach ($characonf as $key => $value) 
		{
			if($value['sex'] == 1)
			{
				$charawoman[] = $key;//配列に直す方法模索
			}
		}
		$womanCharaId = array_flip($charawoman);//キーと入れ替え
		//ランダム処理
		$charaId = array_rand($womanCharaId);
		//ランダムできまった数値を配列に入れる
		$characonf['charaId'] = $charaId;
		//ランダムで決まったキャラの性別も配列に入れる
		$characonf['sex'] = 1;
		
		return $characonf;
	}
	public function menCharaSort() 
	{		
		//configからデータ取ってくる
		$characonf = \Config::get('chara.imgId');
	
		$charaman = [];
		foreach ($characonf as $key => $value) 
		{
			if($value['sex'] == 0)
			{
				$charaman[] = $key;//配列に直す方法模索
			}
		}
		$manCharaId = array_flip($charaman);//キーと入れ替え
		//ランダム処理
		$charaId = array_rand($manCharaId);
		//ランダムできまった数値を配列に入れる
		$characonf['charaId'] = $charaId;
		//ランダムで決まったキャラの性別も配列に入れる
		$characonf['sex'] = 0;
		
		return $characonf;
	}
	
	//最初にイベントガチャを引いた月が今月か調べる
	public function checkEventGachaMonth($userId)
	{
		//DBから現在のボックスガチャの情報を検索
		$gachaData = $this->Model->exec('Gacha','getEventGachaRecord',$userId);
		
		//レコードの作成月を取得
                if($gachaData){$month = date('m',strtotime($gachaData['createDate']));}
		
		if(is_null($gachaData) || $month != date('m'))
		{
			$this->Model->exec('Gacha','createEventGachaRecord',$userId);
			$deck = ['count' => 0, 'N' => 0, 'R' => 0, 'SR' => 0, 'SSR' => 0, 'LR' => 0];
		}
		else 
		{
			$deck = $gachaData;
		}
		return $deck;
	}
	
	//ボックスガチャ用処理
	public function boxGachaData($userId, $gachaConfig )
	{
		//ボックスガチャ用デッキ取得
		$config = $gachaConfig['deck'];
		
		//DBから現在のボックスガチャの情報を検索
		$gachaData = $this->Model->exec('Gacha','getEventGachaRecord',$userId);
		
		//比較に必要なデータを抽出
		$deckData = array_slice($gachaData, 1, 6);
		
		//各レア度の残り枚数の設定
		$cnt = 0;
		foreach ($deckData as $data)
		{
			$test = $config[$cnt];
			$config[$cnt] = $test - (int)$data;
			++$cnt;
		}
		
		//1から現在の最大枚数のデッキから1枚引く
		$num = rand(1, $config[0]);
		
		//引いた数が0以下になるまで残り枚数を引く
		for($i = 1; $num > 0; $i++)
		{
			 $num = $num - $config[$i];
		}
		
		//配列の添え字がそのままレア度になる
		$ratio = $i - 1;
		
		switch ($ratio){
			case 1:
				$rare = 'N';
				break;
			
			case 2:
				$rare = 'R';
				break;
			
			case 3:
				$rare = 'SR';
				break;
			
			case 4:
				$rare = 'SSR';
				break;
			
			case 5:
				$rare = 'LR';
				break;
		}
		
		//DBにガチャ結果を記録する。
		$this->Model->exec('gacha','updateEventGachaRecord',[$userId, $rare]);
		return $ratio;
	}
}
