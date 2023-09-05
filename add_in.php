<?php
if(isset($_SESSION["userid"])==true  and $_SESSION["usertype"]==0)
{
	$soureces=getSources();

$code=<<<a

  <script  type="text/javascript" src="./assets/js/typeahead.bundle.min.js"></script>
<script  type="text/javascript" src="./assets/js/bloodhound.min.js"></script>

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
   AddContent($code,"code",0);	
if(isset($_POST["add_c"])) // اعتماد الملاحظات
    {
$id=$_POST['id'];
        $sql1=mysqli_query($con,"update box set ready='2' where id=$id ;");
			include("links.php");
    }
	 if(isset($_GET["d"])) // الحذف
{
$id=addslashes($_GET["d"]);
$d_sql=mysqli_query($con,"delete from box where id=$id");
if($d_sql)
{
$sql=mysqli_query($con,"select id,name from attach where b_id =$id  ");
echo mysqli_error($con);
if(mysqli_num_rows($sql)>0)
while($res=mysqli_fetch_row($sql))
{
$value = explode(".", $res[1]);
   $e = strtolower(array_pop($value));
unlink("box/{$res[0]}.{$e}");
mysqli_query($con,"delete from attach where id={$res[0]}");}
}
}
if(isset($_POST["edit"])) // التعديل
{
$id=$_POST["id"];
$bid=$_POST["b_id"];
$des=$_POST["desc"];
$date=$_POST["date"];
$to=$_POST["from"];
$s="update box set b_id=$bid ,`desc`='$des' , `date`='$date',`from_to`='$to' where `id`=$id";
$sql=mysqli_query($con,$s);
$i=mysqli_insert_id($con);
if(isset($_FILES["mm"]))
if($_FILES["mm"]["error"]==0)
{      
$tmp_name= $_FILES["mm"]["tmp_name"];
$name = $_FILES["mm"]["name"];
$value = explode(".", $name);
   $e = strtolower(array_pop($value));
$sql=mysqli_query($con,"insert into attach values ('0',$id,'$name',0)");
                     $f_id=mysqli_insert_id($con);
move_uploaded_file($tmp_name, "box/$f_id".".".$e);
}
if(isset($_FILES["files"]))
{
    $uploads_dir = 'box';
foreach ($_FILES["files"]["error"] as $key => $error)
    if ($error == 0) {
        $tmp_name = $_FILES["files"]["tmp_name"][$key];
        $name = $_FILES["files"]["name"][$key];
		 $n = explode(".", $name);
    $e = strtolower(array_pop($n)); 
        $sql=mysqli_query($con,"insert into attach values ('0',$id,'$name',1)");
        $f_id=mysqli_insert_id($con);
        move_uploaded_file($tmp_name, "$uploads_dir/$f_id".".".$e);
    }
}
}
if(isset($_POST["insert"])) // الاضافة
{
$bid=$_POST["b_id"];
$des=$_POST["desc"];
$date=$_POST["date"];
$type=0;
$from=$_POST["from"];
$Syear= $_SESSION["year"];
$sql=mysqli_query($con,"insert into box values('0',$bid,'$des','0','$date','$from','0','$Syear')");//تغيير بعدين
if(mysqli_error($con))
    AddContent("<br><p class='bg-info'>حدث خطأ في الادخال</p>",'body',1);
$i=mysqli_insert_id($con);
if($i!=0)
{
if($_FILES["mm"]["error"]==0 )
{ 
$tmp_name= $_FILES["mm"]["tmp_name"];
$name = $_FILES["mm"]["name"];
$value = explode(".", $name);
   $e = strtolower(array_pop($value));
$sql=mysqli_query($con,"insert into attach values ('0',$i,'$name',0)");
$f_id=mysqli_insert_id($con);
move_uploaded_file($tmp_name, "box/$f_id".".".$e);
}
$uploads_dir = 'box';
foreach ($_FILES["files"]["error"] as $key => $error) {
    if ($error == 0) {
        $tmp_name = $_FILES["files"]["tmp_name"][$key];
        $name = $_FILES["files"]["name"][$key];
		$value = explode(".", $name);
   $e = strtolower(array_pop($value));
        $sql=mysqli_query($con,"insert into attach values ('0',$i,'$name',1)");
        $f_id=mysqli_insert_id($con);
        move_uploaded_file($tmp_name, "$uploads_dir/$f_id".".".$e);
    }
}
}
}
AddContent("البريد الوارد",'title',0);
 AddContent("البريد الوارد",'title2',0);
	if(isset($_GET["new"])=="ok") // الكتب التي بجاجة لملاحظات
	{$s="SELECT * FROM `box` WHERE year='{$_SESSION['year']}' and type='0'  and ready='1';";
	AddContent("البريد الوارد الجديد",'title',0);
 AddContent("البريد الوارد الجديد",'title2',0);
	}else
		$s="SELECT * FROM `box` WHERE year='{$_SESSION['year']}' and type='0';";
$sql=mysqli_query($con,$s); //تعليمة الجدول الرئيسي

$b=<<<a
  <div class="row">
<div class="col-md-12">
  <div class="card  card-mini">
        <div class="card-header">
          البريد الوارد 
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
            <th style="width:1%" class="editT"><i class='fa fa-edit' aria-hidden='true' style='font-size:20px'></th>
            <th style="width:1%" class="editT"><i class='fa fa-trash-o' aria-hidden='true' style='font-size:20px'></th>	
            <th style="width:1%" class="editT"><i class='fa fa-barcode' aria-hidden='true' style='font-size:19px'></th>				
            <th style="width:1%" class="editT"><i class='fa fa-info' aria-hidden='true'></i></th>				
        </tr>
    </thead>
    <tbody>
a;
while($res=mysqli_fetch_row($sql)) 
{	
	$b.=<<<b
        <tr >
            <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[1]</td>
            <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[4]</td>
			 <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[2]</td>
			  <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[5]</td>
	            <td>
b;
$s=mysqli_query($con,"select * from attach where b_id = $res[0] and type=0");
if(mysqli_num_rows($s)==1)
{
while($r=mysqli_fetch_row($s))
{
$value = explode(".", $r[2]);
   $e = strtolower(array_pop($value));
$icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));
$b.="<a href='box/{$r[0]}.{$e}' target='_blank'><i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>";
}
}
else
$b.="لا يوجد مرفقات";

$b.=<<<b
</td>
<td>
b;
$s=mysqli_query($con,"select * from attach where b_id = {$res[0]} and type=1");
if(mysqli_num_rows($s)>0)
{
$c=0;
while($r=mysqli_fetch_row($s))
{
$c++;
$value = explode(".", $r[2]);
   $e = strtolower(array_pop($value));
$icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));
$b.="<a href='box/{$r[0]}.{$e}' target='_blank'> <i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>&nbsp; &nbsp;";
}
}
else
$b.="لا يوجد مرفقات";
$b.=<<<ab
<th style="width:2%"><a href="?page=add_in&edit=ok&id=$res[0]" ><i class='fa fa-edit' aria-hidden='true' style='font-size:20px'></a></th>
<th style="width:2%" ><a  onclick="javascript:confirmDelete('?page=add_in&d=$res[0]')"><i class='fa fa-trash-o' aria-hidden='true' style='font-size:20px'></a></th>	
<th style="width:2%"><a href='barcode/index.php?id=$res[0]&num=$res[1]&date=$res[4]' target="black"><i class='fa fa-barcode' aria-hidden='true' style='font-size:19px'></a></th>
ab;
if($res[6]==1)
$b.=<<<ab
<td><span class="badge badge-warning badge-icon"><i class="fa fa-clock-o" aria-hidden="true"></i></span></td>
ab;
elseif($res[6]==0)
$b.="<td><span class='badge badge-info badge-icon'><i class='fa fa-reply' aria-hidden='true'></i></span></td>";
elseif($res[6]==2)
$b.="<td><span class='badge badge-success badge-icon'><i class='fa fa-check' aria-hidden='true'></i></span></td>";

}
$b.=<<<abb
  
    

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
   AddContent($b,"body",0);	 


if(isset($_GET['com'])) //مشاهدة الملاحظات
{
    $id=addslashes($_GET['id']);
    $d="SELECT * FROM `box` WHERE id=$id  and year='{$_SESSION['year']}' ; ";
$sql=mysqli_query($con,$d);
list($id,$bid,$desc,$type,$date,$f)=mysqli_fetch_row($sql);
$s=mysqli_query($con,"select * from attach where b_id = $id and type=0");
if(mysqli_num_rows($s)==1)
{
while($r=mysqli_fetch_row($s))
{
 $value=$r[2];
 $value = explode(".", $value);
   $e = strtolower(array_pop($value));   //Line 32
   $icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));
$b=<<<a

      <a href='box/{$r[0]}.{$e}' target='_blank' class="list-group-item f">
        <span class="point" > الملف الرئيسي </span><span class="badge" style="float:;">
		<i  class='fa fa-$icon_f' ></i></span>
      </a>
a;
}
}
else
$b=<<<a
     <a  class="list-group-item f">
        <span class="" > لايوجد ملف رئيسي </span>
      </a>";
a;
	  $bb=<<<b

b;
 $s=mysqli_query($con,"select * from attach where b_id = $id and type=1");
if(mysqli_num_rows($s)>0)
{
$c=0;
while($r=mysqli_fetch_row($s))
{
$c++;
 $arr = explode(".", $r[2]);
    $e = strtolower(array_pop($arr));
   $icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));
	
$bb.=<<<a
      <a href='box/{$r[0]}.{$e}' target='_blank' class="list-group-item f">
        <span class="point" > المرفق  $c </span><span class="badge" style="float:;">
		<span class='fa fa-$icon_f'></span></span>
      </a>
a;
}
}
else
$bb=<<<z
     <a  class="list-group-item f list-group-item-warning">
        <span class="" > لايوجد ملف مرفقات </span>
      </a>
z;
	  $sql=mysqli_query($con,"select * from comments where b_id=$id");
    $come="";
if(mysqli_num_rows($sql)>0)
{
    while($res=mysqli_fetch_row($sql))
        {
            $u_id=$res[3];
            $ss=mysqli_query($con,"select name,photo from users where id = $u_id;");
            echo mysqli_error($con);
            $resault=mysqli_fetch_row($ss);
			$date = date_create($res[4]);
	$date= date_format($date, 'Y-m-d ');			
        $come.=<<<a
 <div class="media social-post list-group-item col-md-8">
  <div class="media-left">
    <a href="#">
      <img src="profile/{$resault[1]}" />
    </a>
  </div>
  <div class="media-body">
    <div class="media-heading">
      <h4 class="title" style="font-size:14px;">{$resault[0]}</h4>
      <h5 class="timeing">$date</h5>
    </div>
    <div class="media-content">$res[1]</div>
  </div>
</div>
a;
        }
    }
	else 
	 $come.=<<<a
	 	<div class="form-group col-md-8 form-horizontal eform">
	 		<div class="list-group">
	      <a  class="list-group-item f list-group-item-warning">
        <span class="" >لا يوجد ملاحظات </span>
      </a></div></div>
	 
	 

a;
$a=<<<a

		
		  <div class="row">
<div class="col-md-12">
      <div class="card card-mini">
        <div class="card-header">
          <div class="card-title"> الوارد رقم   <strong class="highligh">&nbsp;&nbsp; $bid &nbsp;&nbsp;</strong></div>
          <ul class="card-action">
            <li class="dropdown">
              <a href="/" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-cogs" aria-hidden="true" style="font-size:20px;"></i>
              </a>
              <ul class="dropdown-menu">
                <li><a href="#">تعديل</a></li>
                <li><a href="#">حذف</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="card-body">
		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">التاريخ :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $date </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-calendar"></span></span>
      </a>
    </div>
      </div>
        </div>
		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">الجهة :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $f </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-flag"></span></span>
      </a>
    </div>
      </div>
        </div>
				<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">الموضوع :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $desc </span><span class="badge" style="float:;">
		<span class="glyphicon glyphicon-edit"></span></span>
      </a>
    </div>
      </div>
        </div>
						<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">الملف الرئيسي :</label>
        <div class="col-md-6 ">
		<div class="list-group">
$b
    </div>
      </div>
        </div>
					<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">المرفقات :</label>
        <div class="col-md-6 ">
		<div class="list-group">
     $bb 
    </div>
      </div>
        </div>   
           <div class="section col-md-12 ">
                  <div class="section-title col-md-12"><i class="icon fa fa-comments-o" aria-hidden="true"> </i><b> الملاحظات </b></div>
                  <div class="section-body __indent">
	  $come
                </div>
				
                </div>	
				<div style="text-align:center">
<form id="submit_this" action='?page=add_in' method='post'><input type='hidden' value="$id" name='id'>
<input type='hidden' value="موافق" name='add_c'>            <button type="submit" class="btn  btn-success "  id="submita" >موافق</button>
            <button type="button" class="btn  btn-default" data-dismiss="modal" onclick="javascript:window.location='?page=add_in'">إلغاء</button>&nbsp; 
</form>				
              </div></div>
     </div>
      </div>
    </div>		
a;
AddContent($a,"body",0);
}
if(isset($_GET["insert"]))
{
             AddContent("إضافة بريد وارد جديد",'title',0);
             AddContent("إضافة بريد وارد جديد",'title2',0);
$sql=mysqli_query($con,"select max(b_id)from box where type='0' and year = '{$_SESSION['year']}' ;");
list($max)=mysqli_fetch_row($sql);
$max++;
$a=<<<a

		  <div class="row">
<div class="col-md-12">
      <div class="card card-mini">
        <div class="card-header">
          <div class="card-title"> اضافة وارد    <strong class="highligh">&nbsp;&nbsp; جديد &nbsp;&nbsp;</strong></div>
        </div>
        <div class="card-body">
		 <div class="section zz" style="">
                  <div class="section-body __indent ">
        <form id="submit_this" enctype="multipart/form-data" method="post"  action="?page=add_in" class="form-horizontal eform">
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
                    <input type='date' class="form-control" name="date" value="" style="text-align:center;"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
      </div>
        </div>		
    <div class="form-group col-md-12">
<label class="col-md-2 control-label">الموضوع :</label>
        <div class="col-md-6 ">
		<textarea  rows="3" class="form-control" name="desc" placeholder="اكتب موضوع الوارد ..." ></textarea>
      </div>
        </div>
    <div class="form-group col-md-12">
<label class="col-md-2 control-label">جهة الورود :</label>
        <div class="col-md-6 " id="prefetch">
<input type="text" name="from" id="from" type="input" style="margin-bottom: 1px;" class="typeahead form-control" placeholder=" الجهة" >
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
            <button type="button" class="btn  btn-default" data-dismiss="modal" onclick="javascript:window.location='?page=add_in'">إلغاء</button>&nbsp; 
</form>				
              </div></div>
     </div>
      </div>
    </div>
a;
AddContent($a,"body",0);
}
if(isset($_GET["edit"])) // edit Form
{
    if(isset($_GET["fd"]))
    {
$file=$_GET["fd"];
$ex=$_GET["e"];        
mysqli_query($con,"delete from attach where id={$file}");
unlink("box/{$file}.{$ex}");
        }
    $id=addslashes($_GET["id"]);
       AddContent('',"links2",0);
             AddContent("تعديل بريد وارد",'title',0);
             AddContent("تعديل بريد وارد",'title2',0);
$a="select b_id,`desc`,date,from_to from box where id=$id";

$sql=mysqli_query($con,$a);
list($bid,$desc,$date,$to)=mysqli_fetch_row($sql);
$a=<<<a
		  <div class="row">
<div class="col-md-12">
      <div class="card card-mini">
        <div class="card-header">
          <div class="card-title"> تعديل الوارد رقم   <strong class="highligh">&nbsp;&nbsp; $bid &nbsp;&nbsp;</strong></div>

        </div>
        <div class="card-body">
		 <div class="section zz" style="">
                  <div class="section-body __indent ">
        <form id="submit_this" enctype="multipart/form-data" method="post"  action="?page=add_in" class="form-horizontal eform">
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
          <input  type="hidden" class="from"><input type="text" name="from" id="from" type="input" style="margin-bottom: 1px;" class="typeahead form-control" value="$to" >
      </div>
        </div>
		 
a;
$sql=mysqli_query($con,"select id,name from attach where b_id=$id and type=0; ");
if(mysqli_num_rows($sql)==1)
{
    list($fid,$name)=mysqli_fetch_array($sql);
 $n = explode(".", $name);
   $e = strtolower(array_pop($n));   //Line 32
   
$a.=<<<a
<div class="form-group col-md-12">
<label class="col-md-2 control-label">الملف الرئيسي :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="point" onclick="window.open('box/$fid.$e', '_blank')"> الملف الرئيسي </span><span onclick="confirmDelete('?page=add_in&fd=$fid&edit=ok&id=$id&e=$e')" class="badge" style="float:left;">
		<span class="point glyphicon glyphicon-trash"></span></span>
      </a>
    </div>
      </div>
        </div>
a;
}
else
$a.=<<<a
<div class="form-group col-md-12">
<label class="col-md-2 control-label">الملف الرئيسي :</label>
        <div class="col-md-6 ">
<input type="file" class="form-control"  name='mm' accept="">
      </div>
        </div>
a;

$sql=mysqli_query($con,"select id,name from attach where b_id=$id and type=1; ");
$a.=<<<a
<div class="form-group col-md-12">
<label class="col-md-2 control-label">المرفقات :</label>
        <div class="col-md-6 ">
		<div class="list-group">
a;
if(mysqli_num_rows($sql)==0)
$a.=<<<a
      <a  class="list-group-item list-group-item-warning f">
لا يوجد مرفقات
      </a>
a;
$n=1;
while($res=mysqli_fetch_row($sql))
{

 $arr = explode(".", $res[1]);
    $e = strtolower(array_pop($arr)); 
  
$a.=<<<a

      <a  class="list-group-item f">
        <span class="point" onclick="window.open('box/{$res[0]}.$e', '_blank')"> المرفق $n </span><span onclick="confirmDelete('?page=add_in&fd={$res[0]}&edit=ok&id=$id&e=$e')" class="badge" style="float:left;">
		<span class="point glyphicon glyphicon-trash"></span></span>
      </a>
a;
$n++;
}
$a.=<<<a
<input type="file" class="form-control" name='files[]' multiple="" id="filesToUpload"  onChange="makeFileList();">
</div>
      </div>
        </div>
				  </div>
                </div>   
				<div style="text-align:center">
<form id="submit_this" action='?page=add_in' method='post'><input type='hidden' value="$id" name='id'>
           <button type="submit" class="btn  btn-success "  id="submita" >تعديل</button>
            <button type="button" class="btn  btn-default" data-dismiss="modal" onclick="javascript:window.location='?page=add_in'">إلغاء</button>&nbsp; 
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
AddContent($a,"body",0);
}
}
elseif(isset($_SESSION["userid"])==true  and $_SESSION["usertype"]==1)
{
		AddContent("البريد الوارد ",'title',0);
 AddContent("البريد الوارد ",'title2',0);
	if(isset($_GET["new"])=="ok") // الكتب التي بجاجة لملاحظات
	{
			AddContent("البريد الوارد الجديد",'title',0);
 AddContent("البريد الوارد الجديد",'title2',0);
		$s="SELECT * FROM `box` WHERE year='{$_SESSION['year']}' and type='0'  and ready='0';";
	}else
		$s="SELECT * FROM `box` WHERE year='{$_SESSION['year']}' and type='0';";
$sql=mysqli_query($con,$s); //تعليمة الجدول الرئيسي
$b=<<<a
  <div class="row">
<div class="col-md-12">
  <div class="card card-mini">
        <div class="card-header">
          البريد الوارد 
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
            <th style="width:7%" >الحالة</th>
			
        </tr>
    </thead>
    <tbody>
a;
while($res=mysqli_fetch_row($sql)) 
{	
	$b.=<<<b
        <tr >
            <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[1]</td>
            <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[4]</td>
			 <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[2]</td>
			  <td  onclick="javascript:window.location='?page=add_in&com=ok&id={$res[0]}';">$res[5]</td>
	            <td>
b;
$s=mysqli_query($con,"select * from attach where b_id = $res[0] and type=0");
if(mysqli_num_rows($s)==1)
{
while($r=mysqli_fetch_row($s))
{
$value = explode(".", $r[2]);
   $e = strtolower(array_pop($value));
      $icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));

$b.="<a href='box/{$r[0]}.{$e}' target='_blank'><i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>";
}
}
else
$b.="لا يوجد مرفقات";

$b.=<<<b
</td>
<td>
b;
$s=mysqli_query($con,"select * from attach where b_id = {$res[0]} and type=1");
if(mysqli_num_rows($s)>0)
{
$c=0;
while($r=mysqli_fetch_row($s))
{
$c++;
$value = explode(".", $r[2]);
   $e = strtolower(array_pop($value));
         $icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));

$b.="<a href='box/{$r[0]}.{$e}' target='_blank'> <i class='fa fa-$icon_f' aria-hidden='true' style='font-size:20px'></i></a>&nbsp; &nbsp;";
}
}
else
$b.="لا يوجد مرفقات";
if($res[6]==0)
$b.=<<<ab
<td><span class="badge badge-warning badge-icon"><i class="fa fa-clock-o" aria-hidden="true"></i><span>انتظار الملاحظة</span></span></td>
ab;
elseif($res[6]==1)
$b.="<td><span class='badge badge-info badge-icon'><i class='fa fa-reply' aria-hidden='true'></i><span>بانتظار التأكيد</span></span></td>";
elseif($res[6]==2)
$b.="<td><span class='badge badge-success badge-icon'><i class='fa fa-check' aria-hidden='true'></i><span>تم التأكيد</span></span></td>";
}
$b.=<<<abb
  
    

    </tbody>
</table>
        </div>
      </div>  </div>  </div>


abb;
   AddContent($b,"body",0);	 
 if(isset($_POST["add_c"]))
    {
        
        $uid=$_SESSION["userid"];
        $id=$_POST["id"];
        $c=$_POST["com"];
            $d= date( 'Y-m-d H:i:s')    ;
        $sql=mysqli_query($con,"insert into comments values('0','$c',$id,$uid,'$d')");

        $sql1=mysqli_query($con,"update box set ready='1' where id=$id");
    }
	
if(isset($_GET['com'])) //مشاهدة الملاحظات
{
 if(isset($_GET["cid"]))
{
$id=addslashes($_GET["cid"]);
mysqli_query($con,"delete from comments where id=$id");
}

    $id=addslashes($_GET['id']);
    $d="SELECT * FROM `box` WHERE id=$id  and year='{$_SESSION['year']}' ; ";
$sql=mysqli_query($con,$d);
list($id,$bid,$desc,$type,$date,$f)=mysqli_fetch_row($sql);
$s=mysqli_query($con,"select * from attach where b_id = $id and type=0");
if(mysqli_num_rows($s)==1)
{
while($r=mysqli_fetch_row($s))
{
 $value=$r[2];
 $value = explode(".", $value);
   $e = strtolower(array_pop($value));   //Line 32
      $icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));

   $b=<<<a

      <a href='box/{$r[0]}.{$e}' target='_blank' class="list-group-item f">
        <span class="point" > الملف الرئيسي </span><span class="badge" style="float:;">
		<span class="fa fa-$icon_f"></span></span>
      </a>
a;
}
}
else
$b=<<<a
     <a  class="list-group-item f">
        <span class="" > لايوجد ملف رئيسي </span>
      </a>";
a;
	  $bb=<<<b
<ol style="padding:1px;">
b;
 $s=mysqli_query($con,"select * from attach where b_id = $id and type=1");
if(mysqli_num_rows($s)>0)
{
$c=0;
while($r=mysqli_fetch_row($s))
{
$c++;
 $arr = explode(".", $r[2]);
    $e = strtolower(array_pop($arr)); 
	      $icon_f=($e=="jpg"|| $e=="jpeg"|| $e=="png")?"file-image-o":(($e=="PDF"|| $e=="pdf")?"file-pdf-o":(($e=="doc"|| $e=="docx")?"file-word-o":"file-text-o"));

$bb.=<<<a
      <a href='box/{$r[0]}.{$e}' target='_blank' class="list-group-item f">
        <span class="point" > المرفق  $c </span><span class="badge" style="float:;">
		<span class="fa fa-$icon_f"></span></span>
      </a>
a;
}
}
else
$bb=<<<z
     <a  class="list-group-item f list-group-item-warning">
        <span class="" > لايوجد ملف مرفقات </span>
      </a>
z;
	  $sql=mysqli_query($con,"select * from comments where b_id=$id");
    $come="";
if(mysqli_num_rows($sql)>0)
{
    while($res=mysqli_fetch_row($sql))
        {
            $u_id=$res[3];
            $ss=mysqli_query($con,"select name,photo from users where id = $u_id;");
            echo mysqli_error($con);
            $resault=mysqli_fetch_row($ss);
						$date = date_create($res[4]);
	$date= date_format($date, 'Y-m-d ');	
 if($_SESSION["userid"]==$u_id)
         $come.=<<<a
 <div class="media social-post list-group-item col-md-12" style="margin-top:0px">
        <span class="" > <div class="media-left">
    <a href="#">
      <img src="profile/{$resault[1]}" />
    </a>
  </div>
  <div class="media-body">
    <div class="media-heading">
      <h4 class="title" style="font-size:14px;">{$resault[0]}</h4>
      <h5 class="timeing">$date</h5>
    </div>
    <div class="media-content">$res[1] <span onclick="confirmDelete('?page=add_in&cid=$res[0]&com=ok&id=$id')" class="badge" style="float:left;">
		<span class="point glyphicon glyphicon-trash"></span></span></div>
  </div> </span></div>
a;
else 
        $come.=<<<a
 <div class="media social-post list-group-item col-md-12" style="margin-top:0px">
        <span class="" > <div class="media-left">
    <a href="#">
      <img src="profile/{$resault[1]}" />
    </a>
  </div>
  <div class="media-body">
    <div class="media-heading">
      <h4 class="title" style="font-size:14px;">{$resault[0]}</h4>
      <h5 class="timeing">$date</h5>
    </div>
    <div class="media-content">$res[1]</div>
  </div> </span></div>
a;
        }
    }
	else 
	 $come.=<<<a
	      <a  class="list-group-item f list-group-item-warning">
        <span class="" >لا يوجد ملاحظات </span>
      </a>
a;
$page=$_GET["page"];
$a=<<<a

		
		  <div class="row">
<div class="col-md-12">
      <div class="card card-mini">
        <div class="card-header">
          <div class="card-title"> الوارد رقم   <strong class="highligh">&nbsp;&nbsp; $bid &nbsp;&nbsp;</strong></div>
      
        </div>
        <div class="card-body">
		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">التاريخ :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $date </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-calendar"></span></span>
      </a>
    </div>
      </div>
        </div>
		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">الجهة :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $f </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-flag"></span></span>
      </a>
    </div>
      </div>
        </div>
				<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">الموضوع :</label>
        <div class="col-md-6 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $desc </span><span class="badge" style="float:;">
		<span class="glyphicon glyphicon-edit"></span></span>
      </a>
    </div>
      </div>
        </div>
						<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">الملف الرئيسي :</label>
        <div class="col-md-6 ">
		<div class="list-group">
$b
    </div>
      </div>
        </div>
					<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-2 control-label">المرفقات :</label>
        <div class="col-md-6 ">
		<div class="list-group">
     $bb 
    </div>
      </div>
        </div>   
           <div class="section col-md-12 ">
                  <div class="section-title col-md-12"><i class="icon fa fa-comments-o" aria-hidden="true"> </i><b> الملاحظات </b></div>
                  <div class="section-body __indent">
				 	<div class="form-group col-md-8 form-horizontal eform">
	 		<div class="list-group">	  
	  $come
	  
  <form id="submit_this" action='?page=$page' method='post'>
     <input placeholder="إضافة ملاحظة..." class="form-control " name="com" >
	 
</div></div>
                </div>
				
                </div>	
				<div style="text-align:center">

<input type='hidden' value="$id" name='id'> 
<input type='hidden'  name='add_c' value='اضافة' > 
          <button type="submit" class="btn  btn-success "  id="submita" >موافق</button>
            <button type="button" class="btn  btn-default" data-dismiss="modal" onclick="javascript:window.location='?page=add_in'">إلغاء</button>&nbsp; 
</form>				
              </div></div>
     </div>
      </div>
    </div>	
<script>
function confirmDelete(delUrl) {
  if (confirm("هل انت متأكد من الحذف")) {
    document.location = delUrl;
  }}
</script>	
a;
AddContent($a,"body",0);
}
}
else
include("main.php");
?>