<?php
if(isset($_SESSION["userid"])==true  and $_SESSION["usertype"]==0)
{
    if(isset($_GET["d"]))
{
$id=addslashes($_GET["d"]);
mysqli_query($con,"delete from source where id=$id");
getSource();

}
    if(isset($_POST["insert"]))
    {
        $name=addslashes($_POST["source"]);
        $a=mysqli_query($con,"insert into source
		values('0','$name');");
getSource();
        }
	addContent("الاعدادات",'title',0);
	addContent("الاعدادات",'title2',0);
	$a=<<<abc
	<div class="row ng-scope">
  

  
    <div class="col-md-6" style="float:left;">
    <div class="card">
      <div class="card-header" style="    padding: 20px;">
        الجهات 	  
           &nbsp;  &nbsp;  <a href="?page=setting&add_s=ok" class="" data-toggle="">
                <i class="fa fa-plus-circle" aria-hidden="true" style="font-size:20px;"></i>
              </a>
          
      </div>

      <div class="card-body no-padding">
<table class="datatable1 table table-striped primary inbox" cellspacing="0"  style="width: 100%; text-align:center"">
    <thead>
        <tr >
            <th style="width:20%">الرقم</th>
            <th style="">الجهة</th>
             <th style="width:20%" class="editT">حذف</th>	
				
        </tr>
    </thead>
    <tbody>
abc;
$sql=mysqli_query($con,"select * from source ");
$n=1;
while($res=mysqli_fetch_row($sql))
{
$a.=<<<a
   <tr >
          <td  >$n</td>
          <td >$res[1]</td>
		<td ><a href="javascript:confirmDelete('?page=setting&d=$res[0]')"><span class="fa fa-trash-o"  style='font-size:20px'></a></span>
  </td>
        </tr>

a;
$n++;
}

$a.=<<<abc
   </tbody>
</table>
      </div>
    </div>
  </div>
  <div class="col-md-6" style="float:left;">
    <div class="card card-mini">
      <div class="card-header">
        الاعدادات العامة
      </div>
      <div class="card-body">
	  		 
		         <div class="section">
  <div class="section-title">المظهر</div>
  <div class="section-body">
    <div class="radio">
      <input type="radio" name="theming" id="radio3" value="default" checked>
      <label for="radio3">
          Default (green)
      </label>
    </div>
    <div class="radio">
      <input type="radio" name="theming" id="radio4" value="blue-sky">
      <label for="radio4">
          Blue Sky
      </label>
    </div>
    <div class="radio">
      <input type="radio" name="theming" id="radio5" value="yellow">
      <label for="radio5">
          Yellow
      </label>
    </div>
    <div class="radio">
      <input type="radio" name="theming" id="radio6" value="red">
      <label for="radio6">
          Red
      </label>
    </div>
  </div>
</div>	 
  </div>
    </div>
  </div>
  <div class="col-md-6" style="float:left; ">
    <div class="card card-mini" >
      <div class="card-header" >
        المستخدمين
      </div>
      <div class="card-body no-padding">
        <div class="table-responsive">
  <table class="table card-table">
    <thead>
      <tr>
        <th style="width:10%">الرقم</th>
        <th style="width:10%">USERNAME</th>
        <th>الاسم</th>
        <th >الصفة</th>
        <th >البريد</th>
        <th style="width:10%">الحساب</th>
      </tr>
    </thead>
    <tbody>
abc;
$sql=mysqli_query($con,"select * from users ");
$n=1;
while($res=mysqli_fetch_row($sql))
{
$a.=<<<a
   <tr style="text-align:center">
          <td  >$n</td>
          <td >$res[1]</td>
          <td >$res[2]</td>
          <td >$res[3]</td>
          <td >$res[5]</td>
a;
if($res[7]== 0)
$a.="<td ><span class='fa fa-user'  style='font-size:20px ; color:#337ab7'></td>";
elseif($res[7]==1)
$a.="<td ><span class='fa fa-user-secret'  style='font-size:20px; color:#FF7260'></td>";
$a.=<<<a
        </tr>

a;
$n++;
}
$a.=<<<a
    </tbody>
  </table>
</div>
      </div>
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

if(isset($_GET["add_s"]))
{
$a.=<<<a


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">أضافة <strong class="highligh">&nbsp; جهة &nbsp;</strong></h4>
          </div>
          <div class="modal-body">

		  	 <div class="section " style="">
                  <div class="section-body __indent ">
		  
		          <form   method="post"  action="?page=setting" class="form-horizontal eform">
    <div class="form-group col-md-12">
<label class="col-md-3 control-label">اسم الجهة :</label>
        <div class="col-md-6 ">
          <input type="input" class="form-control"  value="" name="source" placeholder="ادخل اسم الجهة">
      </div>
        </div>
  
	  
</div>
</div>
</div>
          <div class="modal-footer">
 
            <button type="submit" class="btn  btn-success" name='insert' value='اضافة' > حفظ </button>
			           <button type="button" class="btn  btn-default" data-dismiss="modal">إغلاق</button></form>
					   
					   
					 
          </div>
        </div>
      </div>
    </div>
	
			 
	
	
	<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>

a;

}
addContent($a,"body",0);
}
elseif(isset($_SESSION["userid"])==true  and $_SESSION["usertype"]==1)
{
		addContent("الاعدادات",'title',0);
	addContent("الاعدادات",'title2',0);
	$a=<<<abc
	<div class="row ng-scope">
	  <div class="col-md-6" style="float:left; ">
    <div class="card card-mini" >
      <div class="card-header" >
        المستخدمين
      </div>
      <div class="card-body no-padding">
        <div class="table-responsive">
  <table class="table card-table">
    <thead>
      <tr>
        <th style="width:10%">الرقم</th>
        <th style="width:10%">USERNAME</th>
        <th>الاسم</th>
        <th >الصفة</th>
        <th >البريد</th>
        <th style="width:10%">الحساب</th>
      </tr>
    </thead>
    <tbody>
abc;
$sql=mysqli_query($con,"select * from users ");
$n=1;
while($res=mysqli_fetch_row($sql))
{
$a.=<<<a
   <tr style="text-align:center">
          <td  >$n</td>
          <td >$res[1]</td>
          <td >$res[2]</td>
          <td >$res[3]</td>
          <td >$res[5]</td>
a;
if($res[7]== 0)
$a.="<td ><span class='fa fa-user'  style='font-size:20px ; color:#337ab7'></td>";
elseif($res[7]==1)
$a.="<td ><span class='fa fa-user-secret'  style='font-size:20px; color:#FF7260'></td>";
$a.=<<<a
        </tr>

a;
$n++;
}
$a.=<<<a
    </tbody>
  </table>
</div>
      </div>
    </div>
  </div>

  <div class="col-md-6" style="float:left;">
    <div class="card card-mini">
      <div class="card-header">
        الاعدادات العامة
      </div>
      <div class="card-body">
	  		 
		         <div class="section">
  <div class="section-title">المظهر</div>
  <div class="section-body">
    <div class="radio">
      <input type="radio" name="theming" id="radio3" value="default" checked>
      <label for="radio3">
          Default (green)
      </label>
    </div>
    <div class="radio">
      <input type="radio" name="theming" id="radio4" value="blue-sky">
      <label for="radio4">
          Blue Sky
      </label>
    </div>
    <div class="radio">
      <input type="radio" name="theming" id="radio5" value="yellow">
      <label for="radio5">
          Yellow
      </label>
    </div>
    <div class="radio">
      <input type="radio" name="theming" id="radio6" value="red">
      <label for="radio6">
          Red
      </label>
    </div>
  </div>
</div>	 
  </div>
    </div>
  </div></div>
a;
addContent($a,"body",0);
}
?>