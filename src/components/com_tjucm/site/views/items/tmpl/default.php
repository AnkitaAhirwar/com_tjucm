<?php
/**
 * @version    SVN: <svn_id>
 * @package    Com_Tjucm
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2018 TechJoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.token');

$user = JFactory::getUser();
$userId = $user->get('id');
$tjUcmFrontendHelper = new TjucmHelpersTjucm;
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$appendUrl = '';
$csrf = "&" . JSession::getFormToken() . '=1';

if (!empty($this->created_by))
{
	$appendUrl .= "&created_by=" . $this->created_by;
}

if (!empty($this->client))
{
	$appendUrl .= "&client=" . $this->client;
}

$tmpListColumn = $this->listcolumn;
reset($tmpListColumn);
$firstListColumn = key($tmpListColumn);

$link = 'index.php?option=com_tjucm&view=items' . $appendUrl;
$itemId = $tjUcmFrontendHelper->getItemId($link);
$fieldsData = array();
?>
<script type="text/javascript">
	jQuery(window).load(function()
	{
		var currentUcmType = new FormData();
		currentUcmType.append('client', "<?php echo $this->client ?>");
		var afterCheckCompatibilityOfUcmType = function(error, response){
			response = JSON.parse(response);

			if (response.data !== null)
			{
				jQuery.each(response.data, function(key, value) {
				 jQuery('#ucm_list').append(jQuery("<option></option>").attr("value",value.value).text(value.text)); 
				 jQuery('#ucm_list').trigger('liszt:updated');
				});
			}
		};

		// Code to check ucm type compatibility to copy item
		com_tjucm.Services.Items.chekCompatibility(currentUcmType, afterCheckCompatibilityOfUcmType);
	});
</script>
<form action="<?php echo JRoute::_($link . '&Itemid=' . $itemId); ?>" method="post" name="adminForm" id="adminForm">
	<?php echo $this->loadTemplate('filters'); ?>
	<div>
		<table class="table table-striped" id="itemList">
			<?php
			if (!empty($this->showList))
			{
				if (!empty($this->items))
				{?>
			<thead>
				<tr>
					<th width="1%" class="">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<?php
					if (isset($this->items[0]->state))
					{
						?>
						<th width="5%">
							<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<?php
					}
					?>
					<th class=''>
						<?php echo JHtml::_('grid.sort', 'COM_TJUCM_ITEMS_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
					<?php
					if (!empty($this->listcolumn))
					{
						JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_tjfields/tables');
						$tjFieldsFieldTable = JTable::getInstance('field', 'TjfieldsTable');

						foreach ($this->listcolumn as $fieldId => $col_name)
						{
							if (isset($fieldsData[$fieldId]))
							{
								$tjFieldsFieldTable = $fieldsData[$fieldId];
							}
							else
							{
								$tjFieldsFieldTable = JTable::getInstance('field', 'TjfieldsTable');
								$tjFieldsFieldTable->load($fieldId);
								$fieldsData[$fieldId] = $tjFieldsFieldTable;
							}
							?>
							<th class='left'>
								<?php echo htmlspecialchars($col_name, ENT_COMPAT, 'UTF-8'); ?>
							</th>
							<?php
						}
					}

					if ($this->canEdit || $this->canDelete)
					{
						?>
						<th class="center">
							<?php echo JText::_('COM_TJUCM_ITEMS_ACTIONS'); ?>
						</th>
					<?php
					}
					?>
			</tr>
		</thead>
		<?php
			}
		}?>
		<?php
		if (!empty($this->items))
		{
		?>
		<tfoot>
			<tr>
				<td colspan="<?php echo isset($this->items[0]) ? count($this->items[0]->field_values)+3 : 10; ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<?php
		}
		?>
		<tbody>
		<?php
		if (!empty($this->showList))
		{
			if (!empty($this->items))
			{
				$xmlFileName = explode(".", $this->client);
				$xmlFilePath = JPATH_SITE . "/administrator/components/com_tjucm/models/forms/" . $xmlFileName[1] . "_extra" . ".xml";
				$formXml = simplexml_load_file($xmlFilePath);

				$view = explode('.', $this->client);
				JLoader::import('components.com_tjucm.models.itemform', JPATH_SITE);
				$itemFormModel    = JModelLegacy::getInstance('ItemForm', 'TjucmModel');
				$formObject = $itemFormModel->getFormExtra(
					array(
						"clientComponent" => 'com_tjucm',
						"client" => $this->client,
						"view" => $view[1],
						"layout" => 'edit')
						);

				foreach ($this->items as $i => $item)
				{
					// Call the JLayout to render the fields in the details view
					$layout = new JLayoutFile('list', JPATH_ROOT . '/components/com_tjucm/layouts/list');
					echo $layout->render(array('itemsData' => $item, 'created_by' => $this->created_by, 'client' => $this->client, 'xmlFormObject' => $formXml, 'ucmTypeId' => $this->ucmTypeId, 'fieldsData' => $fieldsData, 'formObject' => $formObject));
				}
			}
			else
			{
				?>
				<div class="alert alert-warning"><?php echo JText::_('COM_TJUCM_NO_DATA_FOUND');?></div>
			<?php
			}
		}
		else
		{
		?>
			<div class="alert alert-warning"><?php echo JText::_('COM_TJUCM_NO_DATA_FOUND');?></div>
		<?php
		}
		?>
		</tbody>
	</table>
</div>
	<?php
	if ($this->allowedToAdd)
	{
		?>
		<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_tjucm&task=itemform.edit' . $appendUrl, false, 2); ?>" class="btn btn-success btn-small">
			<i class="icon-plus"></i><?php echo JText::_('COM_TJUCM_ADD_ITEM'); ?>
		</a>
		<a target="_blank" href="<?php echo JRoute::_('index.php?option=com_tjucm&task=itemform.edit' . $appendUrl, false, 2); ?>" class="btn btn-success btn-small">
			<?php echo JText::_('COM_TJUCM_COPY_ITEM'); ?>
		</a>
		<a data-toggle="modal" onclick="if (document.adminForm.boxchecked.value==0){alert(Joomla.JText._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));}else{jQuery( '#copyModal' ).modal('show'); return true;}" class="btn btn-success btn-small">
			<?php echo JText::_('COM_TJUCM_COPY_ITEM_TO_OTHER'); ?>
		</a>
		<?php
	}
	?>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
	
	<!-- Modal Pop Up for Copy Item to Other-->
	<div id="copyModal" class="copyModal modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close novalidate" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h3>Select Ucm Type</h3>
				</div>
				<div class="modal-body">
						<?php echo JHTML::_('select.genericlist', '', 'filter[ucm_list]', 'class="ucm_list" onchange=""', 'text', 'value',$this->state->get('filter.ucm_list'), 'ucm_list' ); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" onclick="document.getElementById('ucm_list').value='';" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-success" onclick="Joomla.submitbutton('itemform.copyItem');">
						Process
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
<?php
if ($this->canDelete)
{
	?>
	<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem()
	{
		if (!confirm("<?php echo JText::_('COM_TJUCM_DELETE_MESSAGE'); ?>"))
		{
			return false;
		}
	}
	</script>
<?php
}
