<?php
namespace App\Http\Controllers;

class TopController extends BaseGameController
{
	/**
	 * TOP画面表示
	 *
	 */
	public function index()
	{
	return viewWrap('top', $this->viewData);
	}

	/**
	 * ユーザーIDをチェックしてリダイレクト
	 *
	 * @param uid
	 * @return Redirect
	 */
	public function login()
	{
		//cookieの有無を確認
		if(!isset($_COOKIE['userId']))
		{
			//無ければエディット画面にリダイレクトする。
			return $this->Lib->redirect('edit');
		} else {
			//バトル中か確認
			if($this->Model->exec('Battle', 'getBattleData', "", $_COOKIE['userId']))
			{
				//return viewWrap('Error');	//ポップアップ表示予定
			}
		}
		//何もなければマイページヘリダイレクトする
		return $this->Lib->redirect('mypage', 'index');
	}
}
