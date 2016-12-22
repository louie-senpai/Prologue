<?php
if(comments_open()):
	global $withcomments;
	$withcomments = true;
	comments_template("/inline-comments.php");
endif;