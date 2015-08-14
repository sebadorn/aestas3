<?php
$search = isset( $_GET[PERMALINK_GET_SEARCH] ) ? $_GET[PERMALINK_GET_SEARCH] : '';
?>
<header class="main-header">
	<h1><a href="<?php echo URL ?>" title="Home"><?php echo ae_Settings::get( 'blog_title' ) ?></a></h1>

	<form class="search" action="<?php echo URL ?>" method="get">
		<input type="text" class="search-input" name="search" placeholder="Suche" value="<?php echo $search ?>" />
		<button type="submit" class="search-submit icon-before-search"></button>
	</form>

	<div class="about-me">
		<img src="https://secure.gravatar.com/avatar/dea14db0237b0a18cdf7bd87b203ad90?d=mm&amp;s=32" alt="Profilbild Seba" />
		<a href="<?php echo URL ?>ueber">Über mich</a>
	</div>

	<ul class="icons">
		<li class="icon-twit">
			<a href="https://twitter.com/sebadorn" title="Twitter"></a>
		</li>
		<li class="icon-github">
			<a href="https://github.com/sebadorn" title="GitHub"></a>
		</li>
		<li class="icon-rss">
			<a href="https://feeds2.feedburner.com/sebadorn" title="RSS: Artikel"></a>
		</li>
	</ul>
</header>
