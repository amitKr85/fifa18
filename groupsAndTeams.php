<?php
	$data=json_decode(file_get_contents("jsons/teams.json"));
	if(!isset($data) || empty($data))
		trigger_error("empty data");
	$size=count($data);
	$errorOccured=false;
	function error_handler($errlvl,$errmsg){
		global $errorOccured;
		if($errorOccured==false)
			echo "Oops! an error occured, please check your internet connection, reload again or try again sometimes later";
		$errorOccured=true;
	}
	set_error_handler("error_handler");
	function customSortObjArray($obj1,$obj2){
	 	return $obj1->group_id - $obj2->group_id;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0" >
	<title>FIFA WC'18</title>
	<link rel="icon" href="images/logo.ico">
	<link rel="stylesheet" type="text/css" href="css/root_style.css">
	<link rel="stylesheet" type="text/css" href="css/groups_style.css">
	<link rel="stylesheet" type="text/css" href="css/index_nav_style.css">
	<link rel="stylesheet" type="text/css" href="css/footer_style.css">
</head>
<body>
	<header>
		<div id="top-nav" class="bgcolor">
			<a href="index.php"><div class="site-name">FIFA WC <span class="year colored">2018</span></div></a>
			<ul id="top-nav-list">
				<a href="index.php"><li>Home</li></a>
				<a href="#"><li class="current">Groups & Teams</li></a>
				<a href="https://www.fifa.com" target="_blank"><li class="pc_view">Go to FIFA.com</li></a>
			</ul>
		</div>
	</header>
	<div id="content">
		<?php
			usort($data, "customSortObjArray");
			$currentGroupLetter=$data[0]->group_letter;
			echo '<div class="group-name-container">Group '.$currentGroupLetter.'</div><div class="countries-container">';
			for($i=0; $i<$size; ++$i){
				$item=$data[$i];

				$groupLetter=$item->group_letter;
				$countryName=$item->country;
				if($groupLetter != $currentGroupLetter){
					$currentGroupLetter = $groupLetter;
					echo '<div class="group-name-container">Group '.$currentGroupLetter.'</div><div class="countries-container">';
				}
				echo '<div class="country-container"><div class="flag-container"><img src="images/flags/'.$countryName.'.png" alt="'.$countryName.'" class="flag-image"></div><div class="country-name">'.$countryName.'</div><div class="button-container"><a href="teamInfo.php?team='.$countryName.'" class="more-info-button">More Info ></a></div></div>';
				if(($i+1)<$size){
					if($data[$i+1]->group_letter == $currentGroupLetter){
						echo '<hr class="divider mobile-view">';
					}
					else{
						echo '</div>';
					}
				}
				else{
					echo '</div>';
				}
			}
		?>
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
		function updateElements(){
			$('.flag-image').css({
					'width':'80%',
					'display':'block',
					'margin':'auto'
				});
			var flagWidth=$('.flag-image').width();
			console.log(flagWidth);
			var flagHeight=(flagWidth*175)/300;
			$('.flag-image').css('height',flagHeight+'px');
			/////////
			var navHeight=$('#top-nav').outerHeight(true);
			$('#content').css('margin-top',navHeight+'px');
			/////////
			$('.group-name-container').css({
				'position':'sticky',
				'top':navHeight+'px'
			});
			////////
			$('.mobile-view').css('display','block');
		}
		$(window).on('resize',function(){
			console.log('window resized');
			if($(window).width() <=450){
				updateElements();
			}else{
				///////updating margin-top
				var navHeight=$('#top-nav').outerHeight(true);
				$('#content').css('margin-top',navHeight+'px');
				///////
				///resetting style
				$('.flag-image').removeAttr('style');
				$('.group-name-container').removeAttr('style');
				///removing <hr>
				$('.mobile-view').removeAttr('style');
			}
		});
		$(function(){
			console.log($('#top-nav').outerHeight());
			//buildPage();
			var navHeight=$('#top-nav').outerHeight(true);
			$('#content').css('margin-top',navHeight+'px');
			console.log($(window).width());
			if($(window).width() <=450){
				updateElements();
			}
			console.log($('.flag-image').width());
			//removes add
			$('div[style="text-align: right;position: fixed;z-index:9999999;bottom: 0; width: 100%;cursor: pointer;line-height: 0;display:block !important;"]').remove();
		});
	</script>
</body>
</html>