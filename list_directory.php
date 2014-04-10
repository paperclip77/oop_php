<html>
    <head>
        <title>Images</title>
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
        require('PageNavigator.php');
        require('DirectoryItems.php');
        
        define("PERPAGE", 5);
        define("OFFSET", "offset");
        
        $offset = @$_GET[OFFSET];
        
        if(!isset($offset)){
            $totaloffset = 0;
        } else {
            $totaloffset = $offset * PERPAGE;
        }
        
        
        echo '<h1>JPG</h1>';
        $di = new DirectoryItems('images', '_', 'jpg');
        //$di->checkAllImages() or die("Not all files are images");
        $di->naturalCaseInsensitiveOrder();
        
        $filearray = $di->getFileArraySlice($totaloffset, PERPAGE);
        
        foreach($filearray as $key=>$value){
            echo '<img src="images/'.$key.'" title="'.$value.'" />';
            echo '<img src="getthumb.php?path=images/'.$key.'&size=40" title="'.$value.'" />';
            //echo $value.'<br />';
        }

        $pagename = basename($_SERVER["PHP_SELF"]);
        $totalcount = $di->getCount();
        $numpages = ceil($totalcount/PERPAGE);
        
        if($numpages > 1){
            $nav = new PageNavigator($pagename, $totalcount, PERPAGE, $totaloffset);
            $nav->setFirstParamName(OFFSET);
            echo $nav->getNavigator();
        }
        
        ?>
    </body>
</html>