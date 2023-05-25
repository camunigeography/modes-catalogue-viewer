{* Template for index page of collection *}

{$pageHeader}

<div id="index">
	
	<a class="coverimage" href="{$collection.baseUrl}/gallery/"><img src="{$collection.collectionCoverImage_src}" alt="Cover image" title="{$collection.title|htmlspecialchars}" width="100" height="100" class="shadow" /></a>
	
	<h2>{$collection.title|htmlspecialchars}</h2>
	
	<ul class="browselinks nobullet">
		<li><a href="{$collection.baseUrl}/browse/"><img src="/images/icons/layout_content.png" alt="" class="icon" /> <strong>Browse all items</strong> ({$collection.count|number_format} available)</a></li>
		<li><a href="{$collection.baseUrl}/gallery/"><img src="/images/icons/map.png" alt="" class="icon" /> <strong>Gallery</strong> showing all items</a></li>
	</ul>
	
	{if $type == 'picturelibrary'}
		<p><a href="/picturelibrary/ordering/"><img src="/images/icons/page_go.png" alt="Order" class="icon" border="0" /> <strong>How to order copies</strong> of these images</a> for: <a href="/picturelibrary/ordering/commercial.html">commercial use</a> or <a href="/picturelibrary/ordering/private.html">private use</a>.</p>
	{/if}
	
	{$introductoryTextHtml}
	
	{if $articleListing}
		<p id="random">{$randomImagesNumber} random items [<a href="./">more</a>] [<a href="{$collection.baseUrl}/gallery/">view complete gallery</a>]:</p>
		{$articleListing}
	{/if}
	
	<br clear="all" />
	{$collection.sponsorNotice}
	
	<p class="faded small colophon">Data in this catalogue was last updated on {$collection.dataDateHumanReadable}.</p>

</div>

