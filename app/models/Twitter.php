<?php

class Twitter extends AppModel{
	

	public function getTweetsFrom($screen_name, $count){
		$cacheName = 'twitter_'.$screen_name.'_'.$count;

		if(!Core::isCached($cacheName) || Core::cachedSince($cacheName) > 300){
			$url = 'http://twitter.com/statuses/user_timeline/'.$screen_name.'.xml?count='.$count;
			$twitter = curl_init();
			curl_setopt($twitter,CURLOPT_URL,$url);
			curl_setopt($twitter,CURLOPT_TIMEOUT,2);
			curl_setopt($twitter,CURLOPT_RETURNTRANSFER,true);
			$content = curl_exec($twitter);
			Core::writeCache($cacheName, $content);
		}else{
			$content = Core::getCachedFile($cacheName);
		}
		return new SimpleXMLElement($content);
	}


}

?>