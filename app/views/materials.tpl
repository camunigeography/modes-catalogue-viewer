{* Template for materials listing *}

{$pageHeader}

<div id="materials">

<h2>Materials</h2>

{if isSet($notFound)}
	<p>No materials were found.</p>
{else}

	<p>The collection uses the <a target="_blank" href="{$aatLink}">Art &amp; Architecture Thesaurus (AAT)</a> vocabulary for categorisation. The 'tag cloud' of links below indicate the existence of items in the collection for each of the {$items|@count} materials used.</p>
	
	<ul class="tagcloud">
		{foreach from=$items key=name item=item}
			<li class="{$item.class}">{$name|makeMaterialLink}<span class="comment"> ({$item.total})</span></li>
		{/foreach}
	</ul>
	
{/if}

</div>