<?php
echo <<<a
<html>
<head>
<meta charset="utf-8">
  <link href="https://fonts.googleapis.com/css?family=Cairo:400,700" rel="stylesheet">
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="jquery-barcode.js"></script>
<STYLE >
body{dir:rtl;}
table{
font-family:'Cairo';
font-size:11pt;
border:1px solid;
width:6cm;
height:3.5cm;
text-align:center

}
#demo{align:center;
margin: 0 auto
}

</style>

</head>
<body dir="rtl">
a;
 if(isset($_GET["id"]))
{
$id=addslashes($_GET["id"]);
$n=addslashes($_GET["num"]);
$date=addslashes($_GET["date"]);
$year="2018";
echo <<<a
<section>
<script>
window.print();
</script>
<table>
<tr>
<td colspan="4" style="    background: rgba(231, 76, 60, 0.2);"><b> الــوارد الـعــام</b></td>
</tr>
<tr>
<td >الرقم</td>     
<td style="color:#E74C3C" >$n</td>
<td rowspan="2"><img src="1.png" style="width:1.5cm;heigh:1.5cm"></td>
</tr>
<td >التاريخ</td>
<td style="color:#E74C3C">$date</td>
</tr>

</tr>
</tr>
<td colspan="4" ><div id="demo"></div></td>

</tr>
</table>



<script>
  var settings = {
barWidth: 1,
barHeight: 40,
moduleSize: 3,
showHRI: true,
addQuietZone: true,
marginHRI: 5,
bgColor: "#FFFFFF",
color: "#000000",
fontSize: 11,
output: "css",
posX: -45,
posY: 20,

        };
$("#demo").barcode(
"$n-$year-$id", // Value barcode (dependent on the type of barcode)
"code128" // type (string)
,settings

);     
</script>

</section>


</body>
</html>
a;
}
?>