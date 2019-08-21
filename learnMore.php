<?php
	if(isset($_GET["i"]))
		$arrayIndex=$_GET["i"];
	else
		die("incorrect url !!");
	$data=json_decode(file_get_contents("https://worldcup.sfg.io/matches"));
	// $data=json_decode(file_get_contents("test_jsons/matches.json"));
	if(isset($data) && !empty($data)){
		if($arrayIndex >= count($data))
			die('data not available for the requested match.');		
	}
	else{
		trigger_error("empty data", E_USER_ERROR);	
	}
	$item=$data[$arrayIndex];
	if($item->status=="future")
		die('data not available for the requested match.');
	
	function customSortObjArray($obj1,$obj2){
		$time1=str_replace("+","",str_replace("'", "", $obj1->time));
	 	$time2=str_replace("+","",str_replace("'", "", $obj2->time));
	 	if($time1 == $time2)
	 		return 0;
	 	elseif($time1 < $time2)
	 		return -1;
	 	else
	 		return 1;
	}
	$errorOccured=false;
	function error_handler($errlvl,$errmsg){
		global $errorOccured;
		if($errorOccured==false)
			echo "Oops! an error occured, please check your internet connection, reload again or try again sometimes later";
		$errorOccured=true;
	}
	set_error_handler("error_handler");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0" >
	<title>FIFA WC'18</title>
	<link rel="icon" href="images/logo.ico">
	<link rel="stylesheet" type="text/css" href="css/root_style.css">
	<link rel="stylesheet" type="text/css" href="css/learnMore_style.css">
	<link rel="stylesheet" type="text/css" href="css/index_nav_style.css">
	<link rel="stylesheet" type="text/css" href="css/footer_style.css">
	<style type="text/css">
		body{
			/*background-color: black;*/
		}
		#content{
			/*padding-bottom: 700px;*/
		}
	</style>
</head>
<body>
	<header>
		<div id="top-nav">
			<a href="index.php"><div class="site-name">FIFA WC <span class="year">2018</span></div></a>
			<ul id="top-nav-list">
				<a href="index.php"><li>Home</li></a>
				<a href="groupsAndTeams.php"><li>Groups & Teams</li></a>
				<a href="https://www.fifa.com" target="_blank"><li class="pc_view">Go to FIFA.com</li></a>
			</ul>
		</div>
		<div id="cover">
			<div id="cover-data-container">
				<div class="team-container home-team">
					<div class="flag-container home-team">
						<img src="images/flags/<?php echo $item->home_team_country;?>.png" class="flag-image home-team">
					</div>
					<div class="team-name-container home-team">
						<?php echo $item->home_team_country;?>
					</div>
				</div>
				<div class="stage-date-time-score-status-container">
					<div class="stage-container pc-view">
						<?php echo $item->stage_name;?>
					</div>
					<div class="date-container">
						<span class="pc-view">
						<?php 
							$date=date_create($item->datetime);
							$date=date_add($date,date_interval_create_from_date_string("5 hours 30 minutes"));
							echo $date->format("l,");
						?>
						</span>
						<?php
							echo $date->format("j F Y");
						?>
					</div>
					<div class="score-container">
						<?php echo $item->home_team->goals.'-'.$item->away_team->goals; ?>
					</div>
					<div class="time-container">
						<?php echo $date->format("h:i a"); ?>
					</div>
					<div class="status-container">
						<?php echo $item->status; ?>
					</div>
				</div>
				<div class="team-container away-team">
					<div class="flag-container away-team">
						<img src="images/flags/<?php echo $item->away_team_country; ?>.png" class="flag-image away-team">
					</div>
					<div class="team-name-container away-team">
						<?php echo $item->away_team_country; ?>
					</div>
				</div>
			</div>
		</div>
	</header>
	<div id="tabs-container">
		<div class="tab one">Overview</div>
		<div class="tab two">Statistics</div>
		<div class="tab three">Events</div>
	</div>
	<div id="content">
		<div class="page one">
			<div><span class="title">Venue</span>: <?php echo $item->venue; ?></div>
			<div><span class="title">Location</span>: <?php echo $item->location; ?></div>
			<div><span class="title">Stage</span>: <?php echo $item->stage_name; ?></div>
			<div><span class="title">Weather</span>: <?php echo $item->weather->description; ?></div>
				<div><span class="sub title">Humidity</span>: <?php echo $item->weather->humidity; ?>%</div>
				<div><span class="sub title">Wind speed</span>: <?php echo $item->weather->wind_speed; ?> Knots</div>
				<div><span class="sub title">Tempreture</span>: <?php echo $item->weather->temp_celsius; ?>&deg;C</div>
			<div><span class="title">Team A</span>: <?php echo $item->home_team_country; ?></div>
				<div><span class="sub title">Goals</span>: <?php echo $item->home_team->goals; ?></div>
				<div><span class="sub title">Penalties</span>: <?php echo $item->home_team->penalties; ?></div>
			<div><span class="title">Team B</span>: <?php echo $item->away_team_country; ?></div>
				<div><span class="sub title">Goals</span>: <?php echo $item->away_team->goals; ?></div>
				<div><span class="sub title">Penalties</span>: <?php echo $item->away_team->penalties; ?></div>
			<div><span class="title">Winner</span>: <?php echo isset($item->winner) ? $item->winner : "to be determined"; ?></div>
		</div>
		<div class="page two">
			<table>
				<tr><th></th><th><?php echo $item->home_team_country; ?></th><th><?php echo $item->away_team_country; ?></th></tr>
				<?php 
					$i=0;
					$homeTeamStat=$item->home_team_statistics;
					$awayTeamStat=$item->away_team_statistics;
					foreach($homeTeamStat as $key=>$value){
						if($i!=0){
							$keyToShow=ucfirst(str_replace("_"," ",$key));
							echo '<tr><td>'.$keyToShow.'</td><td>'.$homeTeamStat->$key.'</td><td>'.$awayTeamStat->$key.'</td></tr>';
						}
						++$i;
						if($i>19)
							break;
					}
					$homeStartEl=$homeTeamStat->starting_eleven;
					$awayStartEl=$awayTeamStat->starting_eleven;
					echo '<tr class="section"><td colspan="3">Starting Eleven</td></tr>';
					for($i=0;$i<11;++$i){
						echo '<tr><td></td><td><div class="name">'.$homeStartEl[$i]->name;
						if($homeStartEl[$i]->captain)
							echo " [Cap]";
						echo '</div><div class="position">'.$homeStartEl[$i]->position.'</div><div class="shirt-num">'.$homeStartEl[$i]->shirt_number.'</div></td><td><div class="name">'.$awayStartEl[$i]->name;
						if($awayStartEl[$i]->captain)
							echo " [Cap]";
						echo '</div><div class="position">'.$awayStartEl[$i]->position.'</div><div class="shirt-num">'.$awayStartEl[$i]->shirt_number.'</div></td></tr>';						

					}
					$homeSubs=$homeTeamStat->substitutes;
					$awaySubs=$awayTeamStat->substitutes;
					echo '<tr class="section"><td colspan="3">Sustitutes</td></tr>';
					for($i=0;$i<11;++$i){
						echo '<tr><td></td><td><div class="name">'.$homeSubs[$i]->name;
						if($homeSubs[$i]->captain)
							echo " [Cap]";
						echo '</div><div class="position">'.$homeSubs[$i]->position.'</div><div class="shirt-num">'.$homeSubs[$i]->shirt_number.'</div></td><td><div class="name">'.$awaySubs[$i]->name;
						if($awaySubs[$i]->captain)
							echo " [Cap]";
						echo '</div><div class="position">'.$awaySubs[$i]->position.'</div><div class="shirt-num">'.$awaySubs[$i]->shirt_number.'</div></td></tr>';						

					}
				?>
			</table>
		</div>
		<div class="page three">
			<table>
				<tr><th>Time</th><th>Country</th><th>Event</th><th>Player</th></tr>
				<?php
					$homeEvents=$item->home_team_events;
					$awayEvents=$item->away_team_events;
					$homeTeamName=$item->home_team_country;
					foreach($homeEvents as $homeEvent){
	 					$homeEvent->team_name=$item->home_team_country;
	 				}
	 				foreach($awayEvents as $awayEvent){
	 					$awayEvent->team_name=$item->away_team_country;
	 				}
					$events=array_merge($homeEvents, $awayEvents);
					usort($events,"customSortObjArray");
					$events_size=count($events);
					// echo $events_size;
					for($i=0; $i<$events_size; ++$i){
						$event=$events[$i];
						echo '<tr ';
						if($event->team_name == $homeTeamName){
							echo 'class="home-team"';
						}elseif($i<$events_size-1){
							if($events[$i+1]->team_name != $homeTeamName){
								echo 'class="away-team"';
							}
						}
						// if($events[$i+1]->team_name != $homeTeamName || $event->team_name == $homeTeamName)
						// 	echo ($event->team_name == $homeTeamName) ? ('home-team') : ('away-team');
						echo '><td>'.$event->time.'</td><td>'.$event->team_name.'</td><td>'.$event->type_of_event.'</td><td>'.$event->player.'</td></tr>';
					}
				?>
			</table>
		</div>
	</div>
	<footer>
		<div class="footer-email-us">
			Email us for enquiry/issues/contact <a class="email-button" href="mailto:idontknow8527@gmail.com">Email us</a>
		</div>
		<div class="footer-goto-fifa">
			<a href="https://www.fifa.com">Go to Fifa official website</a>
		</div>
		<div class="footer-site-name">
			&copy; FIFA WC 2018
		</div>
	</footer>
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript">
		$(window).on('scroll',function(){
			updateTopNav();
		});
		$(window).on('resize',function(){
			var navHeight=$('#top-nav').outerHeight(true);
			//console.log(navHeight);
			$('#tabs-container').css('top',navHeight+'px');
		});
		function updateTopNav(){
			var $topNav=$('#top-nav');
			var topLimit=$('#cover-data-container').offset().top - $topNav.outerHeight();
			if($(window).scrollTop() > topLimit){
				$('#top-nav').addClass('bgcolor');
				$('.site-name .year').addClass('colored');
			}
			else{
				$('#top-nav').removeClass('bgcolor');	
				$('.site-name .year').removeClass('colored');
			}
		}
		// var currentTabIndex=0;
		$('.tab').on('click',function(){
			if(!this.classList.contains('current-tab')){
				console.log('clicked on tab');
				$('.current-tab').removeClass('current-tab');
				$(this).addClass('current-tab');
				var tabNum=this.classList.item(1);
				console.log(tabNum);
				$('.page').removeAttr('style');
				$('.page.'+tabNum).css({'display':'block','opacity':0}).animate({opacity:1},500);
				var pageHeight=$('.page.'+tabNum).outerHeight(true);
				$('#content').height(pageHeight);
			}
		});
		$(function(){
			console.log($('#top-nav').outerHeight());
			//buildPage();
			var navHeight=$('#top-nav').outerHeight(true);
			//console.log(navHeight);
			$('#tabs-container').css('top',navHeight+'px');
			$('.tab').eq(0).addClass('current-tab');
			$('.page').eq(0).css('display','block');
			var pageHeight=$('.page').eq(0).outerHeight(true);
			$('#content').height(pageHeight);
			//removes add
			$('div[style="text-align: right;position: fixed;z-index:9999999;bottom: 0; width: 100%;cursor: pointer;line-height: 0;display:block !important;"]').remove();
		});
	</script>
</body>
</html>