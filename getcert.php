<?
include './config.php';
$id= $_GET['id'];
if (!empty($id) && is_numeric($id)) {
$result = mysql_query("select org_id,X509Certificate from certs where ID=".$id);
$text = mysql_fetch_assoc($result);


header ("Content-type: application/x-x509-user-cert");
header ("Content-disposition: attachment;filename=".$text['org_id']."-".$id.".cer");

echo $text["X509Certificate"];
}
?>
