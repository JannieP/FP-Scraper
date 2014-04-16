<?php

class FPListingScrape {

   public $cookie = "";
   public $delimiter = "<br>";
   public $result = '';
   public $path = '';
   public $test = "&nots";
   public $proxy = "";
   
   protected $items = array();

   public function getresults (){

      extract($GLOBALS);
      @set_time_limit(0);

      $this->phrase = urlencode($this->phrase);
      $this->exclude = urlencode($this->exclude);

      //http://www.focalprice.com/iphone-leather-case/ca-001012008.html

      $host = "http://www.focalprice.com";
      $path = "iphone-leather-case/ca-001012008.html";

      $this->getNewCookie();
      
      include_once('lib/tb_curl.php');
      $curl=new Curl();
      $curl->setDefaults();
      $curl->referer="http://google.com";
      $curl->url=$host.$path;
      $curl->cookieFileFrom=$this->cookie;
      $curl->cookieFileTo=$this->cookie;
      if($this->proxy != "") $curl->proxy=$this->proxy;
      $curl->timeout=60;

      $arr=$curl->doCurl();
      $this->result = $arr;
      $this->path = $path;

      $dom = new DOMDocument();
      @$dom->loadHTML($curl->content);
      
      $xpath = new DOMXPath($dom);
      
      $pathQuery = "//div[@class='itembox ml15 mb20']/ul";

      $elements = $xpath->query($pathQuery);

      $found=false;
      
      foreach($elements as $element){
      
         //foreach ($element->attributes as $attribute){
         
            //if($attribute->name=='class' && $attribute->value=='itembox ml15 mb20'){
            
               $this->items[] = array(
			'url' => $element->getElementsByTagName('a')->item(0)->getAttribute('href'),
			'title1' => $element->getElementsByTagName('a')->item(1)->getElementsByTagName('img')->item(0)->getAttribute('alt'),
			'title2' => $element->getElementsByTagName('a')->item(1)->getAttribute('title'),
			'title3' => $element->getElementsByTagName('a')->item(1)->nodeValue,
			'price' => $item->getElementsByTagName('span')->nodeValue
		);
               
               //echo 'Testing123';
            
            //}
         //}
      }
   }

   private function getNewCookie(){
      $this->cookie =tempnam ("/tmp", "TWT");    
      if (!file_exists($this->cookie)) {
         fopen($this->cookie,'w');
      }
      return $this->cookie;
   }

}
?>
