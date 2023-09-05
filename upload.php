<?php
if(is_array($_FILES)) {
if(is_uploaded_file($_FILES['userImage']['tmp_name'])) {
	include("functions.php");
$sourcePath = $_FILES['userImage']['tmp_name'];
$id=$_POST["id"];
$name=$_FILES['userImage']['name'];
$value = explode(".", $name);
   $e = strtolower(array_pop($value));
   $file="$id.$e";
changeProfile($id,$file);
$targetPath = "profile/".$id.".".$e;
if(move_uploaded_file($sourcePath,$targetPath)) {
?>
<img class="img-responsive"  src="<?php echo $targetPath; ?>" class="upload-preview" />
<script>

$("#uploadFormLayer").html(" <div><div  class=\"btn btn-danger btn-xs\" role=\"button\"  onClick=\"deleteImage('<?php echo $targetPath; ?>')\"> <i class=\"icon fa fa-times-circle\" aria-hidden=\"true\"></I> &nbsp;   إزالة</div></div>");
</script>
<?php
}
}
}
?>