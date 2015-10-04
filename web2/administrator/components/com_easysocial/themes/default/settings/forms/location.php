<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

$providerOptions = array(
    $settings->makeOption('Service Provider Foursquare', 'foursquare'),
    $settings->makeOption('Service Provider Google Places', 'places'),
    $settings->makeOption('Service Provider Google Maps', 'maps'),
    'help' => true
);

echo $settings->renderPage(
    $settings->renderColumn(
        $settings->renderSection(
            $settings->renderHeader('Service Integration'),
            $settings->renderSetting('Service Provider', 'location.provider', 'list', $providerOptions)
        )
    ),

    $settings->renderColumn(
        $settings->renderSection(
            $settings->renderHeader('Service Provider Foursquare'),
            $settings->renderSetting('Service Provider Foursquare Client ID', 'location.foursquare.clientid', 'input', array('help' => true, 'class' => 'form-control input-xl')),
            $settings->renderSetting('Service Provider Foursquare Client Secret', 'location.foursquare.clientsecret', 'input', array('help' => true, 'class' => 'form-control input-xl'))
        ),
        $settings->renderSection(
            $settings->renderHeader('Service Provider Google Places'),
            $settings->renderSetting('Service Provider Google Places API', 'location.places.api', 'input', array('help' => true, 'class' => 'form-control input-xl'))
        ),
        $settings->renderSection(
            $settings->renderHeader('Service Provider Google Maps'),
            $settings->renderSetting('Service Provider Google Maps API', 'location.maps.api', 'input', array('help' => true, 'class' => 'form-control input-xl'))
        )
    )
);
