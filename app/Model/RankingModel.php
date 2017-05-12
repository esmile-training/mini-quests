<?php

namespace App\Model;

class RankingModel extends BaseGameModel
{
    public function insertRankingData( $userId )
    {
$sql = <<< EOD
	INSERT INTO uRanking(userId) values($userId);
EOD;
    return parent::insert($sql);
    }
    
    
    /*
    *	chardata取得
    */
    public function userRank($userId, $range)
    {
$sql =  <<< EOD
SELECT rank.weekRange, rank.userId, rank.weeklyAward, user.name, rank FROM 
((
	SELECT * FROM (
	    SELECT userId ,weeklyAward, weekRange, (
			SELECT COUNT(*) + 1 FROM 
			`uRanking` bf
			WHERE bf.weeklyAward > af.weeklyAward 
			AND weekRange = '{$range}'
		) AS `rank` 
		FROM `uRanking` af 
		ORDER BY rank
	) as rank
WHERE  weekRange = '{$range}' AND rank > (
	SELECT rank FROM (
		SELECT userId ,weeklyAward, weekRange, (
			SELECT COUNT(*) + 1 FROM
			`uRanking` bf 
			WHERE bf.weeklyAward > af.weeklyAward 
			AND weekRange = '{$range}'
		) AS `rank` 
		FROM `uRanking` af
		WHERE userId = $userId
	) AS rank
WHERE userId = $userId
)
ORDER BY weeklyAward DESC LIMIT 10
) 
UNION ALL
(
	SELECT * FROM (
		SELECT userId ,weeklyAward, weekRange, (
			SELECT COUNT(*) + 1 FROM 
			`uRanking` bf 
			WHERE bf.weeklyAward > af.weeklyAward 
			AND weekRange = '{$range}'
		) AS `rank` 
		FROM `uRanking` af
		ORDER BY rank
	) AS rank
	WHERE weekRange = '{$range}' and rank <= (
		SELECT rank FROM (
			SELECT userId ,weeklyAward, weekRange, (
				SELECT COUNT(*) + 1 FROM 
				`uRanking` bf WHERE bf.weeklyAward > af.weeklyAward 
				AND weekRange = '{$range}'
			) AS `rank`
		FROM `uRanking` af 
		WHERE userId = $userId
	) AS rank
WHERE userId = $userId
) ORDER BY weeklyAward ASC LIMIT 10

))as rank

LEFT outer JOIN user
ON rank.userId = user.id
ORDER BY rank
;
EOD;

	return parent::select($sql, 'all');
    }
    
    /*
     * ランキングのページャー
     */
    
    public function rankingPager($page, $range)
    {
$sql = <<< EOD
	SELECT userId ,weeklyAward, user.name,
	(SELECT COUNT(*) + 1 FROM `uRanking` bf WHERE bf.weeklyAward > af.weeklyAward AND weekRange = '{$range}') 
	AS `rank` FROM `uRanking` af 
	LEFT outer JOIN user
	ON userId = user.id
	WHERE weekRange = '{$range}'
	ORDER BY weeklyAward DESC LIMIT 10 OFFSET $page;
EOD;
return parent::select($sql);
    }
    
    /*
     * キャラランキング
     */
    
    public function rankingStatus($page)
    {
	if($page == null)
	{
	    $page = 0;
	}
$sql = <<< EOD
	SELECT id, name, `totalCharaStatus`,
	(SELECT COUNT(*) + 1 FROM `user` b WHERE b.totalCharaStatus > a.totalCharaStatus) 
	AS `rank` FROM `user` a  
	ORDER BY totalCharaStatus DESC LIMIT 10 OFFSET $page
EOD;
return parent::select($sql);
    }
    
    
    /*
     * charaの最下位を取得
     */
    public function bottomStatus() {
$sql = <<< EOD
	SELECT totalCharaStatus
	FROM user
	ORDER BY hp ASC LIMIT 1;
EOD;
	return parent::select($sql);
    }
    
    /*
     * idの登録数を取得
     */
    public function countRange($range)
    {
$sql = <<< EOD
	SELECT COUNT(weekRange) AS count
	FROM uRanking WHERE weekRange = '{$range}';
EOD;
	return parent::select($sql);
    }
	
	
	// ユーザーIDを元にウィークリーランキングの情報を取得
	public function getRankingData($userId = false)
	{
$sql = <<< EOD
	SELECT *
	FROM uRanking
	WHERE userId	= {$userId}
EOD;
		return $this->select($sql, 'first');
	}
	
	// ランキングデータのIDを元にウィークリーランキングのポイントを更新
	public function updateWeeklyPoint($rankingData)
    {
$sql = <<< EOD
	UPDATE  uRanking
	SET		weeklyAward = {$rankingData['weeklyAward']}
	WHERE  id		= {$rankingData['id']};
EOD;
		$this->update($sql);
    }
    
    /*
     * userの登録数を取得
     */
    
    public function countUser()
    {
$sql = <<< EOD
	SELECT COUNT(id) AS count
	FROM user;
EOD;
	return parent::select($sql);
    }
    
    /*
     * rangeのアップデート
     */

    public function updateRange($userId, $monday)
    {
$sql = <<< EOD
	UPDATE uRanking 
	SET weekRange = '{$monday}'
	WHERE userId = $userId;
EOD;
	return parent::update($sql);
    }
    
    /*
     * 現在の登録の週を取得
     */
    
    public function getRange($userId)
    {
$sql = <<< EOD
	SELECT weekRange
	FROM uRanking
	WHERE userId = $userId;
EOD;
	return parent::select($sql);
    }

}

