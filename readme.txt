=== Auto Delete Posts ===
Contributors: cypher
Donate link: http://ashwinbihari.com
Tags: post, management
Requires at least: 2.0.2
Tested up to: 2.7
Stable tag: 1.0.1

The Auto Delete Posts plugin can be used to delete or move all posts after an expiration date has elapsed.

== Description ==

The Auto Delete Posts plugin can be used to delete all posts after an expiration date has elapsed. It is also
possible to move all posts to a single category after expiration. Disabling the plugin will provide 
a preview of posts that will be deleted or moved.

== Installation ==

1. Copy the directory auto-delete-posts to your /wp-content/plugins directory.
2. Go to 'Plugins' under the Administration menu and Activate the plugin.
3. Go to 'Settings->Auto Delete Posts' to view the current configuration.
4. Modify options, enable plugin and UPDATE.

You're done.

== Frequently Asked Questions ==
Q1) How do I use this plugin?  
A1) The Auto-Delete-Posts plugin will be run each time a new post or page is created or when an existing post or page is edited. 

Q2) Why is there a delay after submitting a new post or editing an existing one?  
A2) If you have a large number of posts that need to deleted/moved, this will slow things down. If you find you end up having to delete/move a lot of posts, then change the expiration days to a smaller values so that older posts are deleted more often and speeding things up.


== Screenshots ==
N/A

== Revisions ==

0.1    	INITIAL: Initial version.  
0.2    	BUG FIX: Modified SQL query to not delete pages.  
        FEATURE: Added option to move posts to a particular category instead of deleting them.  
0.3    	BUG FIX: Incorrect Terminate in functions  
	FEATURE: Instant DELETE/MOVE ability  
0.4    	FEATURE: Added option to delete posts from a particular category.  
0.5    	BUG FIX: Properly handle deleting posts from ALL categories.  
	BUG FIX: Fix the SQL query when deleting posting from a particular category to not  
	summarily delete ALL posts!  
0.6    	BUG FIX: For WP 2.1.2. Fix the SQL query to exclude "Pages" from being deleted.  
	BUG FIX: For WP 2.1.2. Fix the SQL query to exclude cat_ID 1 and 2 from showing up in  
	the delete or move options.  
0.7    	BUG FIX: Use more of the WP API and avoid issues of missing database tables.  
0.7.1  	BUG FIX: Make both the Update Options button do the right thing.  
       	BUG FIX: The Preview now only shows the chosen category, if any, or all categories  
	if none chosen.
1.0	REWRITE: A full rewrite of the plugin to be more modular
1.0.1	BUG FIX: Fix the immediate delete/move hooks being out of scope

== Credits ==
* v0.3 work from Mani Monajjemi (www.manionline.org)  
* v0.6 work from Phil Guier (www.digitalwestex.org)
