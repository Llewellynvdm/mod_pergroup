<?php
/**
* 
* 	@version 	1.0.0 February 06, 2014
* 	@package 	Per Group
* 	@author  	Llewellyn van der Merwe <llewellyn@vdm.io>
* 	@copyright	Copyright (C) 2014 Vast Development Method <http://www.vdm.io>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/
defined( '_JEXEC' ) or die;

// Include the syndicate functions only once
require_once __DIR__ . '/pergroup.php';

// load the module class
$module = new perGroup($params);

require JModuleHelper::getLayoutPath('mod_pergroup', $params->get('layout', 'default'));