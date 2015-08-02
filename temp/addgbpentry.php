<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<div class="outerMargin">
<form name="addgbpentry_form" action="?page=scripts/addgbpentry" method="post" enctype="multipart/form-data">
<table class="myTable" width="600px">
<colgroup>
<col width="200px">
<col width="400px">
</colgroup>
<tr>
<th colspan="2">Bitte f&uuml;llen Sie die erforderlichen Daten aus:</th>
</tr>
<tr>
<td>Eintrag-Typ:</td>
<td>
<select class="myInput" name="addgbpentry_type" size="1">
<option>Einlagern</option>
<option>Auslagern</option>
</select>
</td>
</tr>
<tr class="myTableAlt">
<td>Mitglied:</td>
<td><input class="myInput" type="text" name="addgbpentry_name"></td>
</tr>
<tr>
<td>Wert:</td>
<td><input class="myInput" type="text" name="addgbpentry_points"></td>
</tr>
<tr class="myTableAlt">
<td>Bemerkung:</td>
<td><input class="myInput" type="text" name="addgbpentry_info"></td>
</tr>
<tr class="myTableFooter">
<td class="" colspan="2"><input type="submit" name="addgbpentry_submit" value="Daten absenden"></td>
</tr>
</table>
</form>
</div>
</div>