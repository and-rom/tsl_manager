<?include './config.php';?>
<html>
<head>
<title>Описание услуги</title>
<script src="calendar.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<a href="show.php">К списку</a>
<?
$cert_id= $_GET['cert_id'];
$org_id= $_GET['org_id'];
if (!empty($cert_id) && is_numeric($cert_id) && !isset($_POST['submit'])) {

// передан cert_id, значит редактирование и нужно показать данные в форме
  $form=TRUE;
  $result_c = mysql_query("select * from certs where ID=".$cert_id) or die(mysql_error());
  $row_c = mysql_fetch_assoc($result_c) or die(mysql_error());
  $cert_link="<a href=\"getcert.php?id=".$row_c['ID']."\">Текущий</a>";
  $entrie_id="?cert_id=".$cert_id;

} elseif (!empty($org_id) && is_numeric($org_id) && !isset($_POST['submit'])) {

// передан cert_id, значит редактирование и нужно показать данные в форме
$form=TRUE;
$entrie_id="?org_id=".$org_id;
} elseif (!empty($cert_id) && is_numeric($cert_id) && isset($_POST['submit'])) {

// передан org_id и submit, значит редактирование завершено, нужно обновить данные из формы
  $form=FALSE;
  $query="UPDATE certs SET ";
  if (!empty($_FILES["file"]["tmp_name"])) {
    $file      = $_FILES["file"]["tmp_name"];
    $fp = fopen($file,"r");
    $text = fread($fp, filesize($file));
    $text=str_replace("-----BEGIN CERTIFICATE-----", "", $text);
    $text=str_replace("-----END CERTIFICATE-----", "", $text);
    $text=str_replace("\r", "", $text);
    $text=str_replace("\n", "", $text);
    fclose($fp);
    $query .="X509Certificate='".$text."',";
  }
  $query .="SchemeServiceDefinitionURI='".$_POST['SchemeServiceDefinitionURI']."',";
  $query .="ServiceStatus='".$_POST['ServiceStatus']."',";
  $query .="StatusStartingTime='".$_POST['StatusStartingTime']."'";
  $query .=" WHERE ID='".$cert_id."'";
  mysql_query($query) or die(mysql_error());
  $result = mysql_query("SELECT org_id FROM certs WHERE ID=".$cert_id);
  $text = mysql_fetch_assoc($result);
  header("Refresh: 2; URL=view.php?id=".$text['org_id']);

} elseif (!empty($org_id) && is_numeric($org_id) && isset($_POST['submit'])) {

// передан org_id и submit, значит добавление завершено, нужно добавить данные из формы
$form=FALSE;
$result = mysql_query("SHOW TABLE STATUS LIKE 'certs'");
$row = mysql_fetch_assoc($result);
$next_id = $row['Auto_increment'];

$query = mysql_query("SELECT certs_ids FROM org WHERE ID=".$org_id);
$row = mysql_fetch_assoc($query);
$certs_ids=$row['certs_ids'];
if (!empty($certs_ids)) {$separ=",";} else {$separ="";}
  if (!empty($_FILES["file"]["tmp_name"])) {
    $file      = $_FILES["file"]["tmp_name"];
    $fp = fopen($file,"r");
    $text = fread($fp, filesize($file));
    $text=str_replace("-----BEGIN CERTIFICATE-----", "", $text);
    $text=str_replace("-----END CERTIFICATE-----", "", $text);
    $text=str_replace("\r", "", $text);
    $text=str_replace("\n", "", $text);
    fclose($fp);
    $query509['field']=", X509Certificate";
    $query509['value']=", '".$text."'";
  }
$query  ="INSERT INTO certs (org_id".$query509['field'].", SchemeServiceDefinitionURI, ServiceStatus, StatusStartingTime) VALUES('".$org_id."'".$query509['value'].", '".$_POST['SchemeServiceDefinitionURI']."', '".$_POST['ServiceStatus']."', '".$_POST['StatusStartingTime']."' ) ";
mysql_query($query);
$query="UPDATE org SET certs_ids='".$certs_ids.$separ.$next_id."' WHERE ID='".$org_id."'";
mysql_query($query);
header("Refresh: 2; URL=view.php?id=".$org_id);

} else {

echo "Error!";
// всё хуйня, Миша, давай сначала

}
if ($form) {
echo "
<form ENCTYPE=\"multipart/form-data\" method=\"post\" action=\"".$_SERVER['SCRIPT_NAME'].$entrie_id."\">
<table>
<tr><td>Сертификат: $cert_link</td><td><INPUT NAME=\"file\" TYPE=\"file\"></td></tr>
<tr><td>Точка распространения списка отозванных сертификатов (CDP):</td><td><input type=\"text\" name=\"SchemeServiceDefinitionURI\" value=\"".$row_c['SchemeServiceDefinitionURI']."\" /></td></tr>
<tr><td>Статус услуги:</td><td>
<select name=\"ServiceStatus\">";
echo "<option ".($row_c['ServiceStatus']=='1' ? 'selected' : '')." value=\"1\">Действует</option>";
echo "<option ".($row_c['ServiceStatus']=='2' ? 'selected' : '')." value=\"2\">Не действует</option>";
echo " </select>
</td></tr>
<tr><td>Дата начала действия статуса услуги:</td><td><input type=\"text\" name=\"StatusStartingTime\" value=\"".$row_c['StatusStartingTime']."\" onfocus=\"this.select();lcs(this)\" onclick=\"event.cancelBubble=true;this.select();lcs(this)\"></td></tr>
<input type=\"hidden\" value=\"true\" name=\"submit\" />
<tr><td><input type=\"submit\" /></td><td>&nbsp;</td></tr>
</table>
</form>
";
}
?>
</body>
</html>
