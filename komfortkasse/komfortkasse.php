<?php
/**
 *
 * Komfortkasse plugin
 *
 * @author Guenter Schuebert
 * @version 1.0.0
 * @package VirtueMart
 * @subpackage payment
 * Copyright (C) 2014-2016 Komfortkasse Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
ini_set ('display_errors', 'Off');
defined('_JEXEC') or die('Restricted access');
if (!class_exists('vmPSPlugin')) {
	require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
}

if (!class_exists('Komfortkasse')) {
	require(VMPATH_ROOT .DS.'plugins'.DS.'vmpayment'.DS.'komfortkasse'.DS.'komfortkasse'.DS.'helpers'.DS.'Komfortkasse.php');
}

class plgVmPaymentKomfortkasse extends vmPSPlugin {
	
	function __construct(& $subject, $config) {
		$session = JFactory::getSession();
		$this->displayName = 'Komfortkasse';
		$this->page = basename(__FILE__, '.php');
	
		parent::__construct($subject, $config);
	
		$this->_loggable = TRUE;
		$this->tableFields = array_keys($this->getTableSQLFields());
		$this->_tablepkey = 'id'; //virtuemart_Komfortkasse_id';
		$this->_tableId = 'id'; //'virtuemart_Komfortkasse_id';
		$this->tableFields = array_keys($this->getTableSQLFields());
		$varsToPush = array(
				Komfortkasse_Config::activate_export => array('', 'int'),
				Komfortkasse_Config::activate_update => array('', 'int'),
				Komfortkasse_Config::payment_methods => array('', 'char'),
				Komfortkasse_Config::status_open => array('', 'char'),
				Komfortkasse_Config::status_paid => array('', 'int'),
				Komfortkasse_Config::status_cancelled => array('', 'int'),
				
				Komfortkasse_Config::payment_methods_invoice => array('', 'char'),
				Komfortkasse_Config::status_open_invoice => array('', 'char'),
				Komfortkasse_Config::status_paid_invoice => array('', 'char'),
				Komfortkasse_Config::status_cancelled_invoice => array('', 'char'),
				
				Komfortkasse_Config::payment_methods_cod => array('', 'char'),
				Komfortkasse_Config::status_open_cod => array('', 'char'),
				Komfortkasse_Config::status_paid_cod => array('', 'char'),
				Komfortkasse_Config::status_cancelled_cod => array('', 'char'),
	
				Komfortkasse_Config::encryption => array('', 'char'),
				Komfortkasse_Config::accesscode => array('', 'char'),
				Komfortkasse_Config::apikey => array('', 'char'),
				Komfortkasse_Config::publickey => array('', 'char'),
				Komfortkasse_Config::privatekey => array('', 'char'),

				'Komfortkasse_debug_mode' => array(0, 'char'),
				'log' => array(1, 'int'),
		);
		
		$this->setConfigParameterable($this->_configTableFieldName, $varsToPush);
		
		$action = vRequest::getCmd('action');

		if($action != ''){
			$this->kk();
		}
	}
	
	public function kk(){
		$action = Komfortkasse_Config::getRequestParameter('action');

		
		$kk = new Komfortkasse();
		$kk->$action();
		
		static $anzahl_aufrufe = 0;
		$anzahl_aufrufe++;

		if ($anzahl_aufrufe == 1) {

			$html = '';
			$html .= $this->renderByLayout('kk_cart',
					array(
							'button' => 'xxx'
					)
					);
			echo $html;
		}

		
	}


	public function plgVmDeclarePluginParamsPaymentVM3( &$data) {
		return $this->declarePluginParams('payment', $data);
	}
	
	function plgVmSetOnTablePluginParamsPayment($name, $id, &$table) {
		return $this->setOnTablePluginParams($name, $id, $table);
	}
	
	function plgVmGetTablePluginParams($psType, $name, $id, &$xParams, &$varsToPush){
		return $this->getTablePluginParams($psType, $name, $id, $xParams, $varsToPush);
	}
	
	function plgVmConfirmedOrder(VirtueMartCart $cart, $order) {	
		$kk = new Komfortkasse();
		
		$kk->notifyorder($order['details']['BT']->order_number);
	}

}

// No closing tag
