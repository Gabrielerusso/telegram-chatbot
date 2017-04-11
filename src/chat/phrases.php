<?php


interface iPHRASES{
  public function get_phrase($type = "");
  public function insert_phrase($type = "", $phrase = "");
  public function read_from_file($file_path = "");
}

class PHRASES implements iPHRASES
{
  protected $phrases;
  protected $n_phrases;

  public function get_phrase($type = "")
  {
    if( !isset($this->phrases["$type"]) ) return -1;
    $pos = rand(0,$this->n_phrases - 1);
    return $this->phrases[$type][$pos];
  }

  public function insert_phrase($type = "", $phrase = "")
  {
    $this->phrases[$type][] = $phrase;
    if( isset($this->n_phrases["$type"]) )
      $this->n_phrases["$type"]++;
    else
      $this->n_phrases["$type"]=1;
  }

  public function read_from_file($file_path = "")
  {

  }
  
}


?>
