<?php
if(isset($_SESSION["userid"]))
{
$name=$_SESSION["name"];	
$ph=$_SESSION["photo"];	


$p=<<<ab
        <li class="dropdown profile">
          <a href="/html/pages/profile.html" class="dropdown-toggle"  data-toggle="dropdown">
            <img class="profile-img" src="profile/$ph">
            <div class="title">الملف الشخصي</div>
          </a>
          <div class="dropdown-menu">
            <div class="profile-info">
              <h4 class="username" style="text-align: right;">$name</h4>
            </div>
            <ul class="action">
              <li>
                <a href="?page=acount">
                  الملف الشخصي
                </a>
              </li>
              <li>
                <a href="?page=setting">
                  الاعدادات
                </a>
              </li>
              <li>
                <a href="?page=Logout">
تسجيل الخروج
                </a>
              </li>
            </ul>
          </div>
        </li>
ab;
 if(  $_SESSION["usertype"]==0)
 { $a=<<<a
<ul class="sidebar-nav">
      <li id="main">
        <a href="?page=main">
          <div class="icon">
            <i class="fa fa-tasks" aria-hidden="true"></i>
          </div>
          <div class="title">الرئيسية</div>
        </a>
      </li>
      <li class="dropdown "  id="add_in">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <div class="icon">
            <i class="fa fa-sign-in" aria-hidden="true"></i>
          </div>
          <div class="title">البريد الوارد</div>
        </a>
        <div class="dropdown-menu">
          <ul>
            <li class="section"><i class="fa fa-sign-in" aria-hidden="true"></i> البريد الوارد</li>
            <li><a href="?page=add_in">مشاهدة الكل</a></li>  	           
		   <li><a href="?page=add_in&new=ok">الوارد الجديد</a></li>    		
            <li><a href="?page=add_in&insert=ok">إضافة جديد</a></li>
          </ul>
        </div>
      </li>
	        <li class="dropdown "  id="add_out">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <div class="icon">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
          </div>
          <div class="title">البريد الصادر</div>
        </a>
        <div class="dropdown-menu" >
          <ul>
            <li class="section"><i class="fa fa-sign-out" aria-hidden="true"></i> البريد الصادر</li>
			
            <li><a href="?page=add_out">مشاهدة الكل</a></li>  	           
            <li><a href="?page=add_out&insert=ok">إضافة جديد</a></li>
          </ul>
        </div>
      </li>
	        </li>
	        <li class="dropdown "  id="add_t">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <div class="icon">
            <i class="fa fa-share-alt" aria-hidden="true"></i>
          </div>
          <div class="title">التعاميم</div>
        </a>
        <div class="dropdown-menu">
          <ul>
            <li class="section"><i class="fa fa-share-alt" aria-hidden="true"></i> التعاميم</li>
            <li><a href="?page=add_t">مشاهدة الكل</a></li>  	           
            <li><a href="?page=add_t&insert=ok">إضافة جديد</a></li>
          </ul>
        </div>
      </li>  
    </ul>
                      
a;
$float=<<<a
  <div class="btn-floating" id="help-actions"> 
  <div class="btn-bg"></div>
  <button type="button" class="btn btn-default btn-toggle" data-toggle="toggle" data-target="#help-actions">
    <i class="icon fa fa-plus"></i>
    <span class="help-text">Shortcut</span>
  </button>
  <div class="toggle-content">
    <ul class="actions">
      <li><a href="?page=add_in&insert=ok">إضافة وارد</a></li>
      <li><a href="?page=add_out&insert=ok">إضافة صادر</a></li>
      <li><a href="?page=add_t&insert=ok">إضافة تعميم</a></li>
    </ul>
  </div>
</div>
a;
AddContent($float,"float",0);
 }
else
   $a=<<<a
<ul class="sidebar-nav">
      <li id="main">
        <a href="?page=main">
          <div class="icon">
            <i class="fa fa-tasks" aria-hidden="true"></i>
          </div>
          <div class="title">الرئيسية</div>
        </a>
      </li>
      <li class="dropdown " id="add_in">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <div class="icon">
            <i class="fa fa-sign-in" aria-hidden="true"></i>
          </div>
          <div class="title">البريد الوارد</div>
        </a>
        <div class="dropdown-menu">
          <ul>
            <li class="section"><i class="fa fa-sign-in" aria-hidden="true"></i> البريد الوارد</li>
            <li><a href="?page=add_in">مشاهدة الكل</a></li>  	           
		   <li><a href="?page=add_in&new=ok">الوارد الجديد</a></li>    		
          </ul>
        </div>
      </li>
	       <li id="add_out">
        <a href="?page=add_out">
          <div class="icon">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
          </div>
          <div class="title">الصادر</div>
        </a>
      </li>
	         <li id="add_t">
        <a href="?page=add_t">
          <div class="icon">
            <i class="fa fa-share-alt" aria-hidden="true"></i>
          </div>
          <div class="title">التعاميم</div>
        </a>
      </li>
        
       
    </ul>
        
                
a;
$ready=($_SESSION['usertype']==0)?1:0;
list($non)=mysqli_fetch_row(mysqli_query($con,"SELECT count(id)
FROM `box`
WHERE `type` = '0'  and year='{$_SESSION['year']}'
AND `ready` = '$ready'"));
if($non==0)
$not=<<<a
    <li class="dropdown notification ">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <div class="icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
            <div class="title">نظام التنبيهات</div>
        
          </a>
          <div class="dropdown-menu">
            <ul>
              <li class="dropdown-header">التنبيهات</li>
             
              <li>
                <a href="?page=add_in&new=ok">
				  
                  لايوجد رسائل جديدة
				
                </a>
              </li>
              <li class="dropdown-footer">
                <a href="?page=add_in">مشاهدة الكل <i class="fa fa-angle-left" aria-hidden="true"></i></a>
              </li>
            </ul>
          </div>
        </li>
a;
else
$not=<<<a
    <li class="dropdown notification danger">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <div class="icon"><i class="fa fa-bell" aria-hidden="true"></i></div>
            <div class="title">نظام التنبيهات</div>
            <div class="count">$non</div>
          </a>
          <div class="dropdown-menu">
            <ul>
              <li class="dropdown-header">التنبيهات</li>
             
              <li>
                <a href="?page=add_in&new=ok">
				  <span class="badge badge-danger pull-right">$non</span>
                  الرسائل الغير مقروئة
				
                </a>
              </li>
              <li class="dropdown-footer">
                <a href="?page=add_in">مشاهدة الكل <i class="fa fa-angle-left" aria-hidden="true"></i></a>
              </li>
            </ul>
          </div>
        </li>
a;
if(isset($_GET["page"]) && $_GET["page"]!="account" && $_GET["page"]!="Logout")
{
    $b=$_GET["page"];
    }
    else
    $b="main";
    $a.=<<<a
    <script>
        $("#{$b}").addClass( "active" );
    </script>
a;
AddContent($not,"notification",0);
      AddContent($a,'links',0);
      AddContent($p,'profile',0);
}
else
{
AddContent("","links2",0);
      AddContent('','links',0);
       AddContent('','links3',0);
}
?>