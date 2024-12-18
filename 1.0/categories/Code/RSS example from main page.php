<!--date=20221214 -->

<?php include("../../headercat.php"); ?>

<h1>RSS example from main page</h1>
										
<p>Here is how the RSS feeds are displayed on the main page. Change the title and feed url (first and fourth lines) and use as many times as you want in a page.If you get a blank space under the feed you are trying to display, reduce the value of $limit until you match the amount on the RSS feed. :) I also use a &#60style&#62 img {display: none;}&#60/style&#62 at the top of the page to prevent images from being displayed in the news. Cheers.</p>


<pre><?php show_source("../../rssexample.php"); ?></pre>

<?php include("../../footer.php"); ?>
