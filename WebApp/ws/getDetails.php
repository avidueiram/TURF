<?php
include('util.php');
if(isset($_POST['summonerId']) && isset($_POST['matchId']))
{
	$matchData = getDetailsDataDB($_POST['summonerId'], $_POST['matchId']);
	echo '<table class="spacerTop spacerBottom" border="0" cellpadding="0" cellspacing="0" width="100%">
                	<tr class="thColumn">
                    	<th width="40%">Stats</th>
                        <th width="20%">Value</th>
                        <th width="20%">Points</th>
                    </tr>
                    <tr class="trOdd">
                    	<td><strong>Level</strong></td>
                        <td>'.$matchData['level'].'</td>
                        <td>'.number_format($matchData['level'] * 100).'</td>
                    </tr>
                    <tr class="trEve">
                    	<td><strong>Minions killed</strong></td>
                        <td>'.number_format($matchData['minionsKilled']).'</td>
                        <td>'.number_format($matchData['minionsKilled'] * 100).'</td>
                    </tr>
                    <tr class="trOdd">
                    	<td><strong>Gold earned</strong></td>
                        <td>'.number_format($matchData['goldEarned']).'</td>
                        <td>'.number_format($matchData['goldEarned']).'</td>
                    </tr>
                    <tr class="trEve">
                    	<td><strong>Total damage taken</strong></td>
                        <td>'.number_format($matchData['totalDamageTaken']).'</td>
                        <td>'.number_format($matchData['totalDamageTaken'] * 0.5).'</td>
                    </tr>
                    <tr class="trOdd">
                    	<td><strong>Total damage dealt to champions</strong></td>
                        <td>'.number_format($matchData['totalDamageDealtToChampions']).'</td>
                        <td>'.number_format($matchData['totalDamageDealtToChampions'] * 0.75).'</td>
                    </tr>
                    <tr class="trEve">
                    	<td><strong>Total time crowd control dealt</strong></td>
                        <td>'.number_format($matchData['totalTimeCrowdControlDealt']).'</td>
                        <td>'.number_format($matchData['totalTimeCrowdControlDealt'] * 10).'</td>
                     </tr>
                     <tr class="trOdd">
                     	<td><strong>Total heal</strong></td>
                        <td>'.number_format($matchData['totalHeal']).'</td>
                        <td>'.number_format($matchData['totalHeal'] * 0.8).'</td>
                     </tr>
                     <tr class="trEve">
                     	<td><strong>Wards placed</strong></td>
                        <td>'.number_format($matchData['wardPlaced']).'</td>
                        <td>'.number_format($matchData['wardPlaced'] * 100).'</td>
                     </tr>
                     <tr class="trOdd">
                     	<td><strong>Wards killed</strong></td>
                        <td>'.number_format($matchData['wardKilled']).'</td>
                        <td>'.number_format($matchData['wardKilled'] * 150).'</td>
                     </tr>
                     <tr class="trEve">
                     	<td><strong>Kills</strong></td>
                        <td>'.$matchData['kills'].'</td>
                        <td>'.number_format($matchData['kills'] * 500).'</td>
                     </tr>
                     <tr class="trOdd">
                     	<td><strong>Deaths</strong></td>
                        <td>'.$matchData['deaths'].'</td>
                        <td>-'.number_format($matchData['deaths'] * 500).'</td>
                     </tr>
                     <tr class="trEve">
                     	<td><strong>Assists</strong></td>
                        <td>'.$matchData['assists'].'</td>
                        <td>'.number_format($matchData['assists'] * 600).'</td>
                     </tr>
                     <tr class="trOdd">
                     	<td><strong>Result</strong></td>
                        <td>'.($matchData['win'] ? 'W' : 'L').'</td>
                        <td>+'.($matchData['win'] ? '50' : '0').'%</td>
                     </tr>
                     <tr class="trEve">
                     	<td><strong>Time played</strong></td>
                        <td>'.((int)($matchData['timePlayed'] / 60)).'m '.($matchData['timePlayed'] % 60).'s</td>
                        <td>'.number_format((50000 - ($matchData['timePlayed'] * 13)) < 0 ? 0 : (50000 - ($matchData['timePlayed'] * 13))).'</td>
                     </tr>
                </table>';
}
?>