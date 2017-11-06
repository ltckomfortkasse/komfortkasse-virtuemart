<?php

/** 
 * Komfortkasse
 * Config Class
 * 
 * @copyright (C) 2014-2016 Komfortkasse Team. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 1.5.2-virtuemart
 */
defined('_JEXEC') or die();
class Komfortkasse_Config {
	const activate_export = 'KOMFORTKASSE_ACTIVATE_EXPORT';
	const activate_update = 'KOMFORTKASSE_ACTIVATE_UPDATE';
	const payment_methods = 'KOMFORTKASSE_PAYMENT_CODES';
	const status_open = 'KOMFORTKASSE_STATUS_OPEN';
	const status_paid = 'KOMFORTKASSE_STATUS_PAID';
	const status_cancelled = 'KOMFORTKASSE_STATUS_CANCELLED';
	const payment_methods_invoice = 'KOMFORTKASSE_PAYMENT_CODES_INVOICE';
	const status_open_invoice = 'KOMFORTKASSE_STATUS_OPEN_INVOICE';
	const status_paid_invoice = 'KOMFORTKASSE_STATUS_PAID_INVOICE';
	const status_cancelled_invoice = 'KOMFORTKASSE_STATUS_CANCELLED_INVOICE';
	const payment_methods_cod = 'KOMFORTKASSE_PAYMENT_CODES_COD';
	const status_open_cod = 'KOMFORTKASSE_STATUS_OPEN_COD';
	const status_paid_cod = 'KOMFORTKASSE_STATUS_PAID_COD';
	const status_cancelled_cod = 'KOMFORTKASSE_STATUS_CANCELLED_COD';
	const encryption = 'KOMFORTKASSE_ENCRYPTION';
	const accesscode = 'KOMFORTKASSE_ACCESSCODE';
	const apikey = 'KOMFORTKASSE_APIKEY';
	const publickey = 'KOMFORTKASSE_PUBLICKEY';
	const privatekey = 'KOMFORTKASSE_PRIVATEKEY';
	
	public static function setConfig($constant_key, $value) {
		$q = "SELECT payment_params FROM #__virtuemart_paymentmethods WHERE payment_element='komfortkasse'";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		$params = $db->loadResult();
		$payment_params = explode("|", $params);

		$params = '';
		for ($i = 0; $i < count($payment_params)-1; ++$i) {
			
			$pos = strpos($payment_params[$i], $constant_key);
			if ($pos !== false) {
				$payment_params[$i] = $constant_key.'="'.$value.'"';
			}
			$params .= $payment_params[$i].'|';
		}

		$q = "UPDATE #__virtuemart_paymentmethods SET payment_params = '". $params."' WHERE payment_element LIKE 'komfortkasse'" ;
		$db->setQuery($q);
		$db->loadResult();

	}
	public static function getConfig($constant_key) {
		$q = "SELECT payment_params FROM #__virtuemart_paymentmethods WHERE payment_element='komfortkasse'";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		$params = $db->loadResult();

		$payment_params = explode("|", $params);

		foreach ($payment_params as $payment_param) {
			if (empty($payment_param)) {
				continue;
			}
			$param = explode('=', $payment_param);
			$payment_params[$param[0]] = substr($param[1], 1, -1);
			if ($param[0] == $constant_key){
				return $payment_params[$param[0]];
			}
		
		}
	}
	public static function getRequestParameter($key) {
		$jinput = JFactory::getApplication()->input;
		return urldecode($jinput->get($key));
	}
	
	public static function getVersion() {
		return '0.0'; // TODO
		// $config_q = xtc_db_query("SELECT version FROM database_version");
		// $config_a = xtc_db_fetch_array($config_q);
		// return $config_a ['version'];
	}
}
?>