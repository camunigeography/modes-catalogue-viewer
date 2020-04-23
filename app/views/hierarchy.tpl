{* Template for hierarchy page *}

{$pageHeader}

<div id="hierarchy">

<h2>Browse / drill-down AAT category hierarchy</h2>

{if isSet($notFound)}
	<p>No categories were found.</p>
{else}

	<p>The collection uses the <a target="_blank" href="{$aatLink}">Art &amp; Architecture Thesaurus (AAT)</a> vocabulary for categorisation. The hierarchy below includes only the AAT sections actually in use in this collection.</p>
	<p>Press the + and - buttons to navigate through the categories.<br />Click on a link to show the list of items in the category, or <a href="{$collection.baseUrl}/categories/hierarchy.html">reset the listing</a>.</p>
	
	<script type="text/javascript" src="/sitetech/pde.js"></script>
	{$categoryHierarchy}
	
{/if}

</div>