<?php
/**
 * USPS Module V1.21
 * USPS Module for Zen Cart v1.3.x
 * RateV4, IntlRateV2 API's
 * 
 * @package shippingMethod
 * @copyright Copyright 2011 Marco B
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license GNU Public License V2.0
 * @version $Id: usps.php 81904 2011-29-08 22:45:15Z+10:00 Marco B $
 */

//Quote sorting functions
if (!function_exists('usps_sort_Alphanumeric')) {
  function usps_sort_Alphanumeric ($a, $b) {
    return strcmp($a['title'],$b['title']);
  }
}
if (!function_exists('usps_sort_Price')) {
  function usps_sort_Price ($a, $b) {
    $c=(float)$a['cost'];
    $d=(float)$b['cost'];
    if ($c==$d) return 0;
    return ($c>$d?1:-1);
  }
}
/**
 * USPS Shipping Module class
 *
 */
class usps extends base {
  /**
   * Declare shipping module alias code
   *
   * @var string
   */
  var $code;
  /**
   * Shipping module display name
   *
   * @var string
   */
  var $title;
  /**
   * Shipping module display description
   *
   * @var string
   */
  var $description;
  /**
   * Shipping module icon filename/path
   *
   * @var string
   */
  var $icon;
  /**
   * Shipping module status
   *
   * @var boolean
   */
  var $enabled;
  /**
   * Shipping module list of supported countries (unique to USPS/UPS)
   *
   * @var array
   */
  var $countries;
  
  // use USPS translations for US shops
  var $usps_countries;

  /**
   * Constructor
   *
   * @return usps
   */
  function usps() {
    global $order, $db, $template, $current_page_base;

    $this->code = 'usps';
    $this->title = MODULE_SHIPPING_USPS_TEXT_TITLE;
    $this->description = MODULE_SHIPPING_USPS_TEXT_DESCRIPTION;
    $this->sort_order = MODULE_SHIPPING_USPS_SORT_ORDER;
    $this->icon = $template->get_template_dir('shipping_usps.gif', DIR_WS_TEMPLATE, $current_page_base,'images/icons'). '/' . 'shipping_usps.gif';
    $this->tax_class = MODULE_SHIPPING_USPS_TAX_CLASS;
    $this->tax_basis = MODULE_SHIPPING_USPS_TAX_BASIS;

    // disable only when entire cart is free shipping
    if (zen_get_shipping_enabled($this->code)) {
      $this->enabled = ((MODULE_SHIPPING_USPS_STATUS == 'True') ? true : false);
    }

    if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_USPS_ZONE > 0) ) {
      $check_flag = false;
      $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_USPS_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
      while (!$check->EOF) {
        if ($check->fields['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check->fields['zone_id'] == $order->delivery['zone_id']) {
          $check_flag = true;
          break;
        }
        $check->MoveNext();
      }

      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
    
    //Domestic Shipping services - 25/06/2011
    //Added Service parameters to filter the quotes.
    //regOnly: Service only valid for regular sized packages
    //commOnly: Service is a commercial only service.
    //maxGL: package cannot exceed given Girth + Length.
    $this->types=array(
      'Express Mail'=>
        array('id'=>3, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'', 'fcType'=>'','container'=>'', 'service'=>'EXPRESS COMMERCIAL'), //CLASSID="3"
      'Express Mail Hold For Pickup'=>
        array('id'=>2, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'', 'fcType'=>'','container'=>'', 'service'=>'EXPRESS HFP COMMERCIAL'), //CLASSID="2"
      'Express Mail Sunday/Holiday Delivery'=>
        array('id'=>23, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'', 'fcType'=>'','container'=>'', 'service'=>'EXPRESS SH COMMERCIAL'), //CLASSID="23"
      'Express Mail Flat Rate Envelope'=>
        array('id'=>13, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>'', 'maxLength'=>'12.5', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>70, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'FLAT RATE ENVELOPE', 'service'=>'EXPRESS COMMERCIAL'), //CLASSID="13"
      'Express Mail Flat Rate Envelope Hold For Pickup'=>
        array('id'=>27, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>'', 'maxLength'=>'12.5', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>70, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'FLAT RATE ENVELOPE', 'service'=>'EXPRESS HFP COMMERCIAL'), //CLASSID="27"
      'Express Mail Sunday/Holiday Delivery Flat Rate Envelope'=>
        array('id'=>25, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>'', 'maxLength'=>'12.5', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>70, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'FLAT RATE ENVELOPE', 'service'=>'EXPRESS SH COMMERCIAL'), //CLASSID="25"
      'Express Mail Legal Flat Rate Envelope'=>
        array('id'=>30, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>'', 'maxLength'=>'15', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>90, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'LEGAL FLAT RATE ENVELOPE', 'service'=>'EXPRESS COMMERCIAL'), //CLASSID="30"
      'Express Mail Legal Flat Rate Envelope Hold For Pickup'=>
        array('id'=>31, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>'', 'maxLength'=>'15', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>90, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'LEGAL FLAT RATE ENVELOPE', 'service'=>'EXPRESS HFP COMMERCIAL'), //CLASSID="31"
      'Express Mail Sunday/Holiday Delivery Legal Flat Rate Envelope'=>
        array('id'=>32, 'maxWeight'=>70, 'transitReq'=>'ExpressMail', 'maxGL'=>'', 'maxLength'=>'15', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>90, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'LEGAL FLAT RATE ENVELOPE', 'service'=>'EXPRESS SH COMMERCIAL'), //CLASSID="32"
      'Priority Mail'=>
        array('id'=>1, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'', 'fcType'=>'','container'=>'', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="1"
      'Priority Mail Hold For Pickup'=>
        array('id'=>33, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>1, 'size'=>'', 'fcType'=>'','container'=>'', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="33" **Commercial Only
      'Priority Mail Large Flat Rate Box'=>
        array('id'=>22, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12:23.5', 'maxWidth'=>'12:11.75', 'maxHeight'=>'5.5:3', 'volume'=>835, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'LG FLAT RATE BOX', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="22"
      'Priority Mail Large Flat Rate Box Hold For Pickup'=>
        array('id'=>34, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12:23.5', 'maxWidth'=>'12:11.75', 'maxHeight'=>'5.5:3', 'volume'=>835, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'LG FLAT RATE BOX', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="34" **Commercial Only
      'Priority Mail Medium Flat Rate Box'=>
        array('id'=>17, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'13.5:11', 'maxWidth'=>'11.75:8.5', 'maxHeight'=>'3.25:5.5', 'volume'=>515, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'MD FLAT RATE BOX', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="17"
      'Priority Mail Medium Flat Rate Box Hold For Pickup'=>
        array('id'=>35, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'13.5:11', 'maxWidth'=>'11.75:8.5', 'maxHeight'=>'3.25:5.5', 'volume'=>515, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'MD FLAT RATE BOX', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="35" **Commercial Only
      'Priority Mail Small Flat Rate Box'=>
        array('id'=>28, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'8.5', 'maxWidth'=>'5.25', 'maxHeight'=>'1.5', 'volume'=>67, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'SM FLAT RATE BOX', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="28"
      'Priority Mail Small Flat Rate Box Hold For Pickup'=>
        array('id'=>36, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'8.5', 'maxWidth'=>'5.25', 'maxHeight'=>'1.5', 'volume'=>67, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'SM FLAT RATE BOX', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="36" **Commercial Only
      'Priority Mail Regional Rate Box A'=>
        array('id'=>47, 'maxWeight'=>15, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12.81:10', 'maxWidth'=>'10.94:7', 'maxHeight'=>'2.38:4.75', 'volume'=>333.5, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'REGIONALRATEBOXA', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="47" **Commercial Only
      'Priority Mail Regional Rate Box A Hold For Pickup'=>
        array('id'=>48, 'maxWeight'=>15, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12.81:10', 'maxWidth'=>'10.94:7', 'maxHeight'=>'2.38:4.75', 'volume'=>333.5, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'REGIONALRATEBOXA', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="48" **Commercial Only
      'Priority Mail Regional Rate Box B'=>
        array('id'=>49, 'maxWeight'=>20, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'15.88:12', 'maxWidth'=>'14.38:10.25', 'maxHeight'=>'2.25:5', 'volume'=>656, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'REGIONALRATEBOXB', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="49" **Commercial Only
      'Priority Mail Regional Rate Box B Hold For Pickup'=>
        array('id'=>50, 'maxWeight'=>20, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'15.88:12', 'maxWidth'=>'14.38:10.25', 'maxHeight'=>'2.25:5', 'volume'=>656, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'REGIONALRATEBOXB', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="50" **Commercial Only
      'Priority Mail Flat Rate Envelope'=>
        array('id'=>16, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12.5', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>70, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'FLAT RATE ENVELOPE', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="16"
      'Priority Mail Flat Rate Envelope Hold For Pickup'=>
        array('id'=>37, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12.5', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>70, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'FLAT RATE ENVELOPE', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="37" **Commercial Only
      'Priority Mail Legal Flat Rate Envelope'=>
        array('id'=>44, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'15', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>90, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'LEGAL FLAT RATE ENVELOPE', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="44"
      'Priority Mail Legal Flat Rate Envelope Hold For Pickup'=>
        array('id'=>45, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'15', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>90, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'LEGAL FLAT RATE ENVELOPE', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="45" **Commercial Only
      'Priority Mail Padded Flat Rate Envelope'=>
        array('id'=>29, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12.5', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>70, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'PADDED FLAT RATE ENVELOPE', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="29"
      'Priority Mail Padded Flat Rate Envelope Hold For Pickup'=>
        array('id'=>46, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'12.5', 'maxWidth'=>'9.5', 'maxHeight'=>'0.75', 'volume'=>70, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'PADDED FLAT RATE ENVELOPE', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="46" **Commercial Only
      'Priority Mail Gift Card Flat Rate Envelope'=>
        array('id'=>38, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'10', 'maxWidth'=>'7', 'maxHeight'=>'0.75', 'volume'=>40, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'GIFT CARD FLAT RATE ENVELOPE', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="38"
      'Priority Mail Gift Card Flat Rate Envelope Hold For Pickup'=>
        array('id'=>39, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'10', 'maxWidth'=>'7', 'maxHeight'=>'0.75', 'volume'=>40, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'GIFT CARD FLAT RATE ENVELOPE', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="39" **Commercial Only
      'Priority Mail Small Flat Rate Envelope'=>
        array('id'=>38, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'10', 'maxWidth'=>'6', 'maxHeight'=>'0.75', 'volume'=>34, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'SM FLAT RATE ENVELOPE', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="38"
      'Priority Mail Small Flat Rate Envelope Hold For Pickup'=>
        array('id'=>42, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'10', 'maxWidth'=>'6', 'maxHeight'=>'0.75', 'volume'=>34, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'SM FLAT RATE ENVELOPE', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="42" **Commercial Only
      'Priority Mail Window Flat Rate Envelope'=>
        array('id'=>40, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'10', 'maxWidth'=>'5', 'maxHeight'=>'0.75', 'volume'=>27.5, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'WINDOW FLAT RATE ENVELOPE', 'service'=>'PRIORITY COMMERCIAL'), //CLASSID="40"
      'Priority Mail Window Flat Rate Envelope Hold For Pickup'=>
        array('id'=>41, 'maxWeight'=>70, 'transitReq'=>'PriorityMail', 'maxGL'=>'', 'maxLength'=>'10', 'maxWidth'=>'5', 'maxHeight'=>'0.75', 'volume'=>27.5, 'regOnly'=>1, 'commOnly'=>1, 'size'=>'REGULAR', 'fcType'=>'','container'=>'WINDOW FLAT RATE ENVELOPE', 'service'=>'PRIORITY HFP COMMERCIAL'), //CLASSID="41" **Commercial Only
      'First-Class Mail Package'=>
        array('id'=>0, 'maxWeight'=>0.8125, 'transitReq'=>'StandardB', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'', 'fcType'=>'PARCEL','container'=>'', 'service'=>'FIRST CLASS'), //CLASSID="0"
      'First-Class Mail Letter'=>
        array('id'=>0, 'maxWeight'=>0.21875, 'transitReq'=>'StandardB', 'maxGL'=>'', 'maxLength'=>'11.5', 'maxWidth'=>'6.125', 'maxHeight'=>'0.25', 'volume'=>17.61, 'regOnly'=>0, 'commOnly'=>0, 'size'=>'', 'fcType'=>'LETTER','container'=>'', 'service'=>'FIRST CLASS'), //CLASSID="0"
      'First-Class Mail Flats'=>
        array('id'=>0, 'maxWeight'=>0.8125, 'transitReq'=>'StandardB', 'maxGL'=>'', 'maxLength'=>'15', 'maxWidth'=>'12', 'maxHeight'=>'0.75', 'volume'=>135, 'regOnly'=>0, 'commOnly'=>0, 'size'=>'', 'fcType'=>'Flat','container'=>'', 'service'=>'FIRST CLASS'), //CLASSID="0"
      'First-Class Mail Package Hold For Pickup'=>
        array('id'=>53, 'maxWeight'=>0.8125, 'transitReq'=>'StandardB', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>1, 'size'=>'', 'fcType'=>'PARCEL','container'=>'', 'service'=>'FIRST CLASS HFP COMMERCIAL'), //CLASSID="53" **Commercial Only
      'First-Class Mail Postcards'=>
        array('id'=>0, 'maxWeight'=>0.21875, 'transitReq'=>'StandardB', 'maxGL'=>'', 'maxLength'=>'6', 'maxWidth'=>'4.25', 'maxHeight'=>'', 'volume'=>5, 'regOnly'=>1, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'POSTCARD','container'=>'', 'service'=>'FIRST CLASS'), //CLASSID="0" **Standard Rate Only
      'First-Class Mail Large Postcards'=>
        array('id'=>15, 'maxWeight'=>0.21875, 'transitReq'=>'StandardB', 'maxGL'=>'', 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>6, 'regOnly'=>0, 'commOnly'=>0, 'size'=>'LARGE', 'fcType'=>'POSTCARD','container'=>'', 'service'=>'FIRST CLASS'), //CLASSID="15" **Standard Rate Only
      'Parcel Post'=>
        array('id'=>4, 'maxWeight'=>70, 'transitReq'=>'StandardB', 'maxGL'=>130, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'', 'service'=>'PARCEL'), //CLASSID="4" **Standard Rate Only
      'Media Mail'=>
        array('id'=>6, 'maxWeight'=>70, 'transitReq'=>'StandardB', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'', 'service'=>'MEDIA'), //CLASSID="6" **Standard Rate Only
      'Library Mail'=>
        array('id'=>7, 'maxWeight'=>70, 'transitReq'=>'StandardB', 'maxGL'=>108, 'maxLength'=>'', 'maxWidth'=>'', 'maxHeight'=>'', 'volume'=>'', 'regOnly'=>0, 'commOnly'=>0, 'size'=>'REGULAR', 'fcType'=>'','container'=>'', 'service'=>'LIBRARY'), //CLASSID="7" **Standard Rate Only
    );
    
    /**
     * name: in future will be used to replace shipping service name.
     * 
     */
    $this->intl_types=array(
      'Global Express Guaranteed (GXG)**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>0, 'maxLength'=>'', 'maxWidth'=>'','maxHeight'=>'', 'name'=>'Global Express Guaranteed'), // ID="4"
      'Global Express Guaranteed Non-Document Rectangular'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>0, 'maxLength'=>'', 'maxWidth'=>'','maxHeight'=>'', 'name'=>'Global Express Non-Doc Rect'), // ID="6" 
      'Global Express Guaranteed Non-Document Non-Rectangular'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>0, 'maxLength'=>'', 'maxWidth'=>'','maxHeight'=>'', 'name'=>'Global Express Non-Doc Non-Rect'), // ID="7"
      'USPS GXG Envelopes**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>70, 'maxLength'=>'11', 'maxWidth'=>'8.5','maxHeight'=>'0.75', 'name'=>'USPS GXG Envelopes'), // ID="12"
      'Express Mail International'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>0, 'maxLength'=>'', 'maxWidth'=>'','maxHeight'=>'', 'name'=>'Express Mail Int'), // ID="1" 
      'Express Mail International Flat Rate Envelope'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>70, 'maxLength'=>'11', 'maxWidth'=>'8.5','maxHeight'=>'0.75', 'name'=>'Express Mail Int Flat Rate Env'), // ID="10"
      'Express Mail International Legal Flat Rate Envelope'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>90, 'maxLength'=>'14', 'maxWidth'=>'8.5','maxHeight'=>'0.75', 'name'=>'Express Mail Int Legal'), // ID="17"
      'Priority Mail International'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>0, 'maxLength'=>'', 'maxWidth'=>'','maxHeight'=>'', 'name'=>'Priority Mail International'), // ID="2"
      'Priority Mail International Large Flat Rate Box'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>792, 'maxLength'=>'12', 'maxWidth'=>'12','maxHeight'=>'5.5', 'name'=>'Priority Mail Int Flat Rate Lrg Box'), // ID="11"
      'Priority Mail International Medium Flat Rate Box'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>515, 'maxLength'=>'11:13.5', 'maxWidth'=>'8.5:11.75','maxHeight'=>'5.5:3.25', 'name'=>'Priority Mail Int Flat Rate Med Box'), // ID="9"
      'Priority Mail International Small Flat Rate Box**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>67, 'maxLength'=>'8.5', 'maxWidth'=>'5.25','maxHeight'=>'1.5', 'name'=>'Priority Mail Int Flat Rate Small Box'), // ID="16" 
      'Priority Mail International DVD Flat Rate Box**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>52, 'maxLength'=>'7.5', 'maxWidth'=>'5.5','maxHeight'=>'1.25', 'name'=>'Priority Mail Int DVD'), // ID="24"
      'Priority Mail International Large Video Flat Rate Box**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>95, 'maxLength'=>'9', 'maxWidth'=>'6','maxHeight'=>'1.75', 'name'=>'Priority Mail Int Lrg Video'), // ID="25"
      'Priority Mail International Flat Rate Envelope**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>70, 'maxLength'=>'11', 'maxWidth'=>'8.5','maxHeight'=>'0.75', 'name'=>'Priority Mail Int Flat Rate Env'), // ID="8" 
      'Priority Mail International Legal Flat Rate Envelope**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>90, 'maxLength'=>'14', 'maxWidth'=>'8.5','maxHeight'=>'0.75', 'name'=>'Priority Mail Int Legal Flat Rate Env'), // ID="22"
      'Priority Mail International Padded Flat Rate Envelope**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>70, 'maxLength'=>'11', 'maxWidth'=>'8.5','maxHeight'=>'0.75', 'name'=>'Priority Mail Int Padded Flat Rate Env'), // ID="23"
      'Priority Mail International Gift Card Flat Rate Envelope**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>40, 'maxLength'=>'9', 'maxWidth'=>'6','maxHeight'=>'0.75', 'name'=>'Priority Mail Int Gift Card Flat Rate Env'), // ID=18
      'Priority Mail International Small Flat Rate Envelope**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>34, 'maxLength'=>'9', 'maxWidth'=>'5','maxHeight'=>'0.75', 'name'=>'Priority Mail Int Small Flat Rate Env'), // ID="20"
      'First-Class Mail International Large Envelope**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>70, 'maxLength'=>'11', 'maxWidth'=>'8.5','maxHeight'=>'0.75', 'name'=>'First Class Mail Int Lrg Env'), // ID="14" 
      'First-Class Mail International Package**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>0, 'maxLength'=>'', 'maxWidth'=>'','maxHeight'=>'', 'name'=>'First Class Mail Int Package'), // ID="15" 
      'First-Class Mail International Letter**'=>
        array('maxWeight'=>70, 'maxGL'=>'', 'volume'=>15, 'maxLength'=>'9', 'maxWidth'=>'6','maxHeight'=>'0.25', 'name'=>'First Class Mail Int Letter') // ID="13" 
    );
    
    //International Shipping Translation: Config Key Name => USPS Shipping Service Name
    $this->intl_types_trans = array(
      'Global Express' => 'Global Express Guaranteed (GXG)**',
      'Global Express Non-Doc Rect' => 'Global Express Guaranteed Non-Document Rectangular',
      'Global Express Non-Doc Non-Rect' => 'Global Express Guaranteed Non-Document Non-Rectangular',
      'USPS GXG Envelopes' => 'USPS GXG Envelopes**',
      'Express Mail Int' => 'Express Mail International',
      'Express Mail Int Flat Rate Env' => 'Express Mail International Flat Rate Envelope',
      'Express Mail Int Legal' => 'Express Mail International Legal Flat Rate Envelope',
      'Priority Mail International' => 'Priority Mail International',
      'Priority Mail Int Flat Rate Lrg Box' => 'Priority Mail International Large Flat Rate Box',
      'Priority Mail Int Flat Rate Med Box' => 'Priority Mail International Medium Flat Rate Box',
      'Priority Mail Int Flat Rate Small Box' => 'Priority Mail International Small Flat Rate Box**',
      'Priority Mail Int DVD' => 'Priority Mail International DVD Flat Rate Box**',
      'Priority Mail Int Lrg Video' => 'Priority Mail International Large Video Flat Rate Box**',
      'Priority Mail Int Flat Rate Env' => 'Priority Mail International Flat Rate Envelope**',
      'Priority Mail Int Legal Flat Rate Env' => 'Priority Mail International Legal Flat Rate Envelope**',
      'Priority Mail Int Padded Flat Rate Env' => 'Priority Mail International Padded Flat Rate Envelope**',
      'Priority Mail Int Gift Card Flat Rate Env' => 'Priority Mail International Gift Card Flat Rate Envelope**',
      'Priority Mail Int Small Flat Rate Env' => 'Priority Mail International Small Flat Rate Envelope**',
      'First Class Mail Int Lrg Env' => 'First-Class Mail International Large Envelope**',
      'First Class Mail Int Package' => 'First-Class Mail International Package**',
      'First Class Mail Int Letter' => 'First-Class Mail International Letter**'
    );
    
    $this->domesticOptions = array(
      'Certified' => 0,
      'Insurance' => 1,
      'Restricted Delivery' => 3,
      'Registered without Insurance' => 4,
      'Registered with Insurance' => 5,
      'Collect on Delivery' => 6,
      'Return Receipt for Merchandise' => 7,
      'Return Receipt' => 8,
      'Express Mail Insurance' => 11,
      'Delivery Confirmation' => 13,
      'Signature Confirmation' => 15,
      'Return Receipt Electronic' => 16
    );
    
    $this->intlOptions = array(
      'Registered Mail'=>0,
      'Insurance'=>1,
      'Return Receipt'=>2,
      'Restricted Delivery'=>3,
      'Pick Up on Demand'=>5,
      'Certificate of Mailing'=>6
    );
    
    $this->countries = $this->country_list();

    // use USPS translations for US shops
    $this->usps_countries = $this->usps_translation();

  }

  /**
   * Get quote from shipping provider's API:
   *
   * @param string $method
   * @return array of quotation results
   */
  function quote($method = '') {
    global $order, $shipping_weight, $shipping_num_boxes, $transittime;
    if (empty($order->delivery['postcode'])) { return false; } //Dont retrieve a quote if in shipping estimator and no postcode is provided
    if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo '<div style="color:#fff; background:#c00; padding:5px;"><b>USPS Debug Mode Enabled!</b><br><pre style="background:#000; color:#eee; border:1px solid #ccc; padding:3px; margin:0;">';
    if ( zen_not_null($method) && (isset($this->types[$method]) || isset($this->intl_types[$method])) ) {
      $this->_setService($method);
    }
    
    //Get value of package if this isn't an estimate - used for insurance and cod.
    if (isset($order->products)) {
      foreach ($order->products as $p) {
        $value+=$p['final_price']*$p['qty'];
      }
    } else { //shipping estimator requested a quote and user isn't logged in - so estimate!
      $value=$_SESSION['cart']->show_total(); //Shipping estimator constructs an artificial $order object without a products array when a user isn't logged in.
    }
    $this->_setValue($value);
    
    // usps doesnt accept zero weight send 1 ounce (0.0625) minimum
    //Rounding to 5 decimal places so volume filtering (if used) doesn't affect weight/dimension calculations.
    $usps_shipping_weight = round(($shipping_weight <= 0.0625 ? 0.0625 : $shipping_weight),5);
    $shipping_pounds = floor ($usps_shipping_weight);
    $shipping_ounces = (16 * ($usps_shipping_weight - floor($usps_shipping_weight)));
    // usps currently cannot handle more than 5 digits on international
    $shipping_ounces = zen_round($shipping_ounces, 1);
    // weight must be less than 35lbs and greater than 6 ounces or it is not machinable
    $this->weight=$shipping_weight; //Used in _getQuote method
    
    //TODO: Revise machinable checking - certain services may differ.
    switch(true) {
      case ($shipping_pounds == 0 and $shipping_ounces < 6):
      // override admin choice too light
      $is_machinable = 'False';
      break;

      case ($usps_shipping_weight > 35):
      // override admin choice too heavy
      $is_machinable = 'False';
      break;

      default:
      // admin choice on what to use
      $is_machinable = MODULE_SHIPPING_USPS_MACHINABLE;
    }
    $this->_setMachinable($is_machinable);
    
    //Check Package Dimensions config
    $d=preg_replace(array('/^[\s,:]+/','/[\s,:]+$/'),'',MODULE_SHIPPING_USPS_DIMENSIONS); //Strip leading and trailing whitespace + separators
    $d=preg_split('/[\s,:]+/',$d);
    $doptions=array();
    foreach ($d as $dparam) {
      if (preg_match('/^(?P<dimensions>(?:\d{1,3}(?:\.\d{1,5})?x\d{1,3}(?:\.\d{1,5})?x\d{1,3}(?:\.\d{1,5})?)|regular);(?P<weight>\d{1,2}(?:\.\d{1,5})?|\+)(?:;(?P<container>[NRV]))?$/', $dparam, $b)) {
        $doptions[]=$b;
      } else {
        $doptions=array(array('dimensions'=>'regular','weight'=>'+'));
        break;
      }
    }
    $diff=100; //weights should not be >70lb for usps services
    $wMax=0; //used to select the box for the highest weight - if there is no config using '+'
    $dPass=0;
    foreach ($doptions as $k=>$d) { //Iterate over the dimension parameter sets and find the best match for package weight
      if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo 'weight: '.$d['weight'].'lb, dimensions: '.$d['dimensions']."\n";
      $w=$d['weight']=='+'?70:$d['weight'];
      if (($w-$usps_shipping_weight)>=0 && ($w-$usps_shipping_weight)<=$diff) { //Find the best match for dimensions/order weight
        $diff=$w-$usps_shipping_weight;
        $dim=$k;
        $dPass=1;
      } elseif (!$dPass && $w>=$wMax) { //Fallback to dimensions for the highest weight if all else fails.
        $dim=$k;
        $wMax=$w;
      }
    }
    $dim=$doptions[$dim];
    //Setting the dimensions
    $this->_setDimensions($dim['dimensions']);
    if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo 'Setting dimensions: l='.$this->length.' w='.$this->width.' h='.$this->height.' for Weight: '.$usps_shipping_weight."\n";
    if ($this->size=='LARGE') { //Override container type if size is large, Rectangular is default.
      $container=isset($dim['container'])?$dim['container']:'R';
      $this->_setContainer($container);
    }
    $this->_setWeight($shipping_pounds, $shipping_ounces);
    
    //Check if this quote has already been retrieved..
    if (isset($_SESSION['USPS_CACHE_KEY']) and $_SESSION['USPS_CACHE_KEY']==$this->_getCacheKey()) {
      if (!empty($method)) {
        $uspsQuote = false;
        foreach ($_SESSION['USPS_CACHE'] as $v) {
          if (key($v)==$method) {
            $uspsQuote=array($v);
            break;
          }
        }
      } else {
        $uspsQuote = $_SESSION['USPS_CACHE'];
      }
      if (isset($_SESSION['USPS_TRANSIT'])) { $transittime=$_SESSION['USPS_TRANSIT']; }
    } else {
      $uspsQuote = $this->_getQuote();
    }
    if (is_array($uspsQuote)) {
      if (isset($uspsQuote['error'])) {
        $this->quotes = array('module' => $this->title,
                              'error' => $uspsQuote['error']);
      } else {
        if (in_array('Display weight', explode(', ', MODULE_SHIPPING_USPS_OPTIONS))) {
          switch (SHIPPING_BOX_WEIGHT_DISPLAY) {
            case (0):
            $show_box_weight = '';
            break;
            case (1):
            $show_box_weight = ' (' . $shipping_num_boxes . ' ' . TEXT_SHIPPING_BOXES . ')';
            break;
            case (2):
            $show_box_weight = ' (' . number_format($usps_shipping_weight * $shipping_num_boxes,2) . TEXT_SHIPPING_WEIGHT . ')';
            break;
            default:
            $show_box_weight = ' (' . $shipping_num_boxes . ' x ' . number_format($usps_shipping_weight,2) . TEXT_SHIPPING_WEIGHT . ')';
            break;
          }
        }

        $this->quotes = array('id' => $this->code, 'module' => $this->title . $show_box_weight);
        
        // set handling fee
        if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY  || (SHIPPING_ORIGIN_COUNTRY == '223' && $this->usps_countries == 'US'))
        { // national
          $usps_handling_fee = MODULE_SHIPPING_USPS_HANDLING;
        }
        else
        { // international
          $usps_handling_fee = MODULE_SHIPPING_USPS_HANDLING_INT;
        }
        
        $methods = array();
        $size = sizeof($uspsQuote);
        
        $discount=0;
        if ((int)MODULE_SHIPPING_USPS_DISCOUNT && MODULE_SHIPPING_USPS_DISCOUNT>0 && MODULE_SHIPPING_USPS_DISCOUNT<100) {
          $discount=round((int)MODULE_SHIPPING_USPS_DISCOUNT/100*$this->value,2);
          if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') {
            echo MODULE_SHIPPING_USPS_DISCOUNT."% shipping discount, \$$discount\n";
          }
        }
        
        for ($i=0; $i<$size; $i++) {
          list($type, $cost) = each($uspsQuote[$i]);
          

          $title = $type;//TODO: Improve Service naming.((isset($this->types[$type])) ? $this->types[$type] : $type);
          if (in_array('Display transit time', explode(', ', MODULE_SHIPPING_USPS_OPTIONS)))    $title .= $transittime[$type];
          
          $title = str_replace('**', '', $title); //remove '**' from International types
          $cost = preg_replace('/[^0-9.]/', '',  $cost);
          $cost = ($cost * $shipping_num_boxes) + (MODULE_SHIPPING_USPS_HANDLING_METHOD == 'Box' ? $usps_handling_fee * $shipping_num_boxes : $usps_handling_fee);
          if ($discount)
          {
            if ($cost<$discount) { $cost=0; } 
            else { $cost=$cost-$discount; }
          }
          
          $methods[] = array('id' => $type,
                             'title' => $title,
                             'cost' => $cost );
        }
        
        //Sort the options
        if (MODULE_SHIPPING_USPS_QUOTE_SORT!='Unsorted') {
          usort($methods,'usps_sort_'.MODULE_SHIPPING_USPS_QUOTE_SORT);
        }
        
        $this->quotes['methods'] = $methods;

        if ($this->tax_class > 0) {
          $this->quotes['tax'] = zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
        }
      }
    } elseif ($uspsQuote == -1) {
      $this->quotes = array('module' => $this->title,
                            'error' => MODULE_SHIPPING_USPS_TEXT_SERVER_ERROR . (MODULE_SHIPPING_USPS_SERVER=='test' ? MODULE_SHIPPING_USPS_TEXT_TEST_MODE_NOTICE : ''));
    } else {
      $this->quotes = array('module' => $this->title,
                            'error' => MODULE_SHIPPING_USPS_TEXT_ERROR . (MODULE_SHIPPING_USPS_SERVER=='test' ? MODULE_SHIPPING_USPS_TEXT_TEST_MODE_NOTICE : ''));
    }

    if (zen_not_null($this->icon)) $this->quotes['icon'] = zen_image($this->icon, $this->title);
    
    if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo '</pre></div>';
    return $this->quotes;
  }
  /**
   * check status of module
   *
   * @return boolean
   */
  function check() {
    global $db;
    if (!isset($this->_check)) {
      $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_USPS_STATUS'");
      $this->_check = $check_query->RecordCount();
    }
    return $this->_check;
  }
  /**
   * Install this module
   *
   */
  function install() {
    global $db;
    //Module Enable/Disable
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable USPS Shipping', 'MODULE_SHIPPING_USPS_STATUS', 'True', 'Do you want to offer USPS shipping?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
    //USPS User ID
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Enter the USPS Web Tools User ID', 'MODULE_SHIPPING_USPS_USERID', 'NONE', 'Enter the USPS USERID assigned to you for Rate Quotes/ShippingAPI.', '6', '0', now())");
    //Select Production or Test Server
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Which server to use', 'MODULE_SHIPPING_USPS_SERVER', 'production', 'An account at USPS is needed to use the Production server', '6', '0', 'zen_cfg_select_option(array(\'test\', \'production\'), ', now())");
    //USPS Machinable
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('All Packages are Machinable', 'MODULE_SHIPPING_USPS_MACHINABLE', 'False', 'Are all products shipped machinable based on C700 Package Services 2.0 Nonmachinable PARCEL POST USPS Rules and Regulations?<br /><br /><strong>Note: Nonmachinable packages will usually result in a higher Parcel Post Rate Charge.<br /><br />Packages 35lbs or more, or less than 6 ounces (.375), will be overridden and set to False</strong>', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
    //Domestic Handling Fee
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee - US', 'MODULE_SHIPPING_USPS_HANDLING', '0', 'National Handling fee for this shipping method.', '6', '0', now())");
    //International Handling Fee
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Handling Fee - International', 'MODULE_SHIPPING_USPS_HANDLING_INT', '0', 'International Handling fee for this shipping method.', '6', '0', now())");
    //Handling Fee Option
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Handling Per Order or Per Box', 'MODULE_SHIPPING_USPS_HANDLING_METHOD', 'Box', 'Do you want to charge Handling Fee Per Order or Per Box?', '6', '0', 'zen_cfg_select_option(array(\'Order\', \'Box\'), ', now())");
    //Percentage Quote Discount
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Percent Shipping Discount', 'MODULE_SHIPPING_USPS_DISCOUNT', '0', 'Uses the given percentage of the order products price to lower the shipping cost.', '6', '0', now())");
    //Shipping Quote Display Order
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Quote Sort Order', 'MODULE_SHIPPING_USPS_QUOTE_SORT', 'Unsorted', 'Sorts the returned quotes using the service name Alphanumerically or by Price. Unsorted with put Express options at the top, followed by priority and then first-class.', '6', '0', 'zen_cfg_select_option(array(\'Unsorted\',\'Alphanumeric\', \'Price\'), ', now())");
    //Tax Class
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_USPS_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
    //Tax Basis
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Tax Basis', 'MODULE_SHIPPING_USPS_TAX_BASIS', 'Shipping', 'On what basis is Shipping Tax calculated. Options are<br />Shipping - Based on customers Shipping Address<br />Billing Based on customers Billing address<br />Store - Based on Store address if Billing/Shipping Zone equals Store zone', '6', '0', 'zen_cfg_select_option(array(\'Shipping\', \'Billing\', \'Store\'), ', now())");
    //Zone
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_USPS_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
    //Module Sort Order
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_USPS_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
    //Dimension Config - Text Area
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Package Dimensions Config', 'MODULE_SHIPPING_USPS_DIMENSIONS', 'regular;+', 'Here you can vary package size based on the weight of the order. For example if I want to send packages under 20.5lb in a 6x5x12 box, 20.5-30lb in 8.3x12x15 box, and anything heavier in a 12x12x15 box then my config option would be:<br />6x5x12;20.5<br />8.3x12x15;30<br />12x12x15;+<br />I Recomend separating each parameter set with a new line but space , and : are also accepted.<br /><br />\'regular\' in place of dimensions is equivalent to 12x12x12<br />After weight you may optionally set the package container. N=non-rectangular, R=rectangular, V=variable which only apply if any package dimensions are >12\". V is the default option if nothing is specified.<br /> eg: \'15x5x3;+;R\' is a valid config setting 15x5x3 dimensions with RECTANGULAR container for all quotes. These settings will not affect much until any dimensions exceed 12\"<br /><br />(Length)<b>x</b>(Width)<b>x</b>(Height)<b>;</b>(Weight)<i><b>;</b>(container)</i><br />Girth will be calculated from Width and Height values.<br /><b>Note: If the minimum dimensions you set exceed 12\" (\'regular\' size) certain shipping types will no longer be quoted. Check USPS Documentation for more info.</b>', '6', '0', 'zen_cfg_textarea(', now())");
    //Commercial Rate Quoting on/off
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Commercial Shipping', 'MODULE_SHIPPING_USPS_COMMERCIAL', 'N', 'Use USPS Commercial Rates?', '6', '0', 'zen_cfg_select_option(array(\'Y\', \'N\'), ', now())");
    //Domestic Commercial Shipping Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Domestic Shipping - Commercial Services', 'MODULE_SHIPPING_USPS_COMMERCIAL_TYPES', ".
      "'',".
      "'You must enable commercial rate quoting for these services to be quoted. <br />Select Commecial only shipping services:', '6', '13', 'zen_cfg_select_multioption(".
      "array(\'Priority Mail Hold For Pickup\', \'Priority Mail Large Flat Rate Box Hold For Pickup\', \'Priority Mail Medium Flat Rate Box Hold For Pickup\', \'Priority Mail Small Flat Rate Box Hold For Pickup\', \'Priority Mail Regional Rate Box A\', \'Priority Mail Regional Rate Box A Hold For Pickup\', \'Priority Mail Regional Rate Box B\', \'Priority Mail Regional Rate Box B Hold For Pickup\', \'Priority Mail Flat Rate Envelope\', \'Priority Mail Flat Rate Envelope Hold For Pickup\', \'Priority Mail Legal Flat Rate Envelope Hold For Pickup\', \'Priority Mail Padded Flat Rate Envelope Hold For Pickup\', \'Priority Mail Gift Card Flat Rate Envelope Hold For Pickup\', \'Priority Mail Small Flat Rate Envelope Hold For Pickup\', \'Priority Mail Window Flat Rate Envelope Hold For Pickup\'), ',  now())");
    //Domestic Shipping Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Domestic Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES', ".
      "'Express Mail, Priority Mail, Priority Mail Large Flat Rate Box, Priority Mail Medium Flat Rate Box, Priority Mail Small Flat Rate Box, First-Class Mail Package, Parcel Post',".
      "'Select the domestic services to be offered:', '6', '14', 'zen_cfg_select_multioption(".
      "array(\'Express Mail\', \'Express Mail Hold For Pickup\', \'Express Mail Sunday/Holiday Delivery\', \'Express Mail Flat Rate Envelope\', \'Express Mail Flat Rate Envelope Hold For Pickup\', \'Express Mail Sunday/Holiday Delivery Flat Rate Envelope\', \'Express Mail Legal Flat Rate Envelope\', \'Express Mail Legal Flat Rate Envelope Hold For Pickup\', \'Express Mail Sunday/Holiday Delivery Legal Flat Rate Envelope\', \'Priority Mail\', \'Priority Mail Large Flat Rate Box\', \'Priority Mail Medium Flat Rate Box\', \'Priority Mail Small Flat Rate Box\', \'Priority Mail Flat Rate Envelope\', \'Priority Mail Legal Flat Rate Envelope\', \'Priority Mail Padded Flat Rate Envelope\', \'Priority Mail Gift Card Flat Rate Envelope\', \'Priority Mail Small Flat Rate Envelope\', \'Priority Mail Window Flat Rate Envelope\', \'First-Class Mail Package\', \'First-Class Mail Postcards\', \'First-Class Mail Large Postcards\', \'Parcel Post\', \'Media Mail\', \'Library Mail\'), ',  now())");
    //International Shipping Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('International Shipping Methods', 'MODULE_SHIPPING_USPS_TYPES_INTL',".
      "'Express Mail Int, Priority Mail International, Priority Mail Int Flat Rate Small Box, Priority Mail Int Flat Rate Med Box, Priority Mail Int Flat Rate Lrg Box, First Class Mail Int Package',".
      "'Select the international services to be offered:', '6', '15', 'zen_cfg_select_multioption(".
      "array(\'Global Express\', \'Global Express Non-Doc Rect\', \'Global Express Non-Doc Non-Rect\', \'USPS GXG Envelopes\', \'Express Mail Int\', \'Express Mail Int Flat Rate Env\', \'Express Mail Int Legal\', \'Priority Mail International\', \'Priority Mail Int Flat Rate Env\', \'Priority Mail Int Flat Rate Small Box\', \'Priority Mail Int Flat Rate Med Box\', \'Priority Mail Int Flat Rate Lrg Box\', \'Priority Mail Int DVD\', \'Priority Mail Int Lrg Video\', \'Priority Mail Int Legal Flat Rate Env\', \'Priority Mail Int Padded Flat Rate Env\', \'Priority Mail Int Gift Card Flat Rate Env\', \'Priority Mail Int Small Flat Rate Env\', \'First Class Mail Int Lrg Env\', \'First Class Mail Int Package\', \'First Class Mail Int Letter\'), ',  now())");
    //Domestic Quote Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Domestic Quote Options', 'MODULE_SHIPPING_USPS_DOM_QUOTE_OPTIONS', '', 'Select from the following options.', '6', '17', 'zen_cfg_select_multioption(array(\'Certified\', \'Insurance\', \'Restricted Delivery\', \'Registered without Insurance\', \'Registered with Insurance\', \'Collect on Delivery\', \'Return Receipt for Merchandise\', \'Return Receipt\', \'Express Mail Insurance\', \'Delivery Confirmation\', \'Signature Confirmation\', \'Return Receipt Electronic\'), ',  now())");
    //International Quote Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('International Quote Options', 'MODULE_SHIPPING_USPS_INTL_QUOTE_OPTIONS', '', 'Select from the following options.', '6', '18', 'zen_cfg_select_multioption(array(\'Registered Mail\', \'Insurance\', \'Return Receipt\', \'Restricted Delivery\', \'Pick Up on Demand\', \'Certificate of Mailing\'), ',  now())");
    //Display Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('USPS Options', 'MODULE_SHIPPING_USPS_OPTIONS', 'Display weight, Display transit time', 'Select from the following the USPS options.', '6', '19', 'zen_cfg_select_multioption(array(\'Display weight\', \'Display transit time\'), ',  now())");
    //Filtering Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Shipping Quote Options Filtering', 'MODULE_SHIPPING_USPS_FILTER', 'Off', 'Do you want to filter out certain shipping options (like flat rate options) once an order goes beyond a certain weight or volume?<br><b>Weight: </b> This option will use your dimensions configuration above to determine if certain options should be quoted.<br><b>Volume: </b>In cubic inches. See module documentation. You need to apply a small database patch to filter quote options by volume. This lets you set parameters for each product seperately. Volume is set in digits 6-11 after the decimal point in the products weight field.<br>eg: Product A weighs 1.25lb and is 689.3 cubic inches so, <br>Product A Weight: 1.25000068930', '6', '0', 'zen_cfg_select_option(array(\'Off\', \'Weight\', \'Volume\'), ', now())");
    //Debug Options
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Debug Mode', 'MODULE_SHIPPING_USPS_DEBUG_MODE', 'Off', 'Would you like to enable debug mode?  A complete detailed log of USPS quote results may be emailed to the store owner. Alternatively the XML data can be dumped in the catalogue directory as uspsSend.xml and uspsRecieve.xml', '6', '0', 'zen_cfg_select_option(array(\'Off\', \'XML Dump\', \'Email\'), ', now())");
  }
  /**
   * Remove this module
   *
   */
  function remove() {
    global $db;
    $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE\_SHIPPING\_USPS\_%' ");
  }
  /**
   * Build array of keys used for installing/managing this module
   *
   * @return array
   */
  function keys() {
    $keys_list = array('MODULE_SHIPPING_USPS_STATUS', 'MODULE_SHIPPING_USPS_USERID', 'MODULE_SHIPPING_USPS_SERVER', 'MODULE_SHIPPING_USPS_HANDLING', 'MODULE_SHIPPING_USPS_HANDLING_INT', 'MODULE_SHIPPING_USPS_HANDLING_METHOD', 'MODULE_SHIPPING_USPS_DISCOUNT', 'MODULE_SHIPPING_USPS_QUOTE_SORT', 'MODULE_SHIPPING_USPS_TAX_CLASS', 'MODULE_SHIPPING_USPS_TAX_BASIS', 'MODULE_SHIPPING_USPS_ZONE', 'MODULE_SHIPPING_USPS_SORT_ORDER', 'MODULE_SHIPPING_USPS_MACHINABLE', 'MODULE_SHIPPING_USPS_INTL_QUOTE_OPTIONS', 'MODULE_SHIPPING_USPS_DOM_QUOTE_OPTIONS', 'MODULE_SHIPPING_USPS_OPTIONS', 'MODULE_SHIPPING_USPS_DIMENSIONS', 'MODULE_SHIPPING_USPS_COMMERCIAL', 'MODULE_SHIPPING_USPS_COMMERCIAL_TYPES', 'MODULE_SHIPPING_USPS_TYPES', 'MODULE_SHIPPING_USPS_TYPES_INTL', 'MODULE_SHIPPING_USPS_FILTER');
    $keys_list[]='MODULE_SHIPPING_USPS_DEBUG_MODE';
    return $keys_list;
  }
  //Create a unique identifier for storing quotes in a session.
  function _getCacheKey() {
    global $order;
    static $key;
    if (!$key)
    { 
      $key=serialize(array($order->delivery['country']['id'], $order->delivery['postcode'], $this->pounds, $this->ounces, $this->container, $this->size, $this->width, $this->length, $this->height, $this->girth, $this->machinable, MODULE_SHIPPING_USPS_COMMERCIAL,MODULE_SHIPPING_USPS_TYPES,MODULE_SHIPPING_USPS_COMMERCIAL_TYPES,MODULE_SHIPPING_USPS_TYPES_INTL,MODULE_SHIPPING_USPS_HANDLING,MODULE_SHIPPING_USPS_HANDLING_INT));
    }
    return $key;
  }
  
  /**
   * Set USPS service mode
   *
   * @param string $service
   */
  function _setService($service) {
    $this->service = $service;
  }
  /**
   * Set USPS dimensions
   *
   * @param string $service
   */
  function _setDimensions($d) {
    $d=($d=='regular'?'12x12x12':$d);
    $a=explode('x',$d);
    rsort($a,SORT_NUMERIC);
    $this->length=$l=round($a[0],1);
    $this->width=$w=round($a[1],1);
    $this->height=$h=round($a[2],1);
    $size=($l>12 or $w>12 or $h>12)?'LARGE':'REGULAR';
    $this->_setSize($size);
    $this->girth = 2 * ($w + $h); //may add an elipse formula for girth calculation later (for 'variable/non-rectangular' shaped packages)
  }
  /**
   * Set USPS weight for quotation collection
   *
   * @param integer $pounds
   * @param integer $ounces
   */
  function _setWeight($pounds, $ounces=0) {
    $this->pounds = $pounds;
    $this->ounces = $ounces;
  }
  /**
   * Set USPS container type
   *
   * @param string $container
   */
  function _setContainer($container) {
    $container=(in_array($container,array('V','N','R'))?$container:'');
    $this->container = strtr($container, array('V'=>'VARIABLE', 'N'=>'NONRECTANGULAR', 'R'=>'RECTANGULAR'));
  }

  /**
   * Set USPS Firs Class type
   *
   * @param string $fctype
   */
  function _setFirstClassType($fctype) {
    $this->fctype = $fctype;
  }
  /**
   * Set USPS order value - used for insurance quoting and cod.
   *
   * @param double $value
   */
  function _setValue($value) {
    $this->value = $value;
  }
  /**
   * Set USPS package size
   *
   * @param integer $size
   */
  function _setSize($size) {
    $this->size = $size;
  }
  /**
   * Set USPS machinable flag
   *
   * @param boolean $machinable
   */
  function _setMachinable($machinable) {
    $this->machinable = $machinable;
  }
  
  //Filter out options that exceed size or volume limits?
  function _filterOptions($allowed_types,&$t) {
    if (MODULE_SHIPPING_USPS_FILTER=='Weight') {
      if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo 'Weight/Dimension Filtering Enabled.'."\n";
      $check=array();
      $pass=array();
      foreach ($allowed_types as $s) {
        if (strpos($t[$s]['maxLength'],':')) {//usps have multiple box sizes for some types..
          $a_length=explode(':',$t[$s]['maxLength']);
          $a_width=explode(':',$t[$s]['maxWidth']);
          $a_height=explode(':',$t[$s]['maxHeight']);
          foreach ($a_length as $k=>$v) {
            $check[]=array($s,$v,$a_width[$k],$a_height[$k]);
          }
        } else {
          $check[]=array($s,$t[$s]['maxLength'],$t[$s]['maxWidth'],$t[$s]['maxHeight']);
        }
      }
      $l=&$this->length;
      $w=&$this->width;
      $h=&$this->height;
      foreach ($check as $v)
      {
        if (($l>(float)$v[1] && !empty($v[1])) or ($w>(float)$v[2] && !empty($v[2])) or ($h>(float)$v[3] && !empty($v[3])))
        {
          if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo 'Removed \''.$v[0]."'\n";
          continue;
        }
        else 
        {
          $pass[]=$v[0];
        }
      }
      $allowed_types=array_unique($pass);
    } 
    elseif (MODULE_SHIPPING_USPS_FILTER=='Volume') 
    {
      //extract the volume from the shipping weight.
      if (preg_match('/^\d{1,2}\.\d{5}(\d+)/',$this->weight,$v)) {
        $volume=str_pad($v[1],6,'0')/100;
        if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo 'Volume Filtering Enabled. Volume='.$volume.'" cu'."\n";
        //filter out options..
        $pass=array();
        foreach ($allowed_types as $s) {
          if (empty($t[$s]['volume']) or $t[$s]['volume']>=$volume) {
            $pass[]=$s;
          } else {
            if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo 'Removed \''.$s."'\n";
          }
        }
        $allowed_types=$pass;
      } else {
        if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') echo 'Volume Filtering enabled but volume information not found. Was the database patch applied?';
      }
    }
    return $allowed_types;
  }
  /**
   * Get actual quote from USPS
   *
   * @return array of results or boolean false if no results
   */
  function _getQuote() {
    // BOF: UPS USPS
    global $order, $transittime;
    if(in_array('Display transit time', explode(', ', MODULE_SHIPPING_USPS_OPTIONS))) $transit = TRUE;
    // EOF: UPS USPS

    // translate for US Territories
    if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY || (SHIPPING_ORIGIN_COUNTRY == '223' && $this->usps_countries == 'US')) {
      $request  = '<RateV4Request USERID="' . MODULE_SHIPPING_USPS_USERID . '">'."\n" .
      '<Revision>2</Revision>'."\n";
      $dest_zip = str_replace(' ', '', $order->delivery['postcode']);
      // translate for US Territories
      if ($order->delivery['country']['iso_code_2'] == 'US' || (SHIPPING_ORIGIN_COUNTRY == '223' && $this->usps_countries == 'US')) $dest_zip = substr($dest_zip, 0, 5);

      if (isset($this->service)) {
        $allowed_types = array($this->service);
      } else {
        $allowed_types = explode(', ', MODULE_SHIPPING_USPS_TYPES);
        if (MODULE_SHIPPING_USPS_COMMERCIAL=='Y') {
          $allowed_types = array_merge($allowed_types, explode(', ', MODULE_SHIPPING_USPS_COMMERCIAL_TYPES));
        }
      }
      $allowed_types=$this->_filterOptions($allowed_types,$this->types);
      //Create requests for trasit times (if needed)
      if ($transit) {
        $transreq=array();
        foreach ($allowed_types as $k) { //Determine which transit times are needed.
          if (isset($this->types[$k])) $transreq[$this->types[$k]['transitReq']]='';
        }
        unset($transreq['ExpressMail']); //Not doing this one
        while (list($k, $v) = each($transreq)) {
          $transreq[$k] = 'API='.$k.'&XML='.
            urlencode( '<'.$k.'Request USERID="' . MODULE_SHIPPING_USPS_USERID . '">' .
            '<OriginZip>' . SHIPPING_ORIGIN_ZIP . '</OriginZip>' .
            '<DestinationZip>' . $dest_zip . '</DestinationZip>' .
            '</'.$k.'Request>');
        }
      }
      $services='';
      if (MODULE_SHIPPING_USPS_DOM_QUOTE_OPTIONS!='--none--') {
        $services='<SpecialServices>';
        $serviceArray=array();
        foreach (explode(', ',MODULE_SHIPPING_USPS_DOM_QUOTE_OPTIONS) as $sName) {
          $serviceArray[]=$o=$this->domesticOptions[$sName];
          $services.='<SpecialService>'.$o.'</SpecialService>';
        }
        $services.='</SpecialServices>';
      }
      $services_count=0;
      $packageArray=array();
      foreach ($allowed_types as $k) {
        $t=&$this->types[$k];
        $request .= '<Package ID="' . $services_count . '">'."\n" .
        '  <Service>' . $t['service'] . '</Service>'."\n" .
        '  <FirstClassMailType>' . $t['fcType'] . '</FirstClassMailType>'."\n" .
        '  <ZipOrigination>' . SHIPPING_ORIGIN_ZIP . '</ZipOrigination>'."\n" .
        '  <ZipDestination>' . $dest_zip . '</ZipDestination>'."\n" .
        '  <Pounds>' . $this->pounds . '</Pounds>'."\n" .
        '  <Ounces>' . $this->ounces . '</Ounces>'."\n" .
        '  <Container>'. (!empty($t['container'])?$t['container']:$this->container) .'</Container>'."\n" .
        '  <Size>' . $this->size . '</Size>'."\n" .
        '  <Width>'. $this->width .'</Width>'."\n" . 
        '  <Length>'. $this->length .'</Length>'."\n" . 
        '  <Height>'. $this->height .'</Height>'."\n" . 
        '  <Girth>'. $this->girth .'</Girth>'."\n" . 
        '  <Value>'. $this->value .'</Value>'."\n" . //Used for insurance quoting
        //'  <AmountToCollect>'. $this->value .'</AmountToCollect>'."\n" . //Used for COD - Not implemented.
        (!empty($services)?('  '.$services."\n"):'').
        '  <Machinable>'. $this->machinable .'</Machinable>'."\n" .
        '  <ShipDate Option="HFP">'. gmdate('d-M-Y') .'</ShipDate>'."\n" . //Express Only - delivery commitment dates
        '</Package>'."\n";
        $packageArray[$services_count]=$k;
        $services_count++;
      }
      $request .= '</RateV4Request>';
      $request = 'API=RateV4&XML=' . urlencode($output=$request);
      
    } else {
      $services='';
      if (MODULE_SHIPPING_USPS_INTL_QUOTE_OPTIONS!='--none--') {
        $services='<ExtraServices>';
        $serviceArray=array();
        foreach (explode(', ',MODULE_SHIPPING_USPS_INTL_QUOTE_OPTIONS) as $sName) {
          $serviceArray[]=$o=$this->intlOptions[$sName];
          $services.='<ExtraService>'.$o.'</ExtraService>';
        }
        $services.='</ExtraServices>';
      }
      $request  = '<IntlRateV2Request USERID="' . MODULE_SHIPPING_USPS_USERID . '">'."\n" .
      '<Revision>2</Revision>'."\n" .
      '<Package ID="0">'."\n" .
      '  <Pounds>' . $this->pounds . '</Pounds>'."\n" .
      '  <Ounces>' . $this->ounces . '</Ounces>'."\n" .
      '  <Machinable>'. $this->machinable .'</Machinable>'."\n" .
      '  <MailType>All</MailType>'."\n" . //ENUM(All,Package,Envelope)
      '  <ValueOfContents>'. $this->value .'</ValueOfContents>'."\n" . //10.10 etc.. For insurance etc
      '  <Country>' . $this->countries[$order->delivery['country']['iso_code_2']] . '</Country>'."\n" .
      '  <Container>'. $this->container .'</Container>'."\n" . //RECTANGULAR or NONRECTANGULAR
      '  <Size>' . $this->size . '</Size>'."\n" . //REGULAR or LARGE if >12"
      //'  <GXG><POBoxFlag>Y</POBoxFlag><GiftFlag>Y</GiftFlag></GXG>' . Feature NOT Implemented
      '  <Width>'. $this->width .'</Width>'."\n" .
      '  <Length>'. $this->length .'</Length>'."\n" .
      '  <Height>'. $this->height .'</Height>'."\n" .
      '  <Girth>'. $this->girth .'</Girth>'."\n" .
      //'  <OriginZip>'. SHIPPING_ORIGIN_ZIP .'</OriginZip>'."\n" . Used for GXG - Not implemented.
      '  <CommercialFlag>'. MODULE_SHIPPING_USPS_COMMERCIAL .'</CommercialFlag>'."\n" .
      (!empty($services)?('  '.$services."\n"):'').
      '</Package>'."\n" .
      '</IntlRateV2Request>';
      $request = 'API=IntlRateV2&XML=' . urlencode($output=$request);
    }

    switch (MODULE_SHIPPING_USPS_SERVER) {
      case 'production':
		  $usps_server = 'production.shippingapis.com';
		  $api_dll = 'shippingapi.dll';
		  break;
     // case 'test':
      default:
		  $usps_server = 'testing.shippingapis.com';
		  $api_dll = 'ShippingAPI.dll';
		  break;
    }

    $body = '';
    $http = new httpClient();
    $http->timeout = 5;
    if ($http->Connect($usps_server, 80)) {
      $http->addHeader('Host', $usps_server);
      $http->addHeader('User-Agent', 'Zen Cart');
      $http->addHeader('Connection', 'Close');
      //if ($http->Post('/' . $api_dll, $request)) $body = $http->getBody();

      if ($http->Get('/' . $api_dll . '?' . $request)) $body = $http->getBody();
      if (MODULE_SHIPPING_USPS_DEBUG_MODE == 'XML Dump') {
        file_put_contents('uspsRecieve.xml', $body);
        file_put_contents('uspsSend.xml', str_replace(MODULE_SHIPPING_USPS_USERID,'***SNIP***',$output));
      }
      if (MODULE_SHIPPING_USPS_DEBUG_MODE == 'Email') mail(STORE_OWNER_EMAIL_ADDRESS, 'Debug: USPS rate quote response', '(You can turn off this debug email by editing your USPS module settings in the admin area of your store.) ' . "\n\n" . $body, 'From: <' . EMAIL_FROM . '>');
      // echo 'USPS METHODS: <pre>'; echo print_r($body); echo '</pre>';
      // BOF: UPS USPS

      // translate for US Territories
      if ($transit && is_array($transreq) && ( ($order->delivery['country']['id'] == STORE_COUNTRY || (SHIPPING_ORIGIN_COUNTRY == '223' && $this->usps_countries == 'US') )) ) {
        reset($transreq);
        while (list($key, $value) = each($transreq)) {
          if ($http->Get('/' . $api_dll . '?' . $value)) $transresp[$key] = $http->getBody();
        }
      }

      $http->Disconnect();
    } else {
      return -1;
    }

    // strip reg and trade out 01-02-2011
    $body = str_replace('&amp;lt;sup&amp;gt;&amp;amp;reg;&amp;lt;/sup&amp;gt;', '', $body);
    $body = str_replace('&amp;lt;sup&amp;gt;&amp;amp;trade;&amp;lt;/sup&amp;gt;', '', $body);
    
    $response=simplexml_load_string($body);

    $rates = array();

    // translate for US Territories
    if ($order->delivery['country']['id'] == SHIPPING_ORIGIN_COUNTRY  || (SHIPPING_ORIGIN_COUNTRY == '223' && $this->usps_countries == 'US')) {
      if ($response->getName() == 'Error') {
        $number = (string)$response->Number;
        $description = (string)$response->Description;
        return array('error' => $number . ' - ' . $description);
      }
      foreach ($response->Package as $package) {
        if (!isset($package->Error)) {
          foreach ($package->Postage as $p) {
            $service=$packageArray[(int)$package['ID']];
            $postage=(isset($p->CommercialRate) && (float)$p->CommercialRate!=0 && MODULE_SHIPPING_USPS_COMMERCIAL=='Y')?(float)$p->CommercialRate:(float)$p->Rate;
            if (MODULE_SHIPPING_USPS_COMMERCIAL=='N' and $postage==0) continue;
            if (in_array($service,$allowed_types)) {
              if (isset($this->service) && ($service != $this->service) ) { //Filter for checkout_payment section which retrieves the same quote again and uses the first item in the array.
                continue;
              }
              if (isset($serviceArray)) {
                foreach ($p->SpecialServices->SpecialService as $s) {
                  if (in_array($s->ServiceID,$serviceArray)) {
                    if (MODULE_SHIPPING_USPS_COMMERCIAL=='Y' and (float)$s->PriceOnline) {
                      if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') {
                        echo 'Added '.$s->ServiceName.' cost:'.$s->PriceOnline.' to '.$service."\n";
                      }
                      $postage+=(float)$s->PriceOnline;
                    } elseif ((float)$s->Price) {
                      if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') {
                        echo 'Added '.$s->ServiceName.', cost:'.$s->Price.' to '.$service."\n";
                      }
                      $postage+=(float)$s->Price;
                    }
                  }
                }
              }
              $rates[] = array($service => $postage);
            } else {
              continue;
            }
            if ($transit) {
              $transType=$this->types[$service]['transitReq'];
              switch ($transType) {
                case 'ExpressMail':
                  $cdate=(string)$p->Commitment->CommitmentDate;
                  $ctime=(string)$p->Commitment->CommitmentTime;
                  $time = strtotime($date.' '.$ctime);
                  if (!$time) {
                    $time = '1 - 2 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                  } else {
                    $cdate=round((($time-microtime(true))/86400), 0)==1?'Tomorrow':$cdate;
                    $time = $cdate.' by '.$ctime;
                  }
                  break;
                case 'PriorityMail':
                  $time = preg_match('/<Days>(.*)<\/Days>/msi', $transresp[$transType], $tregs);
                  $time = $tregs[1];
                  if ($time == '' || $time == 'No Data') {
                    $time = '2 - 3 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                  } elseif ($time == '1') {
                    $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                  } else {
                    $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                  }
                  break;
                case 'StandardB':
                  $time = preg_match('/<Days>(.*)<\/Days>/msi', $transresp[$transType], $tregs);
                  $time = $tregs[1];
                  if ($time == '' || $time == 'No Data') {
                    $time = '4 - 7 ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                  } elseif ($time == '1') {
                    $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAY;
                  } else {
                    $time .= ' ' . MODULE_SHIPPING_USPS_TEXT_DAYS;
                  }
                  break;
                default: 
                  $time = '';
                  break;
              }
              if ($time != '') $transittime[$service] = ' (' . $time . ')';
            }
            
          }
        } elseif (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') {
          echo "Error returned for a package:\n";
          print_r($package);
        }
      }
    } else { //International Rates
      if ($response->getName() == 'Error') {
        $number = (string)$response->Number;
        $description = (string)$response->Description;
        return array('error' => $number . ' - ' . $description);
      } else {
        $allowed_types = array();
        foreach (explode(", ", MODULE_SHIPPING_USPS_TYPES_INTL) as $value ) { $allowed_types[$value] = $this->intl_types_trans[$value]; }
        //Volume or Dimensions Filtering 
        $allowed_types=$this->_filterOptions($allowed_types,$this->intl_types); 
        foreach ($response->Package as $package) {
          foreach ($package->Service as $service) {
            if (isset($service->Postage)) {
              $serviceName = (string)$service->SvcDescription;
              $postage = (MODULE_SHIPPING_USPS_COMMERCIAL=='Y' && !empty($service->CommercialPostage))?(float)$service->CommercialPostage:(float)$service->Postage;
              $time = (string)$service->SvcCommitments;
              $time = preg_replace(
                array('/Weeks$/','/Days$/','/Day$/'), 
                array(MODULE_SHIPPING_USPS_TEXT_WEEKS,MODULE_SHIPPING_USPS_TEXT_DAYS,MODULE_SHIPPING_USPS_TEXT_DAY), $time
              );
              if( !in_array($serviceName, $allowed_types) ) continue;
              if ($_SESSION['cart']->total > 400 && strstr($serviceName, 'Priority Mail International Flat Rate Envelope')) continue; // skip value > $400 Priority Mail International Flat Rate Envelope
              if (isset($this->service) && ($serviceName != $this->service) ) {
                continue;
              }
              if (isset($serviceArray)) {
                foreach ($service->ExtraServices->ExtraService as $s) {
                  if (in_array($s->ServiceID,$serviceArray)) {
                    if (MODULE_SHIPPING_USPS_COMMERCIAL=='Y' and (float)$s->PriceOnline) {
                      if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') {
                        echo 'Added '.$s->ServiceName.' cost:'.$s->PriceOnline.' to '.$serviceName."\n";
                      }
                      $postage+=(float)$s->PriceOnline;
                    } elseif ((float)$s->Price) {
                      if (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') {
                        echo 'Added '.$s->ServiceName.', cost:'.$s->Price.' to '.$serviceName."\n";
                      }
                      $postage+=(float)$s->Price;
                    }
                  }
                }
              }
              $rates[] = array($serviceName => $postage);

              if ($time != '') $transittime[$serviceName] = ' (' . $time . ')';
            } elseif (MODULE_SHIPPING_USPS_DEBUG_MODE!='Off') {
              echo "Error returned for a shipping service:\n";
              print_r($service);
            }
          }
        }
      }
    }
    if (count($rates)==0) {
      $rates=false;
    } elseif (MODULE_SHIPPING_USPS_DEBUG_MODE=='Off') {
      $_SESSION['USPS_CACHE']=$rates;
      if ($transit) { $_SESSION['USPS_TRANSIT']=$transittime; }
      $_SESSION['USPS_CACHE_KEY']=$this->_getCacheKey();
    }
    return $rates;
  }
  /**
   * USPS Country Code List
   * This list is used to compare the 2-letter ISO code against the order country ISO code, and provide the proper/expected
   * spelling of the country name to USPS in order to obtain a rate quote
   *
   * @return array
   */
  function country_list() {
    $list = array(
    'AF' => 'Afghanistan',
    'AL' => 'Albania',
    'AX' => 'Aland Island (Finland)',
    'DZ' => 'Algeria',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AG' => 'Antigua and Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AU' => 'Australia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia-Herzegovina',
    'BW' => 'Botswana',
    'BR' => 'Brazil',
    'VG' => 'British Virgin Islands',
    'BN' => 'Brunei Darussalam',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'MM' => 'Burma',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CA' => 'Canada',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island (Australia)',
    'CC' => 'Cocos Island (Australia)',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo, Republic of the',
    'CD' => 'Congo, Democratic Republic of the',
    'CK' => 'Cook Islands (New Zealand)',
    'CR' => 'Costa Rica',
    'CI' => 'Cote d Ivoire (Ivory Coast)',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia, Republic of',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GB' => 'Great Britain and Northern Ireland',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GT' => 'Guatemala',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran',
    'IQ' => 'Iraq',
    'IE' => 'Ireland',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Laos',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macao',
    'MK' => 'Macedonia, Republic of',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte (France)',
    'MX' => 'Mexico',
    'FM' => 'Micronesia, Federated States of',
    'MD' => 'Moldova',
    'MC' => 'Monaco (France)',
    'MN' => 'Mongolia',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'AN' => 'Netherlands Antilles',
    'NC' => 'New Caledonia',
    'NZ' => 'New Zealand',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn Island',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'QA' => 'Qatar',
    'RE' => 'Reunion',
    'RO' => 'Romania',
    'RU' => 'Russia',
    'RW' => 'Rwanda',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts (St. Christopher and Nevis)',
    'LC' => 'Saint Lucia',
    'PM' => 'Saint Pierre and Miquelon',
    'VC' => 'Saint Vincent and the Grenadines',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome and Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovak Republic',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'GS' => 'South Georgia (Falkland Islands)',
    'KR' => 'South Korea (Korea, Republic of)',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syrian Arab Republic',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'East Timor (Indonesia)',
    'TG' => 'Togo',
    'TK' => 'Tokelau (Union) Group (Western Samoa)',
    'TO' => 'Tonga',
    'TT' => 'Trinidad and Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks and Caicos Islands',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VA' => 'Vatican City',
    'VE' => 'Venezuela',
    'VN' => 'Vietnam',
    'WF' => 'Wallis and Futuna Islands',
    'WS' => 'Western Samoa',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe'
    );

    return $list;
  }

// translate for US Territories
  function usps_translation() {
    global $order;
    if (SHIPPING_ORIGIN_COUNTRY == '223') {
      switch($order->delivery['country']['iso_code_2']) {
        case 'AS': // Samoa American
        case 'GU': // Guam
        case 'MP': // Northern Mariana Islands
        case 'PW': // Palau
        case 'PR': // Puerto Rico
        case 'VI': // Virgin Islands US
        case 'FM': // Micronesia, Federated States of
          return 'US';
          break;
        default:
          return $order->delivery['country']['iso_code_2'];
          break;
      }
    } else {
      return $order->delivery['country']['iso_code_2'];
    }
  }
}
