<?php
include('util.php');
if(isset($_POST['fbId']) && isset($_POST['friendsData']) && isset($_POST['sortFilter']))
{	
	$friendsSummonerData = getFriendsSummonerData($_POST['friendsData'], $_POST['fbId'], $_POST['sortFilter']);
	if($friendsSummonerData == null)
	{
		echo 0;
	}
	else
	{
		$title = "";
		switch($_POST['sortFilter'])
		{
			default:
			case 'score': $title = 'Score'; break;
			case 'kills': $title = 'Kills'; break;
			case 'deaths': $title = 'Deaths'; break;
			case 'assists': $title = 'Assists'; break;
			case 'totalDamageDealtToChampions': $title = 'Damage Dealt'; break;
			case 'totalDamageTaken': $title = 'Damage Taken'; break;
		}
		
		echo '<table border="0" class="spacerBottom" cellpadding="0" cellspacing="0" width="100%">
						<tr class="thColumn">                    
							<th width="5%">#</th>
							<th width="15%"></th>
							<th width="35%">Name</th>
							<th width="30%">Summoner</th>
							<th width="15%">'.$title.'</th>
						</tr>';	
						
		for($i = 0; $i < count($friendsSummonerData); $i++)
		{
			if(($i % 2) != 0)
			{
				echo '<tr class="trOdd">
						<td><strong>'.($i + 1).'</strong></td>
						<td align="center"><img style="display: block" src="http://ddragon.leagueoflegends.com/cdn/5.7.2/img/champion/'.$friendsSummonerData[$i]['img'].'" width="48" height="48" border="0" /></td>
						<td>'.utf8_encode($friendsSummonerData[$i]['name']).'</td>
						<td>'.$friendsSummonerData[$i]['summonerName'].'</td>
						<td>'.number_format($friendsSummonerData[$i]['rankValue']).'</td>
					</tr>';	
			}
			else
			{
				echo '<tr class="trEve">
						<td><strong>'.($i + 1).'</strong></td>
						<td align="center"><img style="display: block" src="http://ddragon.leagueoflegends.com/cdn/5.7.2/img/champion/'.$friendsSummonerData[$i]['img'].'" width="48" height="48" border="0" /></td>
						<td>'.utf8_encode($friendsSummonerData[$i]['name']).'</td>
						<td>'.$friendsSummonerData[$i]['summonerName'].'</td>
						<td>'.number_format($friendsSummonerData[$i]['rankValue']).'</td>
					</tr>';
			}
			
		}
		echo '</table>';				
	}
}
?>