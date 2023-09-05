<?php
require __DIR__ . '/vendor/autoload.php';
require_once("prapare.php");
include("functions.php");

include("links.php");

if (isset($_GET["page"]))
    include($_GET["page"] . ".php");
else
    include("main.php");
print_out();
