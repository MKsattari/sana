<?php
/**
 * @package Sj Module Tabs
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;


$position = $params->get('position', '');
$listmodules = array();
if ( !empty($position) ){
	$position_modules = JModuleHelper::getModules($position);
	$nb_module_allow  = $params->get('nb_module', 0);
	foreach ($position_modules as $i => $_module){
		if ($_module->id != $module->id){
			$listmodules[$_module->id] = &$position_modules[$i];
			if ($nb_module_allow==count($listmodules)){
				break;
			}
		} else {
			// do not recursive load
		}
	}
}

// load Renderer
$document	= JFactory::getDocument();
$renderer	= $document->loadRenderer('module');


if ( count($listmodules) > 0 ){
	//$load_by_ajax = (int)$params->get('load_by_ajax', 0);
	foreach ($listmodules as $i => $_module) {
		if ($_module->content==''){
			$_module->content = $renderer->render($_module, array());
		}
// 		if ($load_by_ajax){
// 			// only render first module.
// 			break;
// 		}
	}
	include JModuleHelper::getLayoutPath($module->module);
	//require JModuleHelper::getLayoutPath('mod_sj_moduletabs', $params->get('layout', 'default'));
}?>


