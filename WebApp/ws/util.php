<?php
include('globals.php');

function getSummonerData($summonerName, $region)
{	
	$summonerName = str_replace(' ', '', strtolower($_POST['summonerName']));
	$server = $_POST['server'];
	if(!($data = @file_get_contents('https://'.$server.'.api.pvp.net/api/lol/'.$server.'/v1.4/summoner/by-name/'.$summonerName.'?api_key='.DEF_API_KEY)))
	{
		return null;	
	}
	$data = json_decode($data, true);	
	foreach($data as $d) { }
	$summonerData = $data[str_replace(' ', '', strtolower($d['name']))];
	$summonerData['region'] = $server;
	return $summonerData;
}

function getMatchsData($summonerData)
{
	$data = file_get_contents('https://'.$summonerData['region'].'.api.pvp.net/api/lol/'.$summonerData['region'].'/v1.3/game/by-summoner/'.$summonerData['id'].'/recent?api_key='.DEF_API_KEY); 
	$data = json_decode($data, true);
	
	$totalUrfGames = 0;
	$matchsData = array();
	for($i = 0; $i < count($data['games']); $i++)
	{
		$match = $data['games'][$i];
		if($match['subType'] == 'URF')
		{
			$matchData['gameId'] = $match['gameId'];
			$matchData['championId'] = $match['championId'];
			$matchData['level'] = validate($match['stats'], 'level');
			$matchData['minionsKilled'] = validate($match['stats'], 'minionsKilled');
			$matchData['goldEarned'] = validate($match['stats'], 'goldEarned');
			
			$matchData['totalDamageTaken'] = validate($match['stats'], 'totalDamageTaken');
			$matchData['totalDamageDealtToChampions'] = validate($match['stats'], 'totalDamageDealtToChampions');
			$matchData['totalDamageDealt'] = validate($match['stats'], 'totalDamageDealt');
			
			$matchData['totalTimeCrowdControlDealt'] = validate($match['stats'], 'totalTimeCrowdControlDealt');
			$matchData['totalHeal'] = validate($match['stats'], 'totalHeal');
			
			$matchData['wardPlaced'] = validate($match['stats'], 'wardPlaced');
			$matchData['wardKilled'] = validate($match['stats'], 'wardKilled');
			
			$matchData['win'] = $match['stats']['win'];
			
			$matchData['kills'] = validate($match['stats'], 'championsKilled');
			$matchData['deaths'] = validate($match['stats'], 'numDeaths');
			$matchData['assists'] = validate($match['stats'], 'assists');
			
			$matchData['timePlayed'] = validate($match['stats'], 'timePlayed');
			
			$matchData['score'] = ($matchData['level'] * 100) + 
									   ($matchData['minionsKilled'] * 100) + 
									   ($matchData['goldEarned']) + 
									   ($matchData['totalDamageTaken'] * 0.5) + 
									   ($matchData['totalDamageDealtToChampions'] * 0.75) + 
									   ($matchData['totalTimeCrowdControlDealt'] * 10) + 
									   ($matchData['totalHeal'] * 0.8) + 
									   ($matchData['wardPlaced'] * 100) + 
									   ($matchData['wardKilled'] * 150) + 
									   ($matchData['kills'] * 500) -
									   ($matchData['deaths'] * 500) + 
									   ($matchData['assists'] * 600);
			$matchData['score'] = $matchData['win'] ? $matchData['score'] * 1.5 : $matchData['score'];
			$matchData['score'] += (50000 - ($matchData['timePlayed'] * 13)) < 0 ? 0 : (50000 - ($matchData['timePlayed'] * 13));
			$matchsData[$totalUrfGames] = $matchData;
			$totalUrfGames++;
		}		
	}		
	return $matchsData;
}

function getSummonerFromDB($summonerData)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$result = mysqli_query($link, 'SELECT (SELECT COUNT(*) AS rank FROM tbl_summoner s WHERE s.score >= (SELECT score FROM tbl_summoner WHERE id = '.$summonerData['id'].')) as rank,
s.*,
c.img
FROM tbl_summoner s, tbl_playerstats p, tbl_champion c 
WHERE s.id = '.$summonerData['id'].' AND
s.id = p.id_summoner AND
s.id_match_score = p.id_match AND
p.id_champion = c.id;');
	if(mysqli_num_rows($result) == 0)
	{
		return null;	
	}
	else
	{
		$data = mysqli_fetch_array($result);
		if($data['rank'] == 0)
		{
			return null;	
		}
		return $data;	
	}
	mysqli_close($link);
}

function getSummonerFromDBWithFB($fbId)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$result = mysqli_query($link, 'SELECT s.* FROM tbl_user u, tbl_summoner s WHERE u.id = '.$fbId.' AND u.id_summoner = s.id');
	if(mysqli_num_rows($result) == 0)
	{
		return null;	
	}
	else
	{
		return mysqli_fetch_array($result);	
	}
	mysqli_close($link);
}

function createSummonerDataDB($summonerData, $matchScore, $matchKills, $matchDeaths, $matchAssists, $matchTotalDamageDealtToChampions, $matchTotalDamageTaken)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	mysqli_query($link, 'INSERT INTO tbl_summoner (id, summonerName, region, id_match_score, score, id_match_kills, kills, id_match_deaths, deaths, id_match_assists, assists, id_match_dmg, totalDamageDealtToChampions, id_match_taken, totalDamageTaken) 
	VALUES('.$summonerData['id'].', "'.$summonerData['name'].'", "'.$summonerData['region'].'", '
	.$matchScore['gameId'].','.$matchScore['score'].', '
	.$matchKills['gameId'].','.$matchKills['kills'].','
	.$matchDeaths['gameId'].','.$matchDeaths['deaths'].','
	.$matchAssists['gameId'].','.$matchAssists['assists'].','
	.$matchTotalDamageDealtToChampions['gameId'].','.$matchTotalDamageDealtToChampions['totalDamageDealtToChampions'].','
	.$matchTotalDamageTaken['gameId'].','.$matchTotalDamageTaken['totalDamageTaken'].')');
	mysqli_close($link);	
}

function createSummonerDataDBEmpty($summonerData)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	mysqli_query($link, 'INSERT INTO tbl_summoner (id, summonerName, region, id_match_score, score, id_match_kills, kills, id_match_deaths, deaths, id_match_assists, assists, id_match_dmg, totalDamageDealtToChampions, id_match_taken, totalDamageTaken) 
	VALUES('.$summonerData['id'].', "'.$summonerData['name'].'", "'.$summonerData['region'].'", 
	0,0, 
	0,0,
	0,0,
	0,0,
	0,0,
	0,0)');
	mysqli_close($link);	
}

function saveMatchsDataDB($summonerData, $matchsData)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	foreach($matchsData as $match)
	{
		mysqli_query($link, 'INSERT INTO tbl_playerstats VALUES('
			.$match['gameId'].','
			.$summonerData['id'].','
			.$match['championId'].','
			.$match['level'].','
			.$match['minionsKilled'].','
			.$match['goldEarned'].','
			.$match['totalDamageTaken'].','
			.$match['totalDamageDealtToChampions'].','
			.$match['totalTimeCrowdControlDealt'].','
			.$match['totalHeal'].','
			.$match['wardPlaced'].','
			.$match['wardKilled'].','
			.$match['kills'].','
			.$match['deaths'].','
			.$match['assists'].','
			.((int)$match['win']).','
			.$match['timePlayed'].','
			.intval($match['score']).')');	
	}
	mysqli_close($link);
}

function getMaxMatchFromMatchsList($matchsData, $stat)
{
	$maxStat = -1;
	$maxIndex = -1;
	for($i = 0; $i < count($matchsData); $i++)	
	{
		if($matchsData[$i][$stat] > $maxStat)	
		{
			$maxStat = $matchsData[$i][$stat];
			$maxIndex = $i;
		}
	}
	return $matchsData[$maxIndex];
}

function updateSummonerDataDB($summonerData, $matchScore, $matchKills, $matchDeaths, $matchAssists, $matchTotalDamageDealtToChampions, $matchTotalDamageTaken)
{
	if($matchScore['score'] > $summonerData['score'])
		updateSummonerStatDB($summonerData, $matchScore, 'score');
	if($matchScore['kills'] > $summonerData['kills'])
		updateSummonerStatDB($summonerData, $matchScore, 'kills');
	if($matchScore['deaths'] > $summonerData['deaths'])
		updateSummonerStatDB($summonerData, $matchScore, 'deaths');
	if($matchScore['assists'] > $summonerData['assists'])
		updateSummonerStatDB($summonerData, $matchScore, 'assists');
	if($matchScore['totalDamageDealtToChampions'] > $summonerData['totalDamageDealtToChampions'])
		updateSummonerStatDB($summonerData, $matchScore, 'totalDamageDealtToChampions');
	if($matchScore['totalDamageTaken'] > $summonerData['totalDamageTaken'])
		updateSummonerStatDB($summonerData, $matchScore, 'totalDamageTaken');
}

function updateSummonerStatDB($summonerData, $matchData, $stat)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	switch($stat)
	{
		case 'totalDamageDealtToChampions': mysqli_query($link, 'UPDATE tbl_summoner SET id_match_dmg = '.$matchData['gameId'].', '.$stat.' = '.$matchData[$stat].' WHERE id = '.$summonerData['id']); break;
		case 'totalDamageTaken': mysqli_query($link, 'UPDATE tbl_summoner SET id_match_taken = '.$matchData['gameId'].', '.$stat.' = '.$matchData[$stat].' WHERE id = '.$summonerData['id']); break;
		default: mysqli_query($link, 'UPDATE tbl_summoner SET id_match_'.$stat.' = '.$matchData['gameId'].', '.$stat.' = '.$matchData[$stat].' WHERE id = '.$summonerData['id']); break;
	}
}

function getDetailsDataDB($summonerId, $matchId)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$stmt = mysqli_prepare($link, "SELECT * FROM tbl_playerstats p WHERE id_match = ? AND id_summoner = ?");
	mysqli_stmt_bind_param($stmt, 'ii', $matchId, $summonerId);
	mysqli_stmt_execute($stmt);
	$data = mysqli_stmt_result_metadata($stmt);
    $fields = array();
    $out = array();
    $fields[0] = &$stmt;
	$count = 1;
	while($field = mysqli_fetch_field($data))
	{
		$fields[$count] = &$out[$field->name];
		$count++;
	}
	call_user_func_array('mysqli_stmt_bind_result', $fields);
	mysqli_stmt_fetch($stmt);
	return $out;
}

function getRankingDataDB($region, $sort)
{
	$regionSelect = "";
	switch($region)
	{
		case 'br':  
		case 'eune': 
		case 'euw': 
		case 'kr':
		case 'lan':  
		case 'las':  
		case 'na':  
		case 'oce': 
		case 'ru':  
		case 'tr':
			$regionSelect = ' AND s.region = "'.$region.'" ';
			break;
		default: 
			break;
	}
	
	$sortSelect = "";
	$matchIdSelect = "";
	$orderSelect = "";	
	switch($sort)
	{
		default:		 
			$sortSelect = 's.score';
			$matchIdSelect = 's.id_match_score';
			$orderSelect = 'score';
			break;
			
		case 'score': 
		case 'kills':  
		case 'deaths':  
		case 'assists':  
			$sortSelect = 's.'.$sort;
			$matchIdSelect = 's.id_match_'.$sort;
			$orderSelect = $sort;
			break;
			
		case 'totalDamageDealtToChampions':
		case 'totalDamageTaken':
			$sortSelect = 's.'.$sort;
			$matchIdSelect = ($sort == 'totalDamageTaken') ? 's.id_match_taken' : 's.id_match_dmg';
			$orderSelect = $sort;
			break;
	}
	
	$select = 'SELECT s.summonerName, s.region, c.img, '.$sortSelect.' FROM tbl_summoner s, tbl_playerstats p, tbl_champion c
WHERE s.id > 10 AND
'.$matchIdSelect.' = p.id_match AND
s.id = p.id_summoner AND
p.id_champion = c.id
'.$regionSelect.'
ORDER BY '.$orderSelect.' DESC LIMIT 10;';
	
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$result = mysqli_query($link, $select);
	if(mysqli_num_rows($result) == 0)
	{
		return null;	
	}
	else
	{
		$rankingData = array();
		$index = 0;
		while($data = mysqli_fetch_array($result))
		{
			$rankingData[$index] = $data;
			$index++;
		}
		return $rankingData;
	}
	mysqli_close($link);
}

function getChampionRanking($sort)
{
	$query = 'SELECT id_champion, img, championName, 
avg(p.score) as avgScore,
((c.pickRate * 100) / (SELECT COUNT(*) FROM tbl_playerstats)) as pickRatePerc, 
((c.banRate * 100) / (SELECT COUNT(*) FROM tbl_playerstats)) as banRatePerc, 
((sum(win = true) * 100) / count(DISTINCT id_match)) as winRatePerc 
from tbl_playerstats p, tbl_champion c 
where p.id_champion = c.id 
group by id_champion';
	switch($sort)
	{
		case 'score': $query .= ' ORDER BY avgScore DESC'; break;
		case 'ban': $query .= ' ORDER BY banRatePerc DESC'; break;
		case 'name': $query .= ' ORDER BY c.championName ASC'; break;
		case 'pick': $query .= ' ORDER BY pickRatePerc DESC'; break;
		case 'win': $query .= ' ORDER BY winRatePerc DESC'; break;
	}
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$result = mysqli_query($link, $query);
	echo mysqli_error($link);
	if(mysqli_num_rows($result) == 0)
	{
		return null;	
	}
	else
	{
		$rankingData = array();
		$index = 0;
		while($data = mysqli_fetch_array($result))
		{
			$rankingData[$index] = $data;
			$index++;
		}
		return $rankingData;
	}
	mysqli_close($link);
}

function getBreakingNews()
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$result = mysqli_query($link, '(SELECT SUM(deaths) as rankValue, 0 as timePlayed FROM tbl_playerstats WHERE id_champion = 17)
UNION
(SELECT kills, timePlayed FROM tbl_playerstats ORDER BY kills DESC LIMIT 1)
UNION
(SELECT deaths, timePlayed FROM tbl_playerstats ORDER BY deaths DESC LIMIT 1)
UNION
(SELECT assists, timePlayed FROM tbl_playerstats ORDER BY assists DESC LIMIT 1)
UNION
(SELECT count(*), 0 FROM tbl_playerstats)
UNION
(SELECT sum(goldEarned), 0 FROM tbl_playerstats)');
	if(mysqli_num_rows($result) == 0)
	{
		return null;	
	}
	else
	{
		$rankingData = array();
		$index = 0;
		while($data = mysqli_fetch_array($result))
		{
			$rankingData[$index] = $data;
			$index++;
		}
		if($index < 4)
		{
			return null;	
		}
		return $rankingData;
	}
}

function createFBUser($id, $name, $summonerId)
{
	$name = utf8_decode($name);
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$stmt = mysqli_prepare($link, "INSERT INTO tbl_user (id, name, id_summoner) VALUES(?, ?, ?)");
	mysqli_stmt_bind_param($stmt, 'isi', $id, $name, $summonerId);
	mysqli_stmt_execute($stmt);
	mysqli_close($link);
}

function updateVerificationCode($summonerId, $code)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$stmt = mysqli_prepare($link, "UPDATE tbl_summoner SET verificationCode = ? WHERE id = ?");
	mysqli_stmt_bind_param($stmt, 'si', $code, $summonerId);
	mysqli_stmt_execute($stmt);
	mysqli_close($link);
}

function getSummonerMateries($summonerId, $server)
{
	if(!($data = @file_get_contents('https://'.$server.'.api.pvp.net/api/lol/'.$server.'/v1.4/summoner/'.$summonerId.'/masteries?api_key='.DEF_API_KEY)))
	{
		return null;	
	}
	$data = json_decode($data, true);
	return $data[$summonerId]['pages'];
}

function verifySummoner($summonerId)
{
	$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
	$result = mysqli_query($link, 'UPDATE tbl_summoner SET verificationStatus = "Verified" WHERE id = '.$summonerId);
	mysqli_close($link);
}

function getFriendsSummonerData($friendsIds, $id, $sort)
{
	$friends = explode("#", substr($friendsIds, 1));	
	if(count($friends) == 0)
	{
		return null;
	}
	else
	{
		$sortSelect = "";
		$matchIdSelect = "";
		$orderSelect = "";
		switch($sort)
		{
			default:		 
				$sortSelect = 's.score';
				$matchIdSelect = 's.id_match_score';
				$orderSelect = 'score';
				break;
				
			case 'score': 
			case 'kills':  
			case 'deaths':  
			case 'assists':  
				$sortSelect = 's.'.$sort;
				$matchIdSelect = 's.id_match_'.$sort;
				$orderSelect = $sort;
				break;
				
			case 'totalDamageDealtToChampions':
			case 'totalDamageTaken':
				$sortSelect = 's.'.$sort;
				$matchIdSelect = ($sort == 'totalDamageTaken') ? 's.id_match_taken' : 's.id_match_dmg';
				$orderSelect = $sort;
				break;
		}
		
		$ids = "(";
		foreach($friends as $friend)
		{
			$ids .= $friend.",";
		}
		$ids = substr($ids, 0, -1).','.$id.')';
		
		$link = mysqli_connect(DEF_MYSQL_HOSTNAME, DEF_MYSQL_USERNAME, DEF_MYSQL_PASSWORD, DEF_MYSQL_DATABASE);
		$result = mysqli_query($link, 'SELECT s.summonerName, u.name, c.img, '.$sortSelect.' as rankValue
FROM tbl_user u, tbl_summoner s, tbl_playerstats p, tbl_champion c 
WHERE 
u.id in '.$ids.' AND 
u.id_summoner = s.id AND
'.$matchIdSelect.' = p.id_match AND
s.id = p.id_summoner AND
p.id_champion = c.id
ORDER BY s.'.$orderSelect.' DESC;');
		echo mysqli_error($link);
		$rankingData = array();
		$index = 0;
		while($data = mysqli_fetch_array($result))
		{
			$rankingData[$index] = $data;
			$index++;
		}
		return $rankingData;
		
		mysqli_close($link);
	}
}

function validate($data, $var)
{
	return isset($data[$var]) ? $data[$var] : 0; 	
}
?>