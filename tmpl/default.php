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
$prepare_content = $params->get('prepare_content');
$separator_value = $params->get('separator');

?>

<?php if(is_array($module->results)): ?>
<div>
	<?php $i = 0; ?>
	<?php foreach($module->results as $group_id => &$group): ?>
		<?php if(is_array($group)): ?>
        <?php foreach($group as $text): ?>
			<?php if($counter): ?>
				<?php echo '<br/>'; ?>
			<?php endif; ?>
			<?php echo ($prepare_content) ? JHtml::_('content.prepare', $text) : $text; $i++; ?>
        <?php endforeach; ?>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>