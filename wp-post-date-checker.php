<?php
/*
Plugin Name: Post Date Checker
Plugin URI: http://www.rockia.com
Description: This Wordpress plugin will check the set expiry date, if it's larger than the current datetime, display message to the visitor.
Version: 0.0.1 alpha
Author: Rockia
Author URI: http://www.rockia.com
License: MIT
*/

$timezone	=	"America/Vancouver";   //Default timezone to Vancouver, change accordingly

function get_timezone_offset($remote_tz, $origin_tz = null) {
	if($origin_tz === null) {
		if(!is_string($origin_tz = date_default_timezone_get())) {
			return false; // 
		}
	}
	$origin_dtz = new DateTimeZone($origin_tz);
	$remote_dtz = new DateTimeZone($remote_tz);
	$origin_dt = new DateTime("now", $origin_dtz);
	$remote_dt = new DateTime("now", $remote_dtz);
	$offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
	return $offset;
}

function convert_datetime($str) {

	list($date, $time) = explode(' ', $str);
	list($year, $month, $day) = explode('-', $date);
	list($hour, $minute, $second) = explode(':', $time);

	$timestamp = mktime($hour, $minute, $second, $month, $day, $year);

	return $timestamp;
}


function timezone_offset($zones){
	return get_timezone_offset($zones);
}


function check_post($content){
	$custom_fields = get_post_custom($post->ID);
	if(is_single() ){
		
		if(isset($custom_fields['date'])){
			$my_custom_field = $custom_fields['date'];
			foreach ( $my_custom_field as $key => $value )
			{
				$expired_timestamp = convert_datetime($value). "<br />";
				$current_timestamp = convert_datetime(date("Y-m-d H:i:s"))-timezone_offset($timezone);
			}
		
			if($expired_timestamp > $current_timestamp){
				
				if(date("H",$expired_timestamp)<12){
					$content = $content."Not expired <br />";
				}
					
			}else{
				$content = "Expirted"."<br />".$content;
			}
		}else{
			$content = $content."<br />Date not set"."<br />";
		}
	}else{
			
	}
	return $content;
}

add_filter('the_content', 'check_post');

?>