{* Template for categories listing *}

{$pageHeader}

<div id="categories">

<h2>Categories</h2>

{if isSet($notFound)}
	<p>No categories were found.</p>
{else}

	<p>The collection uses the <a target="_blank" href="{$aatLink}">Art &amp; Architecture Thesaurus (AAT)</a> vocabulary for categorisation. The links below indicate the existence of items in the collection for each of the {$categories|@count} categories.</p>
	<p>You can also <a href="{$collection.baseUrl}/categories/hierarchy.html">drill-down through the AAT category hierarchy</a>.</p>
	
	<table class="lines materials">
		{foreach from=$categories key=key item=category}
		<tr>
			<td><strong>{$category.category|ucfirst|makeCategoryLink}</strong>:</td>
			<td><span class="comment">({$category.count}&nbsp;{($category.count eq 1) ? 'item' : 'items'})</span></td>
			<td>
				{if $category.classification}
					{$category.classification|htmlspecialchars|replace:' &amp; ':' <span class="comment">&raquo;</span> '}
				{else}
					<span class="comment"><em>[Classification available shortly.]</em></span>
				{/if}
			</td>
		</tr>
		{/foreach}
	</table>
	
{/if}

</div>
