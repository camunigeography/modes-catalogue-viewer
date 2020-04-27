{* Template for home page listing of collections *}


<h1>{$grouping} catalogue</h1>

{$searchBox}

{$frontPageTextIntroduction}


<p>There are {$collections.summary.totalCollections} collections currently available, covering some {$collections.summary.totalRecords|number_format} {$type}:</p>

<div id="gallerylisting">
	
	<table class="graybox">
	{foreach $collections.collections item=collection}
		<tr>
			<td>
				<a class="coverimage" href="{$collection.baseUrl}/"><img src="{$collection.collectionCoverImage_src}" alt="Cover image" title="{$collection.title|htmlspecialchars}" width="100" height="100" class="shadow" /></a>
			</td>
			<td>
				<h2><a href="{$collection.baseUrl}/">
					{$collection.title|htmlspecialchars}</a>&nbsp;
					<span>({$collection.count|number_format} {($collection.count eq 1) ? 'item' : 'items'})</span>
				</h2>
				
				{if $collection.introductoryTextBrief}
					<p>{$collection.introductoryTextBrief|htmlspecialchars}</p>
				{else}
					<span class="comment">Sorry, no summary of this catalogue is available yet.</span>
				{/if}
			</td>
		</tr>
	{/foreach}
	</table>
	
</div>


{$footer}


<h2>Your views</h2>
<p>We would welcome your <a href="{$feedbackHref}">feedback</a> on the catalogue and its contents.</p>