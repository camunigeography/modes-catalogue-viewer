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
		
		<table class="lines listing spaced">
		{foreach from=$data.articles item=article}
			<tr>
				<td class="key">
					<a href="{$baseUrl}/article{$article.link}"><img src="/images/general/item.gif"{if ($article.images)} style="background-image: url('{$article.images.0.path}');"{if ($article.images.0.height > $article.images.0.width)} class="portrait"{/if} width="{$article.images.0.width}" height="{$article.images.0.height}" alt="Image" title="{$article.title|htmlspecialchars}"{/if} /></a>
				</td>
				<td>
					<h3><a href="{$baseUrl}/article{$article.link}">{$article.title|htmlspecialchars}</a></h3> <span class="recordnumber">Record: {$article.id}</span>
					<p>{$article.briefDescription|htmlspecialchars}</p>
				</td>
			</tr>
		{/foreach}
		</table>
		
		<div id="endpagination">
			{$paginationHtml}
		</div>
		
	{/if}

</div>

