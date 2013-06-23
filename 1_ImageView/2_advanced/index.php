<!DOCTYPE html> 
<html> 
<head> 
	<title>Galleries</title>

	<!-- Begin jQuery Mobile magical inclusions -->
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1"> 
	<meta name="apple-touch-fullscreen" content="yes" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<!-- End jQuery Mobile magical inclusions -->

	<script>
		window.top.scrollTo(0, 1); // Make sure not to show the URL bar
		// Note: it is known that if the page's height is less than the screen's, this may not hide the address bar.
	</script>
	<style>
		.iCentered { /* Center page elements */
			text-align: center;
		}
		.myThumbs {	/* Keep pics within the bounds of the grid */
			max-width: 80%;
			max-height: 80%;
		}
		.myPics { /* Keep pics within the bounds of the page content */
			max-height: 100%;
			max-width: 100%;
		}
	</style>

</head>
<body>
<?php

/*
	3 main strings holding our content:
	- $frontPage
		- The front/top page, where a list of the galleries will be added
	- $galleryPages
		- Each gallery's page, containing a grid of their images, will be added here
	- $imagePages
		- Each image will have a page, for easy, consistent display
*/

// This is a list of files that we do not want to consider galleries or images
$excludeFiles = array('.',"..",".DS_Store");

// This is a list of acceptable image file extensions
$imageExtensions = array("bmp","gif","jpg","jpeg","png","svg");

// Begin building our front page, up to the point of adding list items (<li>'s) for the galleries
$frontPage = "
<div data-role=\"page\" id=\"galleries\">
	<div data-role=\"header\">
		<h1>Galleries</h1>
	</div>

	<div data-role=\"content\">
		<ul data-role=\"listview\">";

// If the galleries folder doesn't exist, let it be known and end
if (!is_dir("galleries")) {
	$frontPage .= "
			<li>Sadly, there are no galleries at this time.</li>
		</ul>
	</div>
</div>

</body>
</html>
";
	echo $frontPage;
	die();
}

// Get a list of gallery (folder) names and find the list's size
// We are making sure that we only keep/count directories
$galleries = array_values(array_diff(scandir("galleries"),$excludeFiles));
$numGalleries = 0;
foreach ($galleries as $gallery) {
	if (!is_dir("galleries/".$gallery)) {
		unset($galleries[$numGalleries]);
		array_values($images);
	}
	else
		$numGalleries++;
}

// If there are no galleries, let it be known and end
if ($numGalleries == 0) {
	$frontPage .= "
			<li>Sadly, there are no galleries at this time.</li>
		</ul>
	</div>
</div>

</body>
</html>
";
	echo $frontPage;
	die();
}

// Otherwise, begin building our pages by flipping through each gallery folder
$galleryCount = 0;
foreach ($galleries as $gallery) {

	// Get a list of the images in the folder and the list's size
	// We are making sure that we only keep/count images
	$images = array_values(array_diff(scandir("galleries/".$gallery),$excludeFiles));
	$numImages = 0;
	foreach ($images as $image) {
		$nameExploded = explode('.',$image);
		if (sizeof($nameExploded) == 1 || !in_array(strtolower($nameExploded[sizeof($nameExploded)-1]),$imageExtensions)) {
			unset($images[$numImages]);
			array_values($images);
		}
		else
			$numImages++;
	}

	// Only add the gallery if there is at least 1 image
	if ($numImages > 0) {

		// Add the gallery and its size to our front page list with the first image as a thumbnail
		// Note: we are replacing underscores ('_') with spaces when we print the name to make it pretty
		$frontPage .= "
		<li>
			<a href=\"#gallery".$galleryCount."\" data-ajax=\"false\">
				<img src=\"galleries/".$gallery."/".$images[0]."\"/>
				<h3>".str_replace('_',' ',$gallery)."</h3>
				<p>".$numImages." Image";
		
		// Only pluralize image if you should
		if ($numImages > 1)
			$frontPage .= "s";

		$frontPage .= "</p>
			</a>
		</li>";

		// Begin building the gallery's page
		// Note: we are replacing underscores ('_') with spaces when we print the name to make it pretty
		$galleryPages .= "
<div data-role=\"page\" id=\"gallery".$galleryCount."\">
<div data-role=\"header\" data-position=\"fixed\">
	<a href=\"./\" data-role=\"button\" data-icon=\"back\" data-iconpos=\"notext\" data-direction=\"reverse\"></a>
	<h1>".str_replace('_',' ',$gallery)."</h1>
</div>
<div data-role=\"content\">
	<div class=\"ui-grid-a\">";

		// Flip through each image in the folder
		$imageCount = 0;
		foreach ($images as $image) {

			// Alternate between 'a' and 'b' for grid blocks (2 per row)
			if ($imageCount%2 == 0)
				$blockLetter = 'a';
			else
				$blockLetter = 'b';

			// Add each image as a thumbnail on the gallery page with a link to the image's page
			$galleryPages .= "
		<div class=\"ui-block-".$blockLetter." iCentered\"><a href=\"#gallery".$galleryCount."image".$imageCount."\" data-ajax=\"false\"><img src=\"galleries/".$gallery."/".$image."\" class=\"myThumbs\"/></a></div>";

			// Begin building the image's page
			// Note: we are replacing underscores ('_') with spaces when we print the name to make it pretty
			$imagePages .= "
<div data-role=\"page\" id=\"gallery".$galleryCount."image".$imageCount."\">
<div data-role=\"header\" data-position=\"fixed\">
	<a href=\"#gallery".$galleryCount."\" data-role=\"button\" data-icon=\"back\" data-iconpos=\"notext\" data-direction=\"reverse\"></a>
	<h1>".str_replace('_',' ',$image)."</h1>
</div>

<div data-role=\"content\">
	<div data-role=\"content-primary\" class=\"iCentered\">
		<img src=\"galleries/".$gallery."/".$image."\" class=\"myPics\"/>
	</div>
</div>";

			// If there are multiple images to flip through, add navigation arrows
			if ($numImages > 1) {
				$imagePages .= "
<div data-role=\"footer\" data-position=\"fixed\">
	<div data-role=\"navbar\">
		<ul>
			<li><a data-icon=\"arrow-l\" data-iconpos=\"notext\" data-transition=\"slide\" href=\"#gallery".$galleryCount."image";

				// Make the left arrow go to the previous image (or to the last image if this is the first image)
				if ($imageCount > 0)
					$imagePages .= ($imageCount - 1);
				else
					$imagePages .= ($numImages-1);
	
				$imagePages .= "\"></a></li>
			<li><a data-icon=\"arrow-r\" data-iconpos=\"notext\" data-transition=\"slide\" data-direction=\"reverse\" href=\"#gallery".$galleryCount."image";

				// Make the right arrow go to the next image (or to the first image if this is the last image)
				if ($imageCount < $numImages - 1)
					$imagePages .= ($imageCount + 1);
				else
					$imagePages .= '0';
	
				$imagePages .= "\"></a></li>
		</ul>
	</div>
</div>";
			}
		
			// Finish off the image's page
			$imagePages .= "
</div>";

			$imageCount++;

		} // End foreach $image

		// Finish off the gallery's content
		$galleryPages .= "
	</div>
</div>";

		// If there are multiple galleries to flip through, add navigation arrows
		if ($numGalleries > 1) {
			$galleryPages .= "
<div data-role=\"footer\" data-position=\"fixed\">
	<div data-role=\"navbar\">
		<ul>
			<li><a data-icon=\"arrow-l\" data-transition=\"slide\" data-iconpos=\"notext\" href=\"#gallery";

			// Make the left arrow go to the next gallery (or to the first gallery if this is the last gallery)
			if ($galleryCount > 0)
				$galleryPages .= ($galleryCount - 1);
			else
				$galleryPages .= ($numGalleries-1);

			$galleryPages .= "\"></a></li>
			<li><a data-icon=\"arrow-r\" data-transition=\"slide\" data-direction=\"reverse\" data-iconpos=\"notext\" href=\"#gallery";

			// Make the right arrow go to the next gallery (or to the first gallery if this is the last gallery)
			if ($galleryCount < $numGalleries - 1)
				$galleryPages .= ($galleryCount + 1);
			else
				$galleryPages .= '0';

			$galleryPages .= "\"></a></li>
		</ul>
	</div>
</div>";

		}

		// Finish off the gallery's page
		$galleryPages .= "
</div>
";

		$galleryCount++;

	} // End if $numImages > 0

} // End foreach $gallery

// If none of the galleries contain images, let it be known
if ($galleryCount == 0) {
	$frontPage .= "
		<li>Sadly, there are no galleries at this time.</li>";
}

// Finish off the front page, whether or not we have galleries to list
$frontPage .= "
	</ul>
</div>
</div>
";

// And finally, print it all out in order
echo $frontPage . $galleryPages . $imagePages;

?>

</body>
</html>