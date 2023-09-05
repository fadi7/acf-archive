<?php

use setasign\Fpdi;

function AddContent($d, $k, $t = 2)
{
    global $Data;
    switch ($t) {
        case 0:
            $Data[$k] = $d;
            break;
        case 2:
            $Data[$k] .= $d;
            break;
        case 1:
            $Data[$k] = $d . $Data[$k];
            break;
    }
}
function print_out()
{
    if (isset($_SESSION["userid"]) != true) {
        global $Data;
        $tem = file_get_contents("login.html");
        foreach ($Data as $k => $v) {

            $tem = str_ireplace("#" . strtoupper($k) . "#", $v, $tem);
        }
        echo $tem;
    } else {
        global $Data;
        $tem = file_get_contents("template.html");
        foreach ($Data as $k => $v) {

            $tem = str_ireplace("#" . strtoupper($k) . "#", $v, $tem);
        }
        echo $tem;
    }
}
function getSources()
{
    $json_url = "data/sources.json";
    $json = file_get_contents($json_url);
    $data = json_decode($json, TRUE);
    $v = "";
    foreach ($data as $value)
        $v .= "'" . $value . "',";
    return $v;
}
function getSource()
{
    global $con;
    $result = mysqli_query($con, "SELECT * FROM source");
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $data[$i] = $row[1];

        $i = $i + 1;
    }


    $json_string = json_encode($data);

    $file = 'data/sources.json';
    file_put_contents($file, $json_string);
}
function changeProfile($id, $file)
{
    session_start();
    $id = $_SESSION["userid"];
    $conn = mysqli_connect("localhost", "arabcycl_archive", "cycle@@@!!!!1975###", "arabcycl_archive");
    mysqli_query($conn, "update users set photo='$file' where id=$id ");
    mysqli_close($conn);
    $_SESSION["photo"] = $file;
}
function removeProfile()
{
    global $con;
    session_start();
    $_SESSION["photo"] = "0.png";
    mysqli_query($con, "update users set photo='0.png' where id=1 ");
    echo mysqli_error($con);
}

function getCount($t, $y)
{
    global $con;
    $sql = mysqli_query($con, "select count(id) from box where type='$t' and year='$y' ;");
    echo mysqli_error($con);
    list($n) = mysqli_fetch_row($sql);
    echo mysqli_error($con);
    return $n;
}
function team()
{
    global $con;
    $a = "<select>";
    $sql = mysqli_query($con, "select * from teams");
    while ($res = mysqli_fetch_row($sql)) {
        $a .= "<option value='$res[0]'>$res[1]</option>";
    }
    $a .= "</select>";
    return $a;
}
function addBarcode($f, $bid, $oid, $date, $year)
{

    global $path;
    $path = "box/$f.pdf";

    class PDF extends Fpdi\Tcpdf\Fpdi
    {

        var $_tplIdx;

        function Header()
        {
            global $path;
            if ($this->_tplIdx === null) {
                $this->numPages = $this->setSourceFile($path);
                $this->_tplIdx = $this->importPage(1);
            }
            $this->useImportedPage($this->_tplIdx, 0, 0, 210);
        }

        function Footer()
        {
            // emtpy method body
        }
    }

    // initiate PDF
    $pdf = new PDF();
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $lg = array();
    $lg['a_meta_charset'] = 'UTF-8';
    $lg['a_meta_dir'] = 'rtl';
    $lg['a_meta_language'] = 'ar';
    $lg['w_page'] = 'page';

    $pdf->SetCreator('Arab Cycling Federation');
    $pdf->SetAuthor('Arab Cycling');
    $pdf->SetKeywords('Arab Cycling');
    $pdf->SetTitle($oid . "-" . $year);
    $pdf->SetSubject($oid);

    // set some language-dependent strings (optional)
    $pdf->setLanguageArray($lg);
    $pdf->SetAutoPageBreak(true, 40);
    $pdf->setFontSubsetting(false);
    // add a page
    $pdf->AddPage();


    // The new content
    $pdf->SetFont("helvetica", '', 9);
    $pdf->SetTextColor(0, 0, 0);
    $in = $year . "/ " . $oid;
    $pdf->text(28, 50.7, "$in");
    $pdf->text(28, 56.7, "$date");
    //barcode

    // set font
    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetY(0);

    // define barcode style
    $style = array(
        'position' => '3',
        'align' => '',
        'stretch' => false,
        'fitwidth' => true,
        'cellfitalign' => 'L',
        'cellfitalign' => 'L',
        'border' => FALSE,
        'hpadding' => '0',
        'vpadding' => 'auto',
        'fgcolor' => array(19, 39, 63),
        'bgcolor' => false, //array(255,255,255),
        'text' => FALSE,

    );

    // new style
    $style = array(
        'border' => false,
        'padding' => 0,
        'fgcolor' => array(25, 53, 105),
        'bgcolor' => false
    );

    // QRCODE,H : QR-CODE Best error correction
    $pdf->write2DBarcode('https://archive.arabcycling.com/check?id=' . $bid, 'QRCODE,H', 190.5, 12, 29, 29, $style, 'N');

    // $bar = $bid . "-" . $oid;

    // Interleaved 2 of 5 + CHECKSUM
    // $pdf->write1DBarcode("$bar", 'CODE11', '192', '43.1', '39', 4, 0.5, $style, 'N');



    // THIS PUTS THE REMAINDER OF THE PAGES IN
    if ($pdf->numPages > 1) {
        for ($i = 2; $i <= $pdf->numPages; $i++) {
            //$pdf->endPage();
            $pdf->_tplIdx = $pdf->importPage($i);
            $pdf->AddPage();
        }
    }


    //show the PDF in page
    //$pdf->Output();

    // or Output the file as forced download
    //unlink("box/$f.pdf");

    $pdf->Output(getcwd() . "/box/$f.pdf", 'F');
    $pdf->Output("$oid.pdf", 'D');
}
