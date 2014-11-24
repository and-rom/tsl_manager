<?
function simple_detect_language( $text ) {
 
$detectLang = array(
'UKR'=>array( 'і', 'ї', 'є', 'її', 'цьк', 'ськ', 'ія', 'ння', 'ій', 'ися', 'ись' ),
'RU'=>array( 'ы', 'э', 'ё', 'ъ', 'ее', 'её', 'цк', 'ск', 'ия', 'сс', 'ую', 'ение' ),
'EN'=>array( ' and ', 'ing', 'ion', ' or ', ' if ', ' in ', ' a ', ' the ', ' in ', ' on ', ' as ' ),
);
 
# Get chars presence index
$langsDetected = array();
foreach( $detectLang as $langId=>$nativeChars ) {
$langsDetected[$langId] = 0;
foreach( $nativeChars as $nativeChr )
if( preg_match( "/$nativeChr/ui", $text ) )
$langsDetected[$langId] += 1 / count( $nativeChars );
}
 
# Get the most preferred language for this text
$langsList = array_keys( $detectLang );
$lang = null;
$langIndexMax = 0;
foreach( $langsDetected as $langId=>$index )
if( $index > $langIndexMax ) {
$langIndexMax = $index;
$lang = $langId;
}
 
return $lang;
 
}
function org2table ($row) {
	print "
	<table>
	<tr>
	  <td>TSPName</td>
	  <td>".$row['TSPName']."</td>
	</tr>
	<tr>
	  <td>TSPTradeName</td>
	  <td>".$row['TSPTradeName']."</td>
	</tr>
	<tr>
	  <td>Почтовый адрес</td>
	  <td>".$row['TSPPostalCode'].", ".$row['TSPCountryName'].", ".$row['TSPLocality'].", ".$row['TSPStreetAddress']."</td>
	</tr>
	<tr>
	  <td>Электронный адрес службы поддержки (e-mail, www)</td>
	  <td>".$row['TSPURN']."</td>
	</tr>
	<tr>
	  <td>Официальный сайт</td>
	  <td><a href=\"".$row['TSPInformationURI']."\">".$row['TSPInformationURI']."</a></td>
	</tr>
	</table>
	";
}
function cert2table ($row) {
	print "
	<table>
	<tr>
	  <td>Сертификат</td>
	  <td><a href=\"getcert.php?id=".$row['ID']."\">Скачать</a></td>
	</tr>
	<tr>
	  <td>Точка распространения списка отозванных сертификатов (CDP)</td>
	  <td><a href=\"".$row['SchemeServiceDefinitionURI']."\">".$row['SchemeServiceDefinitionURI']."</a></td>
	</tr>
	<tr>
	  <td>Тип услуги</td>
	  <td>Издание сертификатов открытых ключей</td>
	</tr>
	<tr>
	  <td>Статус услуги</td>";
	if ($row['ServiceStatus']==1) {
	  print "<td>Действует</td>";
	} elseif ($row['ServiceStatus']==2){
	  print "<td>Не действует</td>";
    }	else {
print "<td>Сатус неизвестен</td>";
}
	print "
	</tr>
	<tr>
	  <td>Дата начала действия статуса услуги</td>
	  <td>".$row['StatusStartingTime']."</a></td>
	</tr>
	</table>
	";
}
?>
