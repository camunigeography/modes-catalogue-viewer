{* Template for gallery listing *}

{$pageHeader}

<h2>Gallery of all items</h2>

<p>Here you can browse the gallery of all the <a href="{$gallery.baseUrl}/"><strong>{$gallery.title|htmlspecialchars}{if ($gallery.abbreviation)} ({$gallery.abbreviation|htmlspecialchars}){/if}</strong></a> items.</p>
<p>You can also <a href="{$gallery['baseUrl']}/browse/"><img src="/images/icons/layout_content.png" alt="" class="icon"> show descriptions for each item</a>.</p>

{if (!$data.articles)}
	<p>No items were found.</p>
{else}
	
	{* #!# Count shows number of items, not total with images *}
	<p>There {($data.pagination.total eq 1) ? 'is one item' : "are {$data.pagination.total|number_format} items"} in this collection{if $paginationHtml}, of which {($data.pagination.count eq 1) ? 'one is shown' : "{$data.pagination.count|number_format} are shown"} below. Use the navigation to view more pages{/if}:</p>
	
	{$paginationHtml}
	
	{$galleryHtml}
	
	<div id="endpagination">
		{$paginationHtml}
	</div>
	
{/if}

</div>
