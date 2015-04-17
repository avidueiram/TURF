<?php
include('util.php');
if(isset($_POST['summonerName']) && isset($_POST['server']))
{
	$summonerData = getSummonerData($_POST['summonerName'], $_POST['server']);
	if($summonerData == null)
	{
		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                    	<td id="errorMsg">Summoner not found :-(</td>
                    </tr>
                </table>';
	}
	else
	{
		$matchsData = getMatchsData($summonerData);
		$summonerDB = getSummonerFromDB($summonerData);
		//if((count($matchsData) == 0) || ($summonerDB == null))
		if((count($matchsData) == 0) && ($summonerDB == null))
		{
			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
                	<tr>
                    	<td width="120px" rowspan="2"><img style="display: block" class="topLeftRounded bottomLeftRounded" src="img/urf.png" width="120" height="120" border="0" /></td>
                        <td id="yourScore">0</td>
                    </tr>
                    <tr>
                    	<td id="yourGeneralRank">You have not played any recent URF games :-(</td>
                    </tr>
                    
                </table>';
		}
		else
		{
			if(count($matchsData) != 0)
			{
				if($summonerDB == null)
				{
					createSummonerDataDB($summonerData, 
											getMaxMatchFromMatchsList($matchsData, 'score'),
											getMaxMatchFromMatchsList($matchsData, 'kills'),
											getMaxMatchFromMatchsList($matchsData, 'deaths'),
											getMaxMatchFromMatchsList($matchsData, 'assists'),
											getMaxMatchFromMatchsList($matchsData, 'totalDamageTaken'),
											getMaxMatchFromMatchsList($matchsData, 'totalDamageTaken'));
				}
				else
				{
					updateSummonerDataDB($summonerDB, 
											getMaxMatchFromMatchsList($matchsData, 'score'),
											getMaxMatchFromMatchsList($matchsData, 'kills'),
											getMaxMatchFromMatchsList($matchsData, 'deaths'),
											getMaxMatchFromMatchsList($matchsData, 'assists'),
											getMaxMatchFromMatchsList($matchsData, 'totalDamageTaken'),
											getMaxMatchFromMatchsList($matchsData, 'totalDamageTaken'));
				}
				saveMatchsDataDB($summonerData, $matchsData);
			}
			
			$summonerDB = getSummonerFromDB($summonerData);	
			
			echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="120px" rowspan="3"><img style="display: block" class="topLeftRounded bottomLeftRounded" src="http://ddragon.leagueoflegends.com/cdn/5.7.2/img/champion/'.$summonerDB['img'].'" width="120" height="120" border="0" /></td>
					<td id="yourScore">'.$summonerDB['score'].'</td>
				</tr>
				<tr>';
			switch($summonerDB['rank'])
			{
				case 1:	echo '<td id="yourGeneralRank">1st in world ranking</td>'; break;
				case 2: echo '<td id="yourGeneralRank">2nd in world ranking</td>'; break;
				case 3: echo '<td id="yourGeneralRank">3rd in world ranking</td>'; break;
				default: echo '<td id="yourGeneralRank">'.$summonerDB['rank'].'th in world ranking</td>'; break;
			}
			echo '</tr>
					<tr>
						<td><button id="viewDetailButton" onClick="toggleViewDetails();">+ View detail</button></td>
					</tr>
				</table>';
				
			echo '<input type="hidden" id="summonerId" value="'.$summonerData['id'].'">';
			echo '<input type="hidden" id="matchId" value="'.$summonerDB['id_match_score'].'">';
		}
	}	
}
?>