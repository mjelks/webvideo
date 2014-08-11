<?php

// need to add the following to the /etc/apache2/extra/httpd-vhosts.conf file:
/*

 Listen 5180
<VirtualHost *:5180>
  ServerAdmin webmaster@domain.com
  DocumentRoot /path/to/webroot
  ServerName www.servername.dev
  ServerAlias servername.dev
  ErrorLog /path/to/error_log
  CustomLog /path/to/access_log common
</VirtualHost>
 
 */


class VideoLibrary
{
	/* USER CONFIG AREA */
	
	// movies stored in this folder
	public $mediapath = "media";
	// file extensions supported by your device (iOS in this case)
	public $extensions = "m4v|mp4|mov";
	
	/* END USER CONFIG AREA */
	
	// all config is stored in our library file
	// needs 777 access for now
	public $library_file = "src/library/library.src";
	public $ignore_file = "src/library/ingore.src";
	
	public $library;
	
	
	function __construct(){  
	  $this->library = $this->loadLibrary($this->library_file, $this->mediapath);
	}
	
	public function getMediaPath() {
	  return $this->mediapath;
	}
	
    // method declaration
    public function getLibrary() {
	  return $this->library;
    }
	
	private function loadLibrary($library, $path) {
	  $files = scandir($path); // get list of all our files in the media folder
	  $library_array = array();

	  $lines = explode("\n",file_get_contents($library));
	  $fh = fopen($library, 'a+') or die("can't open file");
	  foreach ($files as $file) {
		if (preg_match("/(.+)\.($this->extensions)/",$file,$matches)) {
		  $match = false;
		  foreach ($lines as $line) {
			if (strstr($line, $file)) {
			  $match = true;
			  $meta = explode("|",$line);
			  $library_array[] = unserialize($meta[1]);
			}
		  }
		  if (!$match) {
			$stat = filemtime($path."/".$file);
			$short_name = substr($file, 0, 24);
			$props = array('name' => $file, 'mtime' => $stat, 'rating' => 0, 'short_name' => $short_name, 'ignore' => false );
			$meta = serialize($props);
			fwrite($fh,"$file|$meta\n");
			$library_array[] = $props;
		  }

		}
	  }
	  fclose($fh);
	  
	  return $library_array;
	}
	
	public function sortLibrary($type) {
	  
	  if ($type == "mtime") {
		usort($this->library, function($a, $b) {
		  return $b['mtime'] - $a['mtime'];
		});
	  }
	  
	  if ($type == "rating") {
		usort($this->library, function($a, $b) {
		  return $b['rating'] - $a['rating'];
		});
	  }
	}
	
	public function getFriendlyName($movie) {
	  $patterns = array('/_/', "/$this->extensions/");
	  $replacements = array(' ', '');
	  
	  return preg_replace($patterns, $replacements, $movie);
	}
	
	public function updateRating($movie, $rating) {
	  $lines = explode("\n",file_get_contents($this->library_file));
	  $fh = fopen($this->library_file, 'w') or die("can't open file");
	  foreach ($lines as $line) {
		if (strstr($line, $movie)) {
		  $meta = explode("|",$line);
		  $library_array = unserialize($meta[1]);
		  $library_array['rating'] = $rating;
		  $meta = serialize($library_array);
		  fwrite($fh,"$movie|$meta\n");
		}
		else {
		  fwrite($fh, $line . "\n");
		}
	  }
	  fclose($fh);
	}
	
}
?>