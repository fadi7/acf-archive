<?php
if(isset($_SESSION["userid"])==true  and $_SESSION["usertype"]==0)
{
$in_c=getCount(0,$_SESSION["year"]);
$out_c=getCount(1,$_SESSION["year"]);
$t_c=getCount(2,$_SESSION["year"]);
$cou=<<<a
<div class="row">
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
      <a  href="?page=add_t" class="card card-banner card-green-light">
  <div class="card-body">
    <i class="icon fa fa-share-alt fa-4x"></i>
    <div class="content">
      <div class="title">التعاميم</div>
      <div class="value"><span class="sign"></span>$t_c</div>
    </div>
  </div>
</a>

  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
      <a  href="?page=add_out" class="card card-banner card-blue-light">
  <div class="card-body">
    <i class="icon fa fa-sign-out fa-4x"></i>
    <div class="content">
      <div class="title">البريد الصادر</div>
      <div class="value"><span class="sign"></span>$out_c</div>
    </div>
  </div>
</a>

  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
      <a href="?page=add_in" class="card card-banner card-yellow-light">
  <div class="card-body">
    <i class="icon fa fa-sign-in fa-4x"></i>
    <div class="content">
      <div class="title">البريد الوارد</div>
      <div class="value"><span class="sign"></span>$in_c</div>
    </div>
  </div>
</a>

  </div>
</div>
a;
      if(isset($_POST["add_c"]))
    {
$id=$_POST['id'];
        $sql1=mysqli_query($con,"update box set ready='2' where id=$id");
			include("links.php");
    }
         AddContent("الصفحة الرئيسية",'title',0);
         AddContent("الصفحة الرئيسية",'title2',0);
   $dddd="SELECT *
FROM `box`
WHERE `type` = '0'  and year='{$_SESSION['year']}'
AND `ready` = '1'
order by b_id desc;";
$ccc="";
$sql=mysqli_query($con,$dddd);
if(mysqli_num_rows($sql)==0)
{

$b=<<<av
  <div class="row">
    <div class="col-md-12 col-sm-12" style="margin-bottom:10px">
        <div class="alert alert-success" role="alert" >
            <strong>عمل جيد!</strong>&nbsp; لا يوجد بريد وارد جديد ....
        </div>
    </div>
    </div>
av;
   AddContent($b,"body",0);	  
   AddContent($cou,"body",2);
	}
    else
    {
        $b=<<<a
<script>
function confirmDelete(delUrl) {
  if (confirm("هل انت متأكد من الحذف")) {
    document.location = delUrl;
  }
}
</script>
  <div class="row">
<div class="col-md-12">
			      <div class="card card-mini">
        <div class="card-header">
          البريد الوارد الجديد
        </div>
        <div class="card-body no-padding">
          <table class="datatable table table-striped primary" cellspacing="0" width="100%" style="width: 100%; text-align:center"">
    <thead>
        <tr >
            <th style="width:2%">الرقم</th>
            <th  style="width:13%">التاريخ</th>
            <th style="width:%">الموضوع</th>
            <th style="width:18%">جهة الورود</th>
            <th style="width:14%">الملف الاساسي</th>
            <th style="width:14%">المرفقات</th>			
        </tr>
    </thead>
    <tbody>
a;
while($res=mysqli_fetch_row($sql))
{	
	$b.=<<<b
        <tr data-toggle="modal" data-target="#myModal$res[0]">
            <td>$res[1]</td>
            <td>$res[4]</td>
			 <td>$res[2]</td>
			  <td>$res[5]</td>
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

$f1=<<<a

      <a href='box/{$r[0]}.{$e}' target='_blank' class="list-group-item f">
        <span class="point" > الملف الرئيسي </span><span class="badge" style="float:;">
		<span class="fa fa-$icon_f"></span></span>
      </a>
a;
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
$f2=<<<b

b;
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
$f2.="      <a href='box/{$r[0]}.{$e}' target='_blank' class='list-group-item f'>
        <span class='point' > المرفق  $c </span><span class='badge' style='float:;'>
		<span class='fa fa-$icon_f'></span></span>
      </a>";
}
}
else
{
$b.="لا يوجد مرفقات";
$f2.="     <a  class='list-group-item f list-group-item-warning'>
        <span  > لايوجد ملف مرفقات </span>
      </a>";
}
$sqlC=mysqli_query($con,"select * from comments where b_id={$res[0]};");
    $come="";
if(mysqli_num_rows($sqlC) > 0)
{
    while($res1=mysqli_fetch_row($sqlC))
        {
            $u_id=$res1[3];
            $ss=mysqli_query($con,"select name,photo from users where id = $u_id;");
            $resault=mysqli_fetch_row($ss);  
						$date = date_create($res1[4]);
	$date= date_format($date, 'Y-m-d ');			
        $come.=<<<a
          <div class="media social-post list-group-item">
  <div class="media-left">
    <a href="#">
      <img src="profile/{$resault[1]}" />
    </a>
  </div>
  <div class="media-body">
    <div class="media-heading">
      <h4 class="title" style="font-size:14px;">{$resault[0]}</h4>
      <h5 class="timeing">$date </h5>
    </div>
    <div class="media-content">$res1[1]</div>
  </div>
</div>
a;
        }
    }
	else 
	 $come.=<<<a
 <a  class="list-group-item" style="text-align:center"><b style="color:#03ACDC">لا يوجد ملاحظات </b></a>
a;
$b.="</td>
</tr>";
$ccc.=<<<b

			    <div class="modal fade" id="myModal{$res[0]}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="padding-top:20px;padding-bottom:20px;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" >الوارد رقم   <strong class="highligh">&nbsp;&nbsp; $res[1] &nbsp;&nbsp;</strong></h4>
          </div>
          <div class="modal-body " id="in">

		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">التاريخ :</label>
        <div class="col-md-9 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $res[4] </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-calendar"></span></span>
      </a>
    </div>
      </div>
        </div>
		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">الجهة :</label>
        <div class="col-md-9 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $res[5] </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-flag"></span></span>
      </a>
    </div>
      </div>
        </div>
				<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">الموضوع :</label>
        <div class="col-md-9 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $res[2] </span><span class="badge" style="float:;">
		<span class="glyphicon glyphicon-edit"></span></span>
      </a>
    </div>
      </div>
        </div>
						<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">الملف الرئيسي :</label>
        <div class="col-md-9 ">
		<div class="list-group">
$f1
    </div>
      </div>
        </div>

					<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">المرفقات :</label>
        <div class="col-md-9 ">
		<div class="list-group">
     $f2
    </div>
      </div>
        </div>   
           <div class="section col-md-12 ">
                  <div class="section-title col-md-12"><i class="icon fa fa-comments-o" aria-hidden="true"> </i><b> الملاحظات </b></div>
                  <div class="section-body __indent">
	  $come
                </div>
                </div>	
&nbsp;
		 </div>
          <div class="modal-footer">
		  <form id="submit_this" action='?page=main' method='post'><input type='hidden' value="$res[0]" name='id'>
<input type='hidden' value="موافق" name='add_c'>
            <button type="submit" class="btn btn-sm btn-success"  id="submita" >موافق</button>
			<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">اغلاق</button>
            </form>
          </div>
        </div>
      </div>
    </div>
b;
}
$b.="  
     
    </tbody>
</table>
        </div> </div> </div>
      </div>"; 
	     AddContent($b,"body",0);	   
   AddContent($ccc,"body",2);
 AddContent($cou,"body",1);	    
  }   
}
elseif(isset($_SESSION["userid"])==true  and $_SESSION["usertype"]==1)
{
$in_c=getCount(0,$_SESSION["year"]);
$out_c=getCount(1,$_SESSION["year"]);
$t_c=getCount(2,$_SESSION["year"]);
$cou=<<<a
<div class="row">
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
      <a  href="?page=add_t" class="card card-banner card-green-light">
  <div class="card-body">
    <i class="icon fa fa-square-o fa-4x"></i>
    <div class="content">
      <div class="title">التعاميم</div>
      <div class="value"><span class="sign"></span>$t_c</div>
    </div>
  </div>
</a>

  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
      <a href="?page=add_out" class="card card-banner card-blue-light">
  <div class="card-body">
    <i class="icon fa fa-sign-out fa-4x"></i>
    <div class="content">
      <div class="title">البريد الصادر</div>
      <div class="value"><span class="sign"></span>$out_c</div>
    </div>
  </div>
</a>

  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
      <a  href="?page=add_in" class="card card-banner card-yellow-light">
  <div class="card-body">
    <i class="icon fa fa-sign-in fa-4x"></i>
    <div class="content">
      <div class="title">البريد الوارد</div>
      <div class="value"><span class="sign"></span>$in_c</div>
    </div>
  </div>
</a>

  </div>
</div>
a;
       if(isset($_POST["add_c"]))
    {
        
        $uid=$_SESSION["userid"];
        $id=$_POST["id"];
        $c=$_POST["com"];
            $d= date( 'Y-m-d H:i:s')    ;
        $sql=mysqli_query($con,"insert into comments values('0','$c',$id,$uid,'$d')");

        $sql1=mysqli_query($con,"update box set ready='1' where id=$id");
	include("links.php");
    }
         
		          AddContent("الصفحة الرئيسية",'title',0);
		          AddContent("الصفحة الرئيسية",'title2',0);
   $dddd="SELECT *
FROM `box`
WHERE `type` = '0'  and year='{$_SESSION['year']}'
AND `ready` = '0'
order by b_id desc;";
$sql=mysqli_query($con,$dddd);
$ccc="";
if(mysqli_num_rows($sql)==0)
{
$b=<<<av
  <div class="row">
    <div class="col-md-12 col-sm-12" style="margin-bottom:10px">
        <div class="alert alert-success" role="alert" >
            <strong>عمل جيد!</strong>&nbsp; لا يوجد بريد وارد جديد ....
        </div>
    </div>
    </div>
av;
   AddContent($b,"body",0);	  
   AddContent($cou,"body",2);
	}
    else
    {
	
$b=<<<a

  <div class="row">
<div class="col-md-12">
  <div class="card card-mini">
        <div class="card-header">
          البريد الوارد الجديد
        </div>
        <div class="card-body no-padding">
          <table class="datatable table table-striped primary" cellspacing="0" width="100%" style="width: 100%; text-align:center"">
    <thead>
        <tr >
            <th style="width:2%">الرقم</th>
            <th  style="width:13%">التاريخ</th>
            <th style="width:%">الموضوع</th>
            <th style="width:18%">جهة الورود</th>
            <th style="width:14%">الملف الاساسي</th>
            <th style="width:14%">المرفقات</th>			
        </tr>
    </thead>
    <tbody>
a;
while($res=mysqli_fetch_row($sql))
{	
	$b.=<<<b
        <tr data-toggle="modal" data-target="#myModal$res[0]">
            <td>$res[1]</td>
            <td>$res[4]</td>
			 <td>$res[2]</td>
			  <td>$res[5]</td>
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
  
$f1=<<<a

      <a href='box/{$r[0]}.{$e}' target='_blank' class="list-group-item f">
        <span class="point" > الملف الرئيسي </span><span class="badge" style="float:;">
		<span class="fa fa-$icon_f"></span></span>
      </a>
a;
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
$f2=<<<b

b;
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
 $arr = explode(".", $r[2]);
    $e = strtolower(array_pop($arr)); 
$f2.="      <a href='box/{$r[0]}.{$e}' target='_blank' class='list-group-item f'>
        <span class='point' > المرفق  $c </span><span class='badge' style='float:;'>
		<span class='fa fa-$icon_f'></span></span>
      </a>";}
}
else
{
$b.="لا يوجد مرفقات";
$f2.="     <a  class='list-group-item f list-group-item-warning'>
        <span  > لايوجد ملف مرفقات </span>
      </a>";
}
$sqlC=mysqli_query($con,"select * from comments where b_id={$res[0]};");

    $come="";
if(mysqli_num_rows($sqlC) > 0)
{
    while($res1=mysqli_fetch_row($sqlC))
        {
            $u_id=$res1[3];
            $ss=mysqli_query($con,"select name,photo from users where id = $u_id;");
            $resault=mysqli_fetch_row($ss);
$date = date_create($res1[4]);
	$date= date_format($date, 'Y-m-d ');
        $come.=<<<a
          <div class="media social-post list-group-item">
  <div class="media-left">
    <a href="#">
      <img  src="profile/{$resault[1]}" />
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


  <a  class="list-group-item"><b style="color:#03ACDC">{$resault[0]} : </b>$res[1]
  <div class="indel" style="text-align:left">
  <span onclick="confirmDelete('?page=main&cid=$res[0]&com=ok&id=$id')" class="glyphicon glyphicon-trash"></span></div></a>
<script>
function confirmDelete(delUrl) {
  if (confirm("هل انت متأكد من الحذف")) {
    document.location = delUrl;
  }}
</script>



  <a  class="list-group-item"><b style="color:#03ACDC">{$resault[0]} : </b>$res[1]<div class="indel" style="text-align:left"><span class="glyphicon glyphicon-trash"></span></div></a>

  

a;
        }
    }
$b.=<<<b
</td>
</tr>

b;
$ccc.=<<<b

			    <div class="modal fade" id="myModal{$res[0]}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="padding-top:20px;padding-bottom:20px;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" >الوارد رقم   <strong class="highligh">&nbsp;&nbsp; $res[1] &nbsp;&nbsp;</strong></h4>
          </div>
          <div class="modal-body " id="in">

		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">التاريخ :</label>
        <div class="col-md-9 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $res[4] </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-calendar"></span></span>
      </a>
    </div>
      </div>
        </div>
		<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">الجهة :</label>
        <div class="col-md-9 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $res[5] </span><span class="badge" style="float:;">
		<span class=" glyphicon glyphicon-flag"></span></span>
      </a>
    </div>
      </div>
        </div>
				<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">الموضوع :</label>
        <div class="col-md-9 ">
		<div class="list-group">
      <a  class="list-group-item f">
        <span class="" > $res[2] </span><span class="badge" style="float:;">
		<span class="glyphicon glyphicon-edit"></span></span>
      </a>
    </div>
      </div>
        </div>
<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">الملف الرئيسي :</label>
        <div class="col-md-9 ">
		<div class="list-group">
$f1
    </div>
      </div>
        </div>

					<div class="form-group col-md-12 form-horizontal eform">
<label class="col-md-3 control-label">المرفقات :</label>
        <div class="col-md-9 ">
		<div class="list-group">
     $f2
    </div>
      </div>
        </div>   
           <div class="section col-md-12 ">
                  <div class="section-title col-md-12"><i class="icon fa fa-comments-o" aria-hidden="true"> </i><b> الملاحظات </b></div>
                  <div class="section-body __indent">
	  $come
                		  <form id="submit_this" action='?page=main' method='post'><input type='hidden' value="$res[0]" name='id'>
<input type='hidden' value="موافق" name='add_c'>
	 <input class="form-control" placeholder="ادخل ملاحظات ..." name="com" required="required">
				</div>
                </div>	
&nbsp;
		 </div>
          <div class="modal-footer">
<input type='hidden' value="موافق" name='add_c'>
            <button type="submit" class="btn btn-sm btn-success"  id="submita" >موافق</button>
			<button type="button" class="btn btn-sm btn-default" data-dismiss="modal">اغلاق</button>
            </form>
          </div>
        </div>
      </div>
    </div>
b;
}
$b.=<<<abb
    </tbody>
</table>
        </div>
      </div>  </div>  </div>
	  
abb;
echo "hi";
   AddContent($b,"body",0);	  
   AddContent($ccc,"body",2);
   AddContent($cou,"body",1);  
  }    
}
else
{
include("login.php");
}
?>