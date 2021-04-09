<?php
$search = isset( $_GET[PERMALINK_GET_SEARCH] ) ? $_GET[PERMALINK_GET_SEARCH] : '';
?>
<header class="main-header">
	<div class="about-me">
		<img src="<?php echo THEME_PATH ?>img/avatar_seba.jpg" alt="Profile picture" />
		<a href="<?php echo URL ?>ueber">Über mich</a>
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
	</div>

	<h1><a href="<?php echo URL ?>" title="Home"><?php echo ae_Settings::get( 'blog_title' ) ?> | blog</a></h1>

	<form class="search" action="<?php echo URL ?>" method="get">
		<input type="text" class="search-input" name="search" placeholder="Search" value="<?php echo $search ?>" />
		<button type="submit" class="search-submit icon-before-search"></button>
	</form>
</header>
