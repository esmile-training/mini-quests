<?php

namespace App\Model;

class UserModel extends BaseGameModel {
	/*
	 * 	ユーザ1件取得
	 */

	public function getById($userId = false) {
		if (!$userId && isset($this->user['id'])) {
			$userId = $this->user['id'];
		}

$sql = <<< EOD
	SELECT *
	FROM user
	WHERE id = {$userId}
EOD;
		return $this->select($sql, 'first');
	}

	/*
	 * 	ユーザ作成
	 */

	public function createUser($teamName = null) {
$sql = <<< EOD
	INSERT INTO user ( `name`, `createDate` )
	VALUES("{$teamName}", NOW());
EOD;
		$result = $this->insert($sql);
		return $result;
	}

	/*
	 * 	ユーザ削除
	 */

	public function deleteUser($userId) {
$sql = <<< EOD
    DELETE FROM user 
    WHERE id = {$userId};
EOD;
		$this->delete($sql);
	}

	/*
	 * 	ユーザ名変更
	 */

	public function setUserName($userId, $newName) {
$sql = <<< EOD
	UPDATE  user
	SET	    name = "{$newName}"
	WHERE   id = {$userId};
EOD;
		$this->update($sql);
	}
	// 所持金の更新
	public function updateMoney($user) {
$sql = <<< EOD
	UPDATE  user
	SET		money = {$user['money']}
	WHERE   id		= {$user['id']};
EOD;
		$this->update($sql);
	}

	/*
	 * キャラステータスの更新
	 */
	public function charaStatus($userId) {
$sql = <<< EOD

	UPDATE user
	SET totalCharaStatus = (SELECT SUM(hp) FROM uChara WHERE userId = $userId)
	WHERE id = $userId;
EOD;

		return $this->charaUpdate($sql);
	}

	
	public function ticketRecovery($userId, $ticket, $nextRecoveryTime)
	{
    $sql = <<< EOD
	    UPDATE  user
	    SET battleTicket = {$ticket},
		ticketLossTime = '{$nextRecoveryTime}'
	    WHERE id = {$userId};
EOD;
		$this->update($sql);
	}
		    
	/*
	 * バトルチケットの更新処理
	 */

	public function updateTicket($user) {
$sql = <<< EOD
	UPDATE  user
	SET		battleTicket = {$user['battleTicket']}
	WHERE   id		= {$user['id']};
EOD;
		$this->update($sql);
	}
	
	/*
	 * チケット数を取得
	 */
	
	public function getTicket($userId)
	{
$sql = <<< EOD
	SELECT battleTicket
	FROM user
	WHERE id = {$userId}
EOD;
		return $this->select($sql, 'first');
	}

	/*
	 * 最大数から1個目のバトルチケットを消費した時の更新処理
	 */
	public function firstLossTicket($user, $time) {
$sql = <<< EOD
	UPDATE  user
	SET		battleTicket = {$user['battleTicket']},
			ticketLossTime = '{$time}'
	WHERE   id		= {$user['id']};
EOD;
		$this->update($sql);
	}
}