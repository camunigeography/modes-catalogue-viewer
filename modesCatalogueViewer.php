<?php

# Class to create a MODES catalogue viewer
class modesCatalogueViewer extends frontControllerApplication
{
	# Function to assign defaults additional to the general application defaults
	public function defaults ()
	{
		# Specify available arguments as defaults or as NULL (to represent a required argument)
		$defaults = array (
			'applicationName' => 'Online catalogue',
			'h1' => '',
			'div' => 'modescatalogue',
			'administrators' => true,
			
			# Type, i.e. museum / picturelibrary / archives
			'type' => false,
			
			# Tabs - only enabled for admins below
			'disableTabs' => true,
			'tabUlClass' => 'tabsflat',
			
			'organisationName' => NULL,
			
			'frontPageTextIntroduction' => NULL,
			'frontPageTextFooter' => NULL,
			
			# Images
			'mainImageSize' => 450,
			'listingThumbnailSize' => 100,
			'listingThumbnailType' => 'gif',
			'articleImageClass' => false,
			'imageFilenameLiberalMatching' => true,	// Allow case-insensitive matches of image names
			
			'administratorEmail' => NULL,
			'multiplesDelimiter' => '|',
			'queryTerm' => 'q',
			'aatLink' => 'https://www.getty.edu/research/tools/vocabularies/aat/',
			'removeEmptyGalleries' => true,	// Whether to remove authorised galleries that have no images in them
			
			# Pagination
			#!# This should be different for search vs listings - 250 is too many for a search
			'paginationRecordsPerPage' => 150,
			
			# Image data source (non-slash terminated)
			'imageStoreRoot' => NULL,
			
			# API
			'apiUrl' => NULL,		// Non-slash terminated
			'apiUi' => NULL,
			'userAgent' => ucfirst (__CLASS__) . ' API request',
			
			# Templating
			'useTemplating' => true,
		);
		
		# Return the defaults
		return $defaults;
	}
	
	
	# Function to assign supported actions
	public function actions ()
	{
		# Define available tasks
		$actions = array (
			'home' => array (
				'description' => false,
				'url' => '',
				'tab' => 'Home',
			),
			'globalsearch' => array (
				'description' => false,
				'url' => '',
			),
			'index' => array (
				'description' => false,
				'url' => 'catalogue/%s/',
			),
			'about' => array (
				'description' => false,
				'url' => '',
			),
			'browse' => array (
				'description' => false,
				'url' => '',
			),
			'gallery' => array (
				'description' => false,
				'url' => '',
			),
			'categories' => array (
				'description' => false,
				'url' => '',
			),
			'category' => array (
				'description' => false,
				'url' => '',
			),
			'hierarchy' => array (
				'description' => false,
				'url' => '',
			),
			'materials' => array (
				'description' => false,
				'url' => '',
			),
			'material' => array (
				'description' => false,
				'url' => '',
			),
			'artists' => array (
				'description' => false,
				'url' => '',
			),
			'artist' => array (
				'description' => false,
				'url' => '',
			),
			'contacts' => array (
				'description' => false,
				'url' => '',
			),
			'search' => array (
				'description' => false,
				'url' => '',
			),
			'article' => array (
				'description' => false,
				'url' => '',
			),
			'apidocumentation' => array (
				'description' => 'API (HTTP)',
				'url' => 'api/',
				'tab' => 'API/import',
				'icon' => 'feed',
				'administrator' => true,
			),
			'feedback' => array (
				'description' => 'Feedback/contact form',
				'url' => 'feedback.html',
				'tab' => 'Feedback',
			),
			'templates' => array (
				'description' => 'Templates',
				'url' => 'templates/',
				'tab' => 'Templates',
				'icon' => 'tag',
				'administrator' => true,
			),
		);
		
		# Return the actions
		return $actions;
	}
	
	
	
	# Additional standard processing (pre-actions)
	public function mainPreActions ()
	{
		# Enable tabbing for admins
		if ($this->userIsAdministrator) {
			$this->settings['disableTabs'] = false;
		}
		
		# Register template modifier functions
		$this->templateFunctions = array ('articleIdUrl', 'imgTag', 'makeCategoryLink', 'makeArtistLink', 'makeMaterialLink', );
	}
	
	
	
	# Additional standard processing
	public function main ()
	{
		# Title
		$this->titleBrowser = array ();
		$this->titleBreadcrumbTrail = array ();
		
	}
	
	
	# Import page
	public function import ()
	{
		# Show the HTML
		echo $html = "\n<p>Data can be updated via the import page of the central <a href=\"{$this->settings['apiUi']}/import/\">unified collections data API</a>.</p>";
	}
	
	
	# API documentation page
	public function apidocumentation ($ignored = '')
	{
		# Show the HTML
		echo $html = "\n<p>Data for this site comes from the central <a href=\"{$this->settings['apiUi']}/api/\">unified collections data API</a>, where you can <a href=\"{$this->settings['apiUi']}/import/\"><strong>import catalogue data</strong></a>.</p>";
	}
	
	
	# Function to convert a type key to a name
	private function typeToName ($type)
	{
		# Convert and return the type
		$groupings = array (
			'museum'			=> 'Museum',
			'picturelibrary'	=> 'Picture Library',
			'archives'			=> 'Archives',
		);
		return $groupings[$type];
	}
	
	
	# Function to create the front page
	public function home ()
	{
		# Get the gallery data
		$fields = array ('id', 'title', 'abbreviation', 'count', 'baseUrl', 'introductoryTextBrief', 'coverImage');
		$collections = $this->getCollections ($fields);
		
		# Heading grouping name
		$this->template['grouping'] = $this->typeToName ($this->settings['type']);
		
		# Add the search box
		$this->template['searchBox'] = $this->searchBox ($this->baseUrl, true, $global = true);
		
		# Add introduction text
		$this->template['frontPageTextIntroduction'] = $this->settings['frontPageTextIntroduction'];
		
		# Add gallery data to the template
		$this->template['collections'] = $collections;
		
		# Add the type to the template, e.g. museum/picturelibrary
		$this->template['type'] = $this->settings['type'];
		
		# Footer
		$this->template['footer'] = $this->settings['frontPageTextFooter'];
		
		# Contact link
		$this->template['feedbackHref'] = $this->baseUrl . '/feedback.html';
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to create the page header
	#!# Needs to be templatised
	private function pageHeader ($gallery)
	{
		# Set the title
		if (!$this->titleBrowser) {
			$this->titleBrowser = array (
				htmlspecialchars ($gallery['title']),
			);
		}
		if (!$this->titleBreadcrumbTrail) {
			$this->titleBreadcrumbTrail = array (
				($this->action != 'index' ? "<a href=\"{$gallery['baseUrl']}/\">" : '') . htmlspecialchars ($gallery['title']) . ($this->action != 'index' ? '</a>' : ''),
			);
		}
		
		# Start the HTML
		$html = '';
		
		# Add section heading
		$html .= "\n<h1><a href=\"{$this->baseUrl}/\">" . $this->typeToName ($this->settings['type']) . " catalogue</a>: <span><a href=\"{$gallery['baseUrl']}/\">" . htmlspecialchars ($gallery['title']) . '</a></span></h1>';
		
		# Add the search box
		$html .= $this->searchBox ($gallery['baseUrl'], true);
		
		# Create a tab registry
		$tabs = array (
			'index' => array (
				'subtab'	=> 'Home',
				'url'		=> '/',
			),
			'about' => array (
				'enableIf'	=> $gallery['aboutPageHtml'],
				'subtab'	=> $gallery['aboutPageTabText'],
				'url'		=> '/about.html',
			),
			'browse' => array (
				'subtab'	=> 'Browse all items',
				'icon'		=> 'layout_content',
				'url'		=> '/browse/',
			),
			'gallery' => array (
				'subtab'	=> 'Gallery',
				'icon'		=> 'map',
				'url'		=> '/gallery/',
			),
			'artists' => array (
				'enableIf'	=> ($this->settings['type'] == 'museum' && !$gallery['disableArtists']),
				'subtab'	=> 'Artists',
				'icon'		=> 'paintbrush',
				'url'		=> '/artists/',
			),
			'categories' => array (
				'enableIf'	=> ($this->settings['type'] == 'museum' && !$gallery['disableCategories']),
				'subtab'	=> 'Categories',
				'icon'		=> 'page_copy',
				'url'		=> '/categories/',
			),
			'materials' => array (
				'enableIf'	=> ($this->settings['type'] == 'museum' && !$gallery['disableMaterials']),
				'subtab'	=> 'Materials',
				'icon'		=> 'images',
				'url'		=> '/materials/',
			),
			'contacts' => array (
				'enableIf'	=> (isSet ($gallery['contactsPageHtml']) && strlen ($gallery['contactsPageHtml'])),
				'subtab'	=> 'Contacts',
				'icon'		=> 'email',
				'url'		=> '/contacts.html',
			),
		);
		
		# Compile the tabs
		$list = array ();
		$list['main'] = "<a href=\"{$this->baseUrl}/\"><span class=\"faded\">&laquo; Catalogues list</span></a>";
		foreach ($tabs as $key => $tab) {
			if (isSet ($tab['enableIf']) && !$tab['enableIf']) {continue;}	// Skip if not enabled
			$list[$key]  = "<a href=\"{$gallery['baseUrl']}{$tab['url']}\">";
			if (isSet ($tab['icon'])) {
				$list[$key] .= "<img src=\"/images/icons/{$tab['icon']}.png\" alt=\"\" class=\"icon\"> ";
			}
			$list[$key] .= htmlspecialchars ($tab['subtab']);
			$list[$key] .= '</a>';
		}
		
		# Determine which tab to highlight
		$current = ($this->action == 'article' ? 'browse' : $this->action);
		
		# Compile the HTML
		$html .= application::htmlUl ($list, 0, 'tabsflat', true, false, false, $liClass = true, $current);
		
		# Return the HTML
		return $html;
	}
	
	
	# Function to create an index page for a collection
	public function index ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# Page header
		$this->template['pageHeader'] = $this->pageHeader ($this->gallery);
		
		# Assign the collection data into the template
		$this->template['collection'] = $this->gallery;
		
		# Set the grouping, e.g. 'picturelibrary'
		$this->template['type'] = $this->gallery['grouping'];
		
		#!# Move into model
		$this->template['introductoryTextHtml'] = $this->applyPlaceholderReplacement ($this->gallery['introductoryText']);
		
		# Show some random records
		$constraints = array (
			'collection' => $this->gallery['id'],
			'requireimages' => '1',
			'random' => 5,
		);
		$data = $this->getArticles ($constraints);
		
		if ($data['articles']) {
			$this->template['randomImagesNumber'] = $constraints['random'];
			$this->template['articleListing'] = $this->galleryHtmlFromArticleData ($data['articles'], true);
		} else {
			# If there are no articles, create blank HTML
			$this->template['articleListing'] = '';
		}
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to apply templating changes
	private function applyPlaceholderReplacement ($html)
	{
		# Define templating replacements
		$templatingReplacements = array (
			'%baseUrl' => $this->baseUrl,
			'%catalogueBaseUrl' => $this->gallery['baseUrl'],
		);
		
		# Convert the text to HTML if it is not already (e.g. if coming out of the collection-level records)
		if (!substr_count ($html, '<p')) {	// This is a bit crude but good enough
			$html = application::formatTextBlock ($html);
		}
		
		# Return the HTML
		return strtr ($html, $templatingReplacements);
	}
	
	
	# Function to create a feedback form
	#!# Needs to be templatised
	public function feedback ($id_ignored = NULL, $error_ignored = NULL, $echoHtml = true)
	{
		# Introduction
		$html  = "\n<h1>Online catalogue feedback</h1>";
		$html .= "\n<p>We welcome your feedback on the catalogue and its contents.</p>";
		
		# Load and instantiate the form library
		$form = new form (array (
			'antispam'	=> true,
		));
		
		# Widgets
		$form->textarea (array (
			'name'			=> 'message',
			'title'					=> 'Your feedback',
			'required'				=> true,
			'cols'				=> 40,
		));
		$form->input (array (
			'name'			=> 'name',
			'title'					=> 'Your name',
			'required'				=> true,
			'disallow' => 'https?://',
		));
		$form->email (array (
			'name'			=> 'contacts',
			'title'					=> 'E-mail',
			'required'				=> true,
		));
		
		# Process the form
		$form->setOutputEmail ($this->settings['feedbackRecipient'], $this->settings['administratorEmail'], 'Online catalogue feedback', NULL, $replyToField = 'contacts');
		$form->setOutputScreen ();
		$result = $form->process ($html);
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to browse all records
	public function browse ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# Obtain the data for this gallery
		$parameters = array (
			'collection' => $this->gallery['id'],
			'page' => (isSet ($_GET['page']) && ctype_digit ($_GET['page']) ? $_GET['page'] : NULL),
			'imagesize' => '100',
		);
		$data = $this->getArticles ($parameters);
		
		# End if error
		if (isSet ($data['error'])) {
			$this->page404 ();
			return false;
		}
		
		# Page header
		$this->template['pageHeader'] = $this->pageHeader ($this->gallery);
		
		# Assign the gallery data into the template
		$this->template['collection'] = $this->gallery;
		
		# Base URL
		$this->template['baseUrl'] = $this->baseUrl;
		
		# Assign the data into the template
		$this->template['data'] = $data;
		
		if (!$data['articles']) {
			#!# Inform admin
			application::sendHeader ('404');
			return false;
		}
		
		# Add pagination links
		$this->template['paginationHtml'] = pagination::paginationLinks ($data['pagination']['page'], $data['pagination']['totalPages'], $this->gallery['baseUrl'] . "/{$this->action}/");
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Wrapper function to create a gallery
	#!# Needs templatising
	public function gallery ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# Start the HTML
		$html = '';
		
		# Page header
		$html .= $this->pageHeader ($this->gallery);
		
		$html .= "\n<h2>Gallery of all items</h2>";
		
		$fullTitle = htmlspecialchars ($this->gallery['title']) . ($this->gallery['abbreviation'] ? ' (' . htmlspecialchars ($this->gallery['abbreviation']) . ')' : '');
		$html .= "\n<p>Here you can browse the gallery of all the <a href=\"{$this->gallery['baseUrl']}/\"><strong>{$fullTitle}</strong></a> items.</p>";
		$html .= "\n<p>You can also <a href=\"{$this->gallery['baseUrl']}/browse/\"><img src=\"/images/icons/layout_content.png\" alt=\"\" class=\"icon\"> show descriptions for each item</a>.</p>";
		
		# Obtain the data for this gallery
		$constraints = array (
			'collection' => $this->gallery['id'],
			'requireimages' => 1,
			'page' => (isSet ($_GET['page']) && ctype_digit ($_GET['page']) ? $_GET['page'] : NULL),
		);
		$data = $this->getArticles ($constraints);
		
		# End if error
		if (isSet ($data['error'])) {
			$this->page404 ();
			return false;
		}
		
		# End if no data
		#!# Inform admin
		if (!$data['articles']) {
			application::sendHeader ('404');
			$html = "\n<p>No items were found.</p>";
			echo $html;
			return false;
		}
		
		$paginationHtml = pagination::paginationLinks ($data['pagination']['page'], $data['pagination']['totalPages'], $this->gallery['baseUrl'] . "/{$this->action}/");
		
		# Determine the introduction
		#!# Count shows number of items, not total with images
		$html .= "\n<p>There " . ($data['pagination']['total'] == 1 ? 'is one item' : 'are ' . number_format ($data['pagination']['total']) . ' items')
			. ($this->gallery['count'] == 1 ? ' which has an image' : ' which have images')
			. ' in this collection' . ($paginationHtml ? ', of which ' . ($data['pagination']['count'] == 1 ? 'one is shown' : "{$data['pagination']['count']} are shown") . ' below. Use the navigation to view more pages' : '')
			. '.</p>';
		
		# Create the HTML, surrounded by pagination controls
		$html .= $paginationHtml;
		$html .= $this->galleryHtmlFromArticleData ($data['articles'], true);
		if ($paginationHtml) {$html .= "\n<div id=\"endpagination\">{$paginationHtml}</div>";}
		
		# Show the listing HTML
		echo $html;
	}
	
	
	# Show the 'about' text
	public function about ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# Load the file if it exists
		$file = $_SERVER['DOCUMENT_ROOT'] . $this->gallery['baseUrl'] . '/about.html';
		if (file_exists ($file)) {
			$this->gallery['aboutPageHtml'] = file_get_contents ($file);
		}
		
		# Page header
		$this->template['pageHeader'] = $this->pageHeader ($this->gallery);
		
		# Perform string replacement
		$this->template['html'] = $this->applyPlaceholderReplacement ($this->gallery['aboutPageHtml']);
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
		
	}
	
	
	# Show the 'contacts' text
	public function contacts ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# Get the HTML
		if (!$this->gallery['contactsPageHtml']) {
			echo $html = "<p>No contact details for this collection are available at present.</p>";
			return;
		}
		$html  = $this->gallery['contactsPageHtml'];
		
		# Add a contact form
		if ($this->gallery['contactsPageEmail']) {
			$html .= "\n<h3>Contact form</h3>";
			
			# Add the form
			$form = new form (array (
				'displayRestrictions' => false,
			));
			$form->textarea (array (
				'name'			=> 'message',
				'title'					=> 'Message',
				'required'				=> true,
				'cols'				=> 40,
			));
			$form->input (array (
				'name'			=> 'name',
				'title'					=> 'Your name',
				'required'				=> true,
			));
			$form->email (array (
				'name'			=> 'contacts',
				'title'					=> 'E-mail',
				'required'				=> true,
			));
			$form->input (array (
				'name'			=> 'howfoundout',
				'title'					=> 'How did you discover this site?',
				'required'				=> false,
			));
			
			# Set the processing options
			$form->setOutputEmail ($this->gallery['contactsPageEmail'], $this->settings['administratorEmail'], 'Online catalogue [message via the website]', NULL, $replyToField = 'contacts');
			$form->setOutputScreen ();
			
			# Process the form
			$result = $form->process ($html);
		}
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to do global search
	public function globalsearch ()
	{
		# Run the search page, which will echo directly
		$this->search ($global = true);
	}
	
	
	public function dataListing ($data, $table)
	{
		return $this->galleryHtmlFromArticleData ($data);
	}
	
	
	# Function to create a search facility (also acting as the category screen)
	#!# Split this into the public function and doSearch
	public function search ($global = false)
	{
		# In gallery-specific mode, load gallery
		if (!$global) {
			
			# If no gallery, throw 404 page
			if (!$this->gallery = $this->getCollection ()) {
				$this->page404 ();
				return false;
			}
		}
		
		# Heading and form
		$html  = "\n<h2>Search the" . ($global ? ' whole ' . $this->typeToName ($this->settings['type']) : '') . ' catalogue</h2>';
		$baseUrl = ($global ? $this->baseUrl : $this->gallery['baseUrl']);
		$html .= $this->searchBox ($baseUrl, false, $global);
		
		# Create the results if a query is supplied
		$searchPhrase = (isSet ($_GET[$this->settings['queryTerm']]) ? $_GET[$this->settings['queryTerm']] : '');
		if ($searchPhrase) {
			
			# Obtain the data for this gallery
			$constraints = array (
				'collection' => ($global ? NULL : $this->gallery['id']),
				'search' => $searchPhrase,
				'page' => ((isSet ($_GET['page']) && ctype_digit ($_GET['page'])) ? $_GET['page'] : 1),
			);
			$data = $this->getArticles ($constraints);
			
			#!# Need to handle error properly - e.g. must be at least three characters long
			
			if (isSet ($data['error']) || (!$data['articles'])) {
				$html .= "\n<p>Sorry, no results were found for <strong>" . htmlspecialchars ($searchPhrase) . '</strong>. Try another search using the box above.</p>';
				echo $html;
				return false;
			}
			
			# Add lookups for related terms
			if ($data['relatedTerms']) {
				$html .= $this->formatRelatedTerms ($data['relatedTerms']);
			}
			
			# Determine base link
			$baseLink = ($global ? $this->baseUrl : $this->gallery['baseUrl']) . '/search/';
			
			# Determine the query string
			$queryString = "{$this->settings['queryTerm']}=" . urlencode ($_GET[$this->settings['queryTerm']]);
			
			$paginationHtml = pagination::paginationLinks ($data['pagination']['page'], $data['pagination']['totalPages'], $baseLink, $queryString);
			
			$html .= "\n<p>There " . ($data['pagination']['total'] == 1 ? 'is one item' : 'are ' . number_format ($data['pagination']['total']) . ' items')
			. ' matching <strong>' . htmlspecialchars ($searchPhrase) . '</strong>'
			. ($paginationHtml ? ', of which ' . ($data['pagination']['count'] == 1 ? 'one is shown' : "{$data['pagination']['count']} are shown") . ' below. Use the navigation to view more pages.' : ':')
			. '</p>';
			
			$html .= $paginationHtml;
			$html .= $this->galleryHtmlFromArticleData ($data['articles']);
			if ($paginationHtml) {$html .= "\n<div id=\"endpagination\">{$paginationHtml}</div>";}
		}
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to create the search box
	private function searchBox ($baseUrl, $minisearch = false, $global = false)
	{
		$defaultText = ($global ? 'Search all' : 'Search');
		$query = (isSet ($_GET[$this->settings['queryTerm']]) ? trim ($_GET[$this->settings['queryTerm']]) : '');
		$html = "\n\n" . '<form method="get" action="' . $baseUrl . '/search/' . '" class="' . ($minisearch ? 'minisearch' : 'search') . '" name="' . ($minisearch ? 'minisearch' : 'search') . '">
			' . ($minisearch ? '<img src="/images/icons/magnifier.png" alt="" class="icon"> ' : 'Search word or phrase: ') . '<input name="' . $this->settings['queryTerm'] . '" type="search" size="' . ($minisearch ? ($global ? '20' : '10') : '30') . '" value="' . htmlspecialchars ($query) . '" placeholder="' . $defaultText . '" class="autocomplete" />&nbsp;<input value="Search!" accesskey="s" type="submit" class="button" />' /* . ($minisearch ? '' : " <span class=\"small\">&nbsp;[<a href=\"{$this->baseUrl}/search/\">Advanced search</a></span>]") */ . '
		</form>' . "\n";
		
		# Return the HTML
		return $html;
	}
	
	
	# Function to create a lookup from gallery MODES indicators (e.g. BPA) to gallery id (polarart)
	private function galleryModesIndicatorsToId ($galleries)
	{
		# Loop through each
		$galleryModesIndicators = array ();
		foreach ($galleries as $galleryId => $gallery) {
			$collectionId = $gallery['collection'];
			$galleryModesIndicators[$collectionId] = $galleryId;
		}
		
		# Return the lookup array
		return $galleryModesIndicators;
	}
	
	
	# Function to create the gallery HTML
	#!# Global search throws lots of: "PHP Notice:  Undefined property: modesCatalogueViewer::$gallery"
	private function galleryHtmlFromArticleData ($data, $galleryMode = false, $computedArtistName = false)
	{
		# End if no data
		if (!$data) {
			return $html  = "\n<p>There are no items.</p>";
		}
		
		# Re-sort - there is no NATSORT() in MySQL sadly
		$keys = array_keys ($data);
		//natsort ($keys);
		
		# If not in a current gallery, i.e. if in global mode, get the galleries, so that links to them can be created
		if (!isSet ($this->gallery)) {
			$galleries = $this->getCollections ();
		}
		
		//application::dumpData ($galleries);
		//application::dumpData ($data);
		
		# Loop through each record
		$list = array ();
		foreach ($keys as $key) {
			
			# Obtain the article
			$article = $data[$key];
			
			# In global mode, ensure the image is part of some Collection, or do not list it
			#!# Matching should all be done at the data retrieval API end; counts can currently be wrong
			if (isSet ($this->gallery)) {
				$gallery = $this->gallery;
			} else {
				$gallery = false;
				foreach ($article['collections'] as $gallerySignifier) {
					if (isSet ($galleries['collections'][$gallerySignifier])) {
						$gallery = $galleries['collections'][$gallerySignifier];
					}
				}
				if (!$gallery) {
					continue;	// Do not list it
				}
			}
			
			# Define the link
			$link = '<a href="' . $this->articleIdToUrlSlug ($article['id'], $this->settings['type'], $this->baseUrl, true) . '">';
			
			# Create the items
			$thumbnail = $link . (
				($article['status'] == 'P')
				? "<img src=\"{$this->baseUrl}/images/spacer.gif\" alt=\"Contact curator for image\" class=\"right portrait\" />"
				: $this->createImageHtml ($gallery['imagesSubfolder'], $this->getPhotographNumber ($article, $firstOnly = true), $this->settings['listingThumbnailSize'], false, htmlspecialchars ($article['title']), true)
			) . '</a>';
			
			# Create either a table or a gallery
			if ($galleryMode) {
				$list[] = "\n\t<li>{$thumbnail}</li>";
			} else {
				
				# Create the gallery link HTML
				$galleryLinkHtml  = array ();
				if (!isSet ($this->gallery)) {
					foreach ($article['collections'] as $collectionId) {
						if (isSet ($galleries['collections'][$collectionId])) {
							$galleryLinkHtml[] = '<a title="' . htmlspecialchars ($galleries['collections'][$collectionId]['title']) . '" href="' . $galleries['collections'][$collectionId]['url'] . '">' . htmlspecialchars ($galleries['collections'][$collectionId]['title']) . '</a>';
						}
					}
				}
				
				# Compile the gallery list(s)
				$galleryLinkHtml = ($galleryLinkHtml ? ' <span class="gallerylink">' . ((count ($galleryLinkHtml) == 1) ? 'Gallery' : 'Galleries') . ': ' . implode (', ', $galleryLinkHtml) . '</span>' : '');
				
				# Construct the list
				$list[$key]['thumbnail'] = $thumbnail;
				$list[$key]['info']  = '<h3>' . $link . htmlspecialchars (ucfirst ($article['title'])) . '</a></h3> <span class="recordnumber">Record: ' . str_replace (' ', '&nbsp;', $article['id']) . '</span>' . $galleryLinkHtml;
				if ($computedArtistName) {$list[$key]['info'] .= "\n<p class=\"artist\">by " . htmlspecialchars ($computedArtistName) . "</a></p>\n";}
				$list[$key]['info'] .= '<p>' . $article['briefDescription'] . '</p>';
			}
		}
		
		# Compile the HTML
		if ($galleryMode) {
			$html  = "\n<div class=\"gallery\">\n<ul>" . implode ('', $list) . "\n</ul>\n</div>";
		} else {
			$html  = application::htmlTable ($list, false, 'lines listing spaced', false, true, true);
		}
		
		# Return the HTML
		return $html;
	}
	
	
	# Function to format related terms
	private function formatRelatedTerms ($result)
	{
		# Create the links
		$lookup = array ();
		foreach ($result as $element => $words) {
			$wordLinks = array ();
			foreach ($words as $word) {
				$word = trim ($word);
				$wordLinks[] = "<a href=\"{$this->baseUrl}/search/?{$this->settings['queryTerm']}=" . htmlspecialchars (urlencode ($word)) . '">' . htmlspecialchars ($word) . '</a>';
			}
			$lookup[$element] = $element . (((count ($wordLinks) > 1) && (substr (strtolower ($element), -4) == 'term')) ? 's' : '') . ': ' . implode (', ', $wordLinks);
		}
		$html  = application::htmlUl ($lookup, 1, 'small compact');
		
		# Return the HTML
		return $html;
	}
	
	
	# Function to parse the photograph number
	private function getPhotographNumber ($data, $firstOnly = false)
	{
		# Find the images
		$photographs = $data['imageFiles'];
		
		# If only the first is required, return that
		if ($firstOnly) {
			if ($photographs) {
				return $photographs[0];
			}
		}
		
		# Return the photos list
		return array_values ($photographs);
	}
	
	
	# Function to show the categories
	public function categories ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# End if not enabled
		if ($this->gallery['disableCategories']) {
			$this->page404 ();
			return false;
		}
		
		# Get the categories
		if (!$categories = $this->getCategories ($this->gallery['id'])) {
			#!# Inform admin
			$this->template['notFound'] = true;
		} else {
			
			# Page header
			$this->template['pageHeader'] = $this->pageHeader ($this->gallery);
			
			# Define link
			$this->template['aatLink'] = $this->settings['aatLink'];
			
			# Assign the gallery data into the template
			$this->template['collection'] = $this->gallery;
			
			# Register the list to the template
			$this->template['categories'] = $categories;
		}
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to show the category hierarchy
	public function hierarchy ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# Get the categories or end
		if (!$categories = $this->getCategories ($this->gallery['id'], $includeUnclassified = false)) {
			#!# Inform admin
			$this->template['notFound'] = true;
		} else {
			
			# Page header
			$this->template['pageHeader'] = $this->pageHeader ($this->gallery);
			
			# Define link
			$this->template['aatLink'] = $this->settings['aatLink'];
			
			# Assign the gallery data into the template
			$this->template['collection'] = $this->gallery;
			
			# Get the hierarchy
			$categoryHierarchy = $this->getCategoryHierarchy ($categories);
			$this->template['categoryHierarchy'] = application::htmlUlHierarchical ($categoryHierarchy, 'pde', false, true, true, 0, $baseUrl = "{$this->gallery['baseUrl']}/categories");
		}
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to get the hierarchy of categories
	private function getCategoryHierarchy ($categories, $splitBy = ' & ')
	{
		# Add the final item to the end of the hierarchy if it doesn't already exist as the last component
		foreach ($categories as $key => $values) {
			if (!preg_match ('@' . ' & ' . trim ($key) . '$' . '@i', trim ($values['classification']))) {
				$categories[$key]['classification'] .= $splitBy . '[' . ucfirst ($values['category']) . ']';
			}
		}
		
		# Loop through each category to get the hierarchy of category components
		$categoryComponents = array ();
		foreach ($categories as $key => $attributes) {
			$categoryComponents[$key] = explode ($splitBy, $attributes['classification']);
		}
		
		# Loop through each category to assemble the components hierarchically
		$masterHierarchy = array ();
		foreach ($categoryComponents as $hierarchyComponents) {
			
			# Convert the indexed array into a hierarchy
			$hierarchy = array ();
			$elements = count ($hierarchyComponents);
			for ($i = $elements - 1; $i >= 0; $i--) {
				#!# Use of ucfirst (strtolower ()) and splitting algorithm only necessary due to unclean data
				$name = ucfirst (strtolower (trim ($hierarchyComponents[$i])));
				if (substr ($name, 0, 1) == '[') {
					$name = '[' . ucfirst (substr ($name, 1));
				}
				
				$hierarchy = array ($name => $hierarchy);
			}
			
			# Add the hierarchy into the master hierarchy
			$masterHierarchy = array_merge_recursive ($masterHierarchy, $hierarchy);
		}
		
		# Recursively ksort the hierarchy
		application::ksortRecursive ($masterHierarchy);
		
		# Return the master hierarchy
		return $masterHierarchy;
	}
	
	
	# Function to create the category screen
	public function category ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# End if not enabled
		if ($this->gallery['disableCategories']) {
			$this->page404 ();
			return false;
		}
		
		# Get the categories
		if (!$categories = $this->getCategories ($this->gallery['id'])) {
			#!# Inform admin
			$this->template['notFound'] = true;
			return false;
		}
		
		$category = ucfirst ($_GET['category']);
		
		# Ensure the category exists
		if (!isSet ($categories[$category])) {
			application::sendHeader ('404');
			echo $html  = "\n<p>There is no such category <em>" . htmlspecialchars (rawurldecode ($_GET['category'])) . '</em>.</p>';
			return;
		}
		
		# Start the HTML
		$html = '';
		
		# Page header
		$html .= $this->pageHeader ($this->gallery);
		
		$html .= "<h2>Items in category '<em>" . htmlspecialchars ($category) . "</em>'</h2>";
		
		# Add in the categorisation
		#!# Needs span styling replacement as per listing
		$html .= "\n<p class=\"classification\"><strong>AAT classification:</strong> " . htmlspecialchars ($categories[$category]['classification']) . '</p>';
		
		# Obtain the data for this gallery
		$constraints = array (
			'collection' => $this->gallery['id'],
			'category' => $_GET['category'],
			// 'page' => (isSet ($_GET['page']) && ctype_digit ($_GET['page']) ? $_GET['page'] : NULL),
		);
		$data = $this->getArticles ($constraints);
		
		#!# Inform admin
		if (!$data['articles']) {
			application::sendHeader ('404');
			$html = "\n<p>No items were found.</p>";
			echo $html;
			return false;
		}
		
		$paginationHtml = pagination::paginationLinks ($data['pagination']['page'], $data['pagination']['totalPages'], $this->gallery['baseUrl'] . "/{$this->action}/");
		
		# Determine the introduction
		$html .= "\n<p>There " . ($data['pagination']['total'] == 1 ? 'is one item' : 'are ' . number_format ($data['pagination']['total']) . ' items') . ' in this category'
		. ($paginationHtml ? ', of which ' . ($data['pagination']['count'] == 1 ? 'one is shown' : "{$data['pagination']['count']} are shown") . ' below. Use the navigation to view more pages' : '') . '.'
		. '</p>';
		
		$html .= $paginationHtml;
		$html .= $this->galleryHtmlFromArticleData ($data['articles']);
		if ($paginationHtml) {$html .= "\n<div id=\"endpagination\">{$paginationHtml}</div>";}
		
		
		echo $html;
	}
	
	
	# Function to show materials
	public function materials ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# End if not enabled
		if ($this->gallery['disableMaterials']) {
			$this->page404 ();
			return false;
		}
		
		# Get the data
		if (!$data = $this->getMaterials ($this->gallery['id'])) {
			#!# Inform admin
			$this->template['notFound'] = true;
		} else {
			
			# Page header
			$this->template['pageHeader'] = $this->pageHeader ($this->gallery);
			
			# Define link
			$this->template['aatLink'] = $this->settings['aatLink'];
			
			# Compute class names for a tag cloud
			$tagCloud = application::tagCloud ($data);
			
			# Convert to nested array
			$items = array ();
			foreach ($data as $name => $total) {
				$items[$name] = array ('class' => $tagCloud[$name], 'total' => $total);
			}
			
			# Register the data
			$this->template['items'] = $items;
		}
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to create the material screen
	public function material ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# End if not enabled
		if ($this->gallery['disableMaterials']) {
			$this->page404 ();
			return false;
		}
		
		# Obtain the material from the URL
		$material = $_GET['material'];
		
		# See if the material exists
		$materials = $this->getMaterials ($this->gallery['id']);
		if (!isSet ($materials[$material])) {
			application::sendHeader ('404');
			echo $html  = "\n<p>There is no such material <em>" . ucfirst (htmlspecialchars ($material)) . '</em>.</p>';
			return;
		}
		
		# Start the HTML
		$html = '';
		
		# Page header
		$html .= $this->pageHeader ($this->gallery);
		
		$html .= "<h2>Items made from material: '<em>" . ucfirst (htmlspecialchars ($material)) . "</em>'</h2>";
		
		# Obtain the data for this gallery
		$constraints = array (
			'collection' => $this->gallery['id'],
			'material' => $_GET['material'],
			// 'page' => (isSet ($_GET['page']) && ctype_digit ($_GET['page']) ? $_GET['page'] : NULL),
		);
		$data = $this->getArticles ($constraints);
		
		#!# Inform admin
		if (!$data['articles']) {
			application::sendHeader ('404');
			$html = "\n<p>No items were found.</p>";
			echo $html;
			return false;
		}
		
		$paginationHtml = pagination::paginationLinks ($data['pagination']['page'], $data['pagination']['totalPages'], $this->gallery['baseUrl'] . "/{$this->action}/");
		
		# Determine the introduction
		$html .= "\n<p>There " . ($data['pagination']['total'] == 1 ? 'is one item' : 'are ' . number_format ($data['pagination']['total']) . ' items') . ' which use this material'
		. ($paginationHtml ? ', of which ' . ($data['pagination']['count'] == 1 ? 'one is shown' : "{$data['pagination']['count']} are shown") . ' below. Use the navigation to view more pages' : '') . '.'
		. '</p>';
		
		
		$html .= $paginationHtml;
		$html .= $this->galleryHtmlFromArticleData ($data['articles']);
		if ($paginationHtml) {$html .= "\n<div id=\"endpagination\">{$paginationHtml}</div>";}
		
		
		
		# Show the listing HTML
		echo $html;
	}
	
	
	# Function to show the artists
	public function artists ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# End if not enabled
		if ($this->gallery['disableArtists']) {
			$this->page404 ();
			return false;
		}
		
		# Page header
		$this->template['pageHeader'] = $this->pageHeader ($this->gallery);
		
		# Get the data
		if (!$data = $this->getArtists ($this->gallery['id'])) {
			#!# Inform admin
			$this->template['notFound'] = true;
		} else {
			
			# Convert to nested array
			$items = array ();
			foreach ($data as $name => $total) {
				$items[$name] = array ('total' => $total);
			}
			
			# Register the data
			$this->template['items'] = $items;
		}
		
		# Process the template
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to create the artist screen
	public function artist ()
	{
		# If no gallery, throw 404 page
		if (!$this->gallery = $this->getCollection ()) {
			$this->page404 ();
			return false;
		}
		
		# End if not enabled
		if ($this->gallery['disableArtists']) {
			$this->page404 ();
			return false;
		}
		
		# Get the artists
		$artistsRaw = $this->getArtists ($this->gallery['id']);
		
		# Lowercase the artists for the purposes of the check
		$artists = array ();
		foreach ($artistsRaw as $name => $total) {
			$nameLowercased = strtolower ($name);
			$artists[$nameLowercased] = $name;
		}
		
		# Obtain the artist from the URL
		//$artist = str_replace (' > ', ' & ', rawurldecode ($_GET['artist']));
		$artist = rawurldecode (utf8_encode ($_GET['artist']));
		
		# See if the artist exists
		#!# Merge into the main getArticles call to avoid two HTTP requests
		if (!isSet ($artists[$artist])) {
			application::sendHeader ('404');
			echo $html  = "\n<p>There is no such artist <em>" . htmlspecialchars (rawurldecode ($_GET['artist'])) . '</em>.</p>';
			return;
		}
		
		# Start the HTML
		$html = '';
		
		# Page header
		$html .= $this->pageHeader ($this->gallery);
		
		#!# This should check if there is data, but in theory that should always be the case
		$html .= "<h2>Items by artist <em>" . ucfirst (htmlspecialchars ($artists[$artist])) . "</em></h2>";
		
		# Obtain the data for this gallery
		$constraints = array (
			'collection' => $this->gallery['id'],
			'artist' => $_GET['artist'],
			'page' => (isSet ($_GET['page']) && ctype_digit ($_GET['page']) ? $_GET['page'] : NULL),
		);
		$data = $this->getArticles ($constraints);
		
		# End if error
		if (isSet ($data['error'])) {
			$this->page404 ();
			return false;
		}
		
		#!# Inform admin
		if (!$data['articles']) {
			application::sendHeader ('404');
			$html = "\n<p>No items were found.</p>";
			echo $html;
			return false;
		}
		
		$paginationHtml = pagination::paginationLinks ($data['pagination']['page'], $data['pagination']['totalPages'], $this->gallery['baseUrl'] . '/artists/' . str_replace (' ', '+', htmlspecialchars ($artist)) . '/');
		
		
		# Determine the introduction
		$html .= "\n<p>There " . ($data['pagination']['total'] == 1 ? 'is one item' : 'are ' . number_format ($data['pagination']['total']) . ' items') . ' by this artist'
		. ($paginationHtml ? ', of which ' . ($data['pagination']['count'] == 1 ? 'one is shown' : "{$data['pagination']['count']} are shown") . ' below. Use the navigation to view more pages' : '') . '.'
		. '</p>';
		
		#!# Reverse lookup of un-upper-cased artist is messy
		$artistNameNormalCase = $artists[$artist];
		
		$html .= $paginationHtml;
		$html .= $this->galleryHtmlFromArticleData ($data['articles'], false, $artistNameNormalCase);
		if ($paginationHtml) {$html .= "\n<div id=\"endpagination\">{$paginationHtml}</div>";}
		
		# Show the listing HTML
		echo $html;
	}
	
	
	# Function to determine a baseUrl for a specified type
	private function typeBaseUrl ($type, $item)
	{
		return "/{$type}/" . strtolower (str_replace ('?', '%3F', htmlspecialchars (urlencode ($item)))) . '/';
	}
	
	
	# Function to make a link to a category
	#!# Refactor into typeLink
	public function makeCategoryLink ($category)
	{
		# Return unamended if the category linking is disabled
		if ($this->gallery['disableCategories']) {return $category;}
		
		# Return the assembled string
		$category = str_replace (' & ', ', ', $category);
		return '<a href="' . $this->gallery['baseUrl'] . $this->typeBaseUrl ('categories', $category) . '">' . htmlspecialchars ($category) . '</a>';
	}
	
	
	# Function to get collections from the API
	private function getCollections ($fields = array ())
	{
		# Obtain the record data from the API call
		$grouping = $this->settings['type'];
		if ($this->settings['type'] == 'museum') {$grouping .= ',art';}
		$apiUrl = $this->settings['apiUrl'] . "/collections?baseUrl={$this->baseUrl}&grouping=" . $grouping . ($fields ? '&fields=' . implode (',', $fields) : '');
		$json = file_get_contents ($apiUrl);
		$collections = json_decode ($json, true);
		
		# Return the data
		return $collections;
	}
	
	
	# Function to get a collection
	private function getCollection ()
	{
		# End if no URL value supplied
		if (!isSet ($_GET['gallery']) || !strlen ($_GET['gallery'])) {return false;}
		$galleryId = $_GET['gallery'];
		
		# Get the gallery data
		$apiUrl = $this->settings['apiUrl'] . "/collection?baseUrl={$this->baseUrl}&id=" . $galleryId;
		$json = file_get_contents ($apiUrl);
		$gallery = json_decode ($json, true);
		if (isSet ($gallery['error'])) {
			return false;
		}
		
		# Return the gallery
		return $gallery;
	}
	
	
	# Function to get articles data from the API
	private function getArticles ($constraints)
	{
		# Obtain the record data from the API call
		$apiUrl = $this->settings['apiUrl'] . '/articles?' . http_build_query ($constraints);
		$json = file_get_contents ($apiUrl);
		$articles = json_decode ($json, true);
		
		# Return the data
		return $articles;
	}
	
	
	# Function to get categories from the API
	private function getCategories ($collectionId, $includeUnclassified = true)
	{
		# Obtain the record data from the API call
		$apiUrl = $this->settings['apiUrl'] . '/categories?collection=' . urlencode ($collectionId) . (!$includeUnclassified ? '&includeUnclassified=0' : '');
		$json = file_get_contents ($apiUrl);
		$categories = json_decode ($json, true);
		
		# Return the data
		return $categories;
	}
	
	
	# Function to get artists from the API
	private function getArtists ($galleryId)
	{
		# Obtain the record data from the API call
		$apiUrl = $this->settings['apiUrl'] . '/artists?collection=' . urlencode ($galleryId);
		$json = file_get_contents ($apiUrl);
		$artists = json_decode ($json, true);
		
		# Return the data
		return $artists;
	}
	
	
	# Function to get materials from the API
	private function getMaterials ($galleryId)
	{
		# Obtain the record data from the API call
		$apiUrl = $this->settings['apiUrl'] . '/materials?collection=' . urlencode ($galleryId);
		$json = file_get_contents ($apiUrl);
		$materials = json_decode ($json, true);
		
		# Return the data
		return $materials;
	}
	
	
	# Function to convert an article ID to a URL slug
	#!# Needs to be a pluggable callback
	private function articleIdToUrlSlug ($string, $type, $baseUrl, $asFullUrl = false)
	{
		# Lower-case
		$string = strtolower ($string);
		
		# Convert slash to dot
		$string = str_replace ('/', '.', $string);
		
		# Convert to a full URL if necessary
		if ($asFullUrl) {
			$string = $baseUrl . '/article/' . $string . '/';
		}
		
		# Return the result
		return $string;
	}
	
	
	# Function to convert a URL slug to an article ID
	#!# Needs to be a pluggable callback
	private function urlSlugToArticleId ($string, $type)
	{
		# Convert dot to slash
		$string = str_replace ('.', '/', $string);
		
		# Upper-case
		$string[0] = strtoupper ($string[0]);
		
		# Return the result
		return $string;
	}
	
	
	# Function to show an article
	public function article ()
	{
		# End if no article number supplied
		if (!isSet ($_GET['article']) || !strlen ($_GET['article'])) {
			$this->page404 ();
			return false;
		}
		
		# Obtain and convert the article number
		$id = $this->urlSlugToArticleId ($_GET['article'], $this->settings['type']);
		
		# Obtain the record data from the API call
		$apiUrl = $this->settings['apiUrl'] . '/article?id=' . urlencode ($id) . '&collection=?' . '&imagesize=450' . ($this->userIsAdministrator ? '&includeXml=1' : '');
		$json = file_get_contents ($apiUrl);
		$article = json_decode ($json, true);
		//application::dumpData ($article);
		if (isSet ($article['error'])) {
			$this->page404 ();
			return false;
		}
		
		# End if no gallery context
		if (!$article['collections']) {
			$this->page404 ();
			return false;
		}
		
		# Select the gallery ID to use
		#!# If collections are not enabled, if the first is not enabled, it will be chosen; need to delete gallery specifiers in the data that are not enabled
		$collectionId = $article['collections'][0];
		
		# Obtain the gallery details for the record
		$apiUrl = $this->settings['apiUrl'] . "/collection?baseUrl={$this->baseUrl}&id=" . $collectionId;
		$json = file_get_contents ($apiUrl);
		$gallery = json_decode ($json, true);
		if (isSet ($gallery['error'])) {
			$this->page404 ();
			return false;
		}
		
		# If materials are disabled, remove from article
		#!# Move to model
		if ($gallery['disableMaterials']) {
			$article['materials'] = array ();
		}
		
		# Omit *UDC in classified name type, except for whitelisted collections
		$enableUdcWhitelistedCollections = array ('antc');
		$enableUdcVisibility = (array_intersect ($enableUdcWhitelistedCollections, $article['collections']));
		if (!$enableUdcVisibility) {
			if ($article['classifiedNames']) {
				foreach ($article['classifiedNames'] as $key => $value) {
					#!# regex needs checking
					if (preg_match ('/UDC$/', $key)) {
						unset ($article['classifiedNames'][$key]);
					}
				}
			}
		}
		
		# Set the title
		$this->titleBrowser = array (
			htmlspecialchars ($gallery['title']),
			htmlspecialchars ($article['title']),
		);
		$this->titleBreadcrumbTrail = array (
			"<a href=\"{$gallery['baseUrl']}/\">" . htmlspecialchars ($gallery['title']) . '</a>',
			'Article',
		);
		
		# Page header
		$this->template['pageHeader'] = $this->pageHeader ($gallery);
		
		# Assign the article into the template
		$this->template['article'] = $article;
		
		# Other general fields
		$this->template['feedbackHref'] = $this->baseUrl . '/feedback.html';
		$this->template['organisationName'] = $this->settings['organisationName'];
		
		# Add a flag for whether the record is a museum type
		$this->template['type'] = $this->settings['type'];
		
		#!# Consider adding support for showing the internal location for internal users, which used to be PERMANENT-LOCATION
		
		# Add info about the context of this gallery if required
		#!# Parameterise
		$contexts = array (
		);
		if ($article['context'] && isSet ($contexts[$article['context']])) {
			$this->template['contextHtml'] = $contexts[$article['context']];
		}
		
		# Add contextual navigation links
		$this->template['navigationLinksHtml'] = $this->navigationLinksHtml ($article['navigationIds'], $gallery['baseUrl']);
		if (!$gallery['disableCategories']) {
			if (isSet ($article['navigationIdsAdditional']['categories']) && $article['navigationIdsAdditional']['categories']) {
				foreach ($article['navigationIdsAdditional']['categories'] as $category => $ids) {
					$this->template['navigationLinksHtml'] .= $this->navigationLinksHtml ($ids, $gallery['baseUrl'], 'categories', $category, "Items in the '<em>%s</em>' category");
				}
			}
		}
		if (!$gallery['disableArtists']) {
			if (isSet ($article['navigationIdsAdditional']['artists']) && $article['navigationIdsAdditional']['artists']) {
				foreach ($article['navigationIdsAdditional']['artists'] as $artist => $ids) {
					$this->template['navigationLinksHtml'] .= $this->navigationLinksHtml ($ids, $gallery['baseUrl'], 'artists', $artist, 'Items by <em>%s</em>');
				}
			}
		}
		
		# Add debugging info if in the development runtime
		$this->template['debugInfo'] = $this->debugInfo ($article, $apiUrl);
		
		# Process the template
		#!# Property only needed for makeMaterialLink etc
		$this->gallery = $gallery;
		$html = $this->templatise ();
		
		# Show the HTML
		echo $html;
	}
	
	
	# Function to assemble the navigation links as an HTML list
	private function navigationLinksHtml ($positions, $galleryBaseUrl, $contextType = false, $contextValue = false, $rootLabel = 'Browse all items')
	{
		# Determine the URLs
		foreach ($positions as $key => $value) {
			$positions[$key . 'Href'] = ($positions[$key] ? $this->articleIdToUrlSlug ($value, $this->settings['type'], $this->baseUrl, true) : NULL);
		}
		
		# Determine the title for the root position
		$positions['rootHref'] = '/browse/';
		if ($contextType) {
			$positions['rootHref'] = $this->typeBaseUrl ($contextType, $contextValue);
		}
		
		# Determine the central text
		if ($contextType) {
			$rootLabel = sprintf ($rootLabel, htmlspecialchars ($contextValue));
		}
		
		# Assign the labels
		$labels = array (
			'previous' => '<img src="/images/icons/control_rewind_blue.png" class="icon" alt="Previous" />',
			'root' => $rootLabel,
			'next' => '<img src="/images/icons/control_fastforward_blue.png" class="icon" alt="Next" />',
		);
		
		# Get and create the previous/next links, in the order above
		$list = array ();
		foreach ($labels as $key => $label) {
			$link = $positions[$key . 'Href'];
			if ($key == 'root') {
				$link = $galleryBaseUrl . $link;
			}
			if ($link) {
				$list[$key] = '<a' . ($key == 'root' ? '' : " title=\"Go to {$key}\"") . " href=\"{$link}\">" . $label . '</a>';
			} else {
				$list[$key] = '&nbsp;';
			}
		}
		
		# Compile the HTML
		#!# Needs to be better formatted
		$html = '<p>' . implode ('&nbsp;&nbsp;', $list) . '</p>';
		
		# Return the HTML
		return $html;
	}
	
	
	# Function to show debugging info for a record
	private function debugInfo ($article, $apiUrl)
	{
		# End if the user is not an administrator
		if (!$this->userIsAdministrator) {return;}
		
		# Build the HTML
		$html  = "\n<h3>Debugging info (shown only to administrators)</h3>";
		
		# Show the photograph number
		$html .= "\n<p>Expected image: " . ($article['imageFiles'] ? $article['imageFiles'][0] : '<em class="comment">None</em>') . '</p>';
		
		# Extract the XML
		$xml = $article['xml'];
		unset ($article['xml']);
		
		# Show the JSON
		$html .= "\n" . '<div class="graybox">';
		$html .= "\n" . '<p class="apilink"><a href="' . htmlspecialchars (str_replace ('&includeXml=1', '', $apiUrl)) . '"><img src="/images/icons/feed.png" alt="" class="icon" /> API</a></p>';
		$html .= "\n" . '<p><strong>API response:</strong></p>';
		$html .= "\n" . '<pre>';
		$html .= "\n" . json_encode ($article, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		$html .= "\n</pre>";
		$html .= "\n</div>";
		
		# Show the XML in a box
		$html .= xml::formatter ($xml);
		
		# Return the HTML
		return $html;
	}
	
	
	# Smarty plugin to convert an image reference to an image tag
	#!# Aim to get rid of this wrapper as noted below
	public function imgTag ($filename)
	{
		# Create the image HTML
		#!# Move the URL generation into the model
		#!# Move the tag generation into the view
		return $this->createImageHtml (
			$this->gallery['imagesSubfolder'],
			$filename,
			$this->settings['mainImageSize'],						// size
			$this->settings['articleImageClass'] . ' shadow',		// class
			/* $article['title'] . */ 'Image (c) ' . $this->settings['organisationName']	// alt
		);
	}
	
	
	# Smarty plugin to convert an article ID to a URL slug
	#!# Aim to get rid of this wrapper by adjusting the defaults for articleIdToUrlSlug
	public function articleIdUrl ($articleId)
	{
		return $this->articleIdToUrlSlug ($articleId, $this->settings['type'], $this->baseUrl, true);
	}
	
	
	# Smarty plugin to hyperlink an artist if required
	public function makeArtistLink ($artist)
	{
		# Convert entities in artist name
		$artist = htmlspecialchars ($artist);
		
		# Add link
		#!# Reverse the logic so that 'enableArtists' is the configuration key
		if (!$this->gallery['disableArtists']) {
			$artist = '<a href="' . $this->gallery['baseUrl'] . $this->typeBaseUrl ('artists', $artist) . '">' . $artist . '</a>';
		}
		
		# Return the value
		return $artist;
	}
	
	
	# Smarty plugin to hyperlink a material entry if required
	public function makeMaterialLink ($material)
	{
		# Split out any note in brackets
		$note = false;
		if (preg_match ('/^(.+) \(([^)]+)\)$/', $material, $matches)) {
			$material = $matches[1];
			$note = $matches[2];
		}
		
		# Hyperlink the material name part
		$material = "<a href=\"{$this->gallery['baseUrl']}/materials/" . str_replace ('?', '%3F', htmlspecialchars (urlencode ($material))) . '/">' . ucfirst (htmlspecialchars ($material)) . '</a>';
		
		# If a note was present, add it back in
		if ($note) {
			$material .= htmlspecialchars (" ({$note})");
		}
		
		# Return the value
		return $material;
	}
	
	
	
/* Thumbnails */
	
	
	# Function to convert an image location to a thumbnail location
	private function thumbnailLocation ($location, $outputFormat, $size)
	{
		# Lower-case extensions, and convert Windows backslashes to Unix forward-slashes
		$imageMapping = array (
			'.JPG' => '.jpg',
			'.JPEG' => '.jpeg',
			'.TIF' => '.tif',
			'.TIFF' => '.tiff',
			'\\' => '/',
		);
		$location = trim (str_replace (array_keys ($imageMapping), array_values ($imageMapping), $location));
		
		# Determine the thumbnail location
		$thumbnailLocation  = $this->baseUrl . '/images/' . ($size == $this->settings['listingThumbnailSize'] ? 'thumbnails/' : '');
		$thumbnailLocation .= preg_replace ('/.(jpeg|tiff?)$/', ".{$outputFormat}", basename ($location));
		
		# Return the result
		return $thumbnailLocation;
	}
	
	
	# Function to generate thumbnails
	#!# Refactor out the location stuff so that can be used in the debug view
	private function createImageHtml ($imagesSubfolder, $location, $size = 300, $class = 'right', $alt = 'Image', $imageOnlyIfNone = false)
	{
		// echo $location . "<br>";
		# Absence message
		$absenceMessage = '<img src="/images/icons/page_white_delete.png" alt="" class="icon nullimage" />';
		if (!$imageOnlyIfNone) {$absenceMessage = '<span class="nullimage comment">' . $absenceMessage . ' (No image available for this item)</span>';}
		
		# If no image is specified, end
		if (!$location) {return $absenceMessage;}
		
		# Set the output format; NB GIF intended for thumbnails; JPG is too large filesize and PNG is even worse
		$outputFormat = ($size == $this->settings['listingThumbnailSize'] ? $this->settings['listingThumbnailType'] : 'jpg');
		
		# Obtain the thumbnail location
		$thumbnailLocation = $this->thumbnailLocation ($location, $outputFormat, $size);
		
		# Get the size of the thumbnail, or create the thumbnail if it doesn't exist
		$thumbnailFile = $_SERVER['DOCUMENT_ROOT'] . $thumbnailLocation;
		if (file_exists ($thumbnailFile)) {
			list ($width, $height, $imageType, $imageAttributes) = getimagesize ($thumbnailFile);
		} else {
			
			# Ensure the directory exists, or make it
			$directory = dirname ($thumbnailFile);
			if (!is_dir ($directory)) {
				umask (0);
				if (!mkdir ($directory, 0774, true)) {
					#!# Throw error
					return false;
				}
			}
			
			# Convert the extension to jpg if using the thumbnail store
			if (substr_count ($this->settings['imageStoreRoot'] . $imagesSubfolder, '/thumbnails/')) {
				#!# This is a bit crude and should check against the file extension rather than assume it is .tif
				$location = preg_replace ('/\.tif$/i', '.jpg', $location);
			}
			
			# Assemble the location
			$location = str_replace ('\\', '/', $location);
			$foundLocation = $location;
			
			# "Be liberal in what you expect"
			#!# Ideally this would not be required, but it would be a losing battle to expect people to get e.g. .JPG and .jpg correct when it's not usually visible in Windows
			if ($this->settings['imageFilenameLiberalMatching']) {
				$sourcePath = $this->settings['imageStoreRoot'] . $imagesSubfolder . $location;
				if (!file_exists ($sourcePath)) {
					
					# Extract the filename part
					$filenamePart = pathinfo ($location, PATHINFO_FILENAME);
					
					# Construct an sql_regcase -style equivalent for the extension, i.e. [Tt][Ii][Ff]
					$extensionPart = pathinfo ($location, PATHINFO_EXTENSION);
					$letters = str_split ($extensionPart);
					$extensionSqlRegcase = '';
					foreach ($letters as $letter) {
						$extensionSqlRegcase .= '[' . strtoupper ($letter) . strtolower ($letter) . ']';
					}
					
					#!# Horrid
					$locationPattern = $filenamePart . '.' . $extensionSqlRegcase;
					if ($result = glob ($this->settings['imageStoreRoot'] . $imagesSubfolder . $locationPattern)) {
						$foundLocation = $result[0];
					}
				}
			}
			
			# Set the location as the whole path
			$location = $foundLocation;
			
			# Ensure the file exists and is readable, or inform the administrator
			if (!is_file ($location)) {
				return $absenceMessage;
				#!# Inform the administrator, but compile the message into a single e-mail first
			}
			if (!is_readable ($location)) {
				return $absenceMessage;
				#!# Inform the administrator, but compile the message into a single e-mail first
			}
			
			# Obtain the image height and width when scaled
			list ($width, $height, $imageType, $imageAttributes) = getimagesize ($location);
			list ($width, $height) = image::scaledImageDimensions ($width, $height, $size);
			
			# Determine whether to include the watermark if not in tiny-thumbnail mode
			$watermark = ($size != $this->settings['listingThumbnailSize'] ? array ($this, 'watermarkImagick') : false);
			
			# Resize the image
			ini_set ('max_execution_time', 300);
			image::resize ($location, $outputFormat, $width, '', $thumbnailFile, $watermark);
		}
		
		# Add a class for portrait layout images
		if ($height > $width) {$class = ($class ? "{$class} portrait" : 'portrait');}
		
		# Show the thumbnail
		$html = '<img' . ($class ? " class=\"{$class}\"" : '') . ($size != $this->settings['listingThumbnailSize'] ? " width=\"$width\" height=\"$height\"" : '') . " src=\"/images/general/item.gif\" style=\"background-image: url('" . str_replace ('#', '%23', $thumbnailLocation) . "');\" alt=\"Image\" title=\"{$alt}\" />";
		
		# Return the HTML
		return $html;
	}
	
	
	# Callback from image::resize
	public function watermarkImagick (&$imageHandle, $height)
	{
		# Magickwand implementation
		if (extension_loaded ('magickwand')) {
			$textWand = NewDrawingWand ();
			DrawAnnotation ($textWand, 8, $height - 30, '(c) ' . $this->settings['organisationName']);
			DrawAnnotation ($textWand, 8, $height - 18, $_SERVER['SERVER_NAME']);
			MagickDrawImage ($imageHandle, $textWand);
			
		# ImageMagick implementation
		} else if (extension_loaded ('imagick')) {
			$draw = new ImagickDraw ();
			$draw->annotation (8, $height - 30, '(c) ' . $this->settings['organisationName']);
			$draw->annotation (8, $height - 18, $_SERVER['SERVER_NAME']);
			$imageHandle->drawImage ($draw);
		}
	}
}

?>
