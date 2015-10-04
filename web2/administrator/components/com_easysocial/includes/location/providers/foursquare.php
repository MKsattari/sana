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

FD::import('admin:/includes/location/provider');

class SocialLocationProvidersFoursquare extends SociallocationProviders
{
    protected $queries = array(
        'll' => '',
        'query' => '',
        'client_id' => '',
        'client_secret' => '',
        'm' => 'foursquare',
        'radius' => 800,
        'v' => '20140905',
        'intent' => 'browse'
    );

    protected $url = 'https://api.foursquare.com/v2/venues/search';

    public function __construct()
    {
        // Initialise the client_id and client_secret
        $config = FD::config();
        $client_id = $config->get('location.foursquare.clientid');
        $client_secret = $config->get('location.foursquare.clientsecret');

        // If client_id and client_secret is empty, then we have to mark this as error

        if (empty($client_id)) {
            return $this->setError(JText::_('COM_EASYSOCIAL_LOCATION_PROVIDERS_FOURSQUARE_MISSING_CLIENT_ID'));
        }

        if (empty($client_secret)) {
            return $this->setError(JText::_('COM_EASYSOCIAL_LOCATION_PROVIDERS_FOURSQUARE_MISSING_CLIENT_SECRET'));
        }

        $this->setQuery('client_id', $client_id);
        $this->setQuery('client_secret', $client_secret);
    }

    public function setCoordinates($lat, $lng)
    {
        return $this->setQuery('ll', $lat . ',' . $lng);
    }

    public function setSearch($search = '')
    {
        return $this->setQuery('query', $search);
    }

    public function getResult($queries = array())
    {
        $this->setQueries($queries);

        $connector = FD::connector();
        $connector->setMethod('GET');
        $connector->addUrl($this->buildUrl());

        if (!empty($this->queries['query'])) {
            $this->setQuery('intent', 'global');
            $connector->addUrl($this->buildUrl());
        }

        $connector->execute();
        $result = $connector->getResults();

        $venues = array();

        foreach ($result as $row) {
            $object = json_decode($row->contents);

            if (!isset($object->meta) || !isset($object->meta->code)) {
                $this->setError(JText::_('COM_EASYSOCIAL_LOCATION_PROVIDERS_FOURSQUARE_UNKNOWN_ERROR'));

                return array();
            }

            if ($object->meta->code != 200) {
                $this->setError($object->meta->errorDetail);

                return array();
            }

            if (empty($object->response->venues)) {
                continue;
            }

            // We want to merge in the browse results and global results
            foreach ($object->response->venues as $venue) {
                if (!isset($venues[$venue->id])) {
                    $obj = new SocialLocationData;
                    $obj->latitude = $venue->location->lat;
                    $obj->longitude = $venue->location->lng;
                    $obj->address = isset($venue->location->address) ? $venue->location->address : '';
                    $obj->name = $venue->name;
                    $obj->fulladdress = !empty($obj->address) ? $obj->name . ', ' . $obj->address : '';

                    $venues[$venue->id] = $obj;
                }
            }
        }

        $venues = array_values($venues);

        return $venues;
    }
}
