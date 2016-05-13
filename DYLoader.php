<?php

class DYLoader {
	private $link;
	private $links;
	private $arr;
	
	public function __construct($link){
		$this->link = $link;
	}
	
	private function get_content() { 
		$string;
		
		if(!empty($url = $this->link)){
			$ch = curl_init(); 
			
			curl_setopt ($ch, CURLOPT_URL, $url); 
			curl_setopt ($ch, CURLOPT_HEADER, 0); 
			
			ob_start(); 
			
			curl_exec ($ch); 
			curl_close ($ch); 
			$string = ob_get_contents(); 
			
			ob_end_clean(); 
		}
		
		return $string; 
		
	}
	
	private function getUrls($string){
		$regex = '/https?[^\" ]+/i';
        preg_match_all($regex, urldecode($string), $matches);
        //return (array_reverse($matches[0]));
        return ($matches[0]);
	}
	
	private function getVideos($arr){
		$urls = array();
		
		for ($i =0;$i<count($arr);$i++) {
			$string = $arr[$i];
			if(strpos($string, "itag")!==false){
				$item = substr($string, 0, strpos($string, "\u0026")) ;
				array_push($urls, $item);
			}
			
		}
		return $urls;
		
	}
	
	public function getLinks(){
		
		if(!empty($this->links)){
			return $this->links;
		}
		
		$arr;
		if(!empty($this->link) && empty($this->links) ){
			$content = $this->get_content();
			$allUrls = $this->getUrls($content);
			$arr = $this->getVideos($allUrls);
			$this->links = $arr;
		}
		return $arr;
	}
	
	public function getData(){
		if(!empty($this->arr)){
			return $this->arr;
		}
		
		$arr = array();
		$data = $this->getLinks();
		for($i=0; $i<count($data);$i++){
			$string = $data[$i];
			
			if(strpos($string, "itag=5")!==false){
				$arr['240p-flv'] = $string;
			}else if(strpos($string, "itag=17")!==false){
				$arr['144p-3gp'] = $string;
			}else if(strpos($string, "itag=18")!==false){
				$arr['360p-mp4'] = $string;
			}else if(strpos($string, "itag=22")!==false){
				$arr['720p-mp4'] = $string;
			}else if(strpos($string, "itag=34")!==false){
				$arr['360p-flv-video'] = $string;
			}else if(strpos($string, "itag=35")!==false){
				$arr['480p-flv-video'] = $string;
			}else if(strpos($string, "itag=36")!==false){
				$arr['240p-3gp'] = $string;
			}
		}
		$this->arr = $arr;
		return $arr;
	}
	
	
}

?>