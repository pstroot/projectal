=== Plugin Name ===
Custom About Author
Contributors: edwinkwan
Donate link: http://littleHandyTips.com/support
Tags: about author, author bio, author profile at end of post, profiles, about guest blogger, guest blogger profile, gravatar
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: 1.4.2

This plugin displays the author profile at the end of the post.
There is the option to display the author's user profile or a custom profile.

== Description ==

This plugin acknowleges authors for their post by displaying a brief biography about them at the end of their post.
Giving authors the credit they deserve!

This plugin is perfect if you have multiple guest bloggers on your website and they do not each have a user account.
It also gives an added incentive for bloggers to write guest posts on your site.
Multiple custom profiles can be created and they take preference over website user profiles.
You also have the option to specify a specific profile to display for each post.
Custom profiles are completely configurable, it can include links to social media (such as Twitter, Facebook, LinkedIn & Google+) or you can specify any HTML/text you want to display.
To specify a custom profile on a post, you just need to create a custom field called “post-author” with the value of the custom profile username.

Plugin features:

1. Display author profile at end of post.
2. Display website user profile or a custom profile.
3. Custom profile displayed take precedence over website profile.
4. Able to display ANY custom profile (does not need to match the username of the post author)
5. Ability to display profile display on a per post author basis or on a post basis.
6. Option to include social media buttons on both website profile or custom profile.
7. Custom Profile is fully configurable. You can choose to display anything you like in there.


This plugin displays the author profile at the end of the post. It gives you have the option to display the author's website user profile or a custom profile.

**Why should my post have an author profile?**
Author profiles at the end of every posts are important as they allow readers to learn more about the post authors. 
Also search engines uses author profiles to identify users and when done properly, are able to link users to post articles. 
Have you done a Google search and notice that some of the result entries have a picture of the author next to them? 
That’s because those posts have an author profile.

Find out more at http://littlehandytips.com/plugins/custom-about-author/

A handy plugin from little Handy Tips :)

Support Us and make a donation at http://littlehandytips.com/support/

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the folder `custom-about-author` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Done!

**Configuration**

By default, the plugin will start showing about author displays for all posts. The information will be taken from the user’s profile.

Information that is used are:

* First Name
* Last Name
* Email (This is to obtain the user’s gravatar image)
* Website (If supplied, the name in the display will be a link to the website)
* Biographical Info

In additional, there will now be new fields for social media. (See image below). The social media icons will only be displayed for those which you have filled in.

Lastly there is an option “Disable about author display”. Checking this will disable the automatic displaying of the about author for all posts written by that author.

**Advanced Configuration**

This plugin gives you more options for displaying the about author. The options are:

* Create a custom profile to display.
* Specify a custom profile to display which has a different username from the post’s author.
* Have complete control over the HTML being displayed.

**Create a custom profile to display**

If you do not want to use your user profile information in the about author display, you can create a custom profile instead. 
This is especially useful if you use a different address for your user and your gravatar image.

To create a custom profile, just go to Custom Authors in the Users menu.

There you can create a custom author profile. 
For custom profiles to replace user profiles, you will need to create the custom profile with the same username as that of the user.

**Specify a custom profile to display which has a different username from the post’s author.**

If you like to display a custom profile which has a different username that that of the post’s author, you can do so by using a custom field in the post. 
When you are editing the post, below the post content there should be the option to add custom fields. 
If you can’t find that, you need to adjust the “screen options” which is near the top right of the browser. (next to help)

Add a custom field with the name `post-author` and the value would be the username for the custom profile you like to display for that post.

Note: Custom fields will display a custom profile even if the post author has chosen to disable the “about author display”.

**Customize the HTML code being displayed in the about author.**

There is the option in the custom author profile to display a custom HTML code in the about author display. To do that, just check the box “Use Custom HTML” and enter the Custom HTML.


Find out more at http://littlehandytips.com/plugins/custom-about-author/

A handy plugin from little Handy Tips :)


== Frequently Asked Questions ==

= If I have both a custom author and a website user with the same username, which will be displayed? =

Any custom author will take precendence over website users. So the custom author will be displayed. 

= If I have chosen to disable display for a profile user, would a custom author with the same username be displayed? =

No, if a profile user is disabled in the user settings, then no custom author will be displayed.
The only exception to this is if you have a custom field in the post for "post-author". In that situation, the custom author specified in that field will be displayed.

= How do I disable the display for just a single post? =

Create a custom field in the post for "post-author" and put a non-existent username in there (e.g: nobody).

= How does the plugin work to determine whether and which profile to display? =

The plugin works using the following logic:

1. check if author has disabled display
2. check custom fields "post-author" to see if an author is specified
3. If author has disabled display and there is no valid author specified in (2). then nothing will be displayed.
4. Otherwise check if author has a custom profile 
5. If it does, display custom profile, otherwise display user profile

Find out more at http://littlehandytips.com/plugins/custom-about-author/

A handy plugin from little Handy Tips :)


== Screenshots ==

1. Custom About Author Display. This is the default layout; you have full control on what you put in there.

== Changelog ==

= 1.4.2 =
* Added option to display about author box at the top of post (Big thanks to Ricardo Feliciano from SonyRumers.net for the changes)
* May 5th 2012

= 1.4.1 =
* Added new social media fields (Flickr, YouTube, Vimeo)
* Added global settings to choose type of content to display custom author (home page, page, single post, archive page)
* Add shortcode to display custom author [custom_author] (no username needs to be specified)
* Added php code for use in theme ( caa_get_current_author_bio() or caa_get_author_bio($username) )
* Fix to facebook social media link.
* January 29th 2012

= 1.3 =
* Custom author box no longers display on the main blog page
* Added WYSIWYG editor for editing custom HTML
* December 3rd 2011

= 1.2 =
* Added option to use a custom author image (instead of gravatar)
* Added shortcode to display custom author [custom_author=username]
* December 1st 2011

= 1.1.2 =
* Fix problem with extra \ being added to " and '.
* November 27th 2011.

= 1.0 =
* Initial release.
* October 26th 2011.

== Upgrade Notice == 

Replace the existing files with the newer version
