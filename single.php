<?php
	if($post->post_type == 'post')
		include 'single-blog.php';
	else
		header('Location: /not-found/');
?>