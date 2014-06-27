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
	<?php $i = 0; ?>
	<?php foreach($module->results as $group_id => &$group): ?>
		<?php if(is_array($group)): ?>
			<?php foreach($group as $text): ?>
                <?php if($text): ?>
                        
						<?php if($separator_value): ?>
                        	<?php if($separator_value == 1): ?>
                            	<?php echo ($i) ? '<br/>' : ''; ?>
                            <?php else: ?>
                            	<div<?php echo ($separator_value == 2) ? ' class="separator"' : ''; echo ($separator_value == 3) ? ' class="jumbotron"' : ''; ?>>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- the main text -->
                        <?php echo ($prepare_content) ? JHtml::_('content.prepare', $text) : $text; ?>
                        
                        <?php if($separator_value): ?>
                        	<?php if($separator_value != 1): ?>
                            	</div>
                            <?php endif; ?>
                        <?php endif ?>
                        
						<?php $i++; ?>
                        
            	<?php endif; ?>
            <?php endforeach; ?>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>