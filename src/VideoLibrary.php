<?php

// need to add the following to the /etc/apache2/extra/httpd-vhosts.conf file:
/*
Listen 5180
<VirtualHost *:5180>
  ServerAdmin webmaster@zaftig.net
  DocumentRoot "/Users/mjelks/Sites/test"
  ServerName www.test.dev
  ScriptAlias /cgi-bin/ /Users/mjelks/Sites/test/cgi-bin/
  ErrorLog /var/log/httpd/test-error_log
  CustomLog /var/log/httpd/test-access_log common
</VirtualHost>
*/
//$device = (stristr($_SERVER['HTTP_USER_AGENT'],"iphone")) ? 'iphone' : 'ipad';
//$device = 'iphone';

class VideoLibrary
{
    // all config is stored in our library file
	// needs 777 access for now
	public $library_file = "src/library/library.src";
	public $ignore_file = "src/library/ingore.src";
	
	// movies stored in this folder
	public $mediapath = "media";
	
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
		if (preg_match("/(.+)\.(m4v|mp4|mov)/",$file)) {
		  $match = false;
		  foreach ($lines as $line) {
			if (strstr($line, $file)) {
			  $match = true;
			  $meta = explode("|",$line);
			  $library_array[$file] = unserialize($meta[1]);
			}
		  }
		  if (!$match) {
			$stat = filemtime($path."/".$file);
			$props = array('mtime' => $stat);
			$meta = serialize($props);
			fwrite($fh,"$file|$meta\n");
			$library_array[$file] = $props;
		  }

		}
	  }
	  fclose($fh);

	  return $library_array;
	}

	
	//$library_array = $files = load_library($library,$path);

	/*
	// ignore section -- this is used to remove watched movies from our list (but doesn't delete files)
	$ignorepath = $basepath . "/src/ignorelist/ignore.txt";

	if (isset($_GET['ignore'])) {
	  $handle = fopen($ignorepath, "a");
	  foreach ($_GET['ignore'] as $k => $movie)
	  {
		fwrite($handle,$movie . "\n");
	  }
	  fclose($handle);
	}


	$ignorelist = array();
	// get contents of a file into a string
	// annoying weirdness -- this adds a newline -- so comparisons won't work unless
	// you do a trim or add \n to your other compare array
	$ignorelist = file($ignorepath);
	*/


	
}
?>