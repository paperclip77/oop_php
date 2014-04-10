<?php
class DirectoryItems{
    private $filearray = array();
    private $directory;
    private $replacechar;
    
    public function __construct($directory, $replacechar = "_", $ext = ""){
        $this->directory = $directory;
        $this->replacechar = $replacechar;
        $d = "";
        if(is_dir($directory)){
            $d = opendir($directory) or die("Couldn't open directory.");
            while(false !== ($f=readdir($d))){
                if(is_file("$directory/$f")){
                    
                    $title = $this->createTitle($f);
                    if($ext == ""){
                        $this->filearray[$f] = $title;
                    } else {
                        if($this->getExtension($f) == strtolower($ext)){
                            $this->filearray[$f] = $title;
                        }
                    }
                }
            }
            closedir($d);
        } else {
            die("Must pass in a directory");
        }
    }
    
    private function createTitle($title){
        $e_array = explode(".", $title);
        array_pop($e_array);
        $title = implode(".",$e_array);
        $title = str_replace($this->replacechar," ",$title);
        return $title;
    }
    
    private function getExtension($file_name){
        $e_array = explode(".", $file_name);
        $ext = $e_array[count($e_array)-1];
        $ext = strtolower($ext);
        return $ext;
    }
    
    public function getFileArray(){
        return $this->filearray;
    }
    
    public function indexOrder(){
        sort($this->filearray);
    }
    
    public function naturalCaseInsensitiveOrder(){
        natcasesort($this->filearray);
    }
    
    public function getCount(){
        return count($this->filearray);
    }
    
    public function checkAllSpecificType($extension){
        $extension = strtolower($extension);
        $bln = true;
        $ext = "";
        foreach($this->filearray as $key => $value){
            $e_array = explode(".", $value);
            $ext = $e_array[count($e_array)-1];
            $ext = strtolower($ext);
            if($ext != $extension){
                $bln = false;
                break;
            }
        }
        return $bln;
    }
    
    public function checkAllImages(){
        $bln = true;
        $extension = "";
        $types = array("jpg", "jpeg", "gif", "png", "ds_store");
        foreach($this->filearray as $key => $value){
            //$extension = substr($value,(strpos($value,".")+1));
            
            $e_array = explode(".", $value);
            $extension = $e_array[count($e_array)-1];
            
            $extension = strtolower($extension);
            if(!in_array($extension, $types)){
                $bln = false;
                break;
            }
        }
        return $bln;
    }
    
    public function getFileArraySlice($start, $numberitems){
        return array_slice($this->filearray, $start, $numberitems);
    }
}
?>