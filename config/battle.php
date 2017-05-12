<?php

return [
	// 三すくみの名前
	'typeStr' => [
		0 => '未設定',
		1 => 'グー',
		2 => 'チョキ',
		3 => 'パー',
	],
	// 勝敗の名前
	'resultStr' => [
		'win' => '勝ち',
		'lose' => '負け',
		'draw' => 'あいこ',
	],
	// ダメージの変化量(％)
	'damagePer' => [
		'min' => 80,
		'max' => 120,
	],
	// 賞金の歩合
	'prizeStr' =>[
		'Commission' => 30,
	],
//	// 敵キャラのレベルによる賞金の補正値(％)
//	'prizeRatio' =>[
//		1 => 70,
//		2 => 100,
//		3 => 140,
//	],
	// 難易度
	'difficultyStr' =>[
		1	=> [
		'id' => 1,
		'name' => '下級',
		'prizeRatio' => 70,
		'enemyRatio' => 0.7
		],
		2	=> [
		'id' => 2,
		'name' => '中級',
		'prizeRatio' => 100,
		'enemyRatio' => 1
		],
		3	=> [
		'id' => 3,
		'name' => '上級',
		'prizeRatio' => 140,
		'enemyRatio' => 1.3
		],
	],
//	// 敵キャラのレベルによるステータスの補正値
//	'enemyRatio'=>[
//		1	=> '0.7',
//		2	=> '1',
//		3	=> '1.3',
//	],
];