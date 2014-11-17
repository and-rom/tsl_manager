<?include './config.php';include './func.php';?>
<html>
<head>
<title>Список доверенных УЦ</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?
//Begin functions
function startElement($parser, $name, $attrs) {
  global $tag,$tsp,$tspname,$tradename,$identity,$cert_id;
  switch($name) {
    case 'tsl:TrustServiceProvider':
      $tsp=array();
    break;
    case'tsl:Name':
      if ($tag=='tsl:TSPTradeName') {$tag='tsl:TSPTradeName';}
      if ($tag=='tsl:TSPName') {$tag='tsl:TSPName';}
    break;
    case 'tsl:ServiceDigitalIdentity':
      $identity=array();
    break;
    case 'tsl:X509Certificate':
      $tag = $name;
      $cert_id++;
    break;
    default:
      $tag = $name;
    break;
  };
}
function stringElement($parser, $str) {
  global $tag,$tsp,$tspname,$tradename,$buffstr,$prevtag,$identity,$service,$certs,$id,$cdp,$status,$start_time,$cert_id;
  if (strlen(trim($str)) > 0) {
    if ($tag=="") {$str=$buffstr.$str;$tag=$prevtag;} else {$buffstr=$str;}
    switch($tag) {
      case 'tsl:TSPName':
        $prevtag=$tag;
        $tag=null;  
        $tsp["Name"]=$str;
      break;
      case 'tsl:TSPTradeName':
        $prevtag=$tag;
        $tag=null;  
        $tsp["TradeName"]=$str;
      break;
      case 'tsl:StreetAddress':
        $prevtag=$tag;
        $tag=null;
        $tsp["StreetAddress"]=$str;
      break;
      case 'tsl:Locality':
        $prevtag=$tag;
        $tag=null;
        $tsp["Locality"]=$str;
      break;
      case 'tsl:PostalCode':
        $prevtag=$tag;
        $tag=null;
        $tsp["PostalCode"]=$str;
      break;
      case 'tsl:CountryName':
        $prevtag=$tag;
        $tag=null;
        $tsp["CountryName"]=$str;
      break;
      case 'tsl:URN':
        $prevtag=$tag;
        $tag=null;
        $tsp["URN"]=$str;
      break;
      case 'tsl:TSPInformationURI':
        $prevtag=$tag;
        $tag=null;
        $tsp["InformationURI"]=$str;
      break;
      case 'tsl:ServiceTypeIdentifier':
        $service=$str;
      break;
      case 'tsl:ServiceDigitalIdentity':
        $certs=array();
      break;
      case 'tsl:X509Certificate':
        $prevtag=$tag;
        $tag=null;
        $identity["id"]=$cert_id;    
        $identity["cert"]=$str;
        $identity["org_id"]=$id;
      break;
      case 'tsl:SchemeServiceDefinitionURI':
        $cdp=$str;
        $prevtag=$tag;
        $tag=null;
      break;
      case 'tsl:ServiceStatus':
        $status=$str;
      break;
      case 'tsl:StatusStartingTime':
        $start_time=$str;
      break;
    }
  }
}
function endElement($parser, $name) {
  global $tsp,$id,$identity,$certs,$cdp,$status,$start_time,$cert_id,$query_tsl,$certs_ids;
  switch($name) {
    case "tsl:TSPInformation":
      $id++;
      echo "<tr><td>".$id."</td><td>".$tsp['Name']."</td><td>".$tsp['TradeName']."</td><td>".$tsp['StreetAddress']."</td><td>".$tsp['Locality']."</td><td>".$tsp['PostalCode']."</td><td>".$tsp['CountryName']."</td><td>".$tsp['URN']."</td><td>".$tsp['InformationURI']."</td>";
      $query_tsl="INSERT INTO org (TSPName, TSPTradeName, TSPStreetAddress, TSPLocality, TSPPostalCode, TSPCountryName, TSPURN, TSPInformationURI, certs_ids) VALUES('".$tsp['Name']."', '".$tsp['TradeName']."', '".$tsp['StreetAddress']."', '".$tsp['Locality']."', '".$tsp['PostalCode']."', '".$tsp['CountryName']."', '".$tsp['URN']."', '".$tsp['InformationURI'];
         
    break;
    case "tsl:TSPServices":
      echo "<td>";
      $certs_ids='';
      for ($i=0; $i<count($certs); $i++) {
        if ($i!=count($certs)-1) {$separ=",";} else {$separ="";}
        $certs_ids=$certs_ids.$certs[$i]['id'].$separ;
      }
      echo $certs_ids."</td></tr><tr><td colspan=10><table border=1  width=100%>";
      
      for ($i=0; $i<count($certs); $i++) {
        $query_certs="INSERT INTO certs (org_id, X509Certificate, SchemeServiceDefinitionURI, ServiceStatus, StatusStartingTime) VALUES('";
        echo "<tr><td>".$certs[$i]['id']."</td><td>".$certs[$i]['org_id']."</td><td>".substr($certs[$i]['cert'], 0, 30)."..."."</td><td>".$status."</td><td>".$start_time."</td>";
        if ((count($certs)==1) && (strlen(trim($cdp)) > 0) ) { echo "<td><font color=red>".$cdp."</font></td></tr>";} else {echo "</tr>";$cdp="";}
        $query_certs=$query_certs.$certs[$i]['org_id']."', '".$certs[$i]['cert']."', '".$cdp."', '".$status."', '".$start_time."' ) ";
        //echo $query_certs."<br><br>";
        $cdp="";

        mysql_query($query_certs) or die(mysql_error());
      }
      echo "</table></td></tr>";
    break;
    case "tsl:TrustServiceProvider":
      $query_tsl=$query_tsl."', '".$certs_ids."' ) ";
      //echo $query_tsl."<br><br>";
      mysql_query($query_tsl) or die(mysql_error());
      $tsp=null;
      $certs=null;
    break;
    case "tsl:X509Certificate":
      $certs[]=$identity;
      $identity=null;
    break;
  }
}
//End functions
?>
<html><body>
<!--
// Загрузка файлов на сервер
// Если register_globals=Off
-->
<? if (!isset($_FILES["file"])) { ?>
  <FORM ENCTYPE="multipart/form-data" ACTION="<?=$_SERVER['SCRIPT_NAME']?>" METHOD=POST>
    <INPUT NAME="file" TYPE="file">
    <INPUT TYPE="submit" VALUE="Загрузить">
  </FORM>
  <?} else {
  $file      = $_FILES["file"]["tmp_name"];
  $file_name = $_FILES["file"]["name"];
  $file_size = $_FILES["file"]["size"];
  $file_type = $_FILES["file"]["type"];
  $error_flag  = $_FILES["file"]["error"];
  // Если ошибок не было
  if($error_flag == 0) {
    /*
    print("Имя файла на сервере (во время запроса): ".       $file.     "<br>");
    print("Имя файла на компьютере пользователя: ".          $file_name."<br>");
    print("MIME-тип файла: ".                                $file_type."<br>");
    print("Размер файла: ".                                  $file_size."<br><br>");
    */
    // Получаем содержимое файла
    ?>
<a href="show.php">К списку</a>
    <table border=1 width=100%>
      <tr>
        <td>Номер</td>
        <td>Имя</td>
        <td>Другое имя</td>
        <td>Адрес</td>
        <td>Город</td>
        <td>Индес</td>
        <td>Страна</td>
        <td>URN</td>
        <td>Сайт</td>
        <td>Серты</td>
      </tr>
    <?
    $fp = fopen($file,"r");
    $text = fread($fp, filesize($file));
    fclose($fp);
    /*
    // Вывод содержимого файла
    print($text);
    */
    mysql_query("TRUNCATE `certs`");
    mysql_query("TRUNCATE `org`");
    $service=null;
    $id=0;
    $cert_id=0;
    $prevtag=null;
    $buffstr=null;
    $identity=array();
    $certs=array();
    $tsp=array();
    $tag=null;
    $text=str_replace("\t", "", $text);
    $text=str_replace("\r", "", $text);
    $text=str_replace("\n", "", $text);
    $text=str_replace("                ", "", $text);
    $text=str_replace("               ", "", $text);
    $text=str_replace("              ", "", $text);
    $text=str_replace("             ", "", $text);
    $text=str_replace("            ", "", $text);
    $text=str_replace("           ", "", $text);
    $text=str_replace("          ", "", $text);
    $text=str_replace("         ", "", $text);
    $text=str_replace("        ", "", $text);
    $text=str_replace("       ", "", $text);
    $text=str_replace("      ", "", $text);
    $text=str_replace("     ", "", $text);
    $text=str_replace("    ", "", $text);
    $text=str_replace("   ", "", $text);
    $text=str_replace("  ", "", $text);
    $text=str_replace("><", ">\r<", $text);

    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "stringElement");
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
    if (!xml_parse($xml_parser, $text, true/*feof($fp)*/)) {
      echo "<br>XML Error: ";
      echo xml_error_string(xml_get_error_code($xml_parser));
      echo " at line ".xml_get_current_line_number($xml_parser);
      break;
    }
    xml_parser_free($xml_parser);
    ?></table><?
  }
}?>
</body>
</html>
