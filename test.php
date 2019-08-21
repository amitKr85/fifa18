<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<style type="text/css">
		body{
			margin:0;
		}
	</style>
</head>
<body>
<?php
function date_create_IST($str){
	return changeUTCToIST(date_create($str));
}
function changeUTCToIST($date){
	return date_add($date,date_interval_create_from_date_string("5 hours 30 minutes"));
}
function isSameDate($date1,$date2){
	if($date1=="unknown" && $date2=="unknown")
		return TRUE;
	elseif($date1=="unknown" || $date1=="unknown")
		return FALSE;
	else
		return $date1->format("d m y")==$date2->format("d m y");
}
function isToday($date){
	//return $date->format("d m y") == date()
	$today=new DateTime(null,new DateTimeZone("Asia/Kolkata"));
	return isSameDate($date,$today);
}


	 $data=json_decode(file_get_contents("test_jsons/matches.json"));
	 $arr=array(1=>"amit","2"=>"kumar");
	 echo $arr["3"];
	 // $homeEvents=$data[0]->home_team_events;
	 // $awayEvents=$data[0]->away_team_events;
	 // foreach($homeEvents as $homeEvent){
	 // 	$homeEvent->team='team A';
	 // }
	 // foreach($awayEvents as $awayEvent){
	 // 	$awayEvent->team='team B';
	 // }
	 // $events=array_merge($homeEvents, $awayEvents);
	 // $events[2]->time="43'+2'";
	 // $events[2]->player=null;
	 // $events[3]->time="43'+4'";
	 // $events[4]->time="44'";
	 // //usort($events,"customSortObjArray");
	 // function customSortObjArray($obj1,$obj2){
	 // 	$time1=str_replace("+","",str_replace("'", "", $obj1->time));
	 // 	$time2=str_replace("+","",str_replace("'", "", $obj2->time));
	 // 	if($time1 == $time2)
	 // 		return 0;
	 // 	elseif($time1 < $time2)
	 // 		return -1;
	 // 	else
	 // 		return 1;
	 // }
	 // foreach($events as $event){
	 // 	//echo $event->time." : ".$event->type_of_event." : ".$event->player.":".$event->team."<br>";
	 // }
	 // $vr="45'";
	 // echo str_replace("'", "", $vr);
	 // $i=0;
	 // foreach($data as $item){
	 // 	if($item->home_team_country=="France" || $item->away_team_country=="France"){
	 // 		if($item->home_team_country == "France"){
	 // 			if(isset($item->home_team_statistics) && isset($item->home_team_statistics->starting_eleven))
	 // 				$eleven=$item->home_team_statistics->starting_eleven;
	 // 			else
	 // 				continue;
	 // 			if(isset($item->home_team_statistics) && isset($item->home_team_statistics->substitutes))
	 // 				$subs=$item->home_team_statistics->substitutes;
	 // 			else
	 // 				continue;
	 // 		}else{
	 // 			if(isset($item->away_team_statistics) && isset($item->away_team_statistics->starting_eleven))
	 // 				$eleven=$item->away_team_statistics->starting_eleven;
	 // 			else
	 // 				continue;
	 // 			if(isset($item->away_team_statistics) && isset($item->away_team_statistics->substitutes))
	 // 				$subs=$item->away_team_statistics->substitutes;
	 // 			else
	 // 				continue;
	 // 		}
	 // 		$all=array_merge($eleven, $subs);
	 // 		sort($all);
	 // 		foreach ($all as $one){
	 // 			echo $i." : ".$one->name;
	 // 			if($one->captain)
	 // 				echo " : [captain]"; 
	 // 			echo " : ".$one->position." : ".$one->shirt_number."<br>";
	 // 		}
	 // 		echo "<hr>";
	 // 	}
	 // 	++$i;
	 // }

	// //echo (date_create_IST($data[0]->datetime)->format("d m y") == date("d m y")) ? "today": "0";
	// //date_default_timezone_set('America/New_York');
	// //$date = date('m/d/Y h:i:s a', time());
	// //echo isToday(date_create_IST("2018-06-29T14:00:00Z"));
	// date_default_timezone_set("Asia/Kolkata");
	// //echo strtotime("2018-06-29T14:00:00Z") > time();
	// //echo date_create_IST("2018-06-29")->format("h:i:s a");
	// $file=fopen("images/daily_cover/cover_caption.txt","r");
	// 			echo fgets($file);
	// 			fclose($file);
	// $size=count($data);
	// if(isset($data) && !empty($data) && $size>0){
	// 	$currentDate=date_create_IST($data[0]->datetime);
	// 	echo $currentDate->format("d-m-Y h:i:s a")."<br>";

	// 	for($i=0;$i<$size;++$i){
	// 		$item=$data[$i];

	// 		$dateTime=isset($item->datetime) ? date_create_IST($item->datetime) : "unknown";

	// 		if(!isSameDate($currentDate,$dateTime)){
	// 			$currentDate=$dateTime;
	// 			echo $currentDate->format("d-m-Y h:i:s a")."<br>";
	// 		}

	// 		$homeTeamName=isset($item->home_team_country) ? $item->home_team_country : "unknown";
	// 		$awayTeamName=isset($item->away_team_country) ? $item->away_team_country : "unknown";
	// 		$homeTeamGoals=isset($item->home_team->goals) ? $item->home_team->goals : "unknown";
	// 		$awayTeamGoals=isset($item->away_team->goals) ? $item->away_team->goals : "unknown";
	// 		$status=isset($item->status) ? $item->status : "unknown";
	// 		echo "$i:".$dateTime->format("d-m-Y h:i:s a")." $homeTeamName $homeTeamGoals:$awayTeamGoals $awayTeamName<br/>";

	// 		if(($i+1)<$size){
	// 			$nextItem=$data[$i+1];
	// 			$nextDate=isset($nextItem->datetime) ? date_create_IST($nextItem->datetime) : "unknown";
	// 			if(isSameDate($currentDate,$nextDate)){
	// 				echo "<hr>";
	// 			}
	// 		}

	// 	}
	// }


	// if(isset($data) AND !empty($data))
	// 	echo count($data);
	// echo "<script type=text/javascript>console.log(".var_dump($data).");</script>";
	// $fItem=$data[0];
	// $date1=date_create_IST($fItem->datetime);
	// $date2=date_create_IST($data[1]->datetime);
	// if(isSameDate($date2,$date2))
	// 	echo "true";
	// else
	// 	echo "false";
	// echo $dateTime->format("d m y H:i:s");
	// $dateTime2=date_create($data[1]->datetime);
	// $dateTime=date_add($dateTime,date_interval_create_from_date_string("5 hours 30 minutes"));
	// $dateTime2=date_add($dateTime2,date_interval_create_from_date_string("5 hours 30 minutes"));
	// 	echo date_format($dateTime, "l,j F Y H:i:s").";".$dateTime2->format("l,j F Y H:i:s");
	// 	echo "<br/>";
	// 	echo $dateTime->format("d m y")==$dateTime2->format("d m y");
	// foreach($data as $dataItem){

	// }
?>
</body>
</html>