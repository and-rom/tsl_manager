<?include './config.php';?>
<html>
<head>
<title>Поставщик услуги</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<a href="show.php">К списку</a>
<?
$id= $_GET['id'];

if (!empty($id) && is_numeric($id) && !isset($_POST['submit'])) {

// передан id, но нет submit, значит обновление и надо вывести данные в форму
  $form=TRUE;
  $str="?id=".$id;
  $result_o = mysql_query("select * from org where ID=".$id) or die(mysql_error());
  $row_o = mysql_fetch_assoc($result_o) or die(mysql_error());

} elseif (!empty($id) && is_numeric($id) && isset($_POST['submit'])) {

//передан id и submit, значит обновление завершено и надо данные из формы ОБНОВИТь в БД
  $form=FALSE;
  $query="UPDATE org SET TSPName='".$_POST['TSPName']."', TSPTradeName='".$_POST['TSPTradeName']."', TSPStreetAddress='".$_POST['TSPStreetAddress']."', TSPLocality='".$_POST['TSPLocality']."', TSPPostalCode='".$_POST['TSPPostalCode']."', TSPCountryName='".$_POST['TSPCountryName']."', TSPURN='".$_POST['TSPURN']."', TSPInformationURI='".$_POST['TSPInformationURI']."' WHERE ID='".$id."'";
  mysql_query($query) or die(mysql_error());
  header("Refresh: 2; URL=view.php?id=".$id);
  
  
} elseif (empty($id) && !isset($_POST['submit'])) {

//ничего не передано, просто вывести пустую форму для добавления новой записи
  $form=TRUE;
  $str="";

} elseif (empty($id) && isset($_POST['submit'])) {

//передан только submit, значит добавление закончего и надо данные из формы ВСТАВИТЬ в БД
  $form=FALSE;
  $query="INSERT INTO org (TSPName, TSPTradeName, TSPStreetAddress, TSPLocality, TSPPostalCode, TSPCountryName, TSPURN, TSPInformationURI) VALUES('".$_POST['TSPName']."', '".$_POST['TSPTradeName']."', '".$_POST['TSPStreetAddress']."', '".$_POST['TSPLocality']."', '".$_POST['TSPPostalCode']."', '".$_POST['TSPCountryName']."', '".$_POST['TSPURN']."', '".$_POST['TSPInformationURI']."' ) ";
  mysql_query($query) or die(mysql_error());
  $result = mysql_query("SHOW TABLE STATUS LIKE 'org'");
  $row = mysql_fetch_assoc($result);
  $next_id = $row['Auto_increment']-1;
  header("Refresh: 2; URL=view.php?id=".$next_id);

} else {

//всё хуйня, Миша, давай сначала

}
if ($form) {
  echo "
  <form method=\"post\" action=\"".$_SERVER['SCRIPT_NAME'].$str."\">
  <table>
  <tr><td>TSPName:</td><td><input type=\"text\" name=\"TSPName\" value=\"".$row_o['TSPName']."\" /></td></tr>
  <tr><td>TSPTradeName:</td><td><input type=\"text\" name=\"TSPTradeName\" value=\"".$row_o['TSPTradeName']."\" /></td></tr>
  <tr><td>Адрес:</td><td><input type=\"text\" name=\"TSPStreetAddress\" value=\"".$row_o['TSPStreetAddress']."\" /></td></tr>
  <tr><td>Город:</td><td><input type=\"text\" name=\"TSPLocality\" value=\"".$row_o['TSPLocality']."\" /></td></tr>
  <tr><td>Почтовый индекс:</td><td><input type=\"text\" name=\"TSPPostalCode\" value=\"".$row_o['TSPPostalCode']."\" /></td></tr>
  <tr><td>Страна:</td><td><input type=\"text\" name=\"TSPCountryName\" value=\"".$row_o['TSPCountryName']."\" /></td></tr>
  <tr><td>Электронный адрес службы поддержки (e-mail, www):</td><td><input type=\"text\" name=\"TSPURN\" value=\"".$row_o['TSPURN']."\" /></td></tr>
  <tr><td>Официальный сайт:</td><td><input type=\"text\" name=\"TSPInformationURI\" value=\"".$row_o['TSPInformationURI']."\" /></td></tr>
  <input type=\"hidden\" value=\"true\" name=\"submit\" />
  <tr><td><input type=\"submit\" /></td><td>&nbsp;</td></tr>
  </table>
  </form>
  ";
  }
?>
</body>
</html>
