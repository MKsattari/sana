<?php
/**
 * @package Sj Flat Menu
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

$tag_id = 'sj_weather_'.$module->id;
$cls = $params->get('moduleclass_sfx');
JHtml::stylesheet('modules/'.$module->module.'/assets/css/styles.css');
if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/'.$module->module.'/tmpl/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/tmpl/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
} 

?>

<?php if( $params->get('pretext') != ' ' ) { ?>
    <div class="pretext"><?php echo $params->get('pretext'); ?></div>
<?php } ?>

<div class="sj_weather" id="<?php echo $tag_id;?>">
	<div class="sj_weather_moduleclass_sfx <?php echo $cls;?>">
		<div class="sj_weather_real_time sj_weather_item"><p></p></div>
		<div class="sj_weather_real_day sj_weather_item"><p><?php echo $item['date'];?></p></div>
		<div class="sj_weather_info sj_weather_item"><p>Weather: <span class="sj_weather_icon"><img src="<?php echo $item['icon'];?>" alt/></span> <?php echo  $item['c'];?>&deg;C</p></div>
	</div>
</div>

<?php if( $params->get('posttext') != ' ' ) { ?>
    <div class="posttext"><?php echo $params->get('posttext'); ?></div>
<?php } ?>
<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function($){
		var now = new Date();
		var type;
		var h;
		var m = now.getMinutes();
		if(m < 10){
			m = '0'+m;
		}
		var s = now.getSeconds();
		var timeOut = (60 - parseInt(s)) * 1000;
		if(parseInt(now.getHours()) <= 12){
			type = 'AM';
			h = parseInt(now.getHours());
		}else{
			type = 'PM';
			h = parseInt(now.getHours()) - 12;
		}
		if(h < 10){
			h = '0' + h;				
		}
		$('#<?php echo $tag_id;?> .sj_weather_real_time p').html(h+':'+m+' '+ type);
		setTimeout(function(){ 
			var now = new Date();
			var type;
			var h;
			var m = now.getMinutes();
			if(m < 10){
				m = '0'+m;
			}
			var s = now.getSeconds();
			var timeOut = (60 - parseInt(s)) * 1000;
			if(parseInt(now.getHours()) <= 12){
				type = 'AM';
				h = parseInt(now.getHours());
			}else{
				type = 'PM';
				h = parseInt(now.getHours()) - 12;
			}
			if(h < 10){
				h = '0' + h;					
			}
			$('#<?php echo $tag_id;?> .sj_weather_real_time p').html(h+':'+m+' '+ type);
			setInterval(function(){
				var now = new Date();
				var type;
				var h;
				var m = now.getMinutes();
				if(m < 10){
					m = '0'+m;
				}
				var s = now.getSeconds();
				var timeOut = (60 - parseInt(s)) * 1000;
				if(parseInt(now.getHours()) <= 12){
					type = 'AM';
					h = parseInt(now.getHours());
				}else{
					type = 'PM';
					h = parseInt(now.getHours()) - 12;
				}
				if(h < 0){
					h = '0' + h;				
				}
				$('#<?php echo $tag_id;?> .sj_weather_real_time p').html(h+':'+m+' '+ type);
			},60000)
		}, timeOut);
	})
	//]]>
</script>
