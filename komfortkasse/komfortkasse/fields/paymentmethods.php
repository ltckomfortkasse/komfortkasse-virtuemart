<?php
defined('_JEXEC') or die();

/**
 * @copyright (C) 2014-2016 Komfortkasse Team. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!class_exists('VmConfig')) {
	require(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_virtuemart' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'config.php');
}
if (!class_exists('ShopFunctions')) {
	require(VMPATH_ADMIN . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'shopfunctions.php');
}

/**
 * Renders a multiple item select element
 *
 */

JFormHelper::loadFieldClass('list');
jimport('joomla.form.formfield');

class JFormFieldPaymentMethods extends JFormFieldList {

	var $type = 'vmpayments';

	protected function getOptions() {

		VmConfig::loadConfig();
		VmConfig::loadJLang('com_virtuemart', false);

		$sModel = VmModel::getModel('paymentmethod');
		$values = $sModel->getPayments();
		
		foreach ($values as $v) {
			if($v->payment_name != 'Komfortkasse'){
				$options[] = JHTML::_('select.option', $v->virtuemart_paymentmethod_id, $v->payment_name.' ('.$v->virtuemart_paymentmethod_id.')');
			}
		}
		return $options;
	}
}