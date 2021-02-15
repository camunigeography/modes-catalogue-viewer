{* Template for article screen *}

{$pageHeader}

{$navigationLinksHtml}

<h2 class="article">{$article.title|htmlspecialchars}</h2>

{$shoppingCartButtons}

{if isSet($contextHtml)}
	<div class="gallerycontext">
		{$contextHtml}
	</div>
{/if}


<table class="lines article">
	
	{if $article.imageFiles}
		<tr>
			<td></td>
			<td>
				{if $article.status eq 'P'}		{* Disable private images *}
					<div class="nullimage"><strong>No image available</strong> - please <a href="{$feedbackHref}">contact the curator</a> for information.</div>
				{else}
					{if $article.navigationIds.next}<a title="Go to next" href="{$article.navigationIds.next|articleIdUrl}">{/if}
					{$article.imageFiles.0|imgTag}
					{if $article.navigationIds.next}</a>{/if}
					
					{if $article.imageFiles|@count > 1}
						<p><a href="#images">More (all) images of this article below <strong>&darr;</strong></a></p>
					{/if}
					
					{if $type == 'picturelibrary'}
						<p><a href="/picturelibrary/ordering/"><img src="/images/icons/page_go.png" alt="Order" class="icon" border="0" /> <strong>How to order copies</strong> of this image (no. {$article.id})</a></p>
					{/if}
				{/if}
			</td>
		</tr>
	{/if}
	
	<tr>
		<td>Accession no.:</td>
		<td>{$article.id}</td>
	</tr>

	{if $article.objectName}
		<tr>
			<td>Object name:</td>
			<td>{$article.objectName|htmlspecialchars|ucfirst}</td>
		</tr>
	{/if}
	
	<tr>
		<td>Title:</td>
		<td>{$article.title|htmlspecialchars}</td>
	</tr>
	
	{if $article.briefDescription}
		<tr>
			<td>Description:</td>
			<td>{$article.briefDescription|htmlspecialchars}</td>
		</tr>
	{/if}
	
	{if $article.medium}
		<tr>
			<td>Medium:</td>
			<td>{$article.medium|htmlspecialchars}</td>
		</tr>
	{/if}
	
	{if $article.artist}
		<tr>
			<td>Artist:</td>
			<td>{$article.artist|makeArtistLink}</td>
		</tr>
	{/if}
	
	{if $type == 'museum'}
		{if $article.classifiedNames}
			<tr>
				<td>Classified name:</td>
				<td>
					<table class="compressed">
						{foreach from=$article.classifiedNames key=key item=value}
						<tr>
							<td>{if $key eq '[Unknown system]'}<span class="comment">{$key}</span>{else}{$key}{/if}</td>
							<td>
								{if $key eq 'Getty AAT'}
									{$value|makeCategoryLink}
								{else}
									{$value}
								{/if}
							</td>
						</tr>
						{/foreach}
					</table>
				</td>
		{/if}
	{/if}
	
	{if $type == 'museum'}
		{if $article.fieldCollection}
			<tr>
				<td>Field collection:</td>
				<td>
					<table class="compressed">
						{foreach from=$article.fieldCollection key=key item=value}
						<tr>
							<td>{$key}</td>
							<td>{if $key eq 'Date' && $value eq '[No date]'}<span class="faded">[No date]</span>{else}{$value}{/if}</td>
						</tr>
						{/foreach}
					</table>
				</td>
			</tr>
		{/if}
	{/if}
	
	{if $type == 'museum'}
		{if $article.materials}
			<tr>
				<td>Materials:</td>
				<td>
					<ul class="materials">
					{foreach from=$article.materials item=material}
						<li>{$material|makeMaterialLink}</li>
					{/foreach}
					</ul>
				</td>
			</tr>
		{/if}
	{/if}
	
	{if $type == 'museum'}
		{if $article.type != 'picture'}		{* Do not show for pictures, as they will only ever have one *}
			{if $article.numberOfItems}
				<tr>
					<td>Number of items:</td>
					<td>{$article.numberOfItems}</td>
				</tr>
			{/if}
		{/if}
	{/if}
	
	{if $type == 'museum'}
		{if $article.type eq 'picture'}
			{if $article.note}
				<tr>
					<td>Note:</td>
					<td>{$article.note}</td>
				</tr>
			{/if}
		{/if}
	{/if}
	
	{if $type == 'museum'}
		{if $article.type != 'picture'}
			<tr>
				<td>Full description:</td>
				<td>
					{if $article.fullDescription}
						<p>{$article.fullDescription|replace:"\n\n":"</p>\n<p>"}</p>
					{else}
						<span class="comment">[No description available.]</span>
					{/if}
				</td>
			</tr>
		{/if}
	{/if}
	
	{if $type == 'museum'}
		{if $article.relatedRecords}
			<tr>
				<td>Related record(s):</td>
				<td>
					<ul>
					{* #!# Add support for linking to records where they exist, e.g. for Y: 76/7/2; however note that e.g. record N: 26b has text next to the record name *}
					{foreach from=$article.relatedRecords item=relatedRecord}
						<li>{$relatedRecord}</li>
					{/foreach}
					</ul>
				</td>
			</tr>
		{/if}
	{/if}
	
	{if $article.dimensions}
		<tr>
			<td>Dimensions:</td>
			<td>
				<ul>
				{foreach from=$article.dimensions key=part item=dimensionsList}
					<li>
						{if $article.dimensions|@count != 1}
							<em>{$part}</em>: 
						{/if}
						{foreach from=$dimensionsList key=dimension item=value name=dimensions}
							{if $smarty.foreach.dimensions.first}{$dimension|ucfirst}{else}{$dimension}{/if}: {$value}{if !$smarty.foreach.dimensions.last}, {/if}
						{/foreach}
					</li>
				{/foreach}
				</ul>
			</td>
		</tr>
	{/if}
	
	{if $article.associatedPerson}
		<tr>
			<td>Associated person(s):</td>
			<td>
				<ul>
				{foreach from=$article.associatedPerson item=person}
					<li><span class="comment">{$person.type|ucfirst}</span>: {$person.name|htmlspecialchars} ({$person.dateBegin} - {$person.dateEnd})</li>
				{/foreach}
				</ul>
			</td>
		</tr>
	{/if}
	
	{if $article.associatedOrganisation}
		<tr>
			<td>Associated organisation(s):</td>
			<td>
				<ul>
				{foreach from=$article.associatedOrganisation item=organisation}
					<li><span class="comment">{$organisation.type|ucfirst}</span>: {$organisation.name|htmlspecialchars} ({$organisation.dateBegin} - {$organisation.dateEnd})</li>
				{/foreach}
				</ul>
			</td>
		</tr>
	{/if}
	
	{if $article.associatedExpedition}
		<tr>
			<td>Associated expedition(s):</td>
			<td>
				<ul>
				{foreach from=$article.associatedExpedition item=expedition}
					<li><span class="comment">{$expedition.type|ucfirst}</span>: {$expedition.name|htmlspecialchars} ({$expedition.dateBegin} - {$expedition.dateEnd})</li>
				{/foreach}
				</ul>
			</td>
		</tr>
	{/if}
	
	{if $type == 'picturelibrary'}
		{if $article.placeName}
			<tr>
				<td>Location:</td>
				<td>{$article.placeName|htmlspecialchars}</td>
			</tr>
		{/if}
	{/if}
	
	{if $type == 'picturelibrary'}
		{if $article.imageBy}
			<tr>
				<td>Image by:</td>
				<td>
					{if $article.imageBy|@count eq 1}
						{$article.imageBy.0}
					{else}		{* If several people, render as a list *}
						<ul>
						{foreach from=$article.imageBy item=imageBy}
							<li>{$imageBy}</li>
						{/foreach}
						</ul>
					{/if}
				</td>
			</tr>
		{/if}
	{/if}
	
	{if $type == 'picturelibrary'}
		{if $article.imageColour}
			<tr>
				<td>Colour or B&amp;W?:</td>
				<td>{$article.imageColour}</td>
			</tr>
		{/if}
	{/if}
	
	{if $article.imageFiles}
		{if $article.status != 'P'}
			{if $article.imageFiles|@count > 1}
				<tr id="images">
					<td>All images for this article:</td>
					<td>
						{foreach from=$article.imageFiles item=image}
							{$image|imgTag}
						{/foreach}
					</td>
				</tr>
			{/if}
		{/if}
	{/if}
	
</table>




{$debugInfo}
