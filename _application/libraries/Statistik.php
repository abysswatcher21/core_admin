<?php
/****************************************************************

  This is Statistik by David Higgins (http://zoulcreations.com/)

  All editable items are at the bottom of the source ... 

  Do not edit any portion of the actual class, without knowing
  what you are doing ... 


  Any comments, questions or suggestions:  higginsd@zoulcreations.com

****************************************************************/

class Statistik {
  var $cTotal;    // Grand Total
  var $cCurrent;  // Current Visitors total
  var $cVisitor;  // Visitor ID
  var $cDataFile; // data file path
  var $cUnique;   // Unique visitors to site
  
  var $cFP;       // file pointer to counter dat file
  var $cData;     // Data in data file

  function Statistik($f /* filename */) {
    // constructor!
    $this->cTotal=0;
    $this->cCurrent=0;
    $this->cVisitor=0;
    $this->cUnique=0;
    $this->cDataFile=$f;
    $this->cData=array();
    if(!file_exists($f)) {
      //echo "Counter data file[$f] not found<br>\n";
      touch($f);
    }
    //echo "Filesize: " . filesize($f) . "<br>\n";
    if(filesize($f) > 0) {
      // file exists, lets get the data!
      //echo "File exists!!!<br>\n";
      $this->cFP = fopen($f, 'r');
      $this->cData = explode("\n", fread($this->cFP, filesize($f)));
      fclose($this->cFP);
    } else {
      // file does not exist, first visit!!!
      //echo "File does not exist<br>\n";
      $this->cFP = fopen($f, 'w');
      fwrite($this->cFP, $_SERVER['REMOTE_ADDR'] . '|0');
      $this->cData[0] =$_SERVER['REMOTE_ADDR'] . '|0';
      fclose($this->cFP);
    }
    //echo "this->cData dump: "; var_dump($this->cData); echo "<br>\n";
    $match = false;
    foreach($this->cData as $k=>$a) {
      if(is_integer(strpos($a, '|'))) {
        $data = explode('|', $a);
        //echo "Vardump: "; var_dump($a); echo "<br>\n";
        //foreach($data as $x=>$y) { echo "<b>$a</b><br>\nX: $x &nbsp;&nbsp;&nbsp; Y:$y<br>\n";}
        //echo "Sizeof: " . sizeof($data) . "<br>\n";
        $this->cTotal += (int)trim($data[1]);
        $this->cUnique++;
        if(strstr($a, $_SERVER['REMOTE_ADDR'])) {
          $match = true;
          //echo "Found a match<br>\n";
          $this->cTotal += 1; // this visit counts, no? :P
          $this->cVisitor = ((int)trim($k) + 1);
          $this->cCurrent = ((int)trim($data[1]) + 1); // internal values
          //echo "Sizeof: " . sizeof($data) . "<br>\n";
          $this->cData[$k] = $_SERVER['REMOTE_ADDR'] . '|' . ((int)trim($data[1])+1); // change values ...
        }
      }
    }
    if(!$match) {
      //echo "You are a new unique host<br>\n";
      array_push($this->cData, $_SERVER['REMOTE_ADDR'] . '|1');
      $this->cTotal += 1;
      $this->cCurrent = 1;
      $this->cVisitor = (int) (sizeof($this->cData) - 1);
    }
    //if(is_resource($this->cFP)) fclose($this->cFP);
    $fp = fopen($this->cDataFile, 'w');
    for($x=0;$x<sizeof($this->cData);$x++) {
      //echo "K: $k&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A: '$a'<br>\n";
      if($this->cData[$x] != '') fwrite($fp, $this->cData[$x] . "\n");
    }
    fclose($fp);
  }

  function GrandTotal() {
    echo $this->cTotal;
  }

  function CurrentTotal() {
    echo $this->cCurrent;
  }
  
  function Visitor() {
    echo $this->cVisitor;
  }
  
  function Unique() {
    echo $this->cUnique;
  }
}
?>