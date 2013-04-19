<?php
/**
 * Observer class for sending an extra email during customer-creation/welcome process
 */
class assign_charity_to_order extends base {

  function __construct() 
  {
    global $zco_notifier;
    $zco_notifier->attach($this, array('NOTIFY_ORDER_DURING_CREATE_ADDED_ORDER_HEADER'));
  }

  function update(&$class, $eventID, $paramsArray = array())
  {
	global $db;
	  
	$sql = "INSERT INTO charity_orders_match 
            (orders_id, customers_id, subtotal, shipping_cost, total, charity)
			VALUES
			(':orders_id', ':customers_id', ':subtotal', ':shipping_cost', ':total', ':charity')";
      
    $sql = $db->bindVars($sql, ':orders_id', $paramsArray["orders_id"], 'integer');
    $sql = $db->bindVars($sql, ':customers_id', $paramsArray["customers_id"], 'integer');
    $sql = $db->bindVars($sql, ':subtotal', $class->info["subtotal"], 'float');
    $sql = $db->bindVars($sql, ':shipping_cost', $class->info["shipping_cost"], 'float');
    $sql = $db->bindVars($sql, ':total', $class->info["total"], 'float');
    $sql = $db->bindVars($sql, ':charity', $_SESSION["charity"], 'integer');
    $db->Execute($sql);

  }
  
} 