<?php
$feed = "http://z.about.com/6/g/classicalmusic/b/index.xml";
//$feed = 'rss.xml';
//$feed = 'classicalmusic.xml';
$sxml = simplexml_load_file($feed);

echo '<h2>'.$sxml->channel->title.'</h2>';

foreach($sxml->channel->item as $item){
    $strtemp = '<a href="'.$item->link.'">'.$item->title.'</a>';
    $strtemp.='<p>'.$item->description.'</p>';
    echo $strtemp;
}

foreach($sxml->attributes() as $key => $value){
    echo "RSS $key $value";
}

//API KEY: AIzaSyBV3k9JglHbJG9BnprKCHouRAY7yOIaxjs

?>