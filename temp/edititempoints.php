<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
$tempid = toSaferValue(@$_GET["id"]);
$temppoints = 0;
$result = mysql_query("SELECT * FROM ".$databasename.".".$tableprefix."itempoints WHERE itemid = ".$tempid);
while ($row = @mysql_fetch_assoc($result)) {
	$temppoints = $row["points"];
}
@mysql_free_result($result);
?>
<div class="outerMargin">
<form name="editgbpentry_form" action="?page=scripts/edititempoints" method="post" enctype="multipart/form-data">
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
<td><input class="myInput" type="text" name="edititempoints_id" value="<?php echo $tempid;?>"></td>
</tr>
<tr class="myTableAlt">
<td>Punkte:</td>
<td><input class="myInput" type="text" name="edititempoints_points" value="<?php echo $temppoints;?>"></td>
</tr>
<tr class="myTableFooter">
<td class="" colspan="2"><input type="submit" name="edititempoints_submit" value="Daten absenden"></td>
</tr>
</table>
</form>
</div>
</div>