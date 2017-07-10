<?php
if(!class_exists('Video_Fetcher')){
/**
 * Added since TrueMag 1.0
 * Author admin@cactusthemes.com
 *
 * This class helps to fetch video information from popular video network such as YouTube and Vimeo
 */
	class Video_Fetcher{
		/* Extract Video ID from URL */
		public static function extractIDFromURL($url){
			$channel = Video_Fetcher::extractChanneldFromURL($url);
			$id = '';
			
			switch($channel){
				 case 'youtube':
					$id = substr($url, strrpos($url,'v=')+2);
					break;
				case 'dailymotion':
					$id = substr($url, strrpos($url,'video/')+6);
					break;	
				case 'vimeo':
					$id = substr($url, strrpos($url,'/')+1);
					break;
				default:
					$id = '';
			}
			
			return $id;
		}
		
		/* Extract Channeld Name from URL */
		public static function extractChanneldFromURL($url){
			if(strpos($url,'youtube.com') !== false){
				return 'youtube';
			} else if(strpos($url,'vimeo.com') !== false){
				return 'vimeo';
			} else if(strpos($url,'dailymotion.com') !== false){
				return 'dailymotion';
			} else return '';
		}
		
		/* Fetch Video Metadata from URL 
		 *
		 * $url: Video URL
		 *
		 * return array() of field-values
		 * 			array("title"=>"","description"=>"","duration"=>"","tags"=>"");
		 */
		public static function fetchData($url,$fields = array()){
			$id = Video_Fetcher::extractIDFromURL($url);
			$channel = Video_Fetcher::extractChanneldFromURL($url);
			
			switch($channel){
				case 'youtube': return Video_Fetcher::fetchYoutubeData($id, $fields);
				case 'vimeo': return Video_Fetcher::fetchVimeoData($id, $fields);
				case 'dailymotion': return Video_Fetcher::fetchDailymotionData($id, $fields);
				default: return null;
			}
		}
		
		/* Fetch Youtube Video Metadata 
		 *
		 * require extension=php_openssl.dll in php.ini
		 */
		private static function fetchYoutubeData($video_id){
			$array = array('title'=>'','description'=>'','duration'=>'','tags'=>'','viewCount'=>'');
			$google_api_key ='';
			if(function_exists('ot_get_option')){
				$google_api_key = ot_get_option('google_api_key');
			}
			if($google_api_key==''){
				return false;
			}
			$xml = @file_get_contents('https://www.googleapis.com/youtube/v3/videos?id='.$video_id.'&key='.$google_api_key.'&part=snippet,contentDetails,statistics');
			
			if($xml !== false && $xml != ''){
				/* cannot parse XML using simplexml_load_string or DOMDocument because of child tags like "media:description"
				 * so we use preg_match to search
				 */
				$xml = json_decode($xml);
				$dura= $xml->{'items'}[0]->{'contentDetails'}->{'duration'};
				$dura= str_replace('PT','',$dura);
				$duration= '';
				if(strpos($dura,'H') !== false){
					$cut_t = explode("H",$dura);
					$hour = $cut_t[0]*60*60;
					$cut_t2 = explode("M",$cut_t[1]);
					$min = $cut_t2[0]*60;
					$sec = str_replace('S','',$cut_t2[1])*1;
					$duration = $hour + $min + $sec;
				}else if(strpos($dura,'M') !== false){
					$cut_t = explode("M",$dura);
					$min = $cut_t[0]*60;
					$sec = str_replace('S','',$cut_t[1])*1;
					$duration = $min + $sec;
				}else if(strpos($dura,'S') !== false){
					$sec = str_replace('S','',$dura)*1;
					$duration = $min + $sec;
				}
				$array['title'] = $xml->{'items'}[0]->{'snippet'}->{'title'};
				$array['description'] = $xml->{'items'}[0]->{'snippet'}->{'description'};
				$array['duration'] = $duration;
				$array['tags'] = '';
				$array['viewCount'] = $xml->{'items'}[0]->{'statistics'}->{'viewCount'};
				
			}
			
			return $array;
		}
		
		/* Fetch Vimeo Video Metadata */
		private static function fetchVimeoData($video_id){
			$array = array('title'=>'','description'=>'','duration'=>'','tags'=>'','viewCount'=>'');
			
			$xml = @file_get_contents('http://vimeo.com/api/v2/video/'.$video_id.'.xml');
						
			if($xml !== false && $xml != $video_id . ' is not a valid method.'){			
				$xml = simplexml_load_string($xml);								
				$vimeo = $xml->video[0];				
				if(method_exists($xml,'__toString')){
					$array['title'] = $vimeo->title[0]->__toString();
					$array['description'] = $vimeo->description[0]->__toString();
					$array['duration'] = $vimeo->duration[0]->__toString();
					$array['tags'] = $vimeo->tags[0]->__toString();
					if(isset($vimeo->stats_number_of_plays)){
						$array['viewCount'] = $vimeo->stats_number_of_plays[0]->__toString();				
					}
				} else {
					$array['title'] = $vimeo->title[0];
					$array['description'] = $vimeo->description[0];
					$array['duration'] = $vimeo->duration[0];
					$array['tags'] = $vimeo->tags[0];
					if(isset($vimeo->stats_number_of_plays)){
						$array['viewCount'] = $vimeo->stats_number_of_plays[0];
					}
				}
			}
			
			return $array;
		}
		/* Fetch dailymotion Metadata */
		private static function fetchDailymotionData($video_id){
			$array = array('title'=>'','description'=>'','duration'=>'','tags'=>'','viewCount'=>'');
			
			$xml = @file_get_contents('https://api.dailymotion.com/video/'.$video_id.'?fields=title,description,duration,views_total,tags');
			$xml = json_decode($xml,true);
			$array['title'] = $xml['title'];
			$array['description'] = $xml['description'];
			$array['duration'] = $xml['duration'];
			$array['tags'] = $xml['tags'];
			$array['viewCount'] = $xml['views_total'];
			//print_r($array);exit;	
			return $array;
		}

	}
}