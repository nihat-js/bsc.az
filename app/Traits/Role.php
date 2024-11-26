<?php 

namespace App\Traits;


trait Role {
  public function echoHi($name){
    return "Hi, ".$this->email;
  }
}