<?php

class Request{
  public function isPost(){
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      return true;
    }
  }

  public function getPathInfo(){
    return $_SERVER['REQUEST_URI'];
  }
}
