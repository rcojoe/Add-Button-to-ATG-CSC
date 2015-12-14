<?php
header('Access-Control-Allow-Origin: *'); 
//var_dump($_POST["_D:/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"]);
//var_dump($_POST);

file_put_contents("outputfile.txt", urldecode(str_replace("=&=&=&=&=&=&=","order_status=",file_get_contents("php://input"))));
parse_str(urldecode(str_replace("=&=&=&=&=&=&=","order_status=",file_get_contents("php://input"))),$_POST);
var_dump($_POST["order_status"]);
//exit;
var_dump($_POST["/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"]);
if (isset($_POST["/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"])){
$searchdata = $_POST["/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"];
}
 // You will have to modify the Query to meet your needs.  This has some extended tables (RC_*) that your DB will not have.
$query="
SELECT '<a href=\"#Foo\" onclick=\"atg.commerce.csr.order.loadExistingOrder('''||ATGCORE.DCSPP_ORDER.ORDER_ID||''',''NO_PENDING_ACTION''); return false;\">'||ATGCORE.DCSPP_ORDER.ORDER_ID||'</a>' orderid,
to_char(ATGCORE.DCSPP_ORDER.SUBMITTED_DATE, 'MM-DD-YY_HH24:MI') submit_time,
	ATGCORE.DCSPP_ORDER.STATE ,
	ATGCORE.DCSPP_PAY_GROUP.AMOUNT,
	ATGCORE.DCSPP_ORDER.LAST_MODIFIED_DATE Last_modified,
	ATGCORE.DCSPP_ITEM.PRODUCT_ID,
	ATGCATA.DCS_SKU.DISPLAY_NAME,
	ATGCATA.RC_SKU.RPRO_ID,
	ATGCATA.RC_product.BRAND_TYPE_ID,
	ATGCATA.RC_SKU.SKU_ID,
	atgcore.rc_rpro_inv.UPC,
	atgcore.DCSPP_SHIP_GROUP.SHIPPING_METHOD,
	ATGCORE.RC_DCSPP_ORDER.RD_DATE,
	decode(ATGCORE.RC_DCSPP_ORDER.RD_DATE,'','1', '0') NOTLOADED
FROM ATGCORE.DCSPP_ORDER
		LEFT JOIN ATGCORE.DCSPP_PAY_GROUP
		ON ATGCORE.DCSPP_ORDER.ORDER_ID = ATGCORE.DCSPP_PAY_GROUP.ORDER_REF 
		JOIN ATGCORE.DCSPP_ITEM
		ON ATGCORE.DCSPP_ITEM.ORDER_REF = ATGCORE.DCSPP_ORDER.ORDER_ID 
		JOIN 		ATGCATA.DCS_SKU
		ON ATGCATA.DCS_SKU.SKU_ID = ATGCORE.DCSPP_ITEM.CATALOG_REF_ID 
		JOIN 		ATGCATA.RC_SKU
		ON ATGCATA.RC_SKU.SKU_ID = ATGCORE.DCSPP_ITEM.CATALOG_REF_ID
		left join atgcore.rc_rpro_inv
		on atgcore.rc_rpro_inv.sku_id = ATGCATA.RC_SKU.SKU_ID
		left join atgcata.rc_product
		on atgcata.rc_product.PRODUCT_ID = ATGCORE.DCSPP_ITEM.PRODUCT_ID
		LEFT JOIN 		ATGCORE.RC_DCSPP_ORDER
		ON ATGCORE.RC_DCSPP_ORDER.ORDER_ID = ATGCORE.DCSPP_ORDER.ORDER_ID
		left join atgcore.DCSPP_SHIP_GROUP
		on atgcore.DCSPP_SHIP_GROUP.ORDER_REF =  ATGCORE.DCSPP_ORDER.ORDER_ID 
WHERE ATGCORE.DCSPP_ORDER.STATE = 'PROCESSING'
ORDER BY NOTLOADED,ATGCORE.DCSPP_ORDER.SUBMITTED_DATE, ATGCORE.RC_DCSPP_ORDER.RD_DATE
";

 include "../include/ocireportquery.php";
