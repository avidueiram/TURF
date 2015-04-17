<?php
include('util.php');
if(isset($_POST['summonerName']) && isset($_POST['server']) && isset($_POST['fbId']))
{
	$summonerData = getSummonerData($_POST['summonerName'], $_POST['server']);
	if($summonerData == null)
	{
		echo 0;	
	}
	
	else
	{
		//check if summoner is registered
		$summonerDB = getSummonerFromDB($summonerData);
		if($summonerDB == null)
		{
			createSummonerDataDBEmpty($summonerData);
		}
		else
		{
			if($summonerDB['verificationStatus'] == 'Verified')
			{
				echo 1;	
				exit;
			}
		}
		$masteriesData = getSummonerMateries($summonerData['id'], $_POST['server']);
		foreach($masteriesData as $mastery)
		{
			if($mastery['name'] == $_POST['fbId'])
			{
				echo '<strong>Registration Completed!<script>location.reload();</script></strong>';
				verifySummoner($summonerData['id']);
				exit;
			}
		}
		
		echo 'No masteries found with the name '.$summonerDB['verificationCode'].' - If you are sure that there is a mastery with that name, wait a minute and press Finish Registration again.';
	}
}
?>