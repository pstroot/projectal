<?php

define("SHIPPINGZSETTINGS_VERSION","2.0.0.43426");

# (c) 2009-2010 Z-Firm LLC  ALL RIGHTS RESERVED

############################################## BEGIN Magento Section ##################################################
#
# Only Magento users need this section. All other users please skip to the "All Users" section below.
#
# Full instructions with pictures and detailed steps can be found
# in the enclosed Magento-Setup-Document.pdf
#
# In short, the steps are:
#
# Step M1: Create a new Web Service Role, with read access to catalog items & addresses, and
#          full access to orders, order comments, order status
# Step M2: Create a new Web Service User: SHIPPINGZ
# Step M3: For the API key on this user, create a 
#          random password 30 chars long at http://www.pctools.com/guides/password/
# Step M4: Enter this API Key in the "Magento_Password" define below.
# Step M5: Associate the new user with the role created in M1
# Step M6: Set the Magento_Username and Magento_Password below 
# Step M7: Upload this kit of files to the Magento root directory on the web server.
# Step M8: Continue the Stamps setup wizard.
# 
# NOTE: If running Magento 1.2.0 or earlier please set: define("Magento_StoreShippingInComments",1) 
#
# NOTE: The Magento API requires that the PHP SOAP extension
#       be installed and enabled. This a requirement of the Magento system.
#       Nearly all PHP v5.x systems have SOAP present & enabled.
#       A few (rare) hosts may have it disabled. Check php_info 
#       To confirm that the "SOAP Client" & "SOAP Server" are present & enabled.
#
####################################################################################################################

define("Magento_Username","ENTER USER NAME HERE");      // From Step M2 above. This is the Web Service "User Name" (see page 7 of the Magento-Setup-Document.pdf)
define("Magento_Password","ENTER API KEY - AKA PASSWORD - HERE");   // From Step M3 above. This is the Web Server user's "API Key" (see page 7 of the Magento-Setup-Document.pdf)
#
define("Magento_StoreShippingInComments",0); // Set to 0 for Magento v1.3 & 1.4. Set to 1 for v1.2. 
#                                            // If set to 1, comments will be posted in the general comments area on the order. 
define("Magento_SendsShippingEmail",0);      // If set to 1, send email notification
#
############################################## END Magento Section ##################################################
#
############################################## Magento Users Continue through the All Users section ######################
#
#
################################################### BEGIN All Users Section ##############################################
#
############################################# Please Read These Instructions #######################################
#
#   Your Attention Please !
#
#   There are three steps to take:
#
#   1) Create & configure a random SHIPPING_ACCESS_TOKEN
#   2) .NIX systems: Run chmod on the php files
#   3) ZenCart, OSCommerce & CRE Loaded: Configure this file with your "admin" directory
#
#   Stamps WILL NOT FUNCTION until you take these steps!
#
#   Step 1: Create & configure a random SHIPPING_ACCESS_TOKEN  Please take these steps:
#     1) Go to http://www.pctools.com/guides/password/
#     2) Check ALL the boxes EXCEPT the punctuation box
#     3) Set the LENGTH to 31
#     4) Go to the SHIPPING_ACCESS_TOKEN line below. Paste this value in over the "CHANGE THIS" -- Note: keep the "quotes"
#        Example: define("SHIPPING_ACCESS_TOKEN","1234567");
#     5) Upload the full kit of files to the root directory of your ecommerce system
#        (This is the root directory of the web store containing 'index.php'). 
#        (Yes, it is OK to omit the files for other ecommerce systems. E.g. a Magento user 
#         can remove the Zencart and Oscommerce files.)
#     6) Continue through the Stamps wizard. 
#     7) When the Stamps wizard prompts for the Access Token, enter the token you used in step 4 above.
#
#   Step 2: ZenCart, OSCommerce & CRE Loaded: If your "admin" directory has been changed from "admin" 
#   (hint: it usually IS changed), this needs to be set in the relevant "ADMIN_DIRECTORY" setting below.
#
#   Step 3: .NIX systems: In your store root directory, where these Shipping*.php files are, set all the Shipping*.*
#           files to permission: 444.
#           This can be done from many FTP clients.
#           From a shell prompt, in the directory where the Shipping*.* files reside, run this chmod command:
#             chmod 0444 Shipping*.php    (This sets the files to read only, no-execute, required by SuExec and other systems)
# 
############################################## All Users Settings #######################################

define("SHIPPING_ACCESS_TOKEN","pheNux4hudRukAfEja279eyutredraZ");  // See steps above to set this -- REQUIRED !


############################################## Retrive Order Status & Update Order status Settings #######################################

################################################ Only for Zencart Users ############################################ 
define("ZENCART_RETRIEVE_ORDER_STATUS_1_PENDING",0);
define("ZENCART_RETRIEVE_ORDER_STATUS_2_PROCESSING",1);
define("ZENCART_RETRIEVE_ORDER_STATUS_3_DELIVERED",0);
define("ZENCART_RETRIEVE_ORDER_STATUS_4_UPDATE",0);
# Next question: How do you work with the Zencart Order Status?
# In other words, when an order is shipped, should the order
# always be set to DELIVERED in Zencart?
#
# If you only ship orders that are in a PROCESSING state, you can leave 
# the setting below alone. When shipped, they will be moved automatically to the next
# status: DELIVERED.
#
# However, if you have PENDING orders being retrieved (see setting above)
# AND when shipped, you want those orders to move to a status of
# PROCESSING, then the setting below should be set to 0 (zero)
#
# In all cases, when shipped, the tracking # is posted into Zencart.
#
# Short Explanation:
# If this is set to 1, then, when shipped, orders of
# BOTH STATUS 1 (Pending) and STATUS 2 (Processing) will be
# set to STATUS 3 (Delivered)
define("ZENCART_SHIPPED_STATUS_SET_TO_STATUS_3_DELIVERED",1);//Defaults 1

define("ZENCART_ADMIN_DIRECTORY","projectal_admin");  // = /admin/
################################################ Only for Oscommerce Users ############################################
define("OSCOMMERCE_RETRIEVE_ORDER_STATUS_1_PENDING",0);
define("OSCOMMERCE_RETRIEVE_ORDER_STATUS_2_PROCESSING",1);
define("OSCOMMERCE_RETRIEVE_ORDER_STATUS_3_DELIVERED",0);
# Next question: How do you work with the Oscommerce Order Status?
# In other words, when an order is shipped, should the order
# always be set to DELIVERED in Oscommerce?
#
# If you only ship orders that are in a PROCESSING state, you can leave 
# the setting below alone. When shipped, they will be moved automatically to the next
# status: DELIVERED.
#
# However, if you have PENDING orders being retrieved (see setting above)
# AND when shipped, you want those orders to move to a status of
# PROCESSING, then the setting below should be set to 0 (zero)
#
# In all cases, when shipped, the tracking # is posted into Oscommerce.
#
# Short Explanation:
# If this is set to 1, then, when shipped, orders of
# BOTH STATUS 1 (Pending) and STATUS 2 (Processing) will be
# set to STATUS 3 (Delivered)
define("OSCOMMERCE_SHIPPED_STATUS_SET_TO_STATUS_3_DELIVERED",1);//Defaults 1

define("OSCOMMERCE_ADMIN_DIRECTORY","admin");  // = /admin/

################################################ Only for CRELOADED Users ############################################
define("CRELOADED_RETRIEVE_ORDER_STATUS_1_PENDING",0);
define("CRELOADED_RETRIEVE_ORDER_STATUS_2_PROCESSING",1);
define("CRELOADED_RETRIEVE_ORDER_STATUS_3_DELIVERED",0);

# Next question: How do you work with the Creloaded Order Status?
# In other words, when an order is shipped, should the order
# always be set to DELIVERED in Creloaded?
#
# If you only ship orders that are in a PROCESSING state, you can leave 
# the setting below alone. When shipped, they will be moved automatically to the next
# status: DELIVERED.
#
# However, if you have PENDING orders being retrieved (see setting above)
# AND when shipped, you want those orders to move to a status of
# PROCESSING, then the setting below should be set to 0 (zero)
#
# In all cases, when shipped, the tracking # is posted into Creloaded.
#
# Short Explanation:
# If this is set to 1, then, when shipped, orders of
# BOTH STATUS 1 (Pending) and STATUS 2 (Processing) will be
# set to STATUS 3 (Delivered)

define("CRELOADED_SHIPPED_STATUS_SET_TO_STATUS_3_DELIVERED",1);//Defaults 1

define("CRELOADED_ADMIN_DIRECTORY","admin");  // = /admin/

################################################ Only for Magento Users ###############################################
# This next section controls which order statuses are read from Magento.
# Setting to 0 (zero) turns off retrieval of that status.
# Setting to 1 (one) turns on retrieval of that status.
# By default, all statuses are retrieved EXCEPT for "Pending"
define("MAGENTO_RETRIEVE_ORDER_STATUS_1_PENDING",0); // default 0
define("MAGENTO_RETRIEVE_ORDER_STATUS_2_PROCESSING",1); // default 1
define("MAGENTO_RETRIEVE_ORDER_STATUS_3_COMPLETE",1); // default 1
define("MAGENTO_RETRIEVE_ORDER_STATUS_4_CLOSED",1); // default 1
define("MAGENTO_RETRIEVE_ORDER_STATUS_4_CANCELLED",1); // default 1
#
# Next question: How do you work with the Magento Order Status?
# In other words, when an order is shipped, should the order
# always be set to COMPLETE in Magento?
#
# If you only ship orders that are in a PROCESSING state, you can leave 
# the setting below alone. When shipped, they will be moved automatically to the next
# status: COMPLETE.
#
# However, if you have PENDING orders being retrieved (see setting above)
# AND when shipped, you want those orders to move to a status of
# PROCESSING, then the setting below should be set to 0 (zero)
#
# In all cases, when shipped, the tracking # is posted into Magento.
#
# Short Explanation:
# If this is set to 1, then, when shipped, orders of
# BOTH STATUS 1 (Pending) and STATUS 2 (Processing) will be
# set to STATUS 3 (Complete)
define("MAGENTO_SHIPPED_STATUS_COMPLETE_ALL_SHIPPED_ORDERS",1);// Default is 1
#
# Gift message settings. Controls whether the Magento Gift Message is retrieved.
# A setting of 1 means Yes.
# Normally, only one of these two options would be set to 1.
define("Magento_RetrieveOrderGiftMessage",1);     // ( default is 1 )
define("Magento_RetrieveProductGiftMessage",0);   // ( default is 0 )
#
# Store Settings: 
# - If you have only one store in your Magento system, please ignore the following.
# - If you have multiple stores, and want to retrieve orders from all stores, please ignore the following!
# - If you want to retrieve orders from only one store in a multi-store environment, set the following to
#   the "Store Code" to service.
# - Note: At this time, we do not support retrieving from "multiple stores but not all stores."
#
#   TO FIND THE STORE CODE: 
#   - In the Magento Admin Panel, navigate to System | Manage Stores
#   - You get a list of stores.
#   - Click on the store. You now see the "Edit Store View" screen.
#   - The code to use for this next setting is the "Code" value.
#     For example, if the Code is "shoestore1" then the line below would read:
#       define("Magento_Store_Code_To_Service","shoestore1");
define("Magento_Store_Code_To_Service","-ALL-");  // default -ALL-, which retrieves from all stores on the Magento system
################################################ Only for Xcart Users ################################################
define("XCART_RETRIEVE_ORDER_STATUS_1_QUEUED",0);
define("XCART_RETRIEVE_ORDER_STATUS_2_PROCESSED",1);
define("XCART_RETRIEVE_ORDER_STATUS_3_COMPLETE",0);
# Next question: How do you work with the Xcart Order Status?
# In other words, when an order is shipped, should the order
# always be set to COMPLETE in Xcart?
#
# If you only ship orders that are in a PROCESSING state, you can leave 
# the setting below alone. When shipped, they will be moved automatically to the next
# status: COMPLETE.
#
# However, if you have PENDING orders being retrieved (see setting above)
# AND when shipped, you want those orders to move to a status of
# PROCESSING, then the setting below should be set to 0 (zero)
#
# In all cases, when shipped, the tracking # is posted into Xcart.
#
# Short Explanation:
# If this is set to 1, then, when shipped, orders of
# BOTH STATUS 1 (Pending) and STATUS 2 (Processing) will be
# set to STATUS 3 (Complete)

define("XCART_SHIPPED_STATUS_SET_TO_STATUS_4_COMPLETE",1);//Dafaults 1
################################################ Only for Ubercart Users ############################################
define("UBERCART_RETRIEVE_ORDER_STATUS_1_PENDING",1);
define("UBERCART_RETRIEVE_ORDER_STATUS_2_PAYMENT_RECEIVED",1);
define("UBERCART_RETRIEVE_ORDER_STATUS_3_PROCESSING",1);
define("UBERCART_RETRIEVE_ORDER_STATUS_4_DELIVERED",1);
# Short Explanation:
# If this is set to 1, then, when shipped, orders of
# STATUS 1 (Pending), STATUS 2 (Payment Receive) and STATUS 3 (processing) will be
# set to STATUS 4 (Complete)
define("UBERCART_SHIPPED_STATUS_SET_TO_STATUS_4_COMPLETE",1);//Dafaults 1
############################################## System Settings for Tech Support Only ##############################

define("HTTP_GET_ENABLED",1);//allow get method
define("GetUnshippedOrdersOnly",0);//set 1 to get unshipped orders only

############################################## Legal Notices ######################################################

# ################################################################################
# 	
#   (c) 2010 Z-Firm LLC, ALL RIGHTS RESERVED.
#   Licensed to current Stamps.com customers. 
#
#   The terms of your Stamps.com license 
#   apply to the use of this file and the contents of the  
#   Stamps_ShoppingCart_Integration_Kit__See_README_file.zip   file.
#   
#   This file is protected by U.S. Copyright. Technologies and techniques herein are
#   the proprietary methods of Z-Firm LLC. 
#  
#   For use only by customers in good standing of Stamps.com
#
#
# 	IMPORTANT
# 	=========
# 	THIS FILE IS GOVERNED BY THE STAMPS.COM LICENSE AGREEMENT
#
# 	Using or reading this file indicates your acceptance of the Stamps.com License Agreement.
#
# 	If you do not agree with these terms, this file and related files must be deleted immediately.
#
# 	Thank you for using Stamps.com!
#
################################################################################




?>