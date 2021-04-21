<?php
$search = isset( $_GET[PERMALINK_GET_SEARCH] ) ? $_GET[PERMALINK_GET_SEARCH] : '';
?>
<h1><a href="<?php echo URL ?>" title="Home"><?php echo ae_Settings::get( 'blog_title' ) ?></a></h1>

<aside class="aside-meta">
	<ul class="links">
		<li class="about-page-link">
			<a href="<?php echo URL ?>ueber">
				<img src="<?php echo THEME_PATH ?>img/avatar_seba.jpg" alt="Profile picture" />
				<span class="name">Über mich</span>
			</a>
		</li>
		<li class="icon-bg icon-twit">
			<a href="https://twitter.com/sebadorn" title="Twitter">
				<span class="name">Twitter</span>
			</a>
		</li>
		<li class="icon-bg icon-github">
			<a href="https://github.com/sebadorn" title="GitHub">
				<span class="name">GitHub</span>
			</a>
		</li>
		<li class="icon icon-rss">
			<a href="<?php echo URL ?>feed/" title="RSS: Articles">
				<span class="name">RSS feed</span>
			</a>
		</li>
	</ul>

	<form class="search" action="<?php echo URL ?>" method="get">
		<input type="text" class="search-input" name="search" placeholder="Search" value="<?php echo $search ?>" />
		<button type="submit" class="search-submit icon-before-search"></button>
	</form>
</aside>
