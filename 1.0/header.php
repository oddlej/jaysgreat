<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<title>Website Name</title>

</head>

<body>
<div>
<div style="text-align: right;"><a href="./"><h1>Website Name</h1></a></div></div></div>
<div id="page">
<header id="masthead">
		
<!-- #site-navigation -->
<nav>
			<div class="menu"><ul><li><a href="./">Home</a></li>

<?PHP
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
  function getFileList($dir)
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
          "lastmod" => filemtime("$dir$entry")
        );
      }
    }
    $d->close();
    return $retval;
  }
?>

<?PHP
  $dirlist = getFileList("./categories/");
// echo "<ul class=\"sub-menu\">\n";
  foreach($dirlist as $file) {
echo "<li>\n";
    if(preg_match("/\index.php$/", $file['name'])) continue;
    echo "<a href=\"{$file['name']}\">",basename($file['name']),"</a>\n";
echo "</li>\n";
  }
//echo "</ul>\n";
?>

</li>

<li><a href="about.php">About</a></li>

</ul></div>	
</nav><!-- #site-navigation -->
</header><!-- #masthead -->


<!-- Open Divs -->
<div id="main" class="wrapper">
<div id="primary" class="site-content">
<div id="content" role="main">
