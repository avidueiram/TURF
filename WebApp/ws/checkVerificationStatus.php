<?php
include('util.php');
if(isset($_POST['fbId']))
{
	$summonerDB = getSummonerFromDBWithFB($_POST['fbId']);
	if($summonerDB == null)
	{
		echo 0;
	}
	else
	{
		if($summonerDB['verificationStatus'] == 'Not verified')	
		{
			echo 1;
		}
		else
		{
			echo $summonerDB['summonerName'];
		}
	}
}
?>