<?php
$search = isset( $_GET[PERMALINK_GET_SEARCH] ) ? $_GET[PERMALINK_GET_SEARCH] : '';
?>
<h1><a href="<?php echo URL ?>" title="Home"><?php echo ae_Settings::get( 'blog_title' ) ?></a></h1>

<aside class="aside-meta">
	<ul class="links">
		<li class="link icon-home">
			<a href="<?php echo URL ?>">
				<span class="icon fa fa-home"></span>
				<span class="name">Home</span>
			</a>
		</li>
		<li class="link about-page-link">
			<a href="<?php echo URL ?>ueber">
				<img src="<?php echo THEME_PATH ?>img/avatar_seba.jpg" alt="Profile picture" />
				<span class="name">Über mich</span>
			</a>
		</li>
		<li class="link icon-github">
			<a href="https://github.com/sebadorn" title="GitHub">
				<span class="icon fab fa-github"></span>
				<span class="name">GitHub</span>
			</a>
		</li>
		<li class="link icon-mastodon">
			<a rel="me" href="https://mastodon.gamedev.place/@sebadorn" title="Mastodon">
				<img src="<?php echo THEME_PATH ?>img/mastodon-white.svg" alt="Mastodon icon" />
				<span class="name">Mastodon</span>
			</a>
		</li>
		<li class="link icon-twitter">
			<a href="https://twitter.com/sebadorn" title="Twitter">
				<span class="icon fab fa-twitter"></span>
				<span class="name">Twitter</span>
			</a>
		</li>
		<li class="link icon-rss">
			<a href="<?php echo URL ?>feed/" title="RSS: Articles">
				<span class="icon fa fa-rss"></span>
				<span class="name">RSS feed</span>
			</a>
		</li>
	</ul>

	<form class="search" action="<?php echo URL ?>" method="get">
		<button type="submit" class="search-submit fa fa-search"></button>
		<input type="text" class="search-input" name="search" placeholder="Search…" value="<?php echo $search ?>" />
	</form>
</aside>
