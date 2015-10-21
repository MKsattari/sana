<?php
/**
 * @package SJ Social Media Counter
 * @version 1.0.1
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2014 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die ();

if (!empty($list)) {
    $tag_id = 'sj_social_media_counts_' . rand() . time();
    if ($params->get('pretext') != '') {
        ?>
        <div class="sc-pretext">
            <?php echo $params->get('pretext'); ?>
        </div>
    <?php } ?>
    <div id="<?php echo $tag_id; ?>" class="sj-social-media-counts">
        <div class="sc-wrap cf">
            <?php if (isset($list['count_facebook_like'])) { ?>
                <div class="fb-like-button sc-item">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('facebook_url', '#'); ?>" title="" target="_blank">
                            <span  class="me-icon fa fa-facebook"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_facebook_like'] >=1000):
				$dem=strlen($list['count_facebook_like']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_facebook_like'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_facebook_like'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				<?php echo $list['count_facebook_like'] > 1 ? JText::_('LIKES_LABEL') : JText::_('LIKE_LABEL'); ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['rss_url'])) {
                ?>
                <div class="rss-like-button sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $list['rss_url']; ?>" title="" target="_blank">
                            <span class="me-icon fa fa-rss"></span>
                            <span class="like-count">Subscribe</span>
                            <span class="like-text">RSS Feeds</span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_followers_twitter'])) {
                ?>
                <div class="twitter-like-button  sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('twitter_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-twitter"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_followers_twitter'] >=1000):
				$dem=strlen($list['count_followers_twitter']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_followers_twitter'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_followers_twitter'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				    <?php echo $list['count_followers_twitter'] > 1 ? JText::_('FOLLOWERS_LABEL') : JText::_('FOLLOWER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_followers_linkedin'])) {
                ?>
                <div class="linkedin-like-button  sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('linkedin_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-linkedin"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_followers_linkedin'] >=1000):
				$dem=strlen($list['count_followers_linkedin']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_followers_linkedin'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_followers_linkedin'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
			    <?php echo $list['count_followers_linkedin'] > 1 ? JText::_('FOLLOWERS_LABEL') : JText::_('FOLLOWER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_followers_dribbble'])) {
                ?>
                <div class="dribbble-like-button  sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('dribbble_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-dribbble"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_followers_dribbble'] >=1000):
				$dem=strlen($list['count_followers_dribbble']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_followers_dribbble'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_followers_dribbble'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				    <?php echo $list['count_followers_dribbble'] > 1 ? JText::_('FOLLOWERS_LABEL') : JText::_('FOLLOWER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_subscribers_youtube'])) {
                ?>
                <div class="youtube-subscribers-button  sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('youtube_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-youtube"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_subscribers_youtube'] >=1000):
				$dem=strlen($list['count_subscribers_youtube']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_subscribers_youtube'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_subscribers_youtube'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				    <?php echo $list['count_subscribers_youtube'] > 1 ? JText::_('SUBSCRIBERS_LABEL') : JText::_('SUBSCRIBER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_followers_soundcloud'])) {
                ?>
                <div class="soundcloud-like-button sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('soundcloud_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-soundcloud"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_followers_soundcloud'] >=1000):
				$dem=strlen($list['count_followers_soundcloud']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_followers_soundcloud'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_followers_soundcloud'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				    <?php echo $list['count_followers_soundcloud'] > 1 ? JText::_('FOLLOWERS_LABEL') : JText::_('FOLLOWER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_followers_vimeo'])) {
                ?>
                <div class="vimeo-linke-button sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('vimeo_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-vimeo-square"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_followers_vimeo'] >=1000):
				$dem=strlen($list['count_followers_vimeo']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_followers_vimeo'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_followers_vimeo'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				    <?php echo $list['count_followers_vimeo'] > 1 ? JText::_('FOLLOWERS_LABEL') : JText::_('FOLLOWER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_followers_gplus'])) {
                ?>
                <div class="gplus-like-button  sc-item">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('gplus_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-google-plus"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_followers_gplus'] >=1000):
				$dem=strlen($list['count_followers_gplus']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_followers_gplus'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_followers_gplus'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				    <?php echo $list['count_followers_gplus'] > 1 ? JText::_('FOLLOWERS_LABEL') : JText::_('FOLLOWER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php
            }
            if (isset($list['count_followers_instagram'])) {
                ?>
                <div class="instagram-like-button sc-item ">
                    <div class="sc-item-inner">
                        <a href="<?php echo $params->get('instagram_url', '#'); ?>" title="" target="_blank">
                            <span class="me-icon fa fa-instagram"></span>
                            <span class="like-count">
			    <?php
			    if ($list['count_followers_instagram'] >=1000):
				$dem=strlen($list['count_followers_instagram']);
				$cuoi= $dem - 3;
				$chuoi= substr($list['count_followers_instagram'],0, $cuoi );
				echo $chuoi,'K';
			    else:
				echo $list['count_followers_instagram'];
			    endif;
			    ?>
			    </span>
			    <span class="like-text">
				    <?php echo $list['count_followers_instagram'] > 1 ? JText::_('FOLLOWERS_LABEL') : JText::_('FOLLOWER_LABEL') ?>
			    </span>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
    if ($params->get('posttext') != '') {
        ?>
        <div class="sc-posttext">
            <?php echo $params->get('posttext'); ?>
        </div>
    <?php
    }
} else {
    echo JText::_('Has no content to show!');
}?>