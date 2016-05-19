<?php
defined('_JEXEC') or die('Restricted access');

/**
 *
 * @package    VirtueMart
 * @subpackage Plugins  - Elements
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: $
 */

if (!class_exists('VmConfig')) {
	require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
}
if (!class_exists('ShopFunctions')) {
	require(VMPATH_ADMIN . DS . 'helpers' . DS . 'shopfunctions.php');
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