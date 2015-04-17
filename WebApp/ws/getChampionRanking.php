<?php
include('util.php');
if(isset($_POST['sortChampionFilter']))
{	
	$rankingData = getChampionRanking($_POST['sortChampionFilter']);
	if(count($rankingData) == 0)
	{
		echo '<table border="0" class="spacerBottom" cellpadding="0" cellspacing="0" width="100%">';
		echo '<tr class="trOdd"><td><strong>No champions found</strong></td></tr>';
		echo '</table>';
	}
	else
	{
		echo '<table border="0" class="spacerBottom" cellpadding="0" cellspacing="0" width="100%">
							<tr class="thColumn">                    
								<th width="5%">#</th>
								<th width="15%"></th>
								<th width="35%">Champion</th>
								<th width="15%">Average Score</th>
								<th width="10%">Ban Rate</th>
								<th width="10%">Pick Rate</th>
								<th width="10%">Win Rate</th>
							</tr>';	
		for($i = 0; $i < count($rankingData); $i++)
		{
			if(($i % 2) != 0)
			{
				echo '<tr class="trOdd">
						<td><strong>'.($i + 1).'</strong></td>
						<td align="center"><img style="display: block" src="http://ddragon.leagueoflegends.com/cdn/5.7.2/img/champion/'.$rankingData[$i]['img'].'" width="48" height="48" border="0" /></td>
						<td>'.$rankingData[$i]['championName'].'</td>
						<td>'.number_format($rankingData[$i]['avgScore']).'</td>
						<td>'.number_format($rankingData[$i]['banRatePerc'], 2, '.', ',').'%</td>
						<td>'.number_format($rankingData[$i]['pickRatePerc'], 2, '.', ',').'%</td>
						<td>'.number_format($rankingData[$i]['winRatePerc'], 2, '.', ',').'%</td>
					</tr>';	
			}
			else
			{
				echo '<tr class="trEve">
						<td><strong>'.($i + 1).'</strong></td>
						<td align="center"><img style="display: block" src="http://ddragon.leagueoflegends.com/cdn/5.7.2/img/champion/'.$rankingData[$i]['img'].'" width="48" height="48" border="0" /></td>
						<td>'.$rankingData[$i]['championName'].'</td>
						<td>'.number_format($rankingData[$i]['avgScore']).'</td>
						<td>'.number_format($rankingData[$i]['banRatePerc'], 2, '.', ',').'%</td>
						<td>'.number_format($rankingData[$i]['pickRatePerc'], 2, '.', ',').'%</td>
						<td>'.number_format($rankingData[$i]['winRatePerc'], 2, '.', ',').'%</td>
					</tr>';
			}
			
		}
		echo '</table>';
	}
}
?>