<?php
include('util.php');
if(isset($_POST['summonerName']) && isset($_POST['server']) && isset($_POST['fbName']) && isset($_POST['fbId']))
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
		
		updateVerificationCode($summonerData['id'], $_POST['fbId']);
		
		
		//insert fbid, fbname and summonerid
		createFBUser($_POST['fbId'], $_POST['fbName'], $summonerData['id']);
		//return fbid
		echo $_POST['fbId'];	
	}
}
?>