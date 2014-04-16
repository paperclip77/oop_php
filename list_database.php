<!DOCTYPE html>

<html>
<head>
    <title>Page Title</title>
</head>

<style>
        /* screen = bigger than 0px */
        img{
            display: inline-block;
            max-height: 50px;
            max-width: 50px;
            margin: 2px;
        }
        
        
        @media (min-width: 500px) {
            /* screen = bigger than 500px */
            img{
                display: inline-block;
                max-height: 100px;
                max-width: 100px;
                margin: 4px;
            }
        
        }
        
        @media (min-width: 800px) {
            /* screen = bigger than 800px */
            img{
                display: inline-block;
                max-height: 150px;
                max-width: 150px;
                margin: 6px;
            }
        }
        
        .navigator a, span.inactive{
            display: inline-block;
            padding: 10px;
            border: 1px solid #EEE;
        }
        
    </style>

<body>
<?php
//require('MySQLConnect.php');
//require('PageNavigator.php');

function __autoload($class){
    require $class.'.php';
}

define("OFFSET", "offset");

$offset = @$_GET[OFFSET];
define("PERPAGE",5);

if(!isset($offset)){
    $recordoffset = 0;
} else {
    $recordoffset = $offset * PERPAGE;
}

$category = @$_GET["category"];
if(!isset($category)){
    $category = "LIT";
}

$strsql = "SELECT author, title FROM tblbooks WHERE sold = 0 AND cat = '$category' ORDER BY author LIMIT $recordoffset,".PERPAGE;
try{
    $con = MySQLConnect::getInstance('localhost', 'root', '{frostbite}');
}
catch(MySQLException $e){
    echo $e;
    exit();
}
catch(Exception $e){
    echo $e;
    exit();
}

$rs = $con->createResultSet($strsql, 'oophp_db');
/*
while($row = $rs->getRow()){
    echo $row[0]." - ".$row[1];
    echo '<br />';
}
*/

foreach($rs as $row){
    echo $row[0]." - ".$row[1];
    echo '<br />';
}

$pagename = basename($_SERVER['PHP_SELF']);
$totalrecords = $rs->getUnlimitedNumberRows();
$numpages = ceil($totalrecords/PERPAGE);
$otherparameters = "&amp;category=LIT";
if($numpages > 1){
    $nav = new PageNavigator($pagename, $totalrecords, PERPAGE, $recordoffset, 4, $otherparameters);
    echo $nav->getNavigator();
}

?>

<hr />



</body>
</html>
