<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

require_once( dirname( __FILE__ ) . '/dependencies.php' );

class SocialSearch
{
	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
	}

	public static function factory()
	{
		return new self();
	}

	public function getTaxonomyID( $type )
	{
		$db 	= FD::db();
		$sql 	= $db->sql();

		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = "select id from `#__finder_taxonomy`";
		$query .= " where `access` IN ( $groups )";
		$query .= " and `state` = 1";
		$query .= " and `title` = '$type'";

		$sql->raw( $query );

		$db->setQuery( $sql );

		return $db->loadResult();
	}

	public function getTaxonomyTypes( $type = null )
	{
		$db 	= FD::db();
		$sql 	= $db->sql();

		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());

		$query = "select * from `#__finder_taxonomy`";
		$query .= " where `access` IN ( $groups )";
		$query .= " and `state` = 1";
		$query .= " and parent_id = ( select id from `#__finder_taxonomy` where title = 'Type' )";
		if( $type )
		{
			$query .= " and `title` = '$type'";
		}
		else
		{
			$query .= " and `title` IN ( select title from `#__finder_types` )";
		}

		$sql->raw( $query );

		$db->setQuery( $sql );

		$results = $db->loadObjectList();

		if( $results ) {
			for( $i = 0; $i < count($results); $i++ ) {
				$row =& $results[$i];

				$row->displayTitle = $row->title;
				$row->icon = $this->getIcon( $row->title );

				switch ( $row->title )
				{
					case 'EasySocial.Albums':
							$row->displayTitle = JText::_( 'COM_EASYSOCIAL_SEARCH_TYPE_ALBUMS' );
							break;
					case 'EasySocial.Photos':
							$row->displayTitle = JText::_( 'COM_EASYSOCIAL_SEARCH_TYPE_PHOTOS' );
							break;
					case 'EasySocial.Users':
							$row->displayTitle = JText::_( 'COM_EASYSOCIAL_SEARCH_TYPE_PEOPLE' );
							break;
					case 'EasySocial.Groups':
							$row->displayTitle = JText::_( 'COM_EASYSOCIAL_SEARCH_TYPE_GROUPS' );
							break;
					case 'EasySocial.Events':
							$row->displayTitle = JText::_( 'COM_EASYSOCIAL_SEARCH_TYPE_EVENTS' );
							break;
					case 'EasyBlog':
							$row->displayTitle = JText::_( 'COM_EASYSOCIAL_SEARCH_TYPE_BLOG' );
							break;
					case 'EasyDiscuss':
							$row->displayTitle = JText::_( 'COM_EASYSOCIAL_SEARCH_TYPE_DISCUSS' );
							break;
					default:
						$row->displayTitle 	= JText::_($row->displayTitle);
						break;
				}

			}
		}

		return $results;
	}

	public function formatMini( $results, $q, $highlight = true )
	{
		$config = FD::config();
		$data = array();

		if( $results )
		{

			$searchRegex = '';
			$hlword = $q;
			if ($hlword) {
				$searchRegex = '#(';
				$searchRegex .= preg_quote($hlword, '#');
				$searchRegex .= '(?!(?>[^<]*(?:<(?!/?a\b)[^<]*)*)</a>))#iu';
			}


			foreach( $results as $row )
			{
				$obj 	= new SocialSearchItem();

				$obj->link	= FRoute::search(array('q' => urlencode($row)));

				//lets process the content and title highlight
				$title	= $row;

				if ($highlight) {
					if ($title) {
						$title		= preg_replace($searchRegex, '<span class="highlight">\0</span>', $title);
					}
				}
				$obj->title		= $title;

				$data[] = $obj;
			}
		}

		return $data;
	}

	public function format( $results, $query, $highlight = true )
	{
		$config = FD::config();
		$data = array();
		$userBlockedYou = array();

		if ($config->get('users.blocking.enabled') && !JFactory::getUser()->guest) {
			//get all users who blocked the current logged in user.
			$model = FD::model('Blocks');
			$userBlockedYou = $model->getUsersBlocked(JFactory::getUser()->id, true);
		}

		if( $results )
		{
			foreach( $results as $row )
			{

				if (!method_exists($row, 'getTaxonomy')) {
					continue;
				}

				$itemType = array_values( $row->getTaxonomy('Type') );

				// echo '<pre>';print_r( $row );echo '</pre>';exit;

				//lets format the result.
				$group = '';
				$creatorColumn = 'created_by';
				$objAdapter = null;

				switch ( $itemType[0]->title )
				{
					case 'EasyDiscuss':
						$group = 'discuss';
						break;
					case 'EasyBlog':
							$group = 'blog';
							break;
					case 'EasySocial.Albums':
							$group = 'albums';
							$creatorColumn = 'user_id';
							break;
					case 'EasySocial.Photos':
							$group = 'photos';
							$creatorColumn = 'user_id';
							break;
					case 'EasySocial.Users':
							$group = 'users';
							$creatorColumn = 'id';
							break;
					case 'EasySocial.Groups':
							$group = 'groups';
							$creatorColumn = 'creator_uid';
							// $objAdapter = FD::group($row->id);
							break;
					case 'EasySocial.Events':
							$group = 'events';
							$creatorColumn = 'creator_uid';
							$objAdapter = FD::event($row->id);
							break;
					default:
						$group = $itemType[0]->title;
						break;
				}

				$obj 	= new SocialSearchItem();

				$obj->finder	= $row;

				// remove the cli segment incase the indexing was perform using cli method.
				$tmp = ltrim(JPATH_ROOT,'/');
				$tmp = rtrim($tmp,'/');
				$tmp = $tmp . '/cli/';
				$row->route = str_replace( $tmp, '', $row->route);

				if (! is_null($objAdapter) ) {
					$obj->link		= $objAdapter->getPermalink();
				} else {
					$obj->link		= JRoute::_( $row->route );
				}

				// ensure there is a leading slash.
				$obj->link = '/'. ltrim($obj->link,'/');


				$obj->image 	= '';
				$obj->utype 	= $itemType[0]->title;
				$obj->id 		= $row->link_id;
				$obj->uid 		= $row->id;
				$obj->type_id	= $row->type_id;
				$obj->icon		= $this->getIcon( $itemType[0]->title );

				$image = '';
				$checkBlockedUserId = '';

				// let check if this item contain image param or not
				if ($row->params && is_object($row->params)) {
					$image 	= $row->params->get('image','');
					$checkBlockedUserId = $row->getElement($creatorColumn);
				}

				if ($config->get('users.blocking.enabled') && $userBlockedYou && !JFactory::getUser()->guest) {
					if ($checkBlockedUserId && in_array($checkBlockedUserId, $userBlockedYou)) {
						// lets skip this item.
						continue;
					}
				}

				if (!$image) {
					// try to get any images from the body.
					// @rule: Match images from blog post
					$pattern	= '/<\s*img [^\>]*src\s*=\s*[\""\']?([^\""\'\s>]*)/i';
					preg_match( $pattern , $row->body , $matches );

					$image		= '';
					if( $matches )
					{
						$image		= isset( $matches[1] ) ? $matches[1] : '';

						if( JString::stristr( $matches[1], 'https://' ) === false && JString::stristr( $matches[1], 'http://' ) === false && !empty( $image ) )
						{
							$image	= rtrim(JURI::root(), '/') . '/' . ltrim( $image, '/');
						}
					}

					if( !$image )
					{
						//let give a default image icons.
						$image	= rtrim(JURI::root(), '/') . '/media/com_easysocial/images/defaults/search/large.png';
					}
				}

				$obj->image 		= $image;

				//lets process the content and title highlight
				$title		= $row->title;
				$content	= $row->description ? $row->description : '';
				$content	= JHtml::_('string.truncate', $row->description, 255);

				if ($highlight) {
					$searchwords = $query->highlight;
					if ($searchwords) {
						$searchRegex = '#(';
						$x = 0;

						foreach ($searchwords as $k => $hlword) {
							$searchRegex .= ($x == 0 ? '' : '|');
							$searchRegex .= preg_quote($hlword, '#');
							$x++;
						}
						$searchRegex .= '(?!(?>[^<]*(?:<(?!/?a\b)[^<]*)*)</a>))#iu';

						if ($title) {
							$title		= preg_replace($searchRegex, '<span class="highlight">\0</span>', $title);
						}

						if ($content) {
							$content	= preg_replace($searchRegex, '<span class="highlight">\0</span>', $content);
						}
					}
				}

				$obj->title		= $title;
				$obj->content	= $content;

				$data[ $group ][] = $obj;
			}
		}

		return $data;
	}

	public function getIcon( $type )
	{
		$icon = '';
		switch ( $type )
		{
			case 'EasyDiscuss':
				$icon = 'ies-copy';
				break;
			case 'EasyBlog':
				$icon = 'ies-notebook';
				break;
			case 'EasySocial.Albums':
				$icon = 'ies-pictures';
				break;
			case 'EasySocial.Photos':
				$icon = 'ies-picture';
				break;
			case 'EasySocial.Users':
				$icon = 'ies-user';
				break;
			case 'EasySocial.Groups':
				$icon = 'ies-users';
				break;
			case 'EasySocial.Events':
				$icon = 'ies-calendar';
				break;
			case 'Article':
				$icon = 'ies-list-2';
				break;
			default:
				$icon = 'ies-file';
				break;
		}

		return $icon;
	}

}
