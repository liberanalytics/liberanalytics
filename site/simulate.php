
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LiberAnalytics</title>
	<!-- <link href="css/la.css" rel="stylesheet" type="text/css" charset="utf-8"> -->
    <script type="text/javascript" src="js/simulate.js"></script>

	<style>


#postsearch_results_w {
    max-width: 50rem;
    padding: 2rem 0 0 0;
    margin: 1.25rem auto 0 auto;
    border-top: 1px solid transparent;
    border-image: linear-gradient( to right, 
        var(--clr-lt-light) 0%, 
        var(--clr-med-light) 50%, 
        var(--clr-lt-light) 100% ) 1;    
}

.search-card {
    padding: .625rem;
}

.search-card-desc {
    display: block;
    padding: 0 0 .3125rem 0;
    border-bottom-width: 2px;
    border-bottom-style: solid;
    border-image: linear-gradient( to left, var(--clr-med-light) , var(--clr-lt-light) ) 1;    
    font-size: 1.25rem;
    font-weight: 300;
    color: var(--clr-med-dark);
    text-decoration: none;
    text-align: center;
}

.search-card-components {
    display: grid;
    grid-template-areas: 
    'one two'
    'one three';
    gap: 0 0;
    padding: 1rem 0 0 0;
}

.search-card-img {
    grid-area: one;
    display: block;
    width: 195px;
    height: 135px;
    padding: 0;
    margin: 0;
}

.search-card-longdesc {
    padding: 0;
    margin: 0;
    grid-area: two;
    align-self: start;
}

	</style>

</head>

<body>

	<main>

		<?php echo 'LA_CONFIG_FILE = ' . getenv('LA_CONFIG_FILE'); ?>


	</main>

</body>
</html>