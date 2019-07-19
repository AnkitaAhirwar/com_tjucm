<?php
/**
 * @package	TJ-UCM
 * 
 * @author	 TechJoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2019 TechJoomla. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

if (!key_exists('formObject', $displayData) || !key_exists('xmlFormObject', $displayData))
{
	return;
}

$app = JFactory::getApplication();
$user = JFactory::getUser();

// Layout for field types
$fieldLayout = array();
$fieldLayout['File'] = "file";
$fieldLayout['Image'] = "file";
$fieldLayout['Checkbox'] = "checkbox";
$fieldLayout['Radio'] = "list";
$fieldLayout['List'] = "list";
$fieldLayout['Itemcategory'] = "itemcategory";
$fieldLayout['Video'] = "video";
$fieldLayout['Calendar'] = "calendar";

// Load the tj-fields helper
JLoader::import('components.com_tjfields.helpers.tjfields', JPATH_SITE);
$TjfieldsHelper = new TjfieldsHelper;

// Get JLayout data
$xmlFormObject = $displayData['xmlFormObject'];
$formObject = $displayData['formObject'];
$itemData = $displayData['itemData'];
$isSubForm = $displayData['isSubForm'];

// Define the classes for subform and normal form rendering
$controlGroupDivClass = ($isSubForm) ? 'col-xs-12' : 'col-xs-12 col-md-6';
$labelDivClass = ($isSubForm) ? 'col-xs-6' : 'col-xs-4';
$controlDivClass = ($isSubForm) ? 'col-xs-6' : 'col-xs-8';

// Get Field table
$fieldTableData = new stdClass;
JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_tjfields/tables');
$fieldTableData->tjFieldFieldTable = JTable::getInstance('field', 'TjfieldsTable');

$fieldSets = $formObject->getFieldsets();
$count = 0;

// Iterate through the normal form fieldsets and display each one
foreach ($fieldSets as $fieldName => $fieldset)
{
	$xmlFieldSet = $xmlFormObject[$count];
	$count++;
	?>
	<div class="row">
		<?php
		$fieldCount = 0;

		foreach ($formObject->getFieldset($fieldset->name) as $field)
		{
			// Get the field data by field name to check the field type
			$fieldTableData->tjFieldFieldTable->load(array('name' => $field->__get("fieldname")));
			$canView = false;

			if ($user->authorise('core.field.viewfieldvalue', 'com_tjfields.group.' . $fieldTableData->tjFieldFieldTable->group_id))
			{
				$canView = $user->authorise('core.field.viewfieldvalue', 'com_tjfields.field.' . $fieldTableData->tjFieldFieldTable->id);
			}

			if ($canView || ($itemData->created_by == $user->id))
			{
				if ($field->hidden)
				{
					echo $field->input;
				}

				// Get xml for the field
				$xmlField = $xmlFieldSet->field[$fieldCount];
				$fieldCount++;


				if ($field->type == 'Subform' || $field->type == 'Ucmsubform')
				{
					?>
					<div class="col-xs-12 col-md-6">
						<div class="col-xs-4"><?php echo $field->label; ?>:</div>
						<div class="col-xs-8">
							<?php
							$count = 0;
							$xmlFieldSets = array();

							// Call to extra fields
							JLoader::import('components.com_tjucm.models.item', JPATH_SITE);
							$tjucmItemModel = JModelLegacy::getInstance('Item', 'TjucmModel');

							// Get Subform field data
							$formData = $TjfieldsHelper->getFieldData($field->getAttribute('name'));
							$ucmSubFormFieldValue = json_decode($formObject->getvalue($field->getAttribute('name')));

							$ucmSubFormFieldParams = json_decode($formData->params);
							$ucmSubFormFormSource = explode('/', $ucmSubFormFieldParams->formsource);
							$ucmSubFormClient = $ucmSubFormFormSource[1] . '.' . str_replace('form_extra.xml', '', $ucmSubFormFormSource[4]);
							$view = explode('.', $ucmSubFormClient);

							if (!empty($ucmSubFormFieldValue))
							{
								foreach ($ucmSubFormFieldValue as $ucmSubFormData)
								{
									$contentIdFieldname = str_replace('.', '_', $ucmSubFormClient) . '_contentid';

									$ucmsubform_form_extra = $tjucmItemModel->getFormExtra(
										array(
											"clientComponent" => 'com_tjucm',
											"client" => $ucmSubFormClient,
											"view" => $view[1],
											"layout" => 'default',
											"content_id" => $ucmSubFormData->$contentIdFieldname, )
											);

									$ucmSubFormFormXml = simplexml_load_file($field->formsource);

									foreach ($ucmSubFormFormXml as $k => $xmlFieldSet)
									{
										$xmlFieldSets[$count] = $xmlFieldSet;
										$count++;
									}

									// Call the JLayout recursively to render fields of ucmsubform
									$layout = new JLayoutFile('fields', JPATH_ROOT . '/components/com_tjucm/layouts/detail');
									echo $layout->render(array('xmlFormObject' => $xmlFieldSets, 'formObject' => $ucmsubform_form_extra, 'itemData' => $this->item, 'isSubForm' => 1));
								}
							}
							?>
						</div>
					</div>
					<?php
				}
				else
				{
					$layoutToUse = (array_key_exists($field->type, $fieldLayout)) ? $fieldLayout[$field->type] : 'field';
					?>
					<div class="<?php echo $controlGroupDivClass;?>">
						<div class="<?php echo $labelDivClass;?>"><?php echo $field->label; ?>:</div>
						<div class="<?php echo $controlDivClass;?>">
							<?php
							$layout = new JLayoutFile($layoutToUse, JPATH_ROOT . '/components/com_tjfields/layouts/fields');
							$mediaLink = $layout->render(array('fieldValue' => $field->value));
							$output = $layout->render(array('fieldXml' => $xmlField, 'field' => $field));
							echo $output;
							?>
						</div>
					</div>
					<?php
				}
			}
		}
	?>
	</div>
	<?php
}