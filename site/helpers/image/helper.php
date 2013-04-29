<?php 

/**
 * @package     Sven.Bluege
 * @subpackage  com_eventgallery
 *
 * @copyright   Copyright (C) 2005 - 2013 Sven Bluege All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
jimport('joomla.error.log');
	
class EventgalleryHelpersImageHelper {	


	public static function getPicasawebResult($url) {
		
		JLog::addLogger(
		    array(
		        'text_file' => 'com_eventgallery.log.php'
		    ),
		    JLog::ALL, 
		    'com_eventgallery'
		);
		//JLog::add('processing url '.$url, JLog::INFO, 'com_eventgallery');
		
		
		$cachebasedir=JPATH_CACHE.DIRECTORY_SEPARATOR.'com_eventgallery';
		if (!is_dir($cachebasedir))
		{
		    //mkdir($cachebasedir, 0777);
		    mkdir($cachebasedir);
		    #echo "created $cachebasedir <br>";
		    JLog::add('have to create dir '.$cachebasedir, JLog::INFO, 'com_eventgallery');
		    
		  
		}
		$cachebasedir=JPATH_CACHE.DIRECTORY_SEPARATOR.'com_eventgallery'.DIRECTORY_SEPARATOR.'picasa';
		$cachefilename = $cachebasedir.DIRECTORY_SEPARATOR.md5($url).'.xml';
		
		if (!is_dir($cachebasedir))
		{
		    //mkdir($cachebasedir, 0777);
		    mkdir($cachebasedir);
		    #echo "created $cachebasedir <br>";
		    JLog::add('have to create dir '.$cachebasedir, JLog::INFO, 'com_eventgallery');
		    
		  
		}
		
		
		$cache_life = '360000'; //caching time, in seconds
		
		$xml = "";
		
		if (file_exists($cachefilename) && (time() - filemtime($cachefilename) <= $cache_life) ) {
			$xml = file_get_contents($cachefilename);
			
		} else {
			
			//JLog::add('have write new cache file for '.$url, JLog::INFO, 'com_eventgallery');
			
		    $xml = @file_get_contents($url);
		    #echo "reloading content from $url <br>";
		    if (strlen($xml)>0) {
				$fh = fopen($cachefilename, 'w') or die("can't open file $cachefilename");		
				fwrite($fh, $xml);
				fclose($fh);
			}
			JLog::add('have wrote xml to file '.$cachefilename, JLog::INFO, 'com_eventgallery');
			
		}
		
		return $xml;
	
	}


   /**
    * The following values are valid for the thumbsize and imgmax query parameters and are embeddable on a webpage. These images 
    * are available as both cropped(c) and uncropped(u) sizes by appending c or u to the size. As an example, to retrieve a 72 pixel 
	* image that is cropped, you would specify 72c, while to retrieve the uncropped image, you would specify 72u for the thumbsize or 
	* imgmax query parameter values.
	* 
	* 32, 48, 64, 72, 104, 144, 150, 160
	* 
	* The following values are valid for the thumbsize and imgmax query parameters and are embeddable on a webpage. These images are 
	* available as only uncropped(u) sizes by appending u to the size or just passing the size value without appending anything.
	* 
	* 94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600
	* 
    */
    public static function picasaweb_ListAlbum($userName, $albumNameOrId, $picasaKey = null, $imagesize = 1280) {

		$thumbsizeArray = array(32,48,64,72,104,144,150,160,'32u','48u','64u','72u','104u','144u','150u','160u',94,110,128,200,220,288,320,400,512,576,640,720,800,912,1024,1152,1280,1440);
		$thumbsize = implode(',',$thumbsizeArray);
		
		
		$authkeyParam = (strlen($picasaKey)>0)?"authkey=$picasaKey&":"";
		
		if (is_numeric($albumNameOrId)) {
			$url = 'http://picasaweb.google.com/data/feed/api/user/' .urlencode($userName) . '/albumid/' . urlencode($albumNameOrId) . "?".$authkeyParam."thumbsize=$thumbsize&imgmax=$imagesize&prettyprint=true";
		} else {
			$url = 'http://picasaweb.google.com/data/feed/api/user/' .urlencode($userName) . '/album/' . urlencode($albumNameOrId) . "?".$authkeyParam."thumbsize=$thumbsize&imgmax=$imagesize&prettyprint=true";
		}
		

		$xml = EventgalleryHelpersImageHelper::getPicasawebResult($url);
		
	    $xml = str_replace("xmlns='http://www.w3.org/2005/Atom'", '', $xml);

		if (strlen($xml)==0) {
			return Array();
		}
		
	    $dom = new domdocument;
	    $dom->loadXml($xml);

	    $xpath = new domxpath($dom);
	    $nodes = $xpath->query('//entry');
	    
	    $album = Array();
	    $photos  = Array();
	    
	    foreach ($nodes as $node) {

	    	$photo = Array();
	        
	        $thumbnailNodes = $xpath->query('.//media:thumbnail',$node);
	        
	        $thumbnails = Array();
	        $thumbnailsCrop = Array();
	        
	        foreach($thumbnailNodes as $thumbnailNode) {
	        	if ($thumbnailNode->getAttribute('width')==$thumbnailNode->getAttribute('height')) {
	        		$thumbnailsCrop[$thumbnailNode->getAttribute('width')] = $thumbnailNode->getAttribute('url');
	        	} else {
	        		$thumbnails[$thumbnailNode->getAttribute('width')] = $thumbnailNode->getAttribute('url');
	        	}
	        }
	        
	        $image = $xpath->query('.//media:content', $node)->item(0);
			
			
			$photo['image'] = $image->getAttribute('url');
			$photo['width'] = $image->getAttribute('width');
	        $photo['height'] = $image->getAttribute('height');
	        
	        $photo['thumbs'] = $thumbnails;
	        
	        //$photo['thumbsCrop'] = $thumbnails;
	        // this works because picasa can deliver every image as a crop
	         $photo['thumbsCrop'] = EventgalleryHelpersImageHelper::createCropThumbArray($photo['image'],$thumbsizeArray,"s".$photo['width']);
			
			//print_r($photo['thumbs']);
	        
	        
			$photo['caption'] = $xpath->query('.//summary',$node)->item(0)->textContent;
			$photo['folder'] = $userName.'@'.$albumNameOrId;
			$photo['file'] = $xpath->query('.//gphoto:id',$node)->item(0)->textContent;
			$photo['commentCount'] = $xpath->query('.//gphoto:commentCount',$node)->item(0)->textContent;
				
			
			
			$exif = Array();
			
			$items = $xpath->query('.//exif:tags/exif:fstop',$node); 		$items->length>0 ? $exif['fstop'] = $items->item(0)->textContent 		: $exif['fstop'] = '';
			$items = $xpath->query('.//exif:tags/exif:focallength',$node);	$items->length>0 ? $exif['focallength'] = $items->item(0)->textContent 	: $exif['focallength'] = '';
			$items = $xpath->query('.//exif:tags/exif:model',$node);		$items->length>0 ? $exif['model'] = $items->item(0)->textContent 		: $exif['model'] = '';
			$items = $xpath->query('.//exif:tags/exif:iso',$node);			$items->length>0 ? $exif['iso'] = $items->item(0)->textContent 			: $exif['iso'] = '';
			
			$photo['exif'] = (object)$exif;
			$photo['allowcomments'] = 0;
			
			
	        $photos[] = new EventgalleryHelpersImagePicasa($photo);;
	        unset($photo);
	    }
	    
	    $album['photos'] = $photos;
	    $album['overallCount']=$xpath->query('//feed/openSearch:totalResults')->item(0)->textContent;
	    $album['date']= strftime("%Y-%m-%d %H:%M:%S", $xpath->query('//feed/gphoto:timestamp')->item(0)->textContent/1000);
	    $album['text']=$xpath->query('//feed/subtitle')->item(0)->textContent;
	    $album['description']=$xpath->query('//title')->item(0)->textContent;
	    $album['thumbs']=EventgalleryHelpersImageHelper::createCropThumbArray($xpath->query('//icon')->item(0)->textContent, $thumbsizeArray);
	    $album['width'] = 1440;
	    $album['height'] = 1440;
	    $album['thumbsCrop']=$album['thumbs'];
	    
	    
		#echo "<pre>"; 		print_r($album);		echo "</pre>";
	    return (object)$album;
	}


	public static function createCropThumbArray($thumbUrl, $thumbsizeArray) {
		
		$thumbs = Array();
		
		foreach ($thumbsizeArray as $thumbsize) {
			if (strpos($thumbsize,'u')==0) {
				$thumb = preg_replace("/\/s(\d+)-c/","/s$thumbsize-c",$thumbUrl);
				$thumb = preg_replace("/\/s(\d+)\//","/s$thumbsize-c/",$thumb);
					
				$thumbs[$thumbsize] =  $thumb;
			}
		}
		#echo "<pre>";	echo $thumbUrl."\n\n";	print_r($thumbs);	echo "</pre>";
		return $thumbs;
	}
	

	public static function picasaweb_ListAlbums($userName, $key, $thumbsize = 666) {

		$url = 'http://picasaweb.google.com/data/feed/api/user/'.urlencode($userName).'?authKey=$key&kind=album';
		$xml = file_get_contents($url);
		$xml = str_replace("xmlns='http://www.w3.org/2005/Atom'", '', $xml);

		$albums = Array();

	    $dom = new domdocument;
	    $dom->loadXml($xml);

	    $xpath = new domxpath($dom);
	    $nodes = $xpath->query('//entry');
	    foreach ($nodes as $node) {

	        $imageUrl = $xpath->query('.//media:thumbnail/@url', $node)->item(0)->textContent;
	        $imageUrl = str_replace('?imgmax=160','?imgmax='.$thumbsize,$imageUrl);

	        $albumId    = $xpath->query('.//gphoto:id',$node)->item(0)->textContent;
	        $albumName  = $xpath->query('.//gphoto:name',$node)->item(0)->textContent;
	        $albumTitle = $xpath->query('.//media:title',$node)->item(0)->textContent;
	        $imageCount = $xpath->query('.//gphoto:numphotos',$node)->item(0)->textContent;
	        $published  = $xpath->query('.//published',$node)->item(0)->textContent;

	        $album = Array();
	        $album['folder']        = "$userName@$albumId";
	        $album['name']      = $albumName;
	        $album['description']     = $albumTitle;
	        $album['image']     = $imageUrl;
	        $album['date'] = $published;
	        $album['overallCount']     = $imageCount;
	        $album['url']       = 'http://picasaweb.google.com/'.urlencode($userName).'/'.urlencode($album['name']);

	        $albums[] = (object)$album;
	        
	        unset($album);
	    }
		
	    return $albums;
	}
}

?>