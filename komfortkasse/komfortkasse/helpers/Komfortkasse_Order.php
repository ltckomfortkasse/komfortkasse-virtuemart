<?php

// in KK, an Order is an Array providing the following members:
// number, date, email, customer_number, payment_method, amount, currency_code, exchange_rate, language_code
// delivery_ and billing_: _firstname, _lastname, _company
// products: an Array of item numbers

/** 
 * Komfortkasse
 * Config Class
 * 
 * @version 1.0.0-xtc3
 */
class Komfortkasse_Order {
	
	// return all order numbers that are "open" and relevant for tranfer to kk
	public static function getOpenIDs() {
		
		$ret = array ();
		
        if (Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open) != '' && Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods) != '') {
        	
        	$sql = "select order_number from #__virtuemart_orders where order_status like '" . Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open) . "' and ( ";
        	$paycodes = preg_split('/,/', str_replace('"', '', Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods)));
            for($i = 0; $i < count($paycodes); $i++) {
                $sql .= " virtuemart_paymentmethod_id = " . $paycodes[$i] . " ";
                if ($i < count($paycodes) - 1) {
                    $sql .= " or ";
                }
            }
            $sql .= " )";
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
        	$sql = "select order_number from #__virtuemart_orders where order_status like '" . Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_invoice) . "' and ( ";
        	$paycodes = preg_split('/,/', str_replace('"', '', Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_invoice)));
            for($i = 0; $i < count($paycodes); $i++) {
                $sql .= " virtuemart_paymentmethod_id = " . $paycodes[$i] . " ";
                if ($i < count($paycodes) - 1) {
                    $sql .= " or ";
                }
            }
            
            $sql .= " )";
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

        	$sql = "select order_number from #__virtuemart_orders where order_status like '" . Komfortkasse_Config::getConfig(Komfortkasse_Config::status_open_cod) . "' and ( ";
        	$paycodes = preg_split('/,/', str_replace('"', '', Komfortkasse_Config::getConfig(Komfortkasse_Config::payment_methods_cod)));
            for($i = 0; $i < count($paycodes); $i++) {
                $sql .= " virtuemart_paymentmethod_id = " . $paycodes[$i] . " ";
                if ($i < count($paycodes) - 1) {
                    $sql .= " or ";
                }
            }
            $sql .= " )";

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
			require(VMPATH_ADMIN . DS . 'models' . DS . 'orders.php');
		}
		if (!class_exists('VirtueMartModelCurrency')) {
			require(VMPATH_ADMIN . DS . 'models' . DS . 'currency.php');
		}
		$q = "SELECT virtuemart_order_id FROM #__virtuemart_orders WHERE order_number = '".$number."'";
		
		try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$oid = $db->loadResult();
		}
		catch (Exception $e){
			#echo 'Error';
		}
		
		$order = new VirtueMartModelOrders;
		$orderdetails = $order->getOrder($oid);

		if (empty ( $number ) || empty ( $order )) {
			return null;
		}


		
		$q = "SELECT currency_code_3,currency_exchange_rate FROM #__virtuemart_currencies WHERE virtuemart_currency_id = ".$orderdetails['details']['BT']->order_currency;
		
		try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$currency = $db->loadObjectList();
		}
		catch (Exception $e){
			#echo "Error";
		}
		
		$q = "SELECT country_2_code FROM #__virtuemart_countries WHERE virtuemart_country_id = ".$orderdetails['details']['ST']->virtuemart_country_id;
		
		try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$deliverycountry = $db->loadResult();
		}
		catch (Exception $e){
			#echo 'Error';
		}
		
		$q = "SELECT country_2_code FROM #__virtuemart_countries WHERE virtuemart_country_id = ".$orderdetails['details']['BT']->virtuemart_country_id;
		
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

		for( $i= 0 ; $i < count($orderdetails['items']) ; $i++ ){
			$ret['products'][] = $orderdetails['items'][$i]->order_item_sku ;
			if($orderdetails['items'][$i]->order_item_sku = ''){
				$ret['products'][] = $orderdetails['items'][$i]->order_item_name;
			}
		}

		return $ret;
	}
	
	public static function updateOrder($order, $status, $callbackid) {
		#xtc_db_query("update ".TABLE_ORDERS." set orders_status = '".xtc_db_input($status)."', last_modified = now() where orders_id = '".xtc_db_input($order['number'])."'");
		$db = JFactory::getDBO();
		$q = "UPDATE #__virtuemart_orders SET order_status = '".$status."' WHERE order_number = '".$order['number']."'";
			try {
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$db->loadResult();
		}
		catch (Exception $e){
			echo 'Error';
		}
		
		#xtc_db_query("insert into ".TABLE_ORDERS_STATUS_HISTORY." (orders_id, orders_status_id, date_added, customer_notified, comments) values ('".xtc_db_input($order['number'])."', '".xtc_db_input($status)."', now(), '0', 'Komfortkasse ID ".$callbackid."')");
		$q = "INSERT INTO  #__virtuemart_orders (virtuemart_order_id,order_status_code,customer_notified,published) VALUES (".$order['id'].",".$status.",0,1)" ;
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
