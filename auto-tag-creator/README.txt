=== Auto Tag Creator ===
Contributors: ecreateinfotech, gb071
Tags: seo,tags,keywords,content,posts
Requires at least: 4.6
Tested up to: 6.9
Stable tag: 1.0.3
Requires PHP: 5.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically creates tags from post/product title and category, on update or publish.

== Description ==

This plugin automatically converts keywords in a post/product title and category to tags upon saving. It includes a user-editable list of words you want the plugin to ignore

* Converts keywords in post/product titles to tags
* Converts keywords in post/product category to tags
* Includes user-editable list of words to be ignored
* Setting to enable for title and category separately
* Setting to overwrite previous tag and consider only title and category while generating new one
* Converts on publish and update.

Multi-blog adminstrators take note: this plugin is especially helpful if you're building a community-based site where tagging is important and your bloggers are not always diligent about tagging.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `ec-auto-tag-creator` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Open the `Tag Setting` menu to configure, by default all setting is enable.
4. That's it. Every new post/product will now be automatically tagged.

= For custom work = 
Please contact us <a href="http://ecreateinfotech.com/contact-us/">here</a>.

== Frequently Asked Questions ==

= Does this plugin convert all words in the title and category? =
No. There is a user-configurable list of ignore words into setting menu, those world will be ignored. There is also setting for no of word length, so if you have set it less then your word which will be also gets ignored.

= Will this plugin overwrite my existing tags? =
This is a setting for that, if you have enable overwrite option it will overwrite otherwise it will not overwrite.

= When are the tags added? At publish or save? =
Tags are added to the post/product when it is saved, whether as a update or by publishing, but not when the post is autosaved.

= Will this plugin put tags on my old posts? =
No. It will not handle the process automatically, you need to update older post again.

== Screenshots ==

1. Setting screen
2. Post screen

== Changelog ==

= 1.0.3 =
* First version.