<?php
session_start();
$con = mysqli_connect(
  "localhost",
  "root",
  "123",
  // "arabcycling_archive",
  // "cycle@@@!!!!1975###",
  "acf_archive"
);

mysqli_set_charset($con, 'utf8');

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


$Data = array(
  'head' => ' ',
  'body' => ' ',
  'body1' => ' ',
  'links' => '',
  'links2' => '',
  'links3' => '',
  'links4' => '',
  'title' => '',
  'title2' => '',
  'float' => '',
  'code' => ''
);
