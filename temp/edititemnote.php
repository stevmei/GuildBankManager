<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$tempid = toSaferValue(@$_GET["id"]);
$temppoints = 0;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."itemnotes WHERE itemid = ".$tempid);
while ($row = @mysql_fetch_assoc($result)) {
	$tempnote = $row["itemnote"];
}
@mysql_free_result($result);
?>
<div class="outerMargin">
<form name="edititemnote_form" action="?page=scripts/edititemnote" method="post" enctype="multipart/form-data">
<table class="myTable" width="600px">
<colgroup>
<col width="200px">
<col width="400px">
</colgroup>
<tr>
<th colspan="2">Bitte f&uuml;llen Sie die erforderlichen Daten aus:</th>
</tr>
<tr>
<td>Item-ID:</td>
<td><input class="myInput" type="text" name="edititemnote_id" value="<?php echo $tempid;?>"></td>
</tr>
<tr class="myTableAlt">
<td>Notiz:</td>
<td><input class="myInput" type="text" name="edititemnote_note" value="<?php echo $tempnote;?>"></td>
</tr>
<tr class="myTableFooter">
<td class="" colspan="2"><input type="submit" name="edititemnote_submit" value="Daten absenden"></td>
</tr>
</table>
</form>
</div>
</div>
