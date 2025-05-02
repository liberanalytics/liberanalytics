SELECT

    post_name,
    post_category,
    post_created,
    short_desc,
    long_desc,
    post_img,
    featured_img,
    search_img
        
FROM liberanalytics.fx_get_posts
(
     :px_featured
	,:px_post_name
	,:px_post_category
	,:px_post_tags
	,:px_post_keywords
	,:px_order_by
	,:px_order_by_ascdesc
	,:px_limit
);
