<?php
//
//   $query=oci query to send
//

date_default_timezone_set('America/Chicago');
$objConnect = oci_connect("username","password","ipoforacledbserver/servicename");

if($objConnect)
{
//echo "Oracle Server Connected <br>";
}
else
{
echo "Can not connect to Oracle Server";
exit;
}
$filename = basename($_SERVER['SCRIPT_NAME'],".php").".csv";
//$rename $query2 to $query to test this file.  It's just going to give you a pretty html table if called from browser.
$query2="SELECT * FROM ATGCORE.DCSPP_ORDER";
//echo $query;
$stid = oci_parse($objConnect, $query);
oci_execute($stid);
echo "<table border='1'>\n";
$ncols = OCI_Num_fields($stid);
print "<TR>";
print "<TD>Line</TD>";
for ( $i = 1; $i <= $ncols; $i++ ) {
$column_name = OCI_field_Name($stid,$i);
$colarray[]=$column_name;
print "<TD>$column_name</TD>";
}
print "</TR>";
$k=1;
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
echo "<td>" . $k . "</td>";
$k=$k+1;
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? $item : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}

oci_close($objConnect);
?>
