<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once(dirname(__DIR__, 1) . "/" . 'vendor/autoload.php');

use enums\CrudOperation;
use enums\CrudType;
use enums\CrudResultsetType;
use enums\CrudFetchStyle;	

$laData = new classes\LaData();
$laViewConverter = new classes\LaViewConverter();

//
$postName = basename($_SERVER['PHP_SELF'], ".php");
$post = $laData->crudData(
	CrudOperation::GETPOSTS,
	CrudType::CRUDREAD,
	CrudResultsetType::RESULTSET_ROWS,
	CrudFetchStyle::FETCHSTYLE_ASSOC,
	['post_name' => $postName]						
)[0];

$postTitle = $laViewConverter->escAttr($post['short_desc']);
$postMetaDesc = $laViewConverter->escAttr($post['long_desc']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Standard page metas/links/title. -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Semanticsearch | LiberAnalytics</title>
	<meta name="description" content="<?php echo $postMetaDesc; ?>">
	<link rel="canonical" href="https://liberanalytics.com/Semanticsearch">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Semanticsearch | LiberAnalytics">
	<meta property="og:description" content="<?php echo $postMetaDesc; ?>">
	<meta property="og:url" content="https://liberanalytics.com/Semanticsearch">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="LiberAnalytics">
	<meta property="og:locale" content="en_US">

	<!-- Social media image. -->
	<meta property="og:image" content="https://liberanalytics.com/assets/social/og_core.jpg">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:image:alt" content="LiberAnalytics wordmark on a clean, minimalist background">

	<!-- Browser UI styling for address bar. -->
	<meta name="theme-color" content="#333333">
	<meta name="theme-color" content="#333333" media="(prefers-color-scheme: dark)">
	<meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">

	<!-- Referrer policy: omit referrer header when navigating from HTTPS to HTTP. -->
	<meta name="referrer" content="no-referrer-when-downgrade">
	
	<!-- Stylesheets and javascript modules/files. -->
	<link href="/css/la_tokens.css" rel="stylesheet">
	<link href="/css/la_fonts.css" rel="stylesheet">	
	<link href="/css/la.css" rel="stylesheet">
	<link href="/css/la_hdrftr.css" rel="stylesheet">
	<link href="/css/la_forms.css" rel="stylesheet">
	<link href="/css/la_tags.css" rel="stylesheet">
	<link href="/css/la_ajax.css" rel="stylesheet">
	<link href="/css/post.css" rel="stylesheet">
	<script type="module" src="/js/tagsearch.js"></script>
	<script type="module" src="/js/semanticsearch.js"></script>
</head>

<body class="page page-post site-font-std">

	<?php include_once('laheader.php'); ?>

	<main>
		<div class="page-wrap">
			<article>
				<header class="site-stack-start-mt">
							
					<?php						
						//
						echo $laViewConverter->getPostHeader($post);
					?>

				</header>

				<section class="site-stack-start-mt">

					<p class="site-mt site-font-std"> 
						<span class="site-font-brand">Enterprise</span> AI adoption is increasingly constrained less
                        by access to models than by an organization's ability to make its business semantics, data,
                        workflows, and system interfaces trustworthy enough for models to operate against. The
                        architectural challenge is to reconcile meaning across heterogeneous systems, encode
	                    accepted meanings as reusable metadata, contracts, and rules, enforce deterministic behavior
                        at system boundaries where practical, and confine probabilistic AI to explicitly bounded
                        interpretation and reasoning tasks.
					</p>

                    <p class="site-font-std">
                    	LiberAnalytics currently requires users to express post searches through predefined categories,
                        tags, and other database parameters. Humans, however, do not naturally communicate in database
                        parameters. This article explores whether an LLM can serve as a constrained semantic layer,
                        translating natural-language search intent into validated parameters that can be safely consumed
                        by LiberAnalytics' existing deterministic database orchestration.
                    </p>

					<h3 class="site-font-secondary-heading">Constraining LLM Output with a Database-Derived Schema</h3>

					<p>
						In implementing an LLM-based semantic search the question that must first be posed is:
						what is the LLM permitted to return as valid results from the user's request? If
						an LLM has the ability to interpret and generate human language from a given prompt,
						then the responsibility of the application calling the LLM is to define the set of
						responses permitted and the permissible output contract. Using an application's
						authoritative domain data to constrain an LLM's response to a prompt is one way to
						constrain responses to the application's authoritative domain
						and prevent the model from returning out-of-domain values.
					</p>

					<p>
						LiberAnalytics LLM semantic search limits what the LLM can return from a user request by making the existing authoritative
						post categories and post tags the only valid domain values for those respective response properties. In the below snippet,
						the 'required' is telling the LLM that the postCategory and postTags must be returned, even if the values are null or an
						empty array respectively. The 'additionalProperties' => false instructs the LLM that the returned object may not contain
						properties other than the ones defined under properties.
					</p>				

					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Define the domain of acceptable response values</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// and the structured output schema.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchSchema</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'type'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'object'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'properties'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Define the type and value for post category that</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// can be returned, including null.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'postCategory'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'type'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'string'</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'null'</span>
							<span class="post-snippet-font-std code-php-default-fc">],</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'enum'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">array_merge</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$postCategories</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;[</span>
							<span class="post-snippet-font-std code-php-special-fc">null</span>
							<span class="post-snippet-font-std code-php-default-fc">])</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// Define the type and set of post tag values that</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;// can be returned.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'postTags'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'type'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'array'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'items'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'type'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'string'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'enum'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$postTags</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;],</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Returned objects must contain the postCategory</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// and postTags properties.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'required'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'postCategory'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'postTags'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;],</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Don't return any other properties.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'additionalProperties'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">false</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">];</span>
						</p>

					</div>

					<p>
						We can think of the above schema as four distinct sections:
					</p>

					<ul class="site-list">
						<li>type - defines the permitted JSON type for an object, property, or array item</li>
						<li>enum - defines the specific values permitted</li>
						<li>required - defines which properties must exist</li>
						<li>additionalProperties - defines whether undeclared properties are allowed</li>
					</ul>
					
					<h3 class="site-font-secondary-heading">Defining the LLM’s Role and Output Contract</h3>
					
					<p>
						The LLM’s role is defined in the instructions: identify the user’s primary
						subject as a post category and narrower concepts, technologies, or methods
						as post tags. The instructions also define the no-match behavior, allowing
						the model to return null for postCategory and an empty array for postTags
						when the user’s request does not map clearly to the LiberAnalytics domain.
						The input is simply the user’s submitted search prompt.
					</p>

					<p>
						The more consequential part of the request is the text.format definition.
						Rather than allowing free-form text or arbitrary JSON, the response is
						required to conform to a JSON Schema named semantic_search. With strict
						enabled, the model’s output must adhere to the structure and permitted
						values defined by $semanticSearchSchema.
					</p>					
					
					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Define open ai responses api parameters.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$openAiRequest</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Define the OpenAI model used for semantic interpretation.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'model'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'gpt-5.6-luna'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Guide how the LLM should interpret the user's search prompt</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// within LiberAnalytics' allowed semantic domain, including</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// the expected response behavior when no clear match exists.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'instructions'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Interpret the user search request using only the supplied LiberAnalytics domain. '</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Treat postCategory as the primary subject or broad topic of the request. '</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Choose a postCategory only when the request clearly centers on one of the allowed categories. '</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Treat postTags as narrower concepts, technologies, methods, or attributes mentioned or clearly implied by the request. '</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Be conservative. If there is no clear semantic match, return null for postCategory and an empty array for postTags.'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Supply the user's submitted search prompt.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'input'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$userSearchPromptRequest</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Define the model's text output as structured JSON using a</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// JSON Schema named semantic_search, with strict adherence to</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// the $semanticSearchSchema contract.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'text'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'format'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'type'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'json_schema'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'name'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'semantic_search'</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'strict'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">true</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'schema'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchSchema</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">];</span>
						</p>

					</div>

					<p>
						The Responses API parameters can be summarized as:
					</p>

					<ul class="site-list">
						<li>model - defines the OpenAI model used for interpretation</li>
						<li>instructions - guides how the model should interpret the user's search prompt</li>
						<li>input - supplies the user's natural-language search prompt</li>
						<li>text.format - defines the structured output contract</li>
						<li>text.format.type - output as structured json according to the supplied schema</li>
						<li>text.format.name - identifies the structured-output format for this schema definition</li>
						<li>text.format.strict - requires the response to conform to the supplied schema</li>
						<li>text.format.schema - supplies LiberAnalytics' database-derived response constraints</li>
					</ul>

					<h3 class="site-font-secondary-heading">Orchestrating the OpenAI Responses API Request</h3>

					<p>
						Calling the Responses API is a matter of sending an HTTP POST request
						to the OpenAI Responses API endpoint, supplying the previously defined
						request parameters as a JSON payload and the API key as a Bearer token
						in the request headers.
					</p>

					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Define the OpenAI Responses API call body, url, and key file.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$requestBody</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">json_encode</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$openAiRequest</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$curl</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">curl_init</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-string-fc">'https://api.openai.com/v1/responses'</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$openAiKeyFile</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$laConfig</span>
							<span class="post-snippet-font-std code-php-op-fc">-&gt;</span>
							<span class="post-snippet-font-std code-php-func-fc">getConfigAttr</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-string-fc">'liberanalytics'</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'config'</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'env'</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'openapikey'</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$openAiApiKey</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">trim</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-func-fc">file_get_contents</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$openAiKeyFile</span>
							<span class="post-snippet-font-std code-php-default-fc">));</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Set curl session options, supply the request body.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-func-fc">curl_setopt_array</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$curl</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-special-fc">&nbsp;&nbsp;&nbsp;&nbsp;CURLOPT_POST</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">true</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-special-fc">&nbsp;&nbsp;&nbsp;&nbsp;CURLOPT_RETURNTRANSFER</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">true</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-special-fc">&nbsp;&nbsp;&nbsp;&nbsp;CURLOPT_HTTPHEADER</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Authorization: Bearer '</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$openAiApiKey</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Content-Type: application/json'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;],</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-special-fc">&nbsp;&nbsp;&nbsp;&nbsp;CURLOPT_POSTFIELDS</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$requestBody</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">]);</span>
						</p>

					</div>

					<h3 class="site-font-secondary-heading">Handling the OpenAI Responses API Response - Success</h3>

					<p>
						After decoding the OpenAI response into a PHP associative array, Liber traverses the output collection
						by response type rather than assuming a fixed array position. Message items are inspected for
						output_text content, and the corresponding text value contains the schema-constrained
						semantic-search JSON. That JSON is then decoded into a PHP array and normalized into the expected
						postCategory and postTags response shape before domain validation and return to the calling application.							
					</p>

					<p>
						The shape of the response decoded as a PHP array appears in an xdebug session as shown in the snippet below:
					</p>

					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-special-fc">Listening to Xdebug on port 0.0.0.0:9003 ...</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$openAiResponseArray</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'output'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">array(2)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-number-fc">&nbsp;&nbsp;&nbsp;&nbsp;0</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">array(5)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-number-fc">&nbsp;&nbsp;&nbsp;&nbsp;1</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">array(6)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;id</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">"msg_0fb52ad248af026b006ad627054aa487d1a7eeb5364d490767"</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;type</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">"message"</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;status</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">"completed"</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;content</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">array(1)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-number-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">array(4)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;type</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">"output_text"</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;annotations</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">array(0)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;logprobs</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">array(0)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;text</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">"{&quot;postCategory&quot;:&quot;Database Engineering&quot;,&quot;postTags&quot;:[&quot;Validation&quot;]}"</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;phase</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">"final_answer"</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;role</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">"assistant"</span>
						</p>

					</div>	
					
					<p>
						And the traversal sequence and logic of the output collection is orchestrated in PHP as 
						shown in the snippets below:	
					</p>

					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Decode the OpenAI Responses API response.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$openAiResponse</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">curl_exec</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$curl</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$openAiResponseArray</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">json_decode</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$openAiResponse</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">true</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchResponseJson</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">null</span>
							<span class="post-snippet-font-std code-php-default-fc">;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Iterate through AI response array; store relevant content from</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// contentItem[].</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">foreach</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;(</span>
							<span class="post-snippet-font-std code-php-var-fc">$openAiResponseArray</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'output'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;??&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[]</span>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;as&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$outputItem</span>
							<span class="post-snippet-font-std code-php-default-fc">)&nbsp;{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Inspect content only for outputItems which are actual messages.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;if</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;((</span>
							<span class="post-snippet-font-std code-php-var-fc">$outputItem</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'type'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;??&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">null</span>
							<span class="post-snippet-font-std code-php-default-fc">)</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;!==&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'message'</span>
							<span class="post-snippet-font-std code-php-default-fc">)&nbsp;{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;continue</span>
							<span class="post-snippet-font-std code-php-default-fc">;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;}</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;&nbsp;&nbsp;&nbsp;// Find the text for the given content member.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;foreach</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;(</span>
							<span class="post-snippet-font-std code-php-var-fc">$outputItem</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'content'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;??&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[]</span>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;as&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$contentItem</span>
							<span class="post-snippet-font-std code-php-default-fc">)&nbsp;{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;if</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;(</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(</span>
							<span class="post-snippet-font-std code-php-var-fc">$contentItem</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'type'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;??&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">null</span>
							<span class="post-snippet-font-std code-php-default-fc">)</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;===&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'output_text'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&amp;&amp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">isset</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$contentItem</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'text'</span>
							<span class="post-snippet-font-std code-php-default-fc">])</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$semanticSearchResponseJson</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$contentItem</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'text'</span>
							<span class="post-snippet-font-std code-php-default-fc">];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;break</span>
							<span class="post-snippet-font-std code-php-number-fc">&nbsp;2</span>
							<span class="post-snippet-font-std code-php-default-fc">;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;}</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">}</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Decode the schema-constrained semantic search JSON.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchResponse</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;$semanticSearchResponseJson</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;?&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">json_decode</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchResponseJson</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">true</span>
							<span class="post-snippet-font-std code-php-default-fc">)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">null</span>
							<span class="post-snippet-font-std code-php-default-fc">;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Normalize the expected response properties for application use.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$response</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'postCategory'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchResponse</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'postCategory'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;??&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">null</span>
							<span class="post-snippet-font-std code-php-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;'postTags'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchResponse</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'postTags'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;??&nbsp;</span>
							<span class="post-snippet-font-std code-php-default-fc">[]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Return the response data as name =&gt; value pairs.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">return</span>
							<span class="post-snippet-font-std code-php-var-fc">&nbsp;$response</span>
							<span class="post-snippet-font-std code-php-default-fc">;</span>
						</p>

					</div>

					<h3 class="site-font-secondary-heading">Handling the OpenAI Responses API Response - Failure Modes</h3>

					<p>
						If LiberAnalytics does not receive a usable response from the Responses API,
						or the returned semantic-search payload cannot be parsed into the expected
						application structure, the request is handled as an upstream failure. 
						Liber returns a 502 Bad Gateway response and a generalized error message
						to the client indicating the nature of the failure.
					</p>

					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Execute the cURL request; return 502 if the upstream request fails.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$openAiResponse</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">curl_exec</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$curl</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">if</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;(</span>
							<span class="post-snippet-font-std code-php-var-fc">$openAiResponse</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;===&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">false</span>
							<span class="post-snippet-font-std code-php-default-fc">)&nbsp;{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-func-fc">&nbsp;&nbsp;&nbsp;&nbsp;http_response_code</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-number-fc">502</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;return</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'error'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'Semantic search request failed.'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">}</span>
						</p>

					</div>

					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Return 502 if the extracted semantic search response is unusable.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">if</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;(</span>
							<span class="post-snippet-font-std code-php-op-fc">!</span>
							<span class="post-snippet-font-std code-php-func-fc">is_array</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$semanticSearchResponse</span>
							<span class="post-snippet-font-std code-php-default-fc">))</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-func-fc">&nbsp;&nbsp;&nbsp;&nbsp;http_response_code</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-number-fc">502</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;return</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'error'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'Invalid semantic search response.'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">}</span>
						</p>

					</div>

					<h3 class="site-font-secondary-heading">Validating LLM Responses Against Authoritative Domain Data</h3>

					<p>
						As a best practice when consuming server-side or upstream response data, whether generated by application code
						or returned by OpenAI's Responses API, the returned values should be validated against the application's
						authoritative domain before they are accepted for execution. In the snippets below, the returned post category
						and post tags are checked for membership in LiberAnalytics' authoritative domain data. If either contains an
						invalid value, LiberAnalytics returns an HTTP 502 Bad Gateway response and a generalized error message to
						the client application.		
					</p>
					
					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Make sure the post category is in the authoritative membership domain.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">if</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;(</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;$response</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'postCategory'</span>
							<span class="post-snippet-font-std code-php-default-fc">]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;&nbsp;&nbsp;&nbsp;&amp;&amp;&nbsp;!</span>
							<span class="post-snippet-font-std code-php-func-fc">in_array</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$response</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'postCategory'</span>
							<span class="post-snippet-font-std code-php-default-fc">],&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$postCategories</span>
							<span class="post-snippet-font-std code-php-default-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">true</span>
							<span class="post-snippet-font-std code-php-default-fc">)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">)&nbsp;{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-func-fc">&nbsp;&nbsp;&nbsp;&nbsp;http_response_code</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-number-fc">502</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;return</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'error'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'Invalid post category.'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">}</span>
						</p>

					</div>					

					<div class="post-snippet">

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">// Make sure the post tags are in the authoritative membership domain.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-var-fc">$invalidPostTags</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">array_diff</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$response</span>
							<span class="post-snippet-font-std code-php-default-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'postTags'</span>
							<span class="post-snippet-font-std code-php-default-fc">],&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$postTags</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">if</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;(</span>
							<span class="post-snippet-font-std code-php-var-fc">$invalidPostTags</span>
							<span class="post-snippet-font-std code-php-default-fc">)&nbsp;{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-func-fc">&nbsp;&nbsp;&nbsp;&nbsp;http_response_code</span>
							<span class="post-snippet-font-std code-php-default-fc">(</span>
							<span class="post-snippet-font-std code-php-number-fc">502</span>
							<span class="post-snippet-font-std code-php-default-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;return</span>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-string-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'error'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'Invalid post tag(s).'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">}</span>
						</p>

					</div>
					<h3 class="site-font-secondary-heading">Preparing LiberAnalytics’ Data and UI for Governed LLM Responses</h3>

					<p>
						Before LiberAnalytics could implement a call to the OpenAI Responses API, its own deterministic data pipeline
						had to be hardened to enforce contracts between calling and receiving application components and to allow
						the user interface to consume and render error messages flexibly.
					</p>

					<ul class="site-list site-stack-start-mt">
						<li>
							The PostgreSQL function responsible for retrieving authoritative tag-domain data previously required a
							TEXT[] parameter. By making that parameter optional with DEFAULT NULL and treating NULL as a signal
							to return all tags, LiberAnalytics could expose the complete authoritative tag set for inclusion
							in the semantic-search JSON Schema supplied to the OpenAI Responses API.

						</li>
						<li>
							A final validation step was added to the OpenAI Responses API orchestration to verify that the returned
							post category and post tags exist within LiberAnalytics’ authoritative domain. Even though the combination
							of the semantic-search JSON Schema and the instructions supplied in the API request constrains and guides
							the LLM toward domain-valid values, a defense-in-depth implementation prevents the model response from
							becoming application state merely because OpenAI returned it.
						</li>
						<li>
							A data-integrity issue existed in LiberAnalytics’ post-retrieval function where an inner join to the images
							table caused an otherwise valid post record to disappear from the result set when its corresponding image
							data was missing. After the missing image data was added, the post was returned as expected. This
							illustrated a broader point: for AI integration to be useful, the underlying data relationships and
							deterministic system workflows must be trustworthy enough for the model’s output to operate against.
						</li>
						<li>
							The user interface also required the error renderer to accept parameterized messages and allow the
							caller to specify which JavaScript element should receive the error. Before the AI integration,
							invalid user input was handled by reading the input element’s data-id attribute and rendering
							a generic message in the form &LT;data-id&GT; value &LT;input value&GT; is invalid. The refactored
							renderer shown below preserves that default behavior while also allowing callers to supply more
							contextual messages, such as failures associated with HTTP response codes 500, 502, and other
							application or upstream errors.

							<div class="post-snippet site-mt">

								<p>
									<span class="post-snippet-font-std code-js-keyword-fc">export</span>
									<span class="post-snippet-font-std code-js-keyword-fc">&nbsp;const</span>
									<span class="post-snippet-font-std code-js-fxname-fc">&nbsp;renderError</span>
									<span class="post-snippet-font-std code-js-op-fc">&nbsp;=&nbsp;(</span>
									<span class="post-snippet-font-std code-js-default-fc">{</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;errorEle</span>
									<span class="post-snippet-font-std code-js-op-fc">&nbsp;=&nbsp;</span>
									<span class="post-snippet-font-std code-js-func-fc">getDefaultErrorEle</span>
									<span class="post-snippet-font-std code-js-default-fc">(),</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;errorMsg</span>
									<span class="post-snippet-font-std code-js-op-fc">&nbsp;=&nbsp;</span>
									<span class="post-snippet-font-std code-js-special-fc">null</span>
									<span class="post-snippet-font-std code-js-default-fc">,</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;errorKey</span>
									<span class="post-snippet-font-std code-js-default-fc">,</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;errorValue</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-default-fc">}</span>
									<span class="post-snippet-font-std code-js-op-fc">&nbsp;=&nbsp;{})&nbsp;=&gt;&nbsp;{</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;if</span>
									<span class="post-snippet-font-std code-js-default-fc">&nbsp;(</span>
									<span class="post-snippet-font-std code-js-var-fc">errorEle</span>
									<span class="post-snippet-font-std code-js-default-fc">)&nbsp;{</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-keyword-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;const</span>
									<span class="post-snippet-font-std code-js-var-fc">&nbsp;renderMsg</span>
									<span class="post-snippet-font-std code-js-op-fc">&nbsp;=</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;errorMsg</span>
									<span class="post-snippet-font-std code-js-op-fc">&nbsp;??&nbsp;</span>
									<span class="post-snippet-font-std code-js-string-fc">`</span>
									<span class="post-snippet-font-std code-js-special-fc">${errorKey}</span>
									<span class="post-snippet-font-std code-js-string-fc">&nbsp;value&nbsp;</span>
									<span class="post-snippet-font-std code-js-special-fc">${errorValue}</span>
									<span class="post-snippet-font-std code-js-string-fc">&nbsp;is invalid.`</span>
									<span class="post-snippet-font-std code-js-default-fc">;</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-var-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;errorEle</span>
									<span class="post-snippet-font-std code-js-op-fc">.</span>
									<span class="post-snippet-font-std code-js-var-fc">textContent</span>
									<span class="post-snippet-font-std code-js-op-fc">&nbsp;=&nbsp;</span>
									<span class="post-snippet-font-std code-js-var-fc">renderMsg</span>
									<span class="post-snippet-font-std code-js-default-fc">;</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;}</span>
								</p>

								<p>
									<span class="post-snippet-font-std code-js-default-fc">}</span>
								</p>

							</div>
						</li>
					</ul>

					<h3 class="site-font-secondary-heading">Making LLM Responses Usable Within a Deterministic System</h3>

					<p>

					</p>

				</section>
		
			</article>

			<div class="site-ajax-spinner-8spoke site-ajax-hidden">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
			<div class="site-ajax-overlay-flex site-ajax-hidden">
			</div>

		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>
