<?php
header('Access-Control-Allow-Origin: *'); 
//var_dump($_POST["_D:/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"]);
//var_dump($_POST);
echo "<h2>Top 200 Sorted by Last Modifed Date</h2>";
$search_query = "";
file_put_contents("outputfile.txt", urldecode(str_replace("=&=&=&=&=&=&=","order_status=",file_get_contents("php://input"))));
parse_str(urldecode(str_replace("=&=&=&=&=&=&=","order_status=",file_get_contents("php://input"))),$_POST);
var_dump($_POST["order_status"]);
//exit;
var_dump($_POST["/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"]);
if (isset($_POST["/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"])){
$searchdata = $_POST["/atg/commerce/custsvc/order/OrderSearchTreeQueryFormHandler_searchRequest_fields"];
echo "<BR>";

//order#
if (isset($searchdata[0]) and strlen($searchdata[0]) > 0){
echo "Order# ".$searchdata[0]."<br>";
$searchquery = $searchquery . " and ATGCORE.DCSPP_ORDER.ORDER_ID = '".$searchdata[0]."'";
}
//email
if (isset($searchdata[1]) and strlen($searchdata[1]) > 0){
$searchquery = $searchquery . " and lower(DCSPP_SHIP_ADDR.EMAIL) = lower('".$searchdata[1]."')";
}
//first name
if (isset($searchdata[2]) and strlen($searchdata[2]) > 0){
$searchquery = $searchquery . " and lower(DCSPP_SHIP_ADDR.FIRST_NAME) = lower('".$searchdata[2]."')";
}
//last name
if (isset($searchdata[3]) and strlen($searchdata[3]) > 0){
$searchquery = $searchquery . " and lower(DCSPP_SHIP_ADDR.LAST_NAME) = lower('".$searchdata[3]."')";
}
//login
if (isset($searchdata[4]) and strlen($searchdata[4]) > 0){
$searchquery = $searchquery . " and lower(DCSPP_SHIP_ADDR.EMAIL) = lower('".$searchdata[4]."')";
}
//sku
if (isset($searchdata[5]) and strlen($searchdata[5]) > 0){
$searchquery = $searchquery . " and (lower(ATGCATA.RC_SKU.SKU_ID) = lower('".$searchdata[5]."') or ATGCATA.RC_SKU.RPRO_ID = lower('".$searchdata[5]."') or atgcore.rc_rpro_inv.UPC = lower('".$searchdata[5]."'))";
}

if (isset($_POST["order_status"])and strlen($_POST["order_status"]) > 0){
echo "Order Status ".$_POST["order_status"]."<br>";
$order_status = $_POST["order_status"];
$searchquery = $searchquery . " and ATGCORE.DCSPP_ORDER.STATE = '".$order_status."'";
} else if (strlen($searchquery) < 1) $searchquery = "and ATGCORE.DCSPP_ORDER.LAST_MODIFIED_DATE > sysdate-3 and ATGCORE.DCSPP_ORDER.STATE <> 'INCOMPLETE'";


}
echo "<br><br>".$searchquery."<BR><BR>";


// You will have to modify the Query to meet your needs.  This has some extended tables (RC_*) that your DB will not have.
$query="
SELECT '<a href=\"#Foo\" onclick=\"atg.commerce.csr.order.loadExistingOrder('''||ATGCORE.DCSPP_ORDER.ORDER_ID||''',''NO_PENDING_ACTION''); return false;\">'||ATGCORE.DCSPP_ORDER.ORDER_ID||'</a>' orderid,
       ATGCORE.DCSPP_ORDER.STATE order_state,
       to_char(ATGCORE.DCSPP_ORDER.LAST_MODIFIED_DATE, 'MM-DD-YY HH24:MI') modified_time,

	DCSPP_SHIP_ADDR.FIRST_NAME FIRST_NAME,
	DCSPP_SHIP_ADDR.LAST_NAME last_name,
   	DCSPP_SHIP_ADDR.ADDRESS_1 ADDRESS_1,
   	DCSPP_SHIP_ADDR.ADDRESS_2 ADDRESS_2,
   	DCSPP_SHIP_ADDR.ADDRESS_3 ADDRESS_3,
   	DCSPP_SHIP_ADDR.CITY CITY,
   	DCSPP_SHIP_ADDR.STATE SHIPSTATE,
   	DCSPP_SHIP_ADDR.POSTAL_CODE POSTAL_CODE,
   	DCSPP_SHIP_ADDR.COUNTRY COUNTRY,
	DCSPP_SHIP_ADDR.PHONE_NUMBER,
	DCSPP_SHIP_ADDR.EMAIL,
	ATGCORE.DCSPP_ORDER.LAST_MODIFIED_DATE,
       to_char(ATGCORE.DCSPP_ORDER.SUBMITTED_DATE, 'MM-DD-YY HH24:MI') submit_time,
	ATGCORE.DCSPP_ITEM.PRODUCT_ID,
	ATGCORE.DCSPP_ITEM.QUANTITY,
	ATGCATA.DCS_SKU.DISPLAY_NAME,
	--atgcore.DCSPP_AMOUNT_INFO.AMOUNT,
	ATGCATA.RC_SKU.RPRO_ID,
	ATGCATA.RC_SKU.SKU_ID,
	ATGCATA.RC_SKU.EXTRA1,
	atgcore.rc_rpro_inv.UPC,
	ATGCORE.RC_DCSPP_ORDER.RD_DATE

FROM ATGCORE.DCSPP_ORDER
		JOIN ATGCORE.DCSPP_ITEM
		ON ATGCORE.DCSPP_ITEM.ORDER_REF = ATGCORE.DCSPP_ORDER.ORDER_ID 
		JOIN 		ATGCATA.DCS_SKU
		ON ATGCATA.DCS_SKU.SKU_ID = ATGCORE.DCSPP_ITEM.CATALOG_REF_ID 
		JOIN 		ATGCATA.RC_SKU
		ON ATGCATA.RC_SKU.SKU_ID = ATGCORE.DCSPP_ITEM.CATALOG_REF_ID
		left join atgcore.rc_rpro_inv
		on atgcore.rc_rpro_inv.sku_id = ATGCATA.RC_SKU.SKU_ID
		LEFT JOIN 		ATGCORE.RC_DCSPP_ORDER
		ON ATGCORE.RC_DCSPP_ORDER.ORDER_ID = ATGCORE.DCSPP_ORDER.ORDER_ID
		join atgcore.DCSPP_ORDER_SG
		on atgcore.DCSPP_ORDER_SG.Order_id = ATGCORE.DCSPP_ORDER.ORDER_ID
		join atgcore.DCSPP_SHIP_ADDR
		on atgcore.DCSPP_SHIP_ADDR.SHIPPING_GROUP_ID = atgcore.DCSPP_ORDER_SG.SHIPPING_GROUPS	
where ROWNUM <= 200 ".$searchquery." 
ORDER BY ATGCORE.DCSPP_ORDER.LAST_MODIFIED_DATE desc, ATGCORE.DCSPP_ORDER.ORDER_ID";
//echo $query;
 include "../include/ocireportquery.php";
