<?
include './config.php';

header('Content-type: application/xml');
header("Content-Disposition: attachment;filename=TSL.xml");
$filename = 'TSL.xml';
$file=fopen($filename, 'w');
$header = file_get_contents("header.t");
fwrite($file, $header);
echo $header;
$result_o = mysql_query("select * from org");
while ($row_o = mysql_fetch_assoc($result_o)) {
  $str= "<tsl:TrustServiceProvider><tsl:TSPInformation><tsl:TSPName><tsl:Name xml:lang=\"RU\">".$row_o['TSPName']."</tsl:Name></tsl:TSPName>";
  if (strlen($row_o['TSPTradeName']) > 0) { $str=$str."<tsl:TSPTradeName><tsl:Name xml:lang=\"RU\">".$row_o['TSPTradeName']."</tsl:Name></tsl:TSPTradeName>";}
  $str=$str."<tsl:TSPAddress><tsl:PostalAddresses><tsl:PostalAddress xml:lang=\"RU\"><tsl:StreetAddress>".$row_o['TSPStreetAddress']."</tsl:StreetAddress><tsl:Locality>".$row_o['TSPLocality']."</tsl:Locality><tsl:PostalCode>".$row_o['TSPPostalCode']."</tsl:PostalCode><tsl:CountryName>".$row_o['TSPCountryName']."</tsl:CountryName></tsl:PostalAddress></tsl:PostalAddresses><tsl:ElectronicAddress><tsl:URN>".$row_o['TSPURN']."</tsl:URN></tsl:ElectronicAddress></tsl:TSPAddress><tsl:TSPInformationURI>".$row_o['TSPInformationURI']."</tsl:TSPInformationURI></tsl:TSPInformation>";
  $str=str_replace("><", ">\r\n<", $str);
  fwrite($file, $str);
  echo $str;
  $certs_ids=explode (",",$row_o['certs_ids']);
  for ($j=0; $j<count($certs_ids); $j++) {
    //echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $result_c = mysql_query("select * from certs where ID=".$certs_ids[$j]); 
    $row_c = mysql_fetch_assoc($result_c);
    $str="<tsl:TSPServices><tsl:ServiceInformation><tsl:ServiceStatusInformation><tsl:ServiceTypeIdentifier>1</tsl:ServiceTypeIdentifier><tsl:ServiceName><tsl:Name xml:lang=\"RU\">Выпуск и обслуживание ключей ЭЦП и сертификатов ключей подписей</tsl:Name></tsl:ServiceName><tsl:ServiceDigitalIdentity><tsl:digitalId><tsl:X509Certificate>".$row_c['X509Certificate']."</tsl:X509Certificate></tsl:digitalId></tsl:ServiceDigitalIdentity><tsl:ServiceStatus>".$row_c['ServiceStatus']."</tsl:ServiceStatus><tsl:StatusStartingTime>".$row_c['StatusStartingTime']."</tsl:StatusStartingTime></tsl:ServiceStatusInformation>";
    if (strlen($row_c['SchemeServiceDefinitionURI']) > 0) { $str=$str."<tsl:SchemeServiceDefinitionURI>".$row_c['SchemeServiceDefinitionURI']."</tsl:SchemeServiceDefinitionURI>";}
    $str=$str."</tsl:ServiceInformation><tsl:ServiceHistory></tsl:ServiceHistory></tsl:TSPServices>";
    //if ($row_c['org_id']==$row_o['ID']) {echo "TRUE";} 
      
      $str=str_replace("><", ">\r\n<", $str);
      echo $str;
      fwrite($file, $str);
  }
$str="</tsl:TrustServiceProvider>";
echo $str;
fwrite($file, $str);
}
echo "</tsl:TrustStatusList>";
fwrite($file, "</tsl:TrustStatusList>");
fclose($file);
?>
