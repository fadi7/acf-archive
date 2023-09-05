<?php

if(isset($_SESSION["userid"])==true )
{
	AddContent("الملف الشخصي",'title',0);
AddContent("الملف الشخصي",'title2',0);
 if(isset($_GET["dp"])) // الحذف
{
$uid=$_SESSION["userid"];
List($phid)=mysqli_fetch_row(mysqli_query($con,"select photo from users where id=$uid ;"));
if($phid!='0.png')
{
unlink("profile/$phid");
mysqli_query($con,"update users set photo='0.png' where id=$uid");
$_SESSION["photo"]="0.png";
include("links.php");
}
}

if(isset($_POST['save']))
{
	    $id=$_SESSION["userid"];
if (!empty($_POST['pw'])) 
	{
    $pw=addslashes(md5($_POST["pw"]));
    mysqli_query($con,"update users set password='$pw' where id=$id");
    if(mysqli_errno($con)==0)
 echo"<br><p class='bg-info'>password</p>";
    }
if (!empty($_POST['name'])) 
	{
    $n=addslashes($_POST["name"]);
    mysqli_query($con,"update users set name='$n' where id=$id");
    if(mysqli_errno($con)==0)
    echo"<br><p class='bg-info'>تم تعديل الاسم بنجاح</p>";
    }
if (!empty($_POST['posi'])) 
	{
    $p=addslashes($_POST["posi"]);
    mysqli_query($con,"update users set position='$p' where id=$id");
    if(mysqli_errno($con)==0)
 echo"<br><p class='bg-info'>pos</p>";
    }
	if (!empty($_POST['email'])) 
	{
    $em=addslashes($_POST["email"]);
    mysqli_query($con,"update users set email='$em' where id=$id");
    if(mysqli_errno($con)==0)
 echo"<br><p class='bg-info'>email</p>";
    }
    }
    $sql=mysqli_query($con,"select username,name,position,email from users where id = {$_SESSION['userid']}");
    list($un,$name,$pos,$email)=mysqli_fetch_row($sql);
$id=$_SESSION["userid"];
$ph=$_SESSION["photo"];	
$code=<<<a
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="./assets/js/custom-file-input.js"></script>
<script type="text/javascript">
	window.onload = function () {
    if (location.hash === "#setting")
	{
        $( "#setting" ).addClass("active");
        $( ".nav-tabs li:last-child" ).addClass("active");
		
		}
else
{
        $( "#tab1" ).addClass("active");
        $( ".nav-tabs li:first-child" ).addClass("active");
}
};
function deleteImage(path) {
  if (confirm("هل انت متأكد من الحذف")) {
	$.ajax({
		url: "delete.php",
		type: "POST",
		data:  {'path':path},
		success: function(data){
			$("#targetLayer").html("<div class='no-image'> <img src='profile/0.png' class='img-responsive' style='margin:auto' ></div></div>");
			$("#uploadFormLayer").html(" <label class='btn btn-default'><input type='file' style='display: none;'  name='userImage' id='file' type='file' class='inputfile' accept='image/x-png,image/gif,image/jpeg'><label for='file'><span><i class='icon fa fa-upload ' aria-hidden='true'></i> &nbsp; تغيير الصورة </span></label></label><br/> <input type='submit' value='موافق' class='btnSubmit btn btn-xs btn-success'   />");},
		error: function(){} 	        
	});
}
}
function confirmDelete(delUrl) {
  if (confirm("هل انت متأكد من الحذف")) {
    document.location = delUrl;
  }}
$(document).ready(function (e) {
	$("#uploadForm").on('submit',(function(e) {
		e.preventDefault();
		$.ajax({
        	url: "upload.php",
			type: "POST",
			data:  new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false,
			success: function(data)
		    {
			$("#targetLayer").html(data);
		    },
		  	error: function() 
	    	{
	    	} 	        
	   });
	}));
});
</script>
a;
AddContent($code,"code",0);
    $form=<<<a
	


  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body app-heading">
          <img class="profile-img" src="profile/$ph">
          <div class="app-title">
            <div class="title"><span class="highlight">$name</span></div>
            <div class="description">$pos</div>
          </div>
        </div>
      </div>
    </div>
  </div>
	  <div class="row">  
    <div class="col-lg-12">
      <div class="card card-tab">
        <div class="card-header">
          <ul class="nav nav-tabs">
            <li role="tab1" >
              <a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">الحساب</a>
            </li>
            <li role="setting">
              <a href="#setting" aria-controls="setting" role="tab" data-toggle="tab">الاعدادات</a>
            </li>
          </ul>
        </div>
        <div class="card-body no-padding tab-content">
          <div role="tabpanel" class="tab-pane" id="tab1">
            <div class="row">            
              <div class="col-md-9 col-sm-12">
			  <div class="section">
                  <div class="section-title"><i class="icon fa fa-user" aria-hidden="true">  </i> معلوماتي </div>
                  <div class="section-body __indent">
        <label class="col-md-3 control-label">USER NAME</label>
        <div class="">
        <label class="infor" style="text-transform: uppercase;">$un</label>
        </div>
        <label class="col-md-3 control-label">اسم المستخدم</label>
        <div class="">
        <label class="infor ">$name</label>
        </div>
        <label class="col-md-3 control-label">الصفة</label>
        <div class="">
        <label class="infor ">$pos</label>
        </div>
		        <label class="col-md-3 control-label">البريد الالكتروني</label>
        <div class="">
        <label class="infor ">$email</label>
        </div>
				  </div>
                </div>
                <div class="section">
                  <div class="section-title"><i class="icon fa fa-bar-chart" aria-hidden="true"> </i> احصائيات </div>
                  <div class="section-body __indent">
      
	  SOON
	  
                  </div>
                </div>				
              </div>
			  <div class="col-md-3 col-sm-12">		  
                <div class="section">
                  <div class="section-title"><i class="icon fa fa-file-photo-o " aria-hidden="true"></i> الصورة الشخصية</div>
                  <div class="section-body __indent">  <img class="thumbnail" style="margin:auto;max-width:250px;max-height:200px;" src="profile/$ph"></div>
                </div>            
              </div>		  
            </div>
          </div>

          <div role="tabpanel" class="tab-pane" id="setting">   
		              <div class="row">
              <div class="col-md-9 col-sm-12">					  
			       <form class="form form-horizontal " onsubmit="return cpww()" method="post" action="?page=acount" id="form">
  <div class="section">
    <div class="section-title"><i class="icon fa fa-user" aria-hidden="true"> </i>تغيير المعلومات الشخصية</div>
    <div class="section-body">
      <div class="form-group">
        <label class="col-md-3 control-label">USER NAME</label>
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="$un" disabled>
        </div>
      </div>
	        <div class="form-group">
        <label class="col-md-3 control-label">اسم المستخدم</label>
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="$name"  id="hname1">
        </div>
      </div>
	        <div class="form-group">
        <label class="col-md-3 control-label">الصفة</label>
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="$pos" id="hposi">
        </div>
      </div> 
	  	        <div class="form-group">
        <label class="col-md-3 control-label">البريد الالكتروني</label>
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="$email" id="hemail">
        </div></form>
      </div> 
    </div>
  </div>
  </div>
  
  
  			  <div class="col-md-3 col-sm-12">		  
                <div class="section" style="margin-bottom:4px;">
                  <div class="section-title" style="margin-bottom:4px;width: 90%;margin: auto;"><i class="icon fa fa-file-photo-o " aria-hidden="true"></i> الصورة الشخصية</div>
    <div class="thumbnail" style="margin-bottom:4px;" style="">
a;

if($ph!="0.png")
$form.=<<<a
     <img src="profile/$ph" class="img-responsive" style="" >
	       <div class="caption" style="padding:4px;">
        <div><a href="#" class="btn btn-danger btn-xs" role="button" onclick="confirmDelete('?page=acount&dp=ok#setting')"> <i class="icon fa fa-times-circle" aria-hidden="true"></I> &nbsp;   إزالة</a></div>
      </div>
a;
else
$form.=<<<a
<form id="uploadForm" action="upload.php" method="post"><input type="hidden" name="id" value="$id">
<div id="targetLayer">  <img src="profile/$ph" class="img-responsive" style=" margin: auto;
" ></div>

	       <div class="caption" style="padding:4px;">



<div id="uploadFormLayer">
           <label class="btn btn-default">
                 <input type="file" style="display: none;"  name="userImage" id="file" type="file" class="inputfile" accept="image/x-png,image/gif,image/jpeg">
				
<label for="file"><span><i class="icon fa fa-upload " aria-hidden="true"></i> &nbsp; تغيير الصورة </span></label>
            </label><br/> 

			

			
			
			
			
<input type="submit" value="موافق" class="btnSubmit btn btn-xs btn-success"   />

</div>
</form>	
      </div>
a;
$form.=<<<a

    </div> </div>            
              </div>  

  
  
  
   <div class="section col-md-9 form-horizontal">
    <form class="form form-horizontal " onsubmit="return cpww()" method="post" action="?page=acount">
    <div class="section-title"><i class="icon fa fa-key" aria-hidden="true"> </i>تغيير كلمة السر</div>
                  <div class="section-body __indent">
      <div class="form-group col-md-12 ">
        <label class="col-md-3 control-label">كلمة السر الجديدة</label>
        <div class="col-md-6">
          <input type="password" class="form-control" placeholder="اكتب كلمة السر ..." id="pw" name="pw">
        </div>
      </div>
	        <div class="form-group col-md-12">
        <label class="col-md-3 control-label">تأكيد كلمة السر</label>
        <div class="col-md-6">
          <input type="password" class="form-control" placeholder="تأكيد كلمة السر ..." id="cpw">
        </div>
      </div>
    </div>
  </div>
          <input type="hidden" class="form-control"   id="name" name="name">  
		            <input type="hidden" class="form-control"   id="posi" name="posi">  
		            <input type="hidden" class="form-control"   id="email" name="email">  
  <div class="form-footer">
    <div class="form-group">
      <div class="col-md-12 col-md-offset-0" style="text-align:center">
        <button type="submit" class="btn btn-primary" id="submita" name="save">حـفـظ</button>
      </div>
    </div>
  </div>
</form>
		  </div>
        </div>
        </div>
      </div>
    </div>
  </div>
    <script>
	$('#hname1').change(function() {
 $('#name').val($(this).val());
});
	$('#hemail').change(function() {
 $('#email').val($(this).val());
});
$('#hposi').change(function() {
 $('#posi').val($(this).val());
});
	    $("a#submita").click(function()
    {
    $("#submit_this").submit();
    return false;
    });
    function cpww()
    {


        var pw=document.getElementById('pw');
        var cpw=document.getElementById('cpw');
        if(pw.value==cpw.value)
        {
         return true;
        }
         else
        { 
        alert('كلمة السر غير مطابقة');
        return false;
        }
        }
    </script>
a;
    

        AddContent("حسابي","title",0);
       
	    AddContent($form,"body",0);
}
    else
    include("login.php");
?>