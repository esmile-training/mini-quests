<?php

namespace App\Libs;

class MoneyLib extends BaseGameLib
{
	// 加算処理
	public function addition($user, $add)
	{
		$user['money'] = $user['money'] + $add;
		
		$this->Model->exec('user', 'updateMoney', array($user));
	}
	

	// 減算処理
	public function Subtraction($user, $sub)
	{

		$user['money'] = $user['money'] - $sub;
		
		if($user['money'] < 0){
			return view('Error');
		}

		$this->Model->exec('user', 'updateMoney', array($user));
	}
	

	// 乗算処理
	public function Multiplication($user, $mul)
	{
		$user['money'] = $user['money'] * $mul;

		$this->Model->exec('user', 'updateMoney', array($user));		
	}
	

	// 除算処理
	public function division($user, $div)
	{
		$user['money'] = $user['money'] / $div;

		$this->Model->exec('user', 'updateMoney', array($user));
	}

}
