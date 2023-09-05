<?php
if(!empty($_POST["path"])) {
	unlink($_POST["path"]);
}
?>