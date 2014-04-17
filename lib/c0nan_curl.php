<?php

$d=true; //debug

class Curl{

   public $url="";
   public $javascriptMaxRedirects=10;
   public $javascriptLoop=0;
   public $timeout = 30;
   public $postString = "";
   public $useSsl=false;
   public $aditionalHeaders=array();
   public $referer="";
   public $cookieFileFrom="";
   public $cookiefileTo="";
   public $userAgent="";
   public $followLocation=true;
   public $encoding="";
   public $returnTransfer=true;
   public $maxRedirects=10;
   public $content="";
   public $response;
   public $headers;
   public $proxy="";
   
   
   function setDefaults(){
      $this->aditionalHeaders =array(
         "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729)",
         "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
         "Accept-Language: en-za,en-gb;q=0.8,en-us;q=0.7,af-za;q=0.5,af;q=0.3,en;q=0.2",
         "Accept-Encoding: gzip,deflate",
         "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
         "Keep-Alive: 300",
         "Connection: keep-alive"
      );

      $this->url="";
      $this->javascriptMaxRedirects=10;
      $this->javascriptLoop=0;
      $this->timeout = 30;
      $this->postString = "";
      $this->useSsl=false;
      //$this->aditionalHeaders=array();
      $this->referer="";
      $this->cookieFileFrom="";
      $this->cookieFileTo="";
      $this->userAgent="";
      $this->followLocation=true;
      $this->encoding="";
      $this->returnTransfer=true;
      $this->maxRedirects=10;
      $this->content="";
      $this->response=array();
      $this->headers="";

   }
   
   function setAjax(){
      $this->aditionalHeaders =array(
         "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 (.NET CLR 3.5.30729) FirePHP/0.4",
         "Accept: application/json, text/javascript, */*",
         "Accept-Language: en-za,en-gb;q=0.8,en-us;q=0.7,af-za;q=0.5,af;q=0.3,en;q=0.2",
         "Accept-Encoding: gzip,deflate",
         "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
         "Keep-Alive: 300",
         "Connection: keep-alive",
         "Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
         "X-Requested-With: XMLHttpRequest"
      );

      $this->url="";
      $this->javascriptMaxRedirects=10;
      $this->javascriptLoop=0;
      $this->timeout = 30;
      $this->postString = "";
      $this->useSsl=false;
      $this->referer="";
      $this->cookieFileFrom="";
      $this->cookieFileTo="";
      $this->userAgent="";
      $this->followLocation=true;
      $this->encoding="";
      $this->returnTransfer=true;
      $this->maxRedirects=10;
      $this->content="";
      $this->response=array();
      $this->headers="";


   }

   function doCurl($url=""){

      extract($GLOBALS);
      
      //echo $this->url."<br>";

      $ch = curl_init();
      curl_setopt( $ch, CURLOPT_HTTPHEADER,$this->aditionalHeaders);
      curl_setopt( $ch, CURLOPT_USERAGENT,$this->userAgent);
      
      if($url.""==""){
         curl_setopt( $ch, CURLOPT_URL,$this->url);
      }else{
         curl_setopt( $ch, CURLOPT_URL,$url);
      }

      if($this->cookieFileFrom."" != ""){
         //echo "cookieFrom:".$this->cookieFileFrom."<br>";
         curl_setopt( $ch, CURLOPT_COOKIEFILE,$this->cookieFileFrom);
      }
      if($this->cookieFileTo."" != ""){
         //echo "cookieTo:".$this->cookieFileTo."<br>";
         curl_setopt( $ch, CURLOPT_COOKIEJAR,$this->cookieFileTo);
      }
         

      curl_setopt( $ch, CURLOPT_FOLLOWLOCATION,$this->followLocation);
      curl_setopt( $ch, CURLOPT_ENCODING,$this->encoding);
      curl_setopt( $ch, CURLOPT_RETURNTRANSFER,$this->returnTransfer);

      if($this->referer.""==""){
         curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
      }else{
         curl_setopt( $ch, CURLOPT_REFERER,$this->referer);
      }
      
      if($this->useSsl==true){
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
         
      }
      curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER,false);

      curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT,$this->timeout);
      curl_setopt( $ch, CURLOPT_TIMEOUT,$this->timeout);

      curl_setopt( $ch, CURLOPT_MAXREDIRS,$this->maxRedirects);

      if ($this->postString."" != "") {
         //echo "<textarea cols='80' rows='5'>".$this->postString."</textarea>";
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postString);
      }
      
      if ($this->proxy."" != ""){
         curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL ,true);
         curl_setopt($ch, CURLOPT_PROXY,$this->proxy);
      }
      
      $this->content = curl_exec( $ch );
      $this->response = curl_getinfo( $ch );
      //echo "<textarea cols='80' rows='5'>".$this->content."</textarea>";
      
      curl_close ( $ch );

      if ($this->response['http_code'] == 301 || $this->response['http_code'] == 302){
         
         if ($this->headers = get_headers($this->response['url'])){
            foreach($this->headers as $value){
               if(substr(strtolower($value),0,9) == "location:"){
                  return $this->get_url(trim(substr($value,9,strlen($value))));
               }
            }
         }
      }

      if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i",$this->content, $value) 
        || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i",$this->content, $value)) 
        && $this->javascriptLoop < $this->javascriptMaxRedirects){
         $this->javascriptLoop++;
         return get_url($value[1]);
      }
      else
      {
         return array($this->content, $this->response);
      }
  }
}
?>
