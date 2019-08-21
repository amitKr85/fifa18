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
	$today=new DateTime(null,new DateTimeZone("Asia/Kolkata"));
	if(isSameDate($date,$today))
		return TRUE;
	else
		return FALSE;
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
	<link href="css/root_style.css" rel="stylesheet" type="text/css">
	<link href="css/index_nav_style.css" rel="stylesheet" type="text/css">
	<link href="css/index_carousel_style.css" rel="stylesheet" type="text/css">
	<link href="css/index_style.css" rel="stylesheet" type="text/css">
	<link href="css/footer_style.css" rel="stylesheet" type="text/css">
	<style type="text/css">
		
	</style>
</head>
<body>
<header>
	<div id="cover">
		<div id="image-carousel">
			<div class="slide">
				<img src="images/carousels/four.jpg" class="slide-image">
				<div class="cover-upper-layer"></div>
				<div class="slide-caption">france crowned world champions</div>
			</div>
			<div class="slide">
				<img src="images/carousels/two.jpg" class="slide-image">
				<div class="cover-upper-layer"></div>
				<div class="slide-caption">Griezmann: I'll be the first to buy the jersey with the two stars</div>
			</div>
			<div class="slide">
				<img src="images/carousels/seven.jpg" class="slide-image">
				<div class="cover-upper-layer"></div>
				<div class="slide-caption">Modric: Croatia can be proud</div>
			</div>
			<div class="slide">
				<img src="images/carousels/six.jpg" class="slide-image">
				<div class="cover-upper-layer"></div>
				<div class="slide-caption">2018 is Croatia’s first ever appearance in a World Cup Final</div>
			</div>
			<div class="carousel-circles">
				<span class="carousel-circle"></span>
				<span class="carousel-circle"></span>
				<span class="carousel-circle"></span>
				<span class="carousel-circle"></span>
			</div>
			<div class="arrow previous">&#10092;</div>
			<div class="arrow next">&#10093;</div>
		</div>
		<img src="images/fifa_2018_cup_2.png" alt="FIFA 2018" class="fifa-cup-image">
	</div>
	<?php
		$data=json_decode(file_get_contents("jsons/matches.json"));
		$size=0;
		if(isset($data) && !empty($data)){
			$size=count($data);
		}
		
	?>
	<div id="top-nav">
		<a href="#"><div class="site-name">FIFA WC <span class="year">2018</span></div></a>
		<ul id="top-nav-list">
			<a href="#cover"><li class="top-button">Top</li></a>
			<a href="groupsAndTeams.php"><li>Groups & Teams</li></a>
			<a href="https://www.fifa.com" target="_blank"><li class="pc_view">Go to FIFA.com</li></a>
		</ul>
	</div>
</header>
<div id="content">
	<div id="recent-matches">
		<div class="match-type-container">
			<span class="match-type">Recent or Live-Matches</span>
		</div>
		<?php
			if($size>0){
				$i=$size-1;
				// echo $i." before<br>";
				//loop from back till a live or complete match found
				while($i>=0 && $data[$i]->status == "future"){
					// echo $i."<br>";
					--$i;
				}
				// echo "$i after<br>";
				/////////////
				if($i>=0){

					$currentDate=isset($data[$i]->datetime) ? date_create_IST($data[$i]->datetime) : "unknown";
					$stageName=isset($data[$i]->stage_name) ? $data[$i]->stage_name : "unknown-stage";
					//echo $currentDate->format("d-m-Y h:i:s a")."<br>";
					echo '<div class="date-list-item"><div class="date-container">'.$currentDate->format("l,j F Y").'</div><div class="stage-container">'.$stageName.'</div></div>';
					for($j=$i; ($j>$i-4)&&($j>=0); --$j){
						$item=$data[$j];
						$dateTime=isset($item->datetime) ? date_create_IST($item->datetime) : "unknown";
						$stageName=isset($item->stage_name) ? $item->stage_name : "unknown-stage";

						if(!isSameDate($currentDate,$dateTime)){
							$currentDate=$dateTime;
							//echo $currentDate->format("d-m-Y h:i:s a")."<br>";
							echo '<div class="date-list-item"><div class="date-container">'.$currentDate->format("l,j F Y").'</div><div class="stage-container">'.$stageName.'</div></div>';
						}

						$homeTeamName=isset($item->home_team_country) ? $item->home_team_country : "unknown";
						$awayTeamName=isset($item->away_team_country) ? $item->away_team_country : "unknown";
						$homeTeamGoals=isset($item->home_team->goals) ? $item->home_team->goals : "?";
						$awayTeamGoals=isset($item->away_team->goals) ? $item->away_team->goals : "?";
						$status=isset($item->status) ? $item->status : "unknown";

						echo '<div class="score-list-item"><div class="data-container"><div class="team-container home-team"><div class="flag-container home-team"><img src="images/flags/'.$homeTeamName.'.png" alt="'.$homeTeamName.'" class="flag-image"></div><div class="team-name-container home-team">'.$homeTeamName.'</div></div><div class="time-score-status-container"><div class="time-container">'.$dateTime->format("h:i a").'</div><div class="score-container"><span class="goals home-team">'.$homeTeamGoals.'</span>-<span class="goals away-team">'.$awayTeamGoals.'</span></div><div class="status-container"><span class="'.$status.'"></span>'.$status.'</div>';
						//prints learn more-button
						echo '<div class="learn-more-button-container"><a href="learnMore.php?i='.$j.'" class="learn-more-button">Learn More ></a></div>';
						echo '</div><div class="team-container away-team"><div class="flag-ontainer away-team"><img src="images/flags/'.$awayTeamName.'.png" alt="'.$awayTeamName.'" class="flag-image"></div><div class="team-name-container away-team">'.$awayTeamName.'</div></div></div></div>';

						////////
						if(($i-1)>=0){
							$prevItem=$data[$i-1];
							$prevDate=isset($prevItem->datetime) ? date_create_IST($prevItem->datetime) : "unknown";
							if(isSameDate($currentDate,$prevDate) && ($j>$i-3) && ($j>0) ){
								echo '<hr class="divider">';
							}
						}
					}
				}
			}
		?>
	</div>
	<div id="past-matches">
		<div class="match-type-container">
			<span class="match-type">Past-Matches</span>
		</div>	
		<?php

			if($size>0){
				$currentDate=isset($data[0]->datetime) ? date_create_IST($data[0]->datetime) : "unknown";
				$stageName=isset($data[0]->stage_name) ? $data[0]->stage_name : "unknown-stage";
				//echo $currentDate->format("d-m-Y h:i:s a")."<br>";
				echo '<div class="date-list-item"><div class="date-container">'.$currentDate->format("l,j F Y").'</div><div class="stage-container">'.$stageName.'</div></div>';

				for($i=0;$i<$size;++$i){
					$item=$data[$i];
					//check if reach at live or future matches
					$status=isset($item->status) ? $item->status : "unknown";
					if($status != "completed")
						break;
					$dateTime=isset($item->datetime) ? date_create_IST($item->datetime) : "unknown";
					$stageName=isset($item->stage_name) ? $item->stage_name : "unknown-stage";

					if(!isSameDate($currentDate,$dateTime)){
						$currentDate=$dateTime;
						//echo $currentDate->format("d-m-Y h:i:s a")."<br>";
						echo '<div class="date-list-item"><div class="date-container">'.$currentDate->format("l,j F Y").'</div><div class="stage-container">'.$stageName.'</div></div>';
					}

					$homeTeamName=isset($item->home_team_country) ? $item->home_team_country : "unknown";
					$awayTeamName=isset($item->away_team_country) ? $item->away_team_country : "unknown";
					$homeTeamGoals=isset($item->home_team->goals) ? $item->home_team->goals : "?";
					$awayTeamGoals=isset($item->away_team->goals) ? $item->away_team->goals : "?";
					// $status=isset($item->status) ? $item->status : "unknown";
					// if($status == "future")
					// 	$status="upcoming";
					// echo "$i:".$dateTime->format("d-m-Y h:i:s a")." $homeTeamName $homeTeamGoals:$awayTeamGoals $awayTeamName<br/>";
					echo '<div class="score-list-item"><div class="data-container"><div class="team-container home-team"><div class="flag-container home-team"><img src="images/flags/'.$homeTeamName.'.png" alt="'.$homeTeamName.'" class="flag-image"></div><div class="team-name-container home-team">'.$homeTeamName.'</div></div><div class="time-score-status-container"><div class="time-container">'.$dateTime->format("h:i a").'</div><div class="score-container"><span class="goals home-team">'.$homeTeamGoals.'</span>-<span class="goals away-team">'.$awayTeamGoals.'</span></div><div class="status-container '.$status.'">'.$status.'</div>';
					// if($status!="upcoming" && $status!="unknown")
					//prints learn-more button
					echo '<div class="learn-more-button-container"><a href="learnMore.php?i='.$i.'" class="learn-more-button">Learn More ></a></div>';
					echo '</div><div class="team-container away-team"><div class="flag-ontainer away-team"><img src="images/flags/'.$awayTeamName.'.png" alt="'.$awayTeamName.'" class="flag-image"></div><div class="team-name-container away-team">'.$awayTeamName.'</div></div></div></div>';

					if(($i+1)<$size){
						$nextItem=$data[$i+1];
						$nextDate=isset($nextItem->datetime) ? date_create_IST($nextItem->datetime) : "unknown";
						if(isSameDate($currentDate,$nextDate) && $data[$i+1]->status == "completed"){
							echo '<hr class="divider">';
						}
					}

				}
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
	
	var navDefText; //default text in today top-nav-button//
	var currentSlideIndex=0;
	var slideNum=0;
	var intervalId;

	$(window).on('scroll',function(){

		updateTopNav();
		
		if($(window).scrollTop() > $(document).height()/5){
			// console.log('over docH/5');
			$('.top-button').css('display','block');
		}else{
			// console.log('under docH/5 ');
			$('.top-button').removeAttr('style');
		}
		
	});
	function updateTopNav(){
		var $topNav=$('#top-nav');
		var topLimit=$('.slide-caption').eq(currentSlideIndex).offset().top - $topNav.outerHeight();
		if($(window).scrollTop() > topLimit){
			$('#top-nav').addClass('bgcolor');
			$('.site-name .year').addClass('colored');
		}
		else{
			$('#top-nav').removeClass('bgcolor');	
			$('.site-name .year').removeClass('colored');
		}
	}
	function updateStickyTop(){
		var navHeight=$('#top-nav').outerHeight(true);
		var titleHeight=$('.match-type-container').outerHeight(true);
		var totalTop=navHeight+titleHeight;

		$('.match-type-container').css('top',navHeight+'px');
		$('.date-list-item').css('top',totalTop+'px');
	}
	$(window).on('resize',function(){
		updateStickyTop();
	});
	$('a[href^="#"]').on('click',function(e){
		e.preventDefault();
		e.stopPropagation();
		var tarpos=$( $(this).attr('href') ).offset().top - $('#top-nav').outerHeight();
		$('html,body').animate({scrollTop:tarpos},500);
	});

	////for image-carousel////////////////////////////
	function enableAutoAnim(){
		intervalId=setInterval(nextSlide,5000);
	}
	$('.arrow').on('click',function(){
		// console.log('clicked');
		clearInterval(intervalId);
		if(intervalId !== true)
			intervalId=false;
		if($(this).hasClass('next'))
			nextSlide();
		else
			prevSlide();
		 // $('.slide').eq(0).css('left','-100%').animate({'left':'0'},1000,'swing');
		 if(intervalId === false)
		 	setTimeout(enableAutoAnim,2000);
		 intervalId=true;
	});
	function nextSlide(){
		var nextSlideIndex = (currentSlideIndex+1)%slideNum;
		// console.log(currentSlideIndex+":"+nextSlideIndex);

		$('.slide').eq(currentSlideIndex).animate({'left':'-100%'},500,'swing',function(){$(this).removeAttr('style')});
		$('.slide').eq(nextSlideIndex).css({'left':'100%','display':'block'}).animate({'left':'0'},500,'swing');

		$('.carousel-circle').eq(nextSlideIndex).addClass('current');
		$('.carousel-circle').eq(currentSlideIndex).removeClass('current');

		currentSlideIndex = nextSlideIndex;
	}
	function prevSlide(){
		var prevSlideIndex = (currentSlideIndex-1);
		if(prevSlideIndex == -1)
			prevSlideIndex = slideNum-1;

		$('.slide').eq(currentSlideIndex).animate({'left':'100%'},500,'swing',function(){$(this).removeAttr('style')});
		$('.slide').eq(prevSlideIndex).css({'left':'-100%','display':'block'}).animate({'left':'0'},500,'swing');

		$('.carousel-circle').eq(prevSlideIndex).addClass('current');
		$('.carousel-circle').eq(currentSlideIndex).removeClass('current');
		
		currentSlideIndex = prevSlideIndex;
	}
	////////////////////////////////image-craousel-sec. end////////////////
	$(function(){
		updateStickyTop();
		///////for images-carousel/////////////////
		slideNum=$('.slide').length;
		$('.slide').eq(currentSlideIndex).css('display','block');
		$('.carousel-circle').eq(currentSlideIndex).addClass('current');

		enableAutoAnim();
		//removes add
		$('div[style="text-align: right;position: fixed;z-index:9999999;bottom: 0; width: 100%;cursor: pointer;line-height: 0;display:block !important;"]').remove();
	});
</script>
</body>
</html>