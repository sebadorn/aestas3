<?php
$search = isset( $_GET[PERMALINK_GET_SEARCH] ) ? $_GET[PERMALINK_GET_SEARCH] : '';
?>
<h1><a href="<?php echo URL ?>" title="Home"><?php echo ae_Settings::get( 'blog_title' ) ?></a></h1>

<aside class="about-me">
	<div class="about-page-link">
		<img src="<?php echo THEME_PATH ?>img/avatar_seba.jpg" alt="Profile picture" />
		<a href="<?php echo URL ?>ueber">About me</a>
	</div>

	<ul class="icons">
		<li class="icon-twit">
			<a href="https://twitter.com/sebadorn" title="Twitter"></a>
		</li>
		<li class="icon-github">
			<a href="https://github.com/sebadorn" title="GitHub"></a>
		</li>
		<li class="icon-rss">
			<a href="<?php echo URL ?>feed/" title="RSS: Articles"></a>
		</li>
	</ul>

	<form class="search" action="<?php echo URL ?>" method="get">
		<input type="text" class="search-input" name="search" placeholder="Search" value="<?php echo $search ?>" />
		<button type="submit" class="search-submit icon-before-search"></button>
	</form>
</aside>
