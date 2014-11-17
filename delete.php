<?include './config.php';include './func.php';?>
<html>
<head>
<title>Удаление поставщика услуги</title>
</head>
<link rel="stylesheet" type="text/css" href="style.css">
<body>
<?
$id= $_GET['id'];
if (!empty($id) && is_numeric($id)) {
  $confirm = $_POST['confirm'];
  if ($confirm=='yes') {
    $query = "select * from org where ID=".$id;	
    $result_o = mysql_query($query);
    $row_o = mysql_fetch_assoc($result_o);
    $certs_ids=explode (",",$row_o['certs_ids']);
    for ($j=0; $j<count($certs_ids); $j++) {
      mysql_query("DELETE FROM certs WHERE ID=".$certs_ids[$j]);
    }
    mysql_query ("DELETE FROM org WHERE ID=$id") or die(mysql_error());
    header("Refresh: 3; URL=show.php");
    echo "Через 3 секунды будите перемещены на страницу просмотра.";

  } else {
    $query = "select * from org where ID=".$id;	
    $result_o = mysql_query($query);
    $row_o = mysql_fetch_assoc($result_o);
    org2table($row_o);
    $certs_ids=explode (",",$row_o['certs_ids']);
    for ($j=0; $j<count($certs_ids); $j++) {
      $result_c = mysql_query("select * from certs where ID=".$certs_ids[$j]);
      $row_c = mysql_fetch_assoc($result_c);
      cert2table ($row_c);
    }
    echo "</pre>";
    echo "\n<center>
    <table>
    <tr>
    <td>
    <form action=\"".$_SERVER['SCRIPT_NAME']."?id=".$id."\" method=\"post\">
    <input type=\"hidden\" value=".$id." name=\"id\" />
    <input type=\"hidden\" value=\"yes\" name=\"confirm\" />
    <input type=\"submit\" value=\"Удалить\" />
    </form>
    </td>
    <td>
    <form action=\"show.php\" method=\"post\">
    <input type=\"submit\" value=\"Отменить\" />
    </form>
    </td>
    </tr>
    </table></center>
    ";
  }
} else {
header("Location:show.php");
}
?>
</body>
</html>
