<?php
include('util.php');
if(isset($_POST['regionFilter']) && isset($_POST['sortFilter']))
{
	$rankingData = getRankingDataDB($_POST['regionFilter'], $_POST['sortFilter']);
	if($rankingData == null)
	{
		echo '<table border="0" class="spacerBottom" cellpadding="0" cellspacing="0" width="100%">';
		echo '<tr class="trOdd"><td><strong>No summoners found for region selected</strong></td></tr>';
		echo '</table>';
	}
	else
	{
		$sort = "";
		$title = "";
		switch($_POST['sortFilter'])
		{
			default: $sort = 'score'; break;				
			case 'score': 
			case 'kills':  
			case 'deaths':  
			case 'assists':  
			case 'totalDamageDealtToChampions':
			case 'totalDamageTaken': $sort = $_POST['sortFilter']; break;
		}
		
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
                        <th width="15%">Champion</th>
                        <th width="45%">Summoner</th>
                        <th width="15%">Region</th>
                        <th width="20%">'.$title.'</th>
                    </tr>';	
		for($i = 0; $i < count($rankingData); $i++)
		{
			if(($i % 2) != 0)
			{
				echo '<tr class="trOdd">
						<td><strong>'.($i + 1).'</strong></td>
						<td align="center"><img style="display: block" src="http://ddragon.leagueoflegends.com/cdn/5.7.2/img/champion/'.$rankingData[$i]['img'].'" width="48" height="48" border="0" /></td>
						<td>'.$rankingData[$i]['summonerName'].'</td>
						<td>'.strtoupper($rankingData[$i]['region']).'</td>
						<td>'.number_format($rankingData[$i][$sort]).'</td>
					</tr>';	
			}
			else
			{
				echo '<tr class="trEve">
						<td><strong>'.($i + 1).'</strong></td>
						<td align="center"><img style="display: block" src="http://ddragon.leagueoflegends.com/cdn/5.7.2/img/champion/'.$rankingData[$i]['img'].'" width="48" height="48" border="0" /></td>
						<td>'.$rankingData[$i]['summonerName'].'</td>
						<td>'.strtoupper($rankingData[$i]['region']).'</td>
						<td>'.number_format($rankingData[$i][$sort]).'</td>
					</tr>';
			}
			
		}
		echo '</table>';
		
	}
}
?>