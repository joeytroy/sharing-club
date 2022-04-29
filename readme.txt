=== Sharing Club ===
Contributors: netdelight, jdevroed, mpol, joeytroy
Tags: share, lend, community, borrow, loan, lending, book, library, buddypress, sharing club
Requires at least: 4.0
Tested up to: 5.9.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: https://paypal.me/netdelight

Share books, dvd, tools, toys or any object with your community. Your users can easily lend, borrow and rate items and you know who borrowed what.

== Description ==
Sharing Club lets you create a list of the items (like DVD, books, tools...) you want to lend to your users.
They can easily book, comment and rate these item. On the admin side, you can track what you've lent, who borrowed it and when.

Useful for any NGO, association or community with a sharing philosophy.
Currently available in english, french and dutch - feel free to translate it (using Wordpress [online interface](https://translate.wordpress.org/projects/wp-plugins/sharing-club) or the provided files in the `languages` folder).

The shared object are based on WordPress custom post type and taxonomy (custom categories) which means that :

*	you can use other plugins to extends the Sharing Club possibilities, see the FAQ below
*	you can use easily integrate them, straight in your theme or using the provided templates files (see comments inside the PHP files)
*	you can search for objects from anywhere in your website, like you would do for any other post

If you like this plugin, I would really appreciate if you can leave it a [positive review](https://wordpress.org/plugins/sharing-club/#reviews) (you need to create a [Wordpress account](https://login.wordpress.org/register) if you don't have one yet).

== Installation ==

= Automatic installation =

The easiest way is to let Wordpress do the job for you :

1. Log in to your WordPress dashboard
2. In the dashboard left menu click on Plugins > Add New
3. Search for *Sharing Club*
4. Once you found it, simply click on *Install now*

= Manual installation =

1. Unzip all files to the `/wp-content/plugins/sharing-club` directory
2. Log into WordPress admin and activate the *Sharing Club* plugin through the *Plugins* menu
3. Go to *Sharing Club > Add object* in the left-hand menu to add the item you want to lend
4. Manage your lendings through *Sharing Club > Lendings*

The items list will then be available on this "archive page" : `http://yourblog.yourdomain*/shared_item/*`

You'll get an e-mail notification when an item is requested.

You can also customize how the items will be listed and displayed using your own templates :

* 	`templates/archive-shared_item.php` display the items list based on a custom [walker](https://codex.wordpress.org/Class_Reference/Walker) : `templates/Walker_Category_Posts.php`
* 	`templates/single-shared_item.php` display the booking form and all its options.

Make sure your theme supports the "featured images" so you can display the shared item picture in your item list.

NB: dates are currently in the dd/mm/yyyy format

== Changelog ==
= 1.3 =
- Fixed the user name display bug on the shared_item page
- Added an option to make your shared object visible to anybody (without login)
- Added an option to hide the rating and comment form

= 1.2 =
- Fixed the capabilities bug for admin.
- Added dutch translation (thanks again Jeroen).

= 1.1 =
- For each object, you can use the "excerpt" field to write a short note for admin purpose (ie : library reference, useful comment) to help you manage your objects
- For each lending, you can now write an "admin note" (ie : is the item returned damaged ? broken ? dirty ?)
(thanks to Jeroen for his suggestions !)
- Improved compatibility with other plugins such as ACF, Ultimate Member, Really simple CSV importer...

= 1.0 =
Public release - the plugin icon is based on Hand by Nick Abrams from the Noun Project

== Upgrade Notice ==
This fix the capabilities bug in the admin and add extra fields to the lendings and the objects.

== Screenshots ==
1. The admin
2. The front-end with (basic description, rating and reviews)

== Frequently Asked Questions ==
= Can I add more fields to the shared items or the lendings ? =

Yes, if the "notes" field is not enough, you can add other custom field using [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/).
If you're tech savvy and want to add extra functionalities to the lendings, you have to know that they are actually "custom comments", mapped liked this :

	User			user_id
	Object			comment_post_ID
	Lending date	comment_date
	Return date		comment_date_gmt
	Reviews			comment_content
	Admin notes		comment_agent
	Rating			comment_karma

So, feel free to use the other fields from the `wp_comments` table to store extra lending data.

= Can my members share their own objects ? =

	Yes, you can allow your users to join the club and add their objects. If they are not administrators, use [Ultimate Member](https://wordpress.org/plugins/ultimate-member/) to give them a restricted access to your admin. You can display the user name next to the item title uncommenting `<?php the_author(); ?>` in `templates/single-shared_item.php` or in the shared item list modifying the `templates/Walker_Category_Posts.php` accordingly.

= Is this plugin compatible with BuddyPress ? =

	Yes, actually it was developped for a BuddyPress community in the first place.

= Can I import several objects at once ? =

	Yes, you can create a spreadsheet with all your objects, save it in CSV format and use a plugin like [Really Simple CSV Importer](https://wordpress.org/plugins/really-simple-csv-importer/) to import it in your admin.

= I find this plugin helpful, how can I help you back ? =

	If you like this plugin, I would really appreciate if you can leave it a [positive review](https://wordpress.org/plugins/sharing-club/#reviews) (you need to create a [Wordpress account](https://login.wordpress.org/register) if you don''t have one yet).
	If you find any bug or if you have some idea to improve this plugin, you can use the [support forum](https://wordpress.org/support/plugin/sharing-club).
