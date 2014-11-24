<?
include './func.php';

if (isset($_POST['str'])){

  echo simple_detect_language($_POST['str']);
} else {
?>
<form method="post" action="<?=$_SERVER['SCRIPT_NAME']?>">
<input type="text" name="str"/>
<input type="submit" />
</form>
<?
}
?>
