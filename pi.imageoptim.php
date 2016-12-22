<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$info = ee('App')->get('imageoptim');

class ImageOptim {
	public $return_data = '';

	function ImageOptim() {

		$dirname = $_SERVER['DOCUMENT_ROOT'].'/images/uploads/io_compressed';

		if (!file_exists($dirname)) {
		    mkdir($dirname, 0777, true);
		}

		$vars = '';
		//get image url
		$imageLink = ee()->TMPL->tagdata;
		
		//put compressed image where it belongs
		$image = explode(".", $imageLink);
		$imageDir = explode("/", $imageLink);
		$filename = $imageDir[4];
		
		//if it exists already then don't upload it again
		if (file_exists($dirname.'/'.$filename)) {
		    $this->return_data = $dirname.'/'.$filename;
		}
		
		//get all of the directories in order 
		$uploadFolder = array_slice($imageDir, 1, -2);
		$uploadFolder = implode("/", $uploadFolder);
		$uploadFolder = $uploadFolder.'/io_compressed/';
		$minifiedimg = $image[0]."-min.".$image[1];
		
		$options = "full";
		$postContext = stream_context_create(['http' => ['method' => 'POST']]);
		
		$baseurl = rtrim($this->url(), "/");
		if (strpos($imageLink, $baseurl) !== false) {
			echo $imageLink."1";
		    $imageData = file_get_contents('https://im2.io/wtkrqjrqgk/' . $options . $imageLink, false, $postContext);
		}else{
			$imageData = file_get_contents('https://im2.io/wtkrqjrqgk/' . $options .'/'. $baseurl.$imageLink, false, $postContext);
		}
		
		$imageFinal = "https://img.gs/wtkrqjrqgk/full/".$baseurl.$imageLink;
		$imageFile = file_get_contents($imageFinal);
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.$uploadFolder.$filename, $imageFile);
				
		$compressed_img = '/images/uploads/io_compressed/'.$filename;
		
		$this->return_data = $compressed_img;
	}
	
	function url(){
	    if(isset($_SERVER['HTTPS'])){
	        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	    }
	    else{
	        $protocol = 'http';
	    }
	    return $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
	
}

/* End of file pi.imageoptim.php */ 
/* Location: /__ee_admin/user/addons/imageoptim/pi.imageoptim.php */
?>