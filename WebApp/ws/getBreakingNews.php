<?php
include('util.php');
date_default_timezone_set('America/Santiago');

$timeFirst  = strtotime('2015-04-01 00:00:01');
$timeSecond = strtotime('2015-04-14 00:00:00');
$differenceInSeconds = ($timeSecond - $timeFirst) / 60;

echo '<marquee behavior="scroll" direction="left" width="760">';
$newsData = getBreakingNews();
if($newsData == null)
{
	echo 'No news to report';
}
else
{
	echo 'Teemo death count ascends to '.number_format($newsData[0]['rankValue']).' increasing by '.((int)($newsData[0]['rankValue'] / $differenceInSeconds)).' each minute - Take precautions if you are playing Teemo <strong>|</strong> ';
	echo 'WANTED SERIAL KILLER - Killed '.$newsData[1]['rankValue'].' champions in '.((int)($newsData[1]['timePlayed'] / 60)).' minutes - Last seen diving enemy fountain <strong>|</strong> ';
	echo 'New world death record! An anonymous summoner was killed '.$newsData[2]['rankValue'].' times in '.((int)($newsData[2]['timePlayed'] / 60)).' minutes - "I am the best BY FAR" he said <strong>|</strong> ';
	//assists news
	echo 'Frog "George" is now a protected specie - Authorities are trying to stop the increase rate of suicides but just in the last 24 hrs there have been '.number_format((int)($newsData[4]['rankValue'] / 144)).' cases. <strong>|</strong> ';
	echo 'Summoner\'s Rift shopkeepers will donate 2.5% of the gold spent on their shops to the Pantheon Bakery Shop Dream Foundation (PBSDF) - Total gold collected: '.number_format($newsData[5]['rankValue'] * 0.05).' <strong>|</strong> ';
}
echo '</marquee>';

?>