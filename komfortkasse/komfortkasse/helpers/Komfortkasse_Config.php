<?php

/** 
 * Komfortkasse
 * Config Class
 * 
 * @copyright (C) 2014-2016 Komfortkasse Team. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 1.5.3-virtuemart
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
		$payment_params = explode("|", (string) $params);

		$params = '';
		for ($i = 0; $i < count($payment_params)-1; ++$i) {

			$param = explode('=', $payment_params[$i], 2);
			if ($param[0] === $constant_key) {
				// same format as VirtueMart (VmTable): key=json_encode(value)|
				$payment_params[$i] = $constant_key.'='.json_encode((string) $value);
			}
			$params .= $payment_params[$i].'|';
		}

		$q = "UPDATE #__virtuemart_paymentmethods SET payment_params = ". $db->quote($params) ." WHERE payment_element LIKE 'komfortkasse'" ;
		$db->setQuery($q);
		$db->loadResult();

	}
	public static function getConfig($constant_key) {
		$q = "SELECT payment_params FROM #__virtuemart_paymentmethods WHERE payment_element='komfortkasse'";
		$db = JFactory::getDBO();
		$db->setQuery($q);
		$params = $db->loadResult();

		$payment_params = explode("|", (string) $params);

		foreach ($payment_params as $payment_param) {
			if (empty($payment_param)) {
				continue;
			}
			// VirtueMart stores key=json_encode(value)|: split at the first '=' only (base64 keys
			// contain '='), decode JSON (escaped "\/", arrays of a multiple select field)
			$param = explode('=', $payment_param, 2);
			if ($param[0] != $constant_key || !isset($param[1])) {
				continue;
			}
			$value = json_decode($param[1]);
			if ($value === null && $param[1] !== 'null') {
				// not JSON: old behaviour (strip the surrounding quotes)
				return substr($param[1], 1, -1);
			}
			if (is_array($value)) {
				return implode(',', $value);
			}
			return is_scalar($value) ? (string) $value : '';
		}
		return null;
	}
	public static function getRequestParameter($key) {
		$jinput = JFactory::getApplication()->input;
		// 'raw': the default filter 'cmd' strips '+', '/' and '=' and breaks base64/openssl data
		$value = $jinput->get($key, null, 'raw');
		if (!is_string($value))
			return '';
		return urldecode($value);
	}
	
	public static function getVersion() {
		return '0.0'; // TODO
		// $config_q = xtc_db_query("SELECT version FROM database_version");
		// $config_a = xtc_db_fetch_array($config_q);
		// return $config_a ['version'];
	}
}
?>