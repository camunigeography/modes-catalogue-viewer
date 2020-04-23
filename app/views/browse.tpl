{* Template for browse page of collection *}

{$pageHeader}

<div id="browse">

	<h2>Browse all items</h2>
	
	<p>Here you can browse the complete listing of all the <a href="{$collection.baseUrl}/"><strong>{$collection.title|htmlspecialchars}{if $collection.abbreviation} ({$collection.abbreviation|htmlspecialchars}){/if}</strong></a> items.</p>
	<p>You can also <a href="{$collection.baseUrl}/gallery/"><img src="/images/icons/map.png" alt="" class="icon"> view these items as a gallery of images</a>.</p>
	
	{if (!$data.articles)}
		<p>No items were found.</p>
	{else}
		
		<p>There {($data.pagination.total eq 1) ? 'is one item' : "are {$data.pagination.total|number_format} items"} in this collection{if $paginationHtml}, of which {($data.pagination.count eq 1) ? 'one is shown' : "{$data.pagination.count|number_format} are shown"} below. Use the navigation to view more pages{/if}.</p>
		
		{$paginationHtml}
		
		{$articles}
		
		<div id="endpagination">
			{$paginationHtml}
		</div>
		
	{/if}

</div>

