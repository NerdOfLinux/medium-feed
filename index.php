<?php
/* This PHP script generates a list of all recent Medium posts */
// Just set the below to your publication slug or username
// For example, if your publication URL is https://medium.com/abc123
// you would set medium_slug to abc123
// For your user, just set medium_slug to @username
$medium_slug = 'ofthenerds';

$api_url = "https://medium.com/$medium_slug/latest?format=json";
// Check for cache
$cache_file = md5( $api_url );
if ( is_file( $cache_file ) && time() - filemtime( $cache_file ) <= 3600 ) {
    $raw_api_request = file_get_contents( $cache_file );
} else {
    $raw_api_request = substr( file_get_contents( $api_url ), 16 );
    file_put_contents( $cache_file, $raw_api_request );
}
$payload = json_decode( $raw_api_request, TRUE )['payload'];
$collection = $payload['collection'];

$title = $collection['sections'][0]['collectionHeaderMetadata']['title'];
// Header
?>
<!DOCTYPE html>
<html>
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> <?php echo $title; ?> </title>
	<!-- Load Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">	
	<?php
	if ( isset( $canonical ) ) {
	?>
	    <link rel="canonical" href="<?php echo $canonical; ?>">
	<?php
	}
	?>
    </head>
    <body>
	<div class="container" style="min-height: 100vh">
	    <header class="text-center">
		<a href="/" style="text-decoration: none"> <h1 class="text-center"> <?php echo $title; ?></h1></a>
		<ul class="nav">
		    <?php
		    // Build the navigation bar
		    foreach ( $collection['navItems'] as $nav_item ) {
			$nav_url = $nav_item['url'];
			$nav_title = $nav_item['title'];
			echo "\n" . '<li class="nav-item">';
			echo "\n" . '<a class="nav-link" href="' . $nav_url . '">' . $nav_title . '</a>';
			echo "\n" . '</li>';
		    }
		    ?>
		    </ul>
	    </header>
	    <hr>

<?php
// Display the posts
foreach ( $payload['posts'] as $post ) {
    $post_title = $post['title'];
    $post_url = 'https://medium.com/p/' . $post['id'];
    $post_subtitle = $post['virtuals']['subtitle'];
    echo "\n" . '<h3><a href="' . $post_url . '">' . $post_title . '</a></h3>';
    echo "\n" . $post_subtitle;
    echo "\n" . '<hr>';
}
?>
</div>
</body>
</html>
