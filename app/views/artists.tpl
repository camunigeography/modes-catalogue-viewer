{* Template for artist listing *}

{$pageHeader}

<div id="artists">

<h2>Artists</h2>

{if isSet($notFound)}
	<p>No artists were found.</p>
{else}

	<p class="comment">(The number of items is shown next to each artist's name.)</p>
	
	<ul class="artists">
		{foreach from=$items key=name item=item}
			<li>{$name|makeArtistLink}<span class="comment"> ({$item.total})</span></li>
		{/foreach}
	</ul>
	
{/if}

</div>