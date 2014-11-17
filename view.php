<?include './config.php';include './func.php';?>
<html>
<head>
<title>Поставщик услуги</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<a href="show.php">К списку</a>
<?
$id= $_GET['id'];
if (!empty($id) && is_numeric($id)) {
    $result_o = mysql_query("select * from org where ID=".$id) or die(mysql_error());
    $row_o = mysql_fetch_assoc($result_o) or die(mysql_error());
    org2table($row_o);
    
    //print_r($row_o);
    if (!empty($row_o['certs_ids'])) {
      $certs_ids=explode (",",$row_o['certs_ids']);
      for ($j=0; $j<count($certs_ids); $j++) {
        $result_c = mysql_query("select * from certs where ID=".$certs_ids[$j]);
        $row_c = mysql_fetch_assoc($result_c) or die(mysql_error());
        cert2table ($row_c);
        echo "<a href=\"entrie_c.php?cert_id=".$row_c['ID']."\">Редактировать</a><br><br>";
      }
    }
    echo "<a href=\"entrie_c.php?org_id=".$row_o['ID']."\">Добавить</a>";
} else {
header("Location:show.php");
}
?>
</body>
</html>
