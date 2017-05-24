<?php

// 名前空間の使用宣言
namespace App\Http\Controllers;

// クラス定義
class SelectCharaController extends BaseGameController
{
	public function index()
	{
		//デフォルト処理
		$type = (!isset($_GET['type']))? 'id' : $_GET['type'];
		$order = (!isset($_GET['order']))? 'ASC' : $_GET['order'];
		return $this->listSort($type, $order);
	}
	
	public function listSort($type, $order)
	{
		// ユーザーIDを取得する
		$userId = $this->user['id'];
		// DBのキャラクターデータを取得する
		$alluChara = $this->Model->exec('Chara','getAllUserChara',$userId);
		// DBからキャラクターを取得できたかを確認する
		if(isset($alluChara))
		{	
			//ソート関数の代に引数への変換
			$order = ($order == 'ASC')? 4 : 3;
			//並べ替え処理
			array_multisort(array_column($alluChara, $type), $order , $alluChara);
			// viewDataへ取得したキャラクターを送る
			$this->viewData['charaList'] = $alluChara;
			// ビューへデータを渡す
			return viewWrap('selectChara',$this->viewData);
		}
		else
		{
			//キャラクターがいない場合リストを空にして渡す
			$this->viewData['charaList'] = null;
			return viewWrap('selectChara',$this->viewData);
		}
	}
}
