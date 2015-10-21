<?php
/**
 * @version		2.6.x
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2014 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;
// includes placehold
$yt_temp = JFactory::getApplication()->getTemplate();
include (JPATH_BASE . '/templates/'.$yt_temp.'/includes/placehold.php');

?>

<div id="k2ModuleBox<?php echo $module->id; ?>" class="k2ItemsBlock<?php if($params->get('moduleclass_sfx')) echo ' '.$params->get('moduleclass_sfx'); ?>">

	<?php if($params->get('itemPreText')): ?>
	<p class="modulePretext"><?php echo $params->get('itemPreText'); ?></p>
	<?php endif; ?>

	<?php if(count($items)): ?>
  <ul>
    <?php foreach ($items as $key=>$item): ;?>
	
    <li class="item <?php if((count($items)>1) && ($key > 0)) echo 'item-event'; ?>">
      <!-- Plugins: BeforeDisplay -->
      <?php echo $item->event->BeforeDisplay; ?>

      <!-- K2 Plugins: K2BeforeDisplay -->
      <?php echo $item->event->K2BeforeDisplay; ?>
    <?php if($params->get('itemImage')): ?>
    <a class="moduleItemImage" href="<?php echo $item->link; ?>" title="<?php echo JText::_('K2_CONTINUE_READING'); ?> &quot;<?php echo K2HelperUtilities::cleanHtml($item->title); ?>&quot;">
              <?php 	
                 //Create placeholder items images
                 if(isset($item->image)){
                 $src =$item->image;  }
                 if (!empty( $src)) {								
                         $thumb_img = '<img src="'.$src.'" alt="'.$item->title.'" />'; 
                 } else if ($is_placehold) {					
                         $thumb_img = yt_placehold($placehold_size['education'],$item->title,$item->title); 
                        //var_dump( $thumb_img);
                 }	
                 echo $thumb_img;
             ?>
    </a>
    <?php endif; ?>
    <?php if($params->get('itemTitle')): ?>
    <h4 class="title-cus" data-sr="enter top and move 50px over 0.8s">
        <a  href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
    </h4>
    <?php endif; ?>
	<?php if($params->get('itemExtraFields') && count($item->extra_fields)):
		$metas = $item->extra_fields;
	  ?>
		<div class="main-cus">
		  
			<ul class="main-cus-left pull-left" data-sr="enter left and move 50px over 0.8s">
			    <?php if( (isset($metas[0]) && ($metas[0]->value !=''))):?>
			    <li class="time-of-event"><?php echo $metas[0]->value; ?></li>
			    <?php endif; ?>
			    <?php if( (isset($metas[1]) && ($metas[1]->value !=''))):?>
			    <li class="address"> <?php echo $metas[1]->value; ?></li>
			    <?php endif; ?>
			</ul>
			<?php if($params->get('itemIntroText')): ?>
			<div class="main-cus-right pull-right" data-sr="enter right and move 50px over 0.8s">
			    <p><?php echo $item->introtext; ?></p>
			</div>
			<?php endif; ?>
		    
		</div>
    
		<?php if(isset($metas[4]) && ($metas[4]->value !='')):
			$full_date = JHTML::_('date', $metas[4]->value, 'j-m-Y');
			$event_date = explode("-", $full_date);
			$year_end = $event_date[2];
			$month_end = $event_date[1];
			$day_end = $event_date[0];
			
	      ?>
			<script type="text/javascript"><!--
			jQuery(function () {
				var austDay = new Date(<?php echo $year_end; ?>, <?php echo $month_end; ?>-1 , <?php echo $day_end; ?>);
				jQuery('#countdown-<?php echo $item->id ?>').countdown(austDay) 
				.on('update.countdown', function(event) {
					jQuery(this).html(event.strftime(
					   '<div class="countdown-section time-day"><div class="countdown-amount">%D</div><div class="countdown-period"><?php echo JText::_('DAYS'); ?> </div></div>'
					   + '<div class="countdown-section time-hour"><div class="countdown-amount">%H</div><div class="countdown-period"><?php echo JText::_('HOURS'); ?></div></div>'
					   + '<div class="countdown-section time-min"><div class="countdown-amount">%M</div><div class="countdown-period"><?php echo JText::_('MIN'); ?></div></div>'
					   + '<div class="countdown-section time-sec"><div class="countdown-amount">%S</div><div class="countdown-period"><?php echo JText::_('SEC'); ?> </div></div>'));
				
				})
				.on('finish.countdown', function(event) {
					jQuery(this).html('<h4>This offer has expired!</h4>');
				});;
				
			});
		//--></script>
		<div id="countdown-<?php echo $item->id?>" class="countdown-event"></div>
	      <?php endif;?>
		<?php if( (isset($metas[3]) && ($metas[3]->value !=''))):?>
		<div class="register-btn" data-sr="enter bottom and move 50px over 1s"><?php echo $metas[3]->value; ?></div>
		<?php endif; ?>
	<?php endif;?>
	
      <!-- Plugins: AfterDisplayTitle -->
      <?php echo $item->event->AfterDisplayTitle; ?>

      <!-- K2 Plugins: K2AfterDisplayTitle -->
      <?php echo $item->event->K2AfterDisplayTitle; ?>

      <!-- Plugins: BeforeDisplayContent -->
      <?php echo $item->event->BeforeDisplayContent; ?>

      <!-- K2 Plugins: K2BeforeDisplayContent -->
      <?php echo $item->event->K2BeforeDisplayContent; ?>
      <!-- Plugins: AfterDisplayContent -->
      <?php echo $item->event->AfterDisplayContent; ?>
      <!-- K2 Plugins: K2AfterDisplayContent -->
      <?php echo $item->event->K2AfterDisplayContent; ?>

      <!-- Plugins: AfterDisplay -->
      <?php echo $item->event->AfterDisplay; ?>

      <!-- K2 Plugins: K2AfterDisplay -->
      <?php echo $item->event->K2AfterDisplay; ?>
    </li>
    <?php endforeach; ?>
    <li class="clearList"></li>
  </ul>
  <?php endif; ?>
</div>
