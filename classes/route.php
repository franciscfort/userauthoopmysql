<?php
class signupcontr {
  private  $uid;
     private  $pwd;
     private  $pwdRepeat; 
     private  $email;
  
  public function  __connect($uid, $pwd, $pwdRepeat,$email) {
    $this->$uid =$uid;
    $this->$pwd =$pwd;
    $this->$pwdRepeat =$pwdRepeat;
    $this->$email =$email;
  }
  
  private function  emptyinput() {
    $result;
    if (empty($this->$uid) || empty($this->$pwd) ||  empty($this->$pwdRepeat) ||  empty($this->$email)){
      $result = false; 
    } 
      else {
         $result = true;
      
      }
    return $result;
        }
