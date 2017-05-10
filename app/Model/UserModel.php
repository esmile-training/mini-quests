<?php
namespace App\Model;

class UserModel extends BaseGameModel
{
    /*
    *	ユーザ1件取得
    */
    public function getById( $userId = false )
    {
	if( !$userId && isset($this->user['id']) ){
	    $userId = $this->user['id'];
	}

$sql =  <<< EOD
	SELECT *
	FROM user
	WHERE id = {$userId}
EOD;
	return $this->select($sql, 'first');
    }
        
    /*
    *	ユーザ作成
    */
    public function createUser($teamName = null)
    {
$sql =  <<< EOD
	INSERT INTO user ( `name`, `createDate` )
	VALUES("{$teamName}", NOW());
EOD;
	$result = $this->insert($sql);
	return $result;
    }

    /*
    *	ユーザ削除
    */
    public function deleteUser( $userId )
    {
$sql =  <<< EOD
    DELETE FROM user 
    WHERE id = {$userId};
EOD;
	$this->delete($sql);
    }

    /*
    *	ユーザ名変更
    */
    public function setUserName( $userId, $newName )
    {
$sql =  <<< EOD
    UPDATE  user
    SET	    name = "{$newName}"
    WHERE   id = {$userId};
EOD;
	$this->update($sql);
    }
    
    
    /*
     * キャラステータスの更新
     */
    public function charaStatus( $userId )
    {
$sql = <<< EOD
	UPDATE user set
	totalCharaStatus = 
	(SELECT SUM(hp) AS Status FROM uChara WHERE userId = $userId)
	where id = $userId;
EOD;

    $this->charaUpdate($sql);
    }
    
    
    public function updateMoney($user)
    {
$sql = <<< EOD
	UPDATE  user
	SET		money = {$user['money']}
	WHERE   id		= {$user['id']};
EOD;
		$this->update($sql);
    }
    
    /*
     * 枚数確認
     */
    
    public function numberConfirmation( $userId )
    {
$sql = <<< EOD
	SELECT battleTicket, ticketLossTime
	FROM user
	WHERE id = $userId;
EOD;
    return parent::select($sql);
    }
    
    public function ticketRecovery($userId, $ticket, $nextRecoveryTime)
    {
	var_dump($nextRecoveryTime);
$sql = <<< EOD
	UPDATE  user
	SET battleTicket = {$ticket},
	    ticketLossTime = '{$nextRecoveryTime}'
	WHERE id = {$userId};
EOD;
    return parent::update($sql);
    }
}