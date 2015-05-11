=== Plugin Name ===
Contributors: gicolek
Tags: widget, posts, recent, acf, meta keys, admin
Requires at least: 4.1.1
Tested up to: 4.2.2
Stable tag: 4.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

ACF Recent Posts Widget (ACFRPW) is a WordPress plugin which adds a custom, extended Recent Posts Widget - with ACF and Meta Keys support 

== Description ==

ACFRPW adds a new widget to the Appearance -> Widgets -> ACF Recent Posts Widget. Most of the fields are quite obvious, you can choose from a set of settings to display the posts from. 
Each setting alters the amount and type of posts listed in the sidebar.

= Available Settings =

The widget supports the following settings:

* Widget Title
* Custom Widget container CSS class
* Option to ignore sticky posts
* Search keyword query
* Id based post exclusion
* Date Display, Relative and Custom Date Format specifiaction
* Listing posts from specific date period (start and end)
* Listing posts with specific password, listing password protected posts only or excluding these
* Post Type selection
* Post Formats selection
* Post Statuses selection
* Listing posts limited to author via author id
* Order specifiaction	(ASC or DESC)
* Orderby specification (ID, Author, Title, Date, Modified, Random, Comment Count, Menu Order, Meta Value, Meta Value Numeric)
* Meta Key specifiaction (if Meta Value or Meta Value Numeric were chosen as orderby rule)
* Category limitation
* Tag limitation
* Id based custom taxonomy limitation
* Operator specifiaction for the above rules
* Number of posts to show
* Number of posts to skip
* Thumbnail display, thumbnail size (width, height), thumbnail alignment, default thumbnail
* Excerpt display, its word limit, its readmore text (occurs only if the amount of words exceeds the limit)
* Custom HTML to display before the posts loop
* Custom HTML to display after the loop 
* Custom HTML to display before each posts. It supports custom meta keys and ACF fields
* Custom HTML to display after each posts. It supports custom meta keys and ACF fields
* Custom and default CSS

= ACF supported fields =

The plugin supports the following ACF fields:

* Text
* Textarea
* Number
* Email
* Password
* Wysiwg Editor
* Image
* File

 No other fields have been tested and are supported at the moment.

= Dependencies =

* <a href="http://www.advancedcustomfields.com/">ACF</a> (optional)
* <a href="https://github.com/gicolek/Widget-Base-Class">Widget Base Class</a> (included)

= Other =
* <a href="http://acfrpw-demo.wp-doin.com/">Online Demo</a> 
* <a href="http://wp-doin.com/portfolio/acfrpw/">Plugin site and Docs</a>

= Languages Supported =
* English (default)
* Polish (since May 11 2015)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload and unpack `acf-widget.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Drag and Drop the ACF Recent Posts Widget to the sidebar area

== Frequently Asked Questions ==

= Does the Plugin require Advanced Custom Fields? =

No it doesn't. Some functionalities will be missing though and a notification will be shown to the user on Plugins dashboard page.

= What ACF version does it support? =

The plugin supports the latest ACF 4.X.X version, currently ACF 5 is not supported, however the plugin should work fine. If you want to get rid of the notice see: https://wordpress.org/support/topic/acf-5-compatibility-2


= Does the Widget support author display? =

No it doesn't. We're considering this as an update.

= Does the Plugin support shortcodes, or custom posts function? =

No it doesn't. We're considering this as an update.

= Does the Widget come with any pre made classes to wrap the HTML with? =

No it doesn't.

= The widget styles are messy and the thumbnail doesn't adjust its position ? =

Please make sure to have the default styles checkbox checked.

== Screenshots ==

1. Installing the plugin
2. Meta Key placement (found at the bottom of each post / page edit screen)
3. ACF Field Name placement (found under Custom Fields section with ACF enabled)
4. First widget screen
5. Second widget screen
6. Third widget screen

== Changelog ==

= 4.1.3 =
* Meta Key options
- add meta_compare query parameter
- add meta_value query parameter
- add ability to specify date shortcode inside the meta_value box

* Introduce plugin filters
- add ability to filter the query using add_filter function

* Revamp the readme file
* Introduce polish version

= 4.1.2 =
* Small bug fixes 
- fix issue with plugin not enqueueing default styles in certain cases
- fix issue with date being displayed all the time
- improve readme

= 4.1.1 =
* Plugin first release 

== Upgrade Notice ==

= 1.1 =
- fix issue with plugin not enqueueing displaying styles in certain cases
- fix issue with date being displayed all the time
- improve readme

= 1.0 =
 N/A

== Complex usage ==

This section covers plugin complex usage for advanced user willing to have more control over the behavior of the plugin as well as explains uncommon functionalities.

= Using the HTML textarea fields =

These sections might not be obvious. The HTML or text before / after the whole loop setting is an area where you can specify custom HTML markup to be added before / after the whole posts list.
The HTML or text before / after each post is an area where you can not only specify custom HTML, but you are also given an ability to print any meta key or certain ACF fields (see <a href="#acf-support">ACF supported fields</a>)

= Meta Key Name / ACF Usage =

These fields need to be wrapped inside the [meta {name}] or [acf {field_name}] tags (which are similar to shortcodes). The plugin will then parse these fields and print their according value. Say we have a custom ACF field of type text, for which the Field Name is "text". 
To print its value one has to use [acf text] inside the befoe / after each post textarea. A similar solution applies to the meta key.

= Meta Value Usage =
The Meta Value field supports an array of elements, so that all of the meta_compare parameters could be used. To do so, please specify the two parameters separated by semicolon, for instance: 1234;12345.
The plugin will convert these into an array and apply the proper operation for the two. Whatsmore the [date] shortcode can be used here. It takes the timestamp paramater as an argument. For instance [date +1 day] would generate the tomorrow date in "Ymd" format.
This can be used with custom meta field date.

= Plugin Filters =

The are several filters that can be used to enchance the plugin programatically:

* 'acf_rwp_query' which gives one the ability to filter the query applied to each widget. 
* 'acp_rwp_before' which gives on the ability to filter the Front End output of the plugin before each post. There are two hooks attached here already which allow usage of the [acf] and [meta] shortcodes.
* 'acp_rwp_after' which gives on the ability to filter the Front End output of the plugin after each post. There are two hooks attached here already which allow usage of the [acf] and [meta] shortcodes.
* 'acf_meta_value' which filters the meta_value query parameter. 

Check the acf_widget_widget file for the arguments and use cases.