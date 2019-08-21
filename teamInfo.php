<?php
	if(isset($_GET["team"]))
		$team_name=$_GET["team"];
	else
		die("incorrect url !!");
	$data=json_decode(file_get_contents("https://worldcup.sfg.io/matches"));
	// $data=json_decode(file_get_contents("test_jsons/matches.json"));
	if(isset($data) && !empty($data))
		$size=count($data);
	else
		trigger_error("empty data", E_USER_ERROR);	
	//check for correct country
	$flag=0;
	$teamIndexs=array();
	$i=0;
	foreach ($data as $item) {
		if(isset($item->home_team_country)){
			if($item->home_team_country == $team_name){
				if($flag==0)
					$flag=1;
				array_push($teamIndexs,$i);
				
				// break;
			}
		}
		if(isset($item->away_team_country)){
			if($item->away_team_country == $team_name){
				if($flag==0)
					$flag=2;
				array_push($teamIndexs,$i);
				// break;
			}
		}
		++$i;
	}
	if($flag == 0)
		die("incorrect url !!");
	$players;
	$item=$data[$teamIndexs[0]];
	if($flag ==1){
		$eleven;
		$subs;
		if(isset($item->home_team_statistics)){ 
			if(isset($item->home_team_statistics->starting_eleven))
				$eleven=$item->home_team_statistics->starting_eleven;
			if(isset($item->home_team_statistics->substitutes))
				$subs=$item->home_team_statistics->substitutes;
		}
		$players=array_merge($eleven,$subs);
		sort($players);
	}else{
		$eleven;
		$subs;
		if(isset($item->away_team_statistics)){ 
			if(isset($item->away_team_statistics->starting_eleven))
				$eleven=$item->away_team_statistics->starting_eleven;
			if(isset($item->away_team_statistics->substitutes))
				$subs=$item->away_team_statistics->substitutes;
		}
		$players=array_merge($eleven,$subs);
		sort($players);
	}
	function date_create_IST($str){
		return changeUTCToIST(date_create($str));
	}
	function changeUTCToIST($date){
		return date_add($date,date_interval_create_from_date_string("5 hours 30 minutes"));
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
	<link rel="stylesheet" type="text/css" href="css/index_nav_style.css">
	<link rel="stylesheet" type="text/css" href="css/teamInfo_style.css">
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
				<div class="cover-flag-container">
					<img class="cover-flag-image" src="images/flags/<?php echo $team_name; ?>.png" alt="<?php echo $team_name; ?>">
				</div>
				<div class="cover-team-name-cap-container">
					<div class="cover-team-name">
						<?php echo $team_name; ?>
					</div>
					<div class="cover-captain-name">
						<?php 
							foreach ($players as $player){
								if($player->captain){
									echo 'Captain : '.$player->name;
									break;
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</header>
	<div id="tabs-container">
		<div class="tab one">Players</div>
		<div class="tab two">Matches</div>
	</div>
	<div id="content">
		<div class="page one">
			<table>
				<tr><th>Player</th><th>Position</th><th>Shirt number</th></tr>
				<?php
					foreach($players as $player){
						echo '<tr><td>'.$player->name;
						if($player->captain)
							echo ' [Cap]';
						echo '</td><td>'.$player->position.'</td><td>'.$player->shirt_number.'</td></tr>';
					}
				?>
			</table>
		</div>
		<div class="page two">
			<?php
			$teamIndSize=count($teamIndexs);
				for($i=0; $i<$teamIndSize; ++$i){
					$index=$teamIndexs[$i];
					$item=$data[ $index ];

					$homeTeamName=isset($item->home_team_country) ? $item->home_team_country : "unknown";
					$awayTeamName=isset($item->away_team_country) ? $item->away_team_country : "unknown";
					$homeTeamGoals=isset($item->home_team->goals) ? $item->home_team->goals : "?";
					$awayTeamGoals=isset($item->away_team->goals) ? $item->away_team->goals : "?";
					$status=isset($item->status) ? $item->status : "unknown";
					if($status == "future")
						$status="upcoming";
					$dateTime=isset($item->datetime) ? date_create_IST($item->datetime) : "unknown";

					// echo "$homeTeamName;$awayTeamName;$homeTeamGoals;$awayTeamGoals;".$dateTime->format("d m y");
					echo '<div class="match-container"><div class="flag-score-container"><div class="flag-name-container home-team"><div class="flag-container"><img class="flag-image" src="images/flags/'.$homeTeamName.'.png" alt="'.$homeTeamName.'"></div><div class="team-name-container">'.$homeTeamName.'</div></div><div class="score-container">'.$homeTeamGoals.'-'.$awayTeamGoals.'</div><div class="flag-name-container home-team"><div class="flag-container"><img class="flag-image" src="images/flags/'.$awayTeamName.'.png" alt="'.$awayTeamName.'"></div><div class="team-name-container">'.$awayTeamName.'</div></div></div><div class="date-status-button-container"><div class="date-container">'.$dateTime->format("l,j F").'</div><div class="status-container"><span class="'.$status.'"></span>'.$status.'</div>';
					if($status != "upcoming")
						echo '<div class="button-container"><a href="learnMore.php?i='.$index.'" class="more-info-button">More Info ></a></div>';
					else
						echo '<span class="upcoming"></span>';
					echo '</div></div>';
					if($i < $teamIndSize-1)
						echo '<hr class="divider">';

				}
			?>
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
			updateStickyPos();
			updateTeamSize();
		});
		function updateStickyPos(){
			console.log('resize');
			var navHeight=$('#top-nav').outerHeight(true);
			//console.log(navHeight);
			$('#tabs-container').css('top',navHeight+'px');
		}
		function updateTeamSize(){
			var contWidth=$('#cover-data-container').outerWidth();
			var $coverFlag=$('.cover-flag-image');
			var $teamName=$('.cover-team-name');
			var $capName=$('.cover-captain-name');

			// console.log(contWidth);
			var flagWidth=contWidth/3.282;
			var flagHeight=flagWidth/1.714;
			var teamFont=contWidth/12.8;
			var capFont=contWidth/27.428;

			// console.log(flagWidth+":"+flagHeight+":"+teamFont+":"+capFont);

			$coverFlag.css({
				'width':flagWidth+'px',
				'height':flagHeight+'px'
			});
			$teamName.css('font-size',teamFont+'px');
			$capName.css('font-size',capFont+'px');
		}
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
		$('.tab').on('click',function(){
			if(!this.classList.contains('current-tab')){
				$('.current-tab').removeClass('current-tab');
				$(this).addClass('current-tab');
				var tabNum=this.classList.item(1);
				$('.page').removeAttr('style');
				$('.page.'+tabNum).css({'display':'block','opacity':0}).animate({opacity:1},500);
				var pageHeight=$('.page.'+tabNum).outerHeight(true);
				$('#content').height(pageHeight);
			}
		});
		$(function(){
			console.log(window.innerWidth);
			//buildPage();
			updateStickyPos();
			updateTeamSize();
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
