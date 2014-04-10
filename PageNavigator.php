<?php
class PageNavigator{
    private $pagename;
    private $totalpages;
    private $recordsperpage;
    private $maxpagesshown;
    
    private $currentstartpage;
    private $currentendpage;
    private $currentpage;
    
    private $spannextinactive;
    private $spanpreviousinactive;
    private $firstinactivespan;
    private $lastinactivespan;
    
    private $firstparamname = 'offset';
    private $params;
    
    private $divwrappername = "navigator";
    private $pagedisplaydivname = "totalpagesdisplay";
    private $inactivespanname = "inactive";
    
    private $strfirst = "|&lt;";
    private $strnext = "Next";
    private $strprevious = "Prev";
    private $strlast = "&gt;|";
    
    private $errorstring;
    
    public function __construct($pagename, $totalrecords, $recordsperpage, $recordsoffset, $maxpagesshown = 4, $params = ""){
        $this->pagename = $pagename;
        $this->recordsperpage = $recordsperpage;
        $this->maxpagesshown = $maxpagesshown;
        $this->params = $params;
        
        $this->checkRecordOffset($recordsoffset, $recordsperpage) or die($this->errorstring);
        $this->setTotalPages($totalrecords, $recordsperpage);
        $this->calculateCurrentPage($recordsoffset, $recordsperpage);
        $this->createInactiveSpans();
        $this->calculateCurrentStartPage();
        $this->calculateCurrentEndPage();
        
    }
    
    private function checkRecordOffset($recordoffset, $recordsperpage){
        $bln = true;
        if($recordoffset % $recordsperpage != 0){
            $this->errorstring = "ERROR - not a multiple of records per page";
            $bln = false;
        }
        return $bln;
    }
    
    private function setTotalPages($totalrecords, $recordsperpage){
        $this->totalpages = ceil($totalrecords/$recordsperpage);
    }
    
    private function calculateCurrentPage($recordoffset, $recordsperpage){
        $this->currentpage = $recordoffset/$recordsperpage;
    }
    
    private function createInactiveSpans(){
        $this->spannextinactive = '<span class="'.$this->inactivespanname.'">'.$this->strnext.'</span>';
        $this->lastinactivespan = '<span class="'.$this->inactivespanname.'">'.$this->strlast.'</span>';
        $this->spanpreviousinactive = '<span class="'.$this->inactivespanname.'">'.$this->strprevious.'</span>';
        $this->firstinactivespan = '<span class="'.$this->inactivespanname.'">'.$this->strfirst.'</span>';
    }
    
    private function calculateCurrentStartPage(){
        $temp = floor($this->currentpage/$this->maxpagesshown);
        $this->currentstartpage = $temp * $this->maxpagesshown;
    }
    
    private function calculateCurrentEndPage(){
        $this->currentendpage = $this->currentstartpage + $this->maxpagesshown;
        if($this->currentendpage > $this->totalpages){
            $this->currentendpage = $this->totalpages;
        }
    }
    
    private function createLink($offset, $strdisplay){
        $strtemp = '<a href="'.$this->pagename.'?'.$this->firstparamname.'=';
        $strtemp .= $offset;
        $strtemp .= $this->params.'">'.$strdisplay.'</a>';
        return $strtemp;
    }
    
    private function getPageNumberDisplay(){
        $str = '<div class="'.$this->pagedisplaydivname.'">Page ';
        $str .= $this->currentpage + 1;
        $str .= ' of '.$this->totalpages;
        $str .= '</div>';
        return $str;
    }
    
    //PUBLIC METHODS
    public function getNavigator(){
        $strnavigator = '<div class="'.$this->divwrappername.'">';
        //first button
        if($this->currentpage == 0){
            $strnavigator .= $this->firstinactivespan;
        } else {
            $strnavigator .= $this->createLink(0, $this->strfirst);
        }
        
        //previous button
        if($this->currentpage == 0){
            $strnavigator .= $this->spanpreviousinactive;
        } else {
            $strnavigator .= $this->createLink($this->currentpage-1, $this->strprevious);
        }
        
        
        for($x = $this->currentstartpage; $x < $this->currentendpage; $x++){
            if($x == $this->currentpage){
                $strnavigator .= '<span class="'.$this->inactivespanname.'">';
                $strnavigator .= $x+1;
                $strnavigator .= '</span>';
            } else {
                $strnavigator .= $this->createLink($x, $x+1);
            }
        }
        
        
        //next button
        if($this->currentpage == $this->totalpages-1){
            $strnavigator .= $this->spannextinactive;
        } else {
            $strnavigator .= $this->createLink($this->currentpage + 1, $this->strnext);
        }
        
        //last button
        if($this->currentpage == $this->totalpages-1){
            $strnavigator .= $this->lastinactivespan;
        } else {
            $strnavigator .= $this->createLink($this->totalpages - 1, $this->strlast);
        }
        
        $strnavigator .= '</div>';
        $strnavigator .= $this->getPageNumberDisplay();
        
        return $strnavigator;
    }
    
    public function setFirstParamName($paramname){
        $this->firstparamname = $paramname;
    }
    
}
?>