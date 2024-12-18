<?PHP 
 function getPostDate($url) {
 $data = file_get_contents($url);
$date = preg_match('/<!--date=(.*?) -->/ims', $data, $matches) ? $matches[1] : null;
    return $date;
}
 function getTitle($url) {
 $data = file_get_contents($url);
//$title = preg_match('/<!--title=(.*?) -->/ims', $data, $matches) ? $matches[1] : null;
    $title = preg_match('/<h1[^>]*>(.*?)<\/h1>/ims', $data, $matches) ? $matches[1] : null;
    return $title;
}
  function sortByTimestamp( $a, $b ) {
    if ($a[ 'day' ] == $b[ 'day' ]) {
        return 0;
    }
    return ($a[ 'day' ] > $b[ 'day' ]) ? -1 : 1;
}

// Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
  function getFileListcat($dir)
  {
    // array to hold return value
    $retval = array();
    // add trailing slash if missing
    if(substr($dir, -1) != "/") $dir .= "/";
    // open pointer to directory and read list of files
    $d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading");
    while(false !== ($entry = $d->read())) {
      // skip hidden files
      if($entry[0] == ".") continue;
      if(is_dir("$dir$entry")) {
        $retval[] = array(
          "name" => "$dir$entry/",
          "type" => filetype("$dir$entry"),
          "size" => 0,
          "lastmod" => filemtime("$dir$entry")
        );
      } elseif(is_readable("$dir$entry")) {
        $retval[] = array(
          "name" => "$dir$entry",
          "type" => mime_content_type("$dir$entry"),
          "size" => filesize("$dir$entry"),
          "lastmod" => filemtime("$dir$entry"),
	// added
	"tit" => getTitle("$dir$entry"),
	"day" => getPostDate("$dir$entry")
        );
      }
    }
    $d->close();
usort( $retval, 'sortByTimestamp' );
    return $retval;
  }
?>

<?PHP
  $dirlist = getFileListcat("./");
echo "<ul class=\"sub-menu\">\n";
  foreach($dirlist as $file) {
    if(preg_match("/\index.php$/", $file['name'])) continue;
    echo "<li>\n";
    echo "<a href=\"{$file['name']}\">",basename($file['tit']),"</a>\n";
echo "</li>\n";
  }
echo "</ul>\n";
?>
