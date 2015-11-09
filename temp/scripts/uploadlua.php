<!-- ISADMIN -->
<!-- TEMPLATE -->
<div class="contentBox">
<?php
if (move_uploaded_file(@$_FILES["uploadlua_file"]["tmp_name"], @$_FILES["uploadlua_file"]["name"])) {
	postErrOK(1, 600, "Die Datei wurde erfolgreich hochgeladen!");
	postRedirect(3, "index.php?page=parselua");
} else {
	postErrOK(0, 600, "Es trat ein Fehler auf!");
	postRedirect(3, "index.php?page=uploadlua");
}
?>
</div>
