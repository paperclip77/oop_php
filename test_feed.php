<?php
$feed = "http://z.about.com/6/g/classicalmusic/b/index.xml";
$sxml = simplexml_load_file($feed);
foreach($sxml->attributes() as $key => $value){
    echo "RSS $key $value";
}
?>