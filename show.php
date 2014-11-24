<?include './config.php';?>
<html>
<head>
<title>Список поставщиков услуг</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<a href="./">Домой</a>&nbsp;<a href="entrie_o.php">Добавить</a>
<?

$result = mysql_query("select * from org");
$table="<table>\n";
$table .= "<tr>";
$table .= "<th>№</th>";
$table .= "<th>Имя</th>";
//$table .= "<th>Другое имя</th>";
$table .= "<th>Адрес</th>";
//$table .= "<th>URN</th>";
$table .= "<th>&nbsp;</th>";
$table .= "</tr>\n";
$switch=True;
$i=1;
while ($row = mysql_fetch_assoc($result)) {
  if ($switch) {$table .= "<tr class=\"even\">\n";} else {$table .= "<tr>\n";}
  $table .= "<td>".$i/*." - ".$row['ID']*/."</td>\n";
  $table .= "<td><a href=\"".$row['TSPInformationURI']."\" title=\"".$row['TSPTradeName']."\">".$row['TSPName']."</a></td>\n";
  //if ($row['TSPTradeName']!='') {$table .= "<td>".$row['TSPTradeName']."</td>\n";} else {$table .= "<td>&nbsp;</td>\n";}
  $table .= "<td>".$row['TSPPostalCode']." ".$row['TSPLocality'].", ".$row['TSPStreetAddress']."</td>\n";
  //$table .= "<td>".$row['TSPURN']."</td>\n";
  $table .= "<td><a href=\"view.php?id=".$row['ID']."\">Просмотр</a>&nbsp;<a href=\"entrie_o.php?id=".$row['ID']."\">Редактировать</a>&nbsp;<a href=\"delete.php?id=".$row['ID']."\">Удалить</a></td>\n";
  $table .=  "</tr>\n";
  //print_r($row_o);
  //echo "<br><br>";
  $switch=!$switch;
  $i++;
}
$table .= "</table>\n";
echo $table;
?>
