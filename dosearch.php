<?php

$criterion = @htmlentities($_GET["criterion"], ENT_NOQUOTES);
if(strpos($criterion, '\"')){
    $criterion = stripslashes($criterion);
    echo $criterion.'<hr />';
} else {
    echo '\"'.$criterion.'<hr />';
}


?>