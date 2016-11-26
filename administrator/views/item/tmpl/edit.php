<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Tjucm
 * @author     Parth Lawate <contact@techjoomla.com>
 * @copyright  2016 Techjoomla
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

$client  = JFactory::getApplication()->input->get('client');

?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
	});

	Joomla.submitbutton = function (task) {
		if (task == 'item.cancel') {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
		else {

			if (task != 'item.cancel' && document.formvalidator.isValid(document.id('item-form'))) {

				Joomla.submitform(task, document.getElementById('item-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tjucm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="item-form" class="form-validate">
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_TJUCM_TITLE_ITEM', true)); ?>
				<div class="row-fluid">
					<div class="span10 form-horizontal">
						<fieldset class="adminform">
							<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
							<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
							<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
							<input type="hidden" name="jform[client]" value="<?php echo $client;?>" />
							<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
							<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
							<?php echo $this->form->renderField('created_by'); ?>
							<?php echo $this->form->renderField('created_date'); ?>
							<?php echo $this->form->renderField('modified_by'); ?>
							<?php echo $this->form->renderField('modified_date'); ?>
							<?php echo $this->form->renderField('category_id'); ?>

							<?php if ($this->state->params->get('save_history', 1)) : ?>
								<div class="control-group">
									<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
								</div>
							<?php endif; ?>
						</fieldset>
					</div>
				</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php if ($this->form_extra): ?>
				<?php echo $this->loadTemplate('extrafields'); ?>
			<?php endif; ?>

			<?php if (JFactory::getUser()->authorise('core.admin','tjucm')) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
					<?php echo $this->form->getInput('rules'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>
		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
