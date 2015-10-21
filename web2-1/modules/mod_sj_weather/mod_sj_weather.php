<?php
/**
 * @package Sj Flat Menu
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;


if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}



$layout = $params->get('layout', 'default');
$area = str_replace(' ','',$params->get('sj_weather_area'));
$json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q='.$area);
$json_output = json_decode($json, true);
$item = array();
$item['icon'] = 'http://openweathermap.org/img/w/'.$json_output['weather'][0]['icon'].'.png';
$item['c'] = (int)($json_output['main']['temp'] - 273.15);
$time = time();
$item['date'] = date('l - F d, Y');

require JModuleHelper::getLayoutPath($module->module, $layout);
?>