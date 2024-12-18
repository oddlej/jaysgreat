<?php include("header.php"); ?>

<!-- This overrides image display (!important) and keeps iframes off of this page only. -->
<style>
img {display: none !important;}
iframe {display: none;}
</style>

<h1>Generic Homepage</h1>

<p>This is your home page. It can be structured any way you want. This site is made only from <a href="categories/Code/How%20this%20website%20was%20made.php">a few PHP files and a flat file directory structure</a>.</p>
<p>Have fun and Cheers.</p>


<p>&nbsp;</p>

<p><b>Old School News Feeds (RSS)</b> <a href="categories/Code/RSS%20example%20from%20main%20page.php">How?</a></p>

<p><b>Slashdot</b></p>
<?php
	$rss = new DOMDocument();
	$rss->load('http://rss.slashdot.org/Slashdot/slashdot');
	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			);
		array_push($feed, $item);
	}
	$limit = 5;
	for($x=0;$x<$limit;$x++) {
		$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
		$link = $feed[$x]['link'];
		$description = $feed[$x]['desc'];
		echo '<p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br />';
		echo '<p>'.$description.'</p>';
	}
?>


<p><b>Ars Technica</b></p>
<?php
	$rss = new DOMDocument();
	$rss->load('https://feeds.arstechnica.com/arstechnica/index');
	$feed = array();
	foreach ($rss->getElementsByTagName('item') as $node) {
		$item = array ( 
			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			);
		array_push($feed, $item);
	}
	$limit = 20;
	for($x=0;$x<$limit;$x++) {
		$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
		$link = $feed[$x]['link'];
		$description = $feed[$x]['desc'];
		echo '<p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br />';
		echo '<p>'.$description.'</p>';
	}
?>

<?php include("footer.php"); ?>
					
