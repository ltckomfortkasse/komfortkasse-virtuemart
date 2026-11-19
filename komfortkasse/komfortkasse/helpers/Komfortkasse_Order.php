<?php

// in KK, an Order is an Array providing the following members:
// number, date, email, customer_number, payment_method, amount, currency_code, exchange_rate, language_code
// delivery_ and billing_: _firstname, _lastname, _company
// products: an Array of item numbers

/** 
 * Komfortkasse
 * Config Class
 * 
 * @copyright (C) 2014-2016 Komfortkasse Team. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 1.5.3-virtuemart
 */
defined('_JEXEC') or die();
class Komfortkasse_Order {

	// comma separated config value -> trimmed, non-empty parts
	private static function csvParts($csv) {
		$parts = array ();
		foreach (explode(',', str_replace('"', '', (string) $csv)) as $part) {
			$part = trim($part);
			if ($part !== '') {
				$parts [] = $part;
			}
		}
		return $parts;
	}

	// "order_status in ('P','U') and (virtuemart_paymentmethod_id = 1 or ...)" for the open orders of one payment type
	private static function openWhere($statusCsv, $paymentCsv) {
		$db = JFactory::getDBO();
		$states = array ();
		foreach (self::csvParts($statusCsv) as $state) {
			$states [] = $db->quote($state);
		}
		$ids = array ();
		foreach (self::csvParts($paymentCsv) as $id) {
			$ids [] = "virtuemart_paymentmethod_id = " . (int) $id;
		}
		if (empty($states) || empty($ids)) {
			return null;
		}
		return "order_status in (" . implode(',', $states) . ") and ( " . implode(' or ', $ids) . " )";
	}
	
	// return all order numbers that are "open" and relevant for tranfer to kk
	public static function getOpenIDs() {
		
		$ret = array ();
		
        if (Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open) != '' && Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods) != '') {
        	$where = self::openWhere(Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open), Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods));
        	if ($where === null) {
        		$where = '0';
        	}
        	$sql = "select order_number from #__virtuemart_orders where " . $where;
        	$oid = array ();
			try{
	            $db = JFactory::getDBO();
	            $db->setQuery($sql);
	            $oid = $db->loadObjectList();

	            for($i = 0; $i < count($oid); $i++) {
	            	$ret [] =  $oid[$i]->order_number;
	            }
			}
			catch (Exception $e){
				#echo 'Error';
			}
        }

        if (Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_invoice) != '' && Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_invoice) != '') {
        	$where = self::openWhere(Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_invoice), Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_invoice));
        	if ($where === null) {
        		$where = '0';
        	}
        	$sql = "select order_number from #__virtuemart_orders where " . $where;
        	$oid = array ();
            try{
	            $db = JFactory::getDBO();
	            $db->setQuery($sql);
	            $oid = $db->loadObjectList();
            }
            catch (Exception $e){
            	#echo 'Error';
            }
            for($i = 0; $i < count($oid); $i++) {
            	$ret [] =  $oid[$i]->order_number;
            }
        }
        
        if (Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_cod) != '' && Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_cod) != '') {
        	$where = self::openWhere(Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_cod), Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_cod));
        	if ($where === null) {
        		$where = '0';
        	}
        	$sql = "select order_number from #__virtuemart_orders where " . $where;
        	$oid = array ();

			try{
	            $db = JFactory::getDBO();
	            $db->setQuery($sql);
	            $oid = $db->loadObjectList();
			}
			catch (Exception $e){
				#echo 'Error';
			}
			
            for($i = 0; $i < count($oid); $i++) {
            	$ret [] =  $oid[$i]->order_number;
            }

        }
        
        
        return $ret;
	}
	public static function getOrder($number) {
		if (!class_exists('VirtueMartModelOrders')) {
			require(VMPATH_ADMIN . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'orders.php');
		}
		if (!class_exists('VirtueMartModelCurrency')) {
			require(VMPATH_ADMIN . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'currency.php');
		}
		$number = trim((string) $number);
		if ($number === '') {
			return null;
		}
		$db = JFactory::getDBO();
		$q = "SELECT virtuemart_order_id FROM #__virtuemart_orders WHERE order_number = " . $db->quote($number);
		
		try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$oid = $db->loadResult();
		}
		catch (Exception $e){
			#echo 'Error';
		}
		
		if (empty ( $oid )) {
			return null;
		}

		$order = new VirtueMartModelOrders;
		$orderdetails = $order->getOrder((int) $oid);

		if (empty ( $orderdetails ) || empty ( $orderdetails['details']['BT'] )) {
			return null;
		}


		
		$q = "SELECT currency_code_3,currency_exchange_rate FROM #__virtuemart_currencies WHERE virtuemart_currency_id = ".(int) $orderdetails['details']['BT']->order_currency;
		
		try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$currency = $db->loadObjectList();
		}
		catch (Exception $e){
			#echo "Error";
		}
		
		$q = "SELECT country_2_code FROM #__virtuemart_countries WHERE virtuemart_country_id = ".(int) $orderdetails['details']['ST']->virtuemart_country_id;
		
		try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$deliverycountry = $db->loadResult();
		}
		catch (Exception $e){
			#echo 'Error';
		}
		
		$q = "SELECT country_2_code FROM #__virtuemart_countries WHERE virtuemart_country_id = ".(int) $orderdetails['details']['BT']->virtuemart_country_id;
		
		try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$billingcountry = $db->loadResult();
		}
		catch (Exception $e){
			#echo 'Error';
		}
		

		
		$ret = array ();
        $ret ['number'] = $number;
        $ret ['id'] = $oid;
        $ret ['date'] = date("d.m.Y", strtotime($orderdetails['details']['BT']->created_on));
        $ret ['email'] = $orderdetails['details']['BT']->email;
        $ret ['customer_number'] = $orderdetails['details']['BT']->customer_number;
        $ret ['payment_method'] = $orderdetails['details']['BT']->virtuemart_paymentmethod_id;
        $ret ['amount'] = $orderdetails['details']['BT']->order_total;
        $ret ['currency_code'] = $currency[0]->currency_code_3;
        $ret ['exchange_rate'] = $currency[0]->currency_exchange_rate;
        $ret ['language_code'] = $orderdetails['details']['BT']->order_language . '-' . $billingcountry;
        $ret ['delivery_firstname'] = $orderdetails['details']['ST']->first_name;
        $ret ['delivery_lastname'] = $orderdetails['details']['ST']->last_name;
        $ret ['delivery_company'] = $orderdetails['details']['ST']->company;
        $ret ['delivery_street'] = $orderdetails['details']['ST']->address_1;
        $ret ['delivery_postcode'] = $orderdetails['details']['ST']->zip;
        $ret ['delivery_city'] = $orderdetails['details']['ST']->city;
        $ret ['delivery_countrycode'] = $deliverycountry;
        
        $ret ['billing_firstname'] = $orderdetails['details']['BT']->first_name;
        $ret ['billing_lastname'] = $orderdetails['details']['BT']->last_name;
        $ret ['billing_company'] = $orderdetails['details']['BT']->company;
        $ret ['billing_street'] = $orderdetails['details']['BT']->address_1;
        $ret ['billing_postcode'] = $orderdetails['details']['BT']->zip;
        $ret ['billing_city'] = $orderdetails['details']['BT']->city;
        $ret ['billing_countrycode'] = $billingcountry;
        $ret ['status'] = $orderdetails['details']['BT']->order_status;

		$items = isset($orderdetails['items']) ? $orderdetails['items'] : array ();
		for( $i= 0 ; $i < count($items) ; $i++ ){
			// article number, otherwise the article name (was an assignment instead of a comparison)
			if ($items[$i]->order_item_sku != '') {
				$ret['products'][] = $items[$i]->order_item_sku;
			} else {
				$ret['products'][] = $items[$i]->order_item_name;
			}
		}

		return $ret;
	}
	
	public static function updateOrder($order, $status, $callbackid) {
		$db = JFactory::getDBO();
		$q = "UPDATE #__virtuemart_orders SET order_status = " . $db->quote($status) . " WHERE order_number = " . $db->quote($order['number']);
			try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$db->loadResult();
		}
		catch (Exception $e){
			echo 'Error';
		}
		
		// order history entry (before: wrong table and unquoted status, so the insert always failed)
		$q = "INSERT INTO #__virtuemart_order_histories (virtuemart_order_id,order_status_code,customer_notified,comments,published,created_on) VALUES ("
			. (int) $order['id'] . "," . $db->quote($status) . ",0," . $db->quote('Komfortkasse ID ' . $callbackid) . ",1," . $db->quote(gmdate('Y-m-d H:i:s')) . ")";
			try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$db->loadResult();
		}
		catch (Exception $e){
			#echo 'Error';
		}
	}
}
