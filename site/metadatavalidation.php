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
	<title>Metadatavalidation | LiberAnalytics</title>
	<meta name="description" content="<?php echo $postMetaDesc; ?>">
	<link rel="canonical" href="https://liberanalytics.com/Metadatavalidation">

	<!-- Favicon. -->
	<link rel="icon" href="/favicon.ico" sizes="any">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">	
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">

	<!-- Social media Open Graph data points. -->
	<meta property="og:title" content="Metadatavalidation | LiberAnalytics">
	<meta property="og:description" content="<?php echo $postMetaDesc; ?>">
	<meta property="og:url" content="https://liberanalytics.com/Metadatavalidation">
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
						<span class="site-font-brand">Validating</span> user-supplied inputs in the backend of an application strengthens
						data integrity even if the frontend also performs validation. The challenge in performing server-side validation
						often lies in selecting validation methods appropriate to the business domain, constraints, and systems. A variety
						of techniques can be used: whitelisting/blacklisting, range and boundary checking, data type checking,
						regular expressions, reference/master data queries, or data dictionary lookups.
					</p>

					<p>
						Another validation technique is metadata-driven validation based on the database's existing
						information schema or system catalog metadata. Since database metadata expresses structural
						constraints around table columns and routine parameters, it provides a logical source for
						retrieving validation specifications. Metadata-driven validation helps maintain parity between
						application validation rules and database constraints while reducing duplicate definitions of
						what a column or routine parameter will accept. It also provides a natural decision point for
						routing parameters toward either authoritative domain-membership validation or data-type validation.
					</p>

					<p>
						Before implementing metadata-driven and domain membership validation, the SQL Server instance, client connectivity,
						and PHP SQL Server runtime dependencies must be configured.
					</p>					

					<h3 class="site-font-secondary-heading">Domain-Membership Validation</h3>

					<p>
						Domain-membership validation determines whether a user-supplied value exists within an authoritative set of reference
						or master data. Parameters are mapped in the application layer to a database routine that returns the corresponding
						membership values. The user-submitted value is then compared against the authoritative membership values by applying
						the matching semantics required by the parameter, such as exact equality or wildcard matching.
					</p>

					<h3 class="site-font-secondary-heading post-heading-subsection">Domain-Membership Validation - Database</h3>
					
					<p>
						A simple sales schema is used in this post to demonstrate database metadata and domain-membership validation.
						The stored procedure used to retrieve sales orders exposes the parameter contract shown below. 
						Parameter identity determines whether domain-membership validation or type validation is applied.
					</p>

					<div class="post-snippet code-mssql-snippet">

						<!-- Stored procedure declaration -->
						<p>
							<span class="post-snippet-font-std code-mssql-keyword-fc">CREATE&nbsp;PROCEDURE&nbsp;</span>
							<span class="post-snippet-font-std code-mssql-default-fc">[dbo].[usp_get_orders]</span>
						</p>

						<div class="site-mt">

							<!-- @pxCustomerName -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxCustomerName&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">NVARCHAR</span>
								<span class="post-snippet-font-std code-mssql-default-fc">(</span>
								<span class="post-snippet-font-std code-mssql-number-fc">100</span>
								<span class="post-snippet-font-std code-mssql-default-fc">)&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxBusinessUnitCode -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxBusinessUnitCode&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">CHAR</span>
								<span class="post-snippet-font-std code-mssql-default-fc">(</span>
								<span class="post-snippet-font-std code-mssql-number-fc">3</span>
								<span class="post-snippet-font-std code-mssql-default-fc">)&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxOrderStatusCode -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxOrderStatusCode&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">VARCHAR</span>
								<span class="post-snippet-font-std code-mssql-default-fc">(</span>
								<span class="post-snippet-font-std code-mssql-number-fc">12</span>
								<span class="post-snippet-font-std code-mssql-default-fc">)&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxSalesChannelCode -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxSalesChannelCode&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">CHAR</span>
								<span class="post-snippet-font-std code-mssql-default-fc">(</span>
								<span class="post-snippet-font-std code-mssql-number-fc">3</span>
								<span class="post-snippet-font-std code-mssql-default-fc">)&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxOrderDate -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxOrderDate&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">DATE</span>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxRequestedShipDate -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxRequestedShipDate&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">DATE</span>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxRequestedShipDateOperator -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxRequestedShipDateOperator&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">VARCHAR</span>
								<span class="post-snippet-font-std code-mssql-default-fc">(</span>
								<span class="post-snippet-font-std code-mssql-number-fc">16</span>
								<span class="post-snippet-font-std code-mssql-default-fc">)&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxMinTotalAmount -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxMinTotalAmount&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">DECIMAL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">(</span>
								<span class="post-snippet-font-std code-mssql-number-fc">12</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-number-fc">2</span>
								<span class="post-snippet-font-std code-mssql-default-fc">)&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxMaxTotalAmount -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxMaxTotalAmount&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">DECIMAL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">(</span>
								<span class="post-snippet-font-std code-mssql-number-fc">12</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-number-fc">2</span>
								<span class="post-snippet-font-std code-mssql-default-fc">)&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
								<span class="post-snippet-font-std code-mssql-default-fc">,</span>
							</p>

							<!-- @pxExpedited -->
							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxExpedited&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-datatype-fc">BIT</span>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;=&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-null-fc">NULL</span>
							</p>

						</div>

					</div>					

					<h3 class="site-font-secondary-heading post-heading-subsection">Domain-Membership Validation - Application Layer</h3>

					<p>
						In the application layer, the orchestrator preserves parameter identities consistent with the corresponding
						SQL Server routine. When the database operation is invoked, those application parameters are mapped to PDO
						named placeholders, which in turn map directly to the stored procedure parameters. This naming parity keeps
						the application-to-database contract explicit while avoiding ordinal-only parameter mapping.
					</p>
					
					<p>

				<div class="post-snippet">


    <p>
        <span class="post-snippet-font-std code-php-comment-fc">/**</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;getOrders</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;Receives&nbsp;user-supplied&nbsp;CLI&nbsp;parameters,&nbsp;validates&nbsp;each&nbsp;parameter</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;according&nbsp;to&nbsp;its&nbsp;domain-membership&nbsp;or&nbsp;type-validation&nbsp;contract,</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;then&nbsp;returns&nbsp;the&nbsp;resulting&nbsp;orders&nbsp;data&nbsp;to&nbsp;the&nbsp;CLI.</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@return&nbsp;array</span>
    </p>

    <p>
        <span class="post-snippet-font-std code-php-comment-fc">&nbsp;*/</span>
    </p>			

					<!-- getOrders signature -->
					<p>
						<span class="post-snippet-font-std code-php-keyword-fc">function&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">getOrders</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxCustomerName</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxBusinessUnitCode</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxOrderStatusCode</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxSalesChannelCode</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxOrderDate</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxRequestedShipDate</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxRequestedShipDateOperator</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxMinTotalAmount</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxMaxTotalAmount</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxExpedited</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-op-fc">):&nbsp;</span>
						<span class="post-snippet-font-std code-php-type-fc">array</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-op-fc">{</span>
					</p>

					<!-- Validated parameter mapping -->
					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxs&nbsp;</span>
						<span class="post-snippet-font-std code-php-op-fc">=&nbsp;[</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxCustomerName'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxCustomerName'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxCustomerName</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxBusinessUnitCode'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxBusinessUnitCode'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxBusinessUnitCode</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxOrderStatusCode'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxOrderStatusCode'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxOrderStatusCode</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxSalesChannelCode'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxSalesChannelCode'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxSalesChannelCode</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxOrderDate'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxOrderDate'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxOrderDate</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxRequestedShipDate'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxRequestedShipDate'</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxRequestedShipDate</span>
						<span class="post-snippet-font-std code-php-op-fc">,</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxRequestedShipDateOperator</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxRequestedShipDateOperator'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxRequestedShipDateOperator'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxRequestedShipDateOperator</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxMinTotalAmount'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxMinTotalAmount'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxMinTotalAmount</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxMaxTotalAmount'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxMaxTotalAmount'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxMaxTotalAmount</span>
						<span class="post-snippet-font-std code-php-op-fc">),</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxExpedited'</span>
						<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
						<span class="post-snippet-font-std code-php-func-fc">validatePx</span>
						<span class="post-snippet-font-std code-php-op-fc">(</span>
						<span class="post-snippet-font-std code-php-string-fc">'pxExpedited'</span>
						<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
						<span class="post-snippet-font-std code-php-var-fc">$pxExpedited</span>
						<span class="post-snippet-font-std code-php-op-fc">)</span>
					</p>

					<p>
						<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<span class="post-snippet-font-std code-php-op-fc">];</span>
					</p>

				</div>

					<p>
						Parameters requiring domain-membership validation are identified separately in the application layer.
						Their parameter keys are maintained in an allowlist and routed to the domain-membership validation
						path before database execution.						
					</p>

					<div class="post-snippet">

						<!-- Domain membership parameter allowlist -->
						<p>
							<span class="post-snippet-font-std code-php-comment-fc">/**</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;Domain&nbsp;membership&nbsp;validation&nbsp;parameters.</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@var&nbsp;array&lt;string&gt;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*/</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">const&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">REFDATA_PXS</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&nbsp;[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'pxCustomerName'</span>
							<span class="post-snippet-font-std code-php-op-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'pxBusinessUnitCode'</span>
							<span class="post-snippet-font-std code-php-op-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'pxOrderStatusCode'</span>
							<span class="post-snippet-font-std code-php-op-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'pxSalesChannelCode'</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-op-fc">];</span>
						</p>
						
						<div class="site-mt">

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">//&nbsp;Validate&nbsp;domain&nbsp;membership&nbsp;p(x)'s.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-keyword-fc">if&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-func-fc">in_array</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxParameter</span>
								<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-php-special-fc">REFDATA_PXS</span>
								<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-php-keyword-fc">true</span>
								<span class="post-snippet-font-std code-php-op-fc">))</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-op-fc">{</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$validPx&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-php-func-fc">filterDomainMembership</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxParameter</span>
								<span class="post-snippet-font-std code-php-op-fc">,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$validatePx</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">);</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-op-fc">}</span>
							</p>

						</div>
						
					</div>

					<h3 class="site-font-secondary-heading">Type Validation</h3>

					<p>
						With domain-membership validation established, the remaining parameters can be validated
						against structural metadata exposed by SQL Server. This form of metadata-driven validation
						uses schema introspection to inspect the database routine’s parameter definitions and
						derive validation rules from the authoritative database contract.
					</p>

					<h3 class="site-font-secondary-heading post-heading-subsection">Type Validation - Database</h3>

					<div class="post-snippet code-mssql-snippet">

						<p>
							<span class="post-snippet-font-std code-mssql-keyword-fc">CREATE&nbsp;FUNCTION&nbsp;</span>
							<span class="post-snippet-font-std code-mssql-default-fc">[dbo].[fx_get_metadata]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-default-fc">(</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxSchema&nbsp;</span>
							<span class="post-snippet-font-std code-mssql-datatype-fc">SYSNAME</span>
							<span class="post-snippet-font-std code-mssql-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxObjectName&nbsp;</span>
							<span class="post-snippet-font-std code-mssql-datatype-fc">SYSNAME</span>
							<span class="post-snippet-font-std code-mssql-default-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;@pxName&nbsp;</span>
							<span class="post-snippet-font-std code-mssql-datatype-fc">VARCHAR</span>
							<span class="post-snippet-font-std code-mssql-default-fc">(32)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-default-fc">)</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-keyword-fc">RETURNS&nbsp;TABLE</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-keyword-fc">AS</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-keyword-fc">RETURN</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-mssql-default-fc">(</span>
						</p>

						<div class="site-mt">

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">SELECT</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SPECIFIC_SCHEMA&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">specific_schema,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SPECIFIC_NAME&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">specific_name,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PARAMETER_NAME&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">parameter_name,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ORDINAL_POSITION&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">ordinal_position,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATA_TYPE&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">data_type,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CHARACTER_MAXIMUM_LENGTH&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">character_maximum_length,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERIC_PRECISION&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">numeric_precision,</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERIC_SCALE&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">numeric_scale</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">FROM&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-schema-fc">INFORMATION_SCHEMA.PARAMETERS</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">&nbsp;AS&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">metadata_px</span>
							</p>

						</div>

						<div class="site-mt">

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">WHERE&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">1=1</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">metadata_px.SPECIFIC_SCHEMA&nbsp;=&nbsp;@pxSchema</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">metadata_px.SPECIFIC_NAME&nbsp;=&nbsp;@pxObjectName</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-mssql-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-keyword-fc">AND&nbsp;</span>
								<span class="post-snippet-font-std code-mssql-default-fc">metadata_px.PARAMETER_NAME&nbsp;=&nbsp;@pxName</span>
							</p>

						</div>

						<p>
							<span class="post-snippet-font-std code-mssql-default-fc">);</span>
						</p>

					</div>

					<p>
						The above function, dbo.fx_get_metadata, returns a metadata row for each
						parameter in a SQL Server routine. The PHP application layer consumes this
						resultset, using the data_type column to select the appropriate validation
						function. Character maximum length and numeric precision/scale provide the
						additional constraints needed to validate character and DECIMAL values.
						An example resultset is shown below.
					</p>

					<div class="post-img-container">
						<img 
							src="assets/metadatavalidation_ssms_fxgetmetadata_rs.png"
							alt="metadatavalidation ssms fx_get_metadata resultset"
							class="post-img-screenshot"
							width="820"
							height="74"
							tabindex="0"
						>
					</div>					

					<h3 class="site-font-secondary-heading post-heading-subsection">Type Validation - Application Layer</h3>

					<p>
						The routine parameter metadata expresses the database's authoritative structural contract for acceptable values.
						The application layer consumes this metadata and routes each parameter to the appropriate type-validation
						function using a lookup structure analogous to the domain-membership routing list. The character and decimal
						filters shown below derive regular-expression constraints from the corresponding metadata and compare each
						user-supplied value against those constraints. When validation succeeds, the parameter value is returned;
						otherwise, false is returned. In this pattern, the filter functions both determine validity and return the
						accepted representation for continued processing. Character validation can be extended to account for database
						collation and encoding rules where the application domain requires it.
					</p>

					<div class="post-snippet">

						<!-- filterType -->
						<p>
							<span class="post-snippet-font-std code-php-comment-fc">/**</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;filterType</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@param&nbsp;string&nbsp;$pxParameter</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@param&nbsp;string&nbsp;$pxValue</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@return&nbsp;string|false</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*/</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-keyword-fc">function&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">filterType</span>
							<span class="post-snippet-font-std code-php-op-fc">(</span>
							<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$pxParameter</span>
							<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
							<span class="post-snippet-font-std code-php-op-fc">):&nbsp;</span>
							<span class="post-snippet-font-std code-php-type-fc">string|false</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-op-fc">{</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$dbOperation&nbsp;</span>
							<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">getDBOperation</span>
							<span class="post-snippet-font-std code-php-op-fc">(</span>
							<span class="post-snippet-font-std code-php-special-fc">__FUNCTION__</span>
							<span class="post-snippet-font-std code-php-op-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$typeMetaData&nbsp;</span>
							<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
							<span class="post-snippet-font-std code-php-func-fc">executeQuery</span>
							<span class="post-snippet-font-std code-php-op-fc">(</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$dbOperation</span>
							<span class="post-snippet-font-std code-php-op-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'pxSchema'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'dbo'</span>
							<span class="post-snippet-font-std code-php-op-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'pxObjectName'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'usp_get_orders'</span>
							<span class="post-snippet-font-std code-php-op-fc">,</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'pxName'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;=&gt;&nbsp;</span>
							<span class="post-snippet-font-std code-php-string-fc">'@'</span>
							<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$pxParameter</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-op-fc">)[0];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$filterDataType&nbsp;</span>
							<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$typeMetaData</span>
							<span class="post-snippet-font-std code-php-op-fc">[</span>
							<span class="post-snippet-font-std code-php-string-fc">'data_type'</span>
							<span class="post-snippet-font-std code-php-op-fc">];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$filterFx&nbsp;</span>
							<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
							<span class="post-snippet-font-std code-php-special-fc">TYPE_FXS</span>
							<span class="post-snippet-font-std code-php-op-fc">[</span>
							<span class="post-snippet-font-std code-php-var-fc">$filterDataType</span>
							<span class="post-snippet-font-std code-php-op-fc">];</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$filterType&nbsp;</span>
							<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$filterFx</span>
							<span class="post-snippet-font-std code-php-op-fc">(</span>
							<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
							<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$typeMetaData</span>
							<span class="post-snippet-font-std code-php-op-fc">);</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<span class="post-snippet-font-std code-php-keyword-fc">return&nbsp;</span>
							<span class="post-snippet-font-std code-php-var-fc">$filterType</span>
							<span class="post-snippet-font-std code-php-op-fc">;</span>
						</p>

						<p>
							<span class="post-snippet-font-std code-php-op-fc">}</span>
						</p>


						<!-- filterCharacter -->
						<div class="site-mt">

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">/**</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;filterCharacter</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@param&nbsp;string&nbsp;$pxValue</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@param&nbsp;array&nbsp;$pxTypeMetaData</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@return&nbsp;string|false</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*/</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-keyword-fc">function&nbsp;</span>
								<span class="post-snippet-font-std code-php-func-fc">filterCharacter</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
								<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-php-type-fc">array&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxTypeMetaData</span>
								<span class="post-snippet-font-std code-php-op-fc">):&nbsp;</span>
								<span class="post-snippet-font-std code-php-type-fc">string|false</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-op-fc">{</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-comment-fc">//&nbsp;Validate&nbsp;the&nbsp;input&nbsp;length&nbsp;against&nbsp;the&nbsp;metadata's&nbsp;maximum&nbsp;character&nbsp;length.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-comment-fc">//&nbsp;Without&nbsp;the&nbsp;'u'&nbsp;modifier,&nbsp;the&nbsp;regex&nbsp;operates&nbsp;on&nbsp;bytes.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$validationStringPrecision&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxTypeMetaData</span>
								<span class="post-snippet-font-std code-php-op-fc">[</span>
								<span class="post-snippet-font-std code-php-string-fc">'character_maximum_length'</span>
								<span class="post-snippet-font-std code-php-op-fc">];</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pattern&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-php-string-fc">'/^.{1,'</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$validationStringPrecision</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
								<span class="post-snippet-font-std code-php-string-fc">'}$/'</span>
								<span class="post-snippet-font-std code-php-op-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-keyword-fc">return&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-func-fc">\preg_match</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-var-fc">$pattern</span>
								<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
								<span class="post-snippet-font-std code-php-op-fc">)&nbsp;===&nbsp;</span>
								<span class="post-snippet-font-std code-php-number-fc">1</span>
								<span class="post-snippet-font-std code-php-op-fc">)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">?&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">:&nbsp;</span>
								<span class="post-snippet-font-std code-php-keyword-fc">false</span>
								<span class="post-snippet-font-std code-php-op-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-op-fc">}</span>
							</p>

						</div>


						<!-- filterDecimal -->
						<div class="site-mt">

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">/**</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;filterDecimal</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@param&nbsp;string&nbsp;$pxValue</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@param&nbsp;array&nbsp;$pxTypeMetaData</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*&nbsp;@return&nbsp;string|false</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-comment-fc">&nbsp;*/</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-keyword-fc">function&nbsp;</span>
								<span class="post-snippet-font-std code-php-func-fc">filterDecimal</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-type-fc">string&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
								<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-php-type-fc">array&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxTypeMetaData</span>
								<span class="post-snippet-font-std code-php-op-fc">):&nbsp;</span>
								<span class="post-snippet-font-std code-php-type-fc">string|false</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-op-fc">{</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-comment-fc">//&nbsp;Derive&nbsp;integer&nbsp;precision&nbsp;from&nbsp;the&nbsp;metadata's&nbsp;total&nbsp;precision&nbsp;and&nbsp;scale.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-comment-fc">//&nbsp;Build&nbsp;a&nbsp;regex&nbsp;that&nbsp;constrains&nbsp;the&nbsp;integer&nbsp;and&nbsp;fractional&nbsp;digit&nbsp;counts;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-comment-fc">//&nbsp;The&nbsp;optional&nbsp;fractional&nbsp;portion&nbsp;uses&nbsp;a&nbsp;non-capturing&nbsp;group,&nbsp;'(?:...)'.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$numericPrecision&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxTypeMetaData</span>
								<span class="post-snippet-font-std code-php-op-fc">[</span>
								<span class="post-snippet-font-std code-php-string-fc">'numeric_precision'</span>
								<span class="post-snippet-font-std code-php-op-fc">];</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$numericScale&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxTypeMetaData</span>
								<span class="post-snippet-font-std code-php-op-fc">[</span>
								<span class="post-snippet-font-std code-php-string-fc">'numeric_scale'</span>
								<span class="post-snippet-font-std code-php-op-fc">];</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$integerPrecision&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">=&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$numericPrecision</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;-&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$numericScale</span>
								<span class="post-snippet-font-std code-php-op-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pattern&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">=</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-string-fc">'/^-?\d{1,'</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$integerPrecision</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
								<span class="post-snippet-font-std code-php-string-fc">'}'</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;.</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-string-fc">'(?:\.\d{1,'</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$numericScale</span>
								<span class="post-snippet-font-std code-php-op-fc">&nbsp;.&nbsp;</span>
								<span class="post-snippet-font-std code-php-string-fc">'})?$/'</span>
								<span class="post-snippet-font-std code-php-op-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-keyword-fc">return&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-func-fc">\preg_match</span>
								<span class="post-snippet-font-std code-php-op-fc">(</span>
								<span class="post-snippet-font-std code-php-var-fc">$pattern</span>
								<span class="post-snippet-font-std code-php-op-fc">,&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
								<span class="post-snippet-font-std code-php-op-fc">)&nbsp;===&nbsp;</span>
								<span class="post-snippet-font-std code-php-number-fc">1</span>
								<span class="post-snippet-font-std code-php-op-fc">)</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">?&nbsp;</span>
								<span class="post-snippet-font-std code-php-var-fc">$pxValue</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-default-fc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<span class="post-snippet-font-std code-php-op-fc">:&nbsp;</span>
								<span class="post-snippet-font-std code-php-keyword-fc">false</span>
								<span class="post-snippet-font-std code-php-op-fc">;</span>
							</p>

							<p>
								<span class="post-snippet-font-std code-php-op-fc">}</span>
							</p>

						</div>

					</div>

					<h3 class="site-font-secondary-heading">Keeping Validation Aligned with Authoritative Sources</h3>

					<p>
						
					</p>

				</section>		
			</article>
		</div>
	</main>

	<?php include_once('lafooter.php'); ?>

</body>
</html>    