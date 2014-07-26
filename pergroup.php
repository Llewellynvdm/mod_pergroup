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

jimport('joomla.application.component.helper');

class perGroup
{
	protected $userId;
	protected $userGroups 	= false;
	protected $redirect		= false;
	protected $document;
	protected $jQuery_set;
	protected $params;
	protected $found 		= false;
	
	protected $range_low 	= 0;
	protected $range_high 	= 8;
	
	public $results;
	public $groups;
	
	public function __construct(&$params)
	{
		// set params
		$this->params = $params;
		// check if loged in
		$this->userId 	= JFactory::getUser()->id;
		if($this->userId){
			// setup user groups
			$this->userGroups 	= JUserHelper::getUserGroups($this->userId);
		} else {
			$params_users = JComponentHelper::getParams('com_users');
			// setup quest user groups
			$this->userGroups 	= array($params_users->get('guest_usergroup'));
		}
		// check direction of execution
		$up = $this->params->get('execution_direction');
		if($up){
			// set group range
			$this->appfields	= array_reverse(range($this->range_low,$this->range_high));
		} else {
			// set group range
			$this->appfields 	= range($this->range_low,$this->range_high);
		}
		// get documnet object
		$this->document = JFactory::getDocument();
		// check if jQuery is loaded
		$head_data = $this->document->getHeadData();
		foreach (array_keys($head_data['scripts']) as $script) {
			if (stristr($script, 'jquery')) {
				$this->jQuery_set = true;
				break;
			} else {
				$this->jQuery_set = false;
			}
		}
		// set the results
		$this->setResults();
	}
	
	// set data found per field
	protected function setResults()
	{
		foreach($this->appfields as $field_id){
			$this->results[$field_id] = $this->getData($field_id);
			// of first redirect stop and redirect
			if($this->redirect){
				$app = JFactory::getApplication();	
				$app->redirect($this->results[$field_id]);
				return true;
			}
			// if set so that not all content should be loaded, then break when firt data is found
			if($this->found && $this->params->get('first_found_content')){
				break;
			}
		}
		return true;
	}
	
	// get data per field
	protected function getData($id)
	{
		// check if field is active
		$fieldActive = $this->params->get('group'.$id.'-active');
		
		if($fieldActive){
			
			// get the set groups
			$fieldGroups = (array)$this->params->get('group'.$id);
			
			// get group type
			$in_group = (int)$this->params->get('group'.$id.'-type');
			
			// set default text array
			$text = array();
			foreach($this->userGroups as $userGroup){
				if($in_group){
					// check user is in appGroup
					if (in_array($userGroup, $fieldGroups)){
						$result = $this->find($id);
						if($this->redirect){
							return $result;
						}
						// set result only if not a redirect
						$text[] = $result;
						$this->found = true;
						break;
					} else {
						// when user is not in active group
						$text[] = false;
					}
				} else {
					// check if yous is not in the appGroup
					if (!in_array($userGroup, $fieldGroups)){
						$result = $this->find($id);
						if($this->redirect){
							return $result;
						}
						// set result only if not a redirect
						$text[] = $result;
						$this->found = true;
						break;
					} else {
						// when user is not in active group
						$text[] = false;
					}
				}
			}
			return $text;
		} 
		// when group is not active
		return false;
	}
	
	protected function find($id)
	{
		// check redirect
		$this->redirect = $this->params->get('group'.$id.'-redirect');
		if($this->redirect){
			// check redirect url
			$redirect_url 	= $this->params->get('group'.$id.'-url');
			// check redirect menu
			$redirect_menu	= $this->params->get('group'.$id.'-menu');
			if($redirect_url){
				return $redirect_url;
			} elseif($redirect_menu){
				return 'index.php?Itemid='.$redirect_menu;
			}
		} else {
			// get css
			$group_css 		= $this->params->get('group'.$id.'-css');
			if ($group_css){
				$this->document->addStyleDeclaration($group_css);
			}
			// get javascript
			$group_js 		= $this->params->get('group'.$id.'-js');
			if ($group_js){
				if (!$this->jQuery_set){
					$this->document->addScript(JURI::base().'modules/mod_perGroup/js/jquery.js');
					$this->jQuery_set = true;
				}
				$script = ''.$group_js.'';
				$this->document->addScriptDeclaration($script);
			}
			// get php if set
			$php = $this->params->get('group'.$id.'-php');
			// get text
			$html	= $this->params->get('group'.$id.'-notice');
			// run the php here
			strlen($php) ? eval( " " . $php . " " ) : '';
			if ($html){							
				return $html;
			}
			return false;
		}
				
	}
}