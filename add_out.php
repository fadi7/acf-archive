<?php

use Ramsey\Uuid\Uuid;

if (isset($_SESSION["userid"]) == true  and $_SESSION["usertype"] == 0) {
  $soureces = getSources();

  AddContent("البريد الصادر", 'title', 0);
  AddContent("البريد الصادر", 'title2', 0);
  $code = <<<a
<script  type="text/javascript" src="./assets/js/typeahead.bundle.min.js"></script>
<script  type="text/javascript" src="./assets/js/bloodhound.min.js"></script>
getSource
<script type="text/javascript">
$( ".editT" ).removeClass( "sorting" );
var sources = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.whitespace,
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  local: [
$soureces
  ]
});

// passing in `null` for the `options` arguments will result in the default
// options being used
$('.typeahead').typeahead({
  hint: false
},{
  name: 'sources',
  source: sources
});
</script>
a;
  AddContent($code, "code", 0);
  if (isset($_GET["d"])) // الحذف
  {
    $id = addslashes($_GET["d"]);
    $d_sql = mysqli_query($con, "delete from box where id=$id");
    if ($d_sql) {
      $sql = mysqli_query($con, "select id,name from attach where b_id =$id  ");
      echo mysqli_error($con);
      if (mysqli_num_rows($sql) > 0)
        while ($res = mysqli_fetch_row($sql)) {
          $value = explode(".", $res[1]);
          $e = strtolower(array_pop($value));
          unlink("box/{$res[0]}.{$e}");
          mysqli_query($con, "delete from attach where id={$res[0]}");
        }
    }
  }
  if (isset($_POST["edit"])) // التعديل
  {
    $id = $_POST["id"];
    $bid = $_POST["b_id"];
    $des = $_POST["desc"];
    $date = $_POST["date"];
    $to = $_POST["from"];
    $s = "update box set b_id=$bid ,`desc`='$des' , `date`='$date',`from_to`='$to' where `id`=$id";
    $sql = mysqli_query($con, $s);
    $i = mysqli_insert_id($con);
    if (isset($_FILES["mm"]))
      if ($_FILES["mm"]["error"] == 0) {
        $tmp_name = $_FILES["mm"]["tmp_name"];
        $name = $_FILES["mm"]["name"];
        $value = explode(".", $name);
        $e = strtolower(array_pop($value));
        $sql = mysqli_query($con, "insert into attach values ('0',$id,'$name',0)");
        $f_id = mysqli_insert_id($con);
        move_uploaded_file($tmp_name, "box/$f_id" . "." . $e);
      }
    if (isset($_FILES["files"])) {
      $uploads_dir = 'box';
      foreach ($_FILES["files"]["error"] as $key => $error)
        if ($error == 0) {
          $tmp_name = $_FILES["files"]["tmp_name"][$key];
          $name = $_FILES["files"]["name"][$key];
          $n = explode(".", $name);
          $e = strtolower(array_pop($n));
          $sql = mysqli_query($con, "insert into attach values ('0',$id,'$name',1)");
          $f_id = mysqli_insert_id($con);
          move_uploaded_file($tmp_name, "$uploads_dir/$f_id" . "." . $e);
        }
    }
  }
  if (isset($_POST["insert"])) // الاضافة
  {

    $bid = $_POST["b_id"];
    $des = $_POST["desc"];
    $date = $_POST["date"];
    $type = 0;
    $from = $_POST["from"];
    $Syear = $_SESSION["year"];
    $bar = $_POST["barcode"];
    $uuid = Uuid::uuid4()->toString();
    $sql = mysqli_query($con, "insert into box values('0',$bid,'$des','1','$date','$from','0','$Syear','$uuid')"); //تغيير بعدين
    if (mysqli_error($con))
      AddContent("<br><p class='bg-info'>حدث خطأ في الادخال</p>", 'body', 1);
    $i = mysqli_insert_id($con);
    if ($i != 0) {
      if ($_FILES["mm"]["error"] == 0) {
        $tmp_name = $_FILES["mm"]["tmp_name"];
        $name = $_FILES["mm"]["name"];
        $value = explode(".", $name);
        $e = strtolower(array_pop($value));
        $sql = mysqli_query($con, "insert into attach values ('0',$i,'$name',0)");
        $f_id = mysqli_insert_id($con);
        move_uploaded_file($tmp_name, "box/$f_id" . "." . $e);
        if ($bar == 1)
          addBarcode($f_id, $uuid, $bid, $date, $Syear);
      }
      $uploads_dir = 'box';
      foreach ($_FILES["files"]["error"] as $key => $error) {
        if ($error == 0) {
          $tmp_name = $_FILES["files"]["tmp_name"][$key];
          $name = $_FILES["files"]["name"][$key];
          $value = explode(".", $name);
          $e = strtolower(array_pop($value));
          $sql = mysqli_query($con, "insert into attach values ('0',$i,'$name',1)");
          $f_id = mysqli_insert_id($con);
          move_uploaded_file($tmp_name, "$uploads_dir/$f_id" . "." . $e);
        }
      }
    }
  }

  $s = "SELECT * FROM `box` WHERE year='{$_SESSION['year']}' and type='1';";
  $sql = mysqli_query($con, $s); //تعليمة الجدول الرئيسي
  $b = <<<a
  <div class="row">
<div class="col-md-12">
  <div class="card card-mini">
        <div class="card-header">
          البريد الصادر 
        </div>
        <div class="card-body no-padding">
          <table class="datatable table table-striped primary inbox" cellspacing="0"  style="width: 100%; text-align:center"">
    <thead>
        <tr >
            <th style="width:2%">الرقم</th>
            <th  style="width:12%">التاريخ</th>
            <th style="width:%">الموضوع</th>
            <th style="width:11%">الجهة</th>
            <th style="width:11%">الملف الاساسي</th>
            <th style="width:12%" >المرفقات</th>		
            <th style="width:1%" class="editT"><i class='fa fa-edit' aria-hidden='true' style='font-size:20px'></th>
            <th style="width:1%" class="editT"><i class='fa fa-trash-o' aria-hidden='true' style='font-size:20px'></th>	
            <th style="width:1%" class="editT"><i class='fa fa-barcode' aria-hidden='true' style='font-size:19px'></th>							
        </tr>
    </thead>
    <tbody>
a;
  while ($res = mysqli_fetch_row($sql)) {
    $b .= <<<b
        <tr >
            <td >$res[1]</td>
            <td  >$res[4]</td>
			 <td  >$res[2]</td>
			  <td>$res[5]</td>
	            <td>
b;
    $s = mysqli_query($con, "select * from attach where b_id = $res[0] and type=0");
    if (mysqli_num_rows($s) == 1) {
      while ($r = mysqli_fetch_row($s)) {
        $value = explode(".", $r[2]);
        $e = strtolower(array_pop($value));
        $icon_f = ($e == "jpg" || $e == "jpeg" || $e == "png") ? "file-image-o" : (($e == "PDF" || $e == "pdf") ? "file-pdf-o" : (($e == "doc" || $e == "docx") ? "file-word-o" : "file-text-o"));
        $b .= "<a href='box/{$r[0]}.{$e}' target='_blank'><i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>";
      }
    } else
      $b .= "لا يوجد مرفقات";

    $b .= <<<b
</td>
<td>
b;
    $s = mysqli_query($con, "select * from attach where b_id = {$res[0]} and type=1");
    if (mysqli_num_rows($s) > 0) {
      $c = 0;
      while ($r = mysqli_fetch_row($s)) {
        $c++;
        $value = explode(".", $r[2]);
        $e = strtolower(array_pop($value));
        $icon_f = ($e == "jpg" || $e == "jpeg" || $e == "png") ? "file-image-o" : (($e == "PDF" || $e == "pdf") ? "file-pdf-o" : (($e == "doc" || $e == "docx") ? "file-word-o" : "file-text-o"));
        $b .= "<a href='box/{$r[0]}.{$e}' target='_blank'> <i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>&nbsp; &nbsp;";
      }
    } else
      $b .= "لا يوجد مرفقات";
    $b .= <<<ab
<th style="width:2%"><a href="?page=add_out&edit=ok&id=$res[0]" ><i class='fa fa-edit' aria-hidden='true' style='font-size:20px'></a></th>
<th style="width:2%" ><a  onclick="javascript:confirmDelete('?page=add_out&d=$res[0]')"><i class='fa fa-trash-o' aria-hidden='true' style='font-size:20px'></a></th>	
<th style="width:2%"><a href='barcode/index.php?id=$res[0]&num=$res[1]&date=$res[4]&type=2' target="black"><i class='fa fa-barcode' aria-hidden='true' style='font-size:19px'></a></th>
ab;
  }
  $b .= <<<abb
  
    

    </tbody>
</table>
        </div>
      </div>  </div>  </div>
	  <script>
function confirmDelete(delUrl) {
  if (confirm("هل انت متأكد من الحذف")) {
    document.location = delUrl;
  }}
</script>

abb;
  AddContent($b, "body", 0);


  if (isset($_GET["insert"])) {
    AddContent("اضافة بريد صادر جديد", 'title', 0);
    AddContent("اضافة بريد صادر جديد", 'title2', 0);
    $sql = mysqli_query($con, "select max(b_id)from box where type='1' and year = '{$_SESSION['year']}' ;");
    list($max) = mysqli_fetch_row($sql);
    $max++;
    $a = <<<a

		  <div class="row">
<div class="col-md-12">
      <div class="card card-mini">
        <div class="card-header">
          <div class="card-title"> اضافة صادر    <strong class="highligh">&nbsp;&nbsp; جديد &nbsp;&nbsp;</strong></div>
        </div>
        <div class="card-body">
		 <div class="section zz" style="">
                  <div class="section-body __indent ">
        <form id="submit_this" enctype="multipart/form-data" method="post"  action="?page=add_out" class="form-horizontal eform">
    <div class="form-group col-md-12">
<label class="col-md-2 control-label">الرقم :</label>
        <div class="col-md-6 ">
          <input type="input" class="form-control"  value="{$max}" name="b_id">
      </div>
        </div>
    <div class="form-group col-md-12" >
<label class="col-md-2 control-label">التاريخ :</label>
        <div class="col-md-6 ">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='date' class="form-control" required name="date" value="" style="text-align:center;"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
      </div>
        </div>		
    <div class="form-group col-md-12">
<label class="col-md-2 control-label">الموضوع :</label>
        <div class="col-md-6 ">
		<textarea  rows="3" class="form-control" name="desc" placeholder="اكتب موضوع الصادر ..." ></textarea>
      </div>
        </div>
		    <div class="form-group col-md-12">
<label class="col-md-2 control-label" >الجهة :</label>
        <div class="col-md-6 ">
          <input type="input" class="typeahead form-control" style="margin-bottom: 1px;"  name="from" placeholder=" الجهة" required>
      </div>
        </div>
 <div class="form-group col-md-12">
<label class="col-md-2 control-label">اضافة باركود :</label>
        <div class="col-md-6 ">
  <div class="radio radio-inline">
          <input type="radio" name="barcode" id="radio5" value="1" checked>
          <label for="radio5">
              نعم
          </label>
      </div>      <div class="radio radio-inline">
          <input type="radio" name="barcode" id="radio6" value="0" >
          <label for="radio6">
              لا
          </label>
      </div>
      </div>
        </div>
<div class="form-group col-md-12">
<label class="col-md-2 control-label">الملف الرئيسي :</label>
        <div class="col-md-6 ">
<input type="file" class="form-control"  name='mm' >
      </div>
        </div>
<div class="form-group col-md-12">
<label class="col-md-2 control-label">المرفقات :</label>
        <div class="col-md-6 ">
		<div class="list-group">
<input type="file" class="form-control" name='files[]' multiple="" id="filesToUpload"  onChange="makeFileList();">
</div>
      </div>
        </div>
				  </div>
                </div>   
				<div style="text-align:center">
  <input type='hidden' value="موافق" name='insert'>  <button type="submit" class="btn  btn-success "  id="submita" >موافق</button>
            <button type="button" class="btn  btn-default" data-dismiss="modal" onclick="javascript:window.location='?page=add_out'">إلغاء</button>&nbsp; 
</form>				
              </div></div>
     </div>
      </div>
    </div>
a;
    AddContent($a, "body", 0);
  }
  if (isset($_GET["edit"])) // edit Form
  {
    if (isset($_GET["fd"])) {
      $file = $_GET["fd"];
      $ex = $_GET["e"];
      mysqli_query($con, "delete from attach where id={$file}");
      unlink("box/{$file}.{$ex}");
    }
    $id = addslashes($_GET["id"]);
    AddContent('', "links2", 0);
    AddContent("تعديل بريد صادر", 'title', 0);
    AddContent("تعديل بريد صادر", 'title2', 0);
    $a = "select b_id,`desc`,date,from_to from box where id=$id";

    $sql = mysqli_query($con, $a);
    list($bid, $desc, $date, $to) = mysqli_fetch_row($sql);
    $a = <<<a
		  <div class="row">
<div class="col-md-12">
      <div class="card card-mini">
        <div class="card-header">
          <div class="card-title"> تعديل الصادر رقم   <strong class="highligh">&nbsp;&nbsp; $bid &nbsp;&nbsp;</strong></div>

        </div>
        <div class="card-body">
		 <div class="section zz" style="">
                  <div class="section-body __indent ">
        <form id="submit_this" enctype="multipart/form-data" method="post"  action="?page=add_out" class="form-horizontal eform">
<input type="hidden" value='$id'  name="id">
<input type="hidden" value='تعديل'  name="edit">
    <div class="form-group col-md-12">
<label class="col-md-2 control-label">الرقم :</label>
        <div class="col-md-6 ">
          <input type="input" class="form-control" value="$bid" name="b_id">
      </div>
        </div>
    <div class="form-group col-md-12" >
<label class="col-md-2 control-label">التاريخ :</label>
        <div class="col-md-6 ">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='date' class="form-control" name="date" value="$date" style="text-align:center;"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
      </div>
        </div>		
    <div class="form-group col-md-12">
<label class="col-md-2 control-label">الموضوع :</label>
        <div class="col-md-6 ">
		<textarea  rows="3" class="form-control" name="desc">$desc</textarea>
      </div>
        </div>
    <div class="form-group col-md-12">
<label class="col-md-2 control-label">جهة الورود :</label>
        <div class="col-md-6 ">
          <input  type="hidden" class="from"><input type="text" name="from" id="from" style="margin-bottom: 1px;" type="input" class="typeahead form-control" value="$to" >
      </div>
        </div>
		 
a;
    $sql = mysqli_query($con, "select id,name from attach where b_id=$id and type=0; ");
    if (mysqli_num_rows($sql) == 1) {
      list($fid, $name) = mysqli_fetch_array($sql);
      $n = explode(".", $name);
      $e = strtolower(array_pop($n));   //Line 32
      $a .= <<<a
<div class="form-group col-md-12">
<label class="col-md-2 control-label">الملف الرئيسي :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="point" onclick="window.open('box/$fid.$e', '_blank')"> الملف الرئيسي </span><span onclick="confirmDelete('?page=add_out&fd=$fid&edit=ok&id=$id&e=$e')" class="badge" style="float:left;">
		<span class="point glyphicon glyphicon-trash"></span></span>
      </a>
    </div>
      </div>
        </div>
a;
    } else
      $a .= <<<a
<div class="form-group col-md-12">
<label class="col-md-2 control-label">الملف الرئيسي :</label>
        <div class="col-md-6 ">
<input type="file" class="form-control"  name='mm' accept="">
      </div>
        </div>
a;

    $sql = mysqli_query($con, "select id,name from attach where b_id=$id and type=1; ");
    $a .= <<<a
<div class="form-group col-md-12">
<label class="col-md-2 control-label">المرفقات :</label>
        <div class="col-md-6 ">
		<div class="list-group">
a;
    if (mysqli_num_rows($sql) == 0)
      $a .= <<<a
      <a  class="list-group-item list-group-item-warning f">
لا يوجد مرفقات
      </a>
a;
    $n = 1;
    while ($res = mysqli_fetch_row($sql)) {

      $arr = explode(".", $res[1]);
      $e = strtolower(array_pop($arr));

      $a .= <<<a

      <a  class="list-group-item f">
        <span class="point" onclick="window.open('box/{$res[0]}.$e', '_blank')"> المرفق $n </span><span onclick="confirmDelete('?page=add_out&fd={$res[0]}&edit=ok&id=$id&e=$e')" class="badge" style="float:left;">
		<span class="point glyphicon glyphicon-trash"></span></span>
      </a>
a;
      $n++;
    }
    $a .= <<<a
<input type="file" class="form-control" name='files[]' multiple="" id="filesToUpload"  onChange="makeFileList();">
</div>
      </div>
        </div>
				  </div>
                </div>   
				<div style="text-align:center">
 <button type="submit" class="btn  btn-success "  id="submita" >تعديل</button>
            <button type="button" class="btn  btn-default" data-dismiss="modal" onclick="javascript:window.location='?page=add_out'">إلغاء</button>&nbsp; 
</form>				
              </div></div>
     </div>
      </div>
    </div>
<script>
function confirmDelete(delUrl) {
  if (confirm("هل انت متأكد من الحذف")) {
    document.location = delUrl;
  }
}
</script>	
a;
    AddContent($a, "body", 0);
  }
} elseif (isset($_SESSION["userid"]) == true  and $_SESSION["usertype"] == 1) {
  AddContent("البريد الصادر", 'title', 0);
  AddContent("البريد الصادر", 'title2', 0);
  $s = "SELECT * FROM `box` WHERE year='{$_SESSION['year']}' and type='1';";
  $sql = mysqli_query($con, $s); //تعليمة الجدول الرئيسي
  $b = <<<a
  <div class="row">
<div class="col-md-12">
  <div class="card card-mini">
        <div class="card-header">
          البريد الصادر 
        </div>
        <div class="card-body no-padding">
          <table class="datatable table table-striped primary inbox" cellspacing="0"  style="width: 100%; text-align:center"">
    <thead>
        <tr >
            <th style="width:2%">الرقم</th>
            <th  style="width:12%">التاريخ</th>
            <th style="width:%">الموضوع</th>
            <th style="width:11%">جهة الورود</th>
            <th style="width:11%">الملف الاساسي</th>
            <th style="width:12%" >المرفقات</th>		
			
        </tr>
    </thead>
    <tbody>
a;
  while ($res = mysqli_fetch_row($sql)) {
    $b .= <<<b
        <tr >
            <td >$res[1]</td>
            <td >$res[4]</td>
			 <td>$res[2]</td>
			  <td>$res[5]</td>
	            <td>
b;
    $s = mysqli_query($con, "select * from attach where b_id = $res[0] and type=0");
    if (mysqli_num_rows($s) == 1) {
      while ($r = mysqli_fetch_row($s)) {
        $value = explode(".", $r[2]);
        $e = strtolower(array_pop($value));
        $icon_f = ($e == "jpg" || $e == "jpeg" || $e == "png") ? "file-image-o" : (($e == "PDF" || $e == "pdf") ? "file-pdf-o" : (($e == "doc" || $e == "docx") ? "file-word-o" : "file-text-o"));

        $b .= "<a href='box/{$r[0]}.{$e}' target='_blank'><i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>";
      }
    } else
      $b .= "لا يوجد مرفقات";

    $b .= <<<b
</td>
<td>
b;
    $s = mysqli_query($con, "select * from attach where b_id = {$res[0]} and type=1");
    if (mysqli_num_rows($s) > 0) {
      $c = 0;
      while ($r = mysqli_fetch_row($s)) {
        $c++;
        $value = explode(".", $r[2]);
        $e = strtolower(array_pop($value));
        $icon_f = ($e == "jpg" || $e == "jpeg" || $e == "png") ? "file-image-o" : (($e == "PDF" || $e == "pdf") ? "file-pdf-o" : (($e == "doc" || $e == "docx") ? "file-word-o" : "file-text-o"));

        $b .= "<a href='box/{$r[0]}.{$e}' target='_blank'><i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>";
      }
    } else
      $b .= "لا يوجد مرفقات";
  }
  $b .= <<<abb
  
    

    </tbody>
</table>
        </div>
      </div>  </div>  </div>


abb;
  AddContent($b, "body", 0);
} else
  include("main.php");
