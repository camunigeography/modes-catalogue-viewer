{* Template for home page listing of collections *}


<h1>{$grouping} catalogue</h1>

{$searchBox}

<br />

{$frontPageTextIntroduction}

<p>There are {$collections.summary.totalCollections} collections currently available online, covering some {$collections.summary.totalRecords|number_format} {($type == 'picturelibrary') ? 'images' : 'items'}:</p>

<div class="collectionslist flexrow">
	<div>
		<ul class="clearfix">
		{foreach $collections.collections item=collection}
			<li><a class="coverimage" href="{$collection.baseUrl}/">
				<div title="{$collection.introductoryTextBrief|htmlspecialchars}">
					<img src="{$collection.coverImage}" />
					<p class="coverimage">{$collection.title|htmlspecialchars}</p>
				</div>
			</a></li>
		{/foreach}
		</ul>
	</div>
	<div>
	</div>
</div>


{$footer}


<h2>Your views</h2>
<p>We welcome your <a href="{$feedbackHref}">feedback</a> on the catalogue and its contents.</p>
