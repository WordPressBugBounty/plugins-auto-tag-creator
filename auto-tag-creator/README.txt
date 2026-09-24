=== Auto Tag Creator ===
Contributors: ecreateinfotech, gb071
Tags: tags, auto tagging, woocommerce, seo, keywords
Requires at least: 4.7
Tested up to: 7.1
Stable tag: 1.1.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically creates tags from post and product titles and categories when you publish or update.

== Description ==

Auto Tag Creator adds tags to your posts and WooCommerce products for you. When you publish or update an item, the plugin takes the words from its title and category names and saves them as tags. You decide which words to skip, how long a word must be, and how many tags each item gets.

It is a good fit for blogs, news sites, community sites and online stores where content is added often and tags are easy to forget.

= Features =

* Creates tags from post and product titles.
* Creates tags from post and product category names.
* Title and category tagging can be turned on or off separately for posts and products.
* Works with the block editor, the classic editor, Quick Edit and scheduled posts.
* Works with WooCommerce products, including products added through the WooCommerce REST API, CSV imports and other plugins.
* Skips common English words such as "the", "and", "for" and "with".
* Your own list of words to ignore.
* Minimum word length, so short words are not turned into tags.
* Maximum number of new tags per post or product.
* Tag letter case: keep words as written, use lowercase, or use Title Case.
* Option to replace existing tags or keep them and add new ones.
* Bulk tagging for posts and products that are already published, with a progress bar.
* Settings are kept when the plugin is deactivated and removed only when the plugin is deleted.

= How it works =

Say you publish a post titled "Easy Tomato Soup Recipe" in the category "Healthy Food". With the default settings, the post gets these tags: Easy, Tomato, Soup, Recipe, Healthy and Food. Common words and words shorter than the minimum length are skipped.

= Need it for other post types? =

Auto Tag Creator works with posts and WooCommerce products. If you need the same feature for a custom post type, or any other custom WordPress work, please [contact us](https://www.ecreateinfotech.com/contact/?utm_source=wp-plugin&utm_medium=readme&utm_campaign=auto-tag-creator).

== Installation ==

1. In your WordPress admin, go to Plugins > Add New Plugin and search for "Auto Tag Creator".
2. Click Install Now, then Activate.
3. Go to Tag Setting in the admin menu, or click the Settings link on the Plugins page.
4. Choose your settings and click Save Settings.

That's it. New and updated posts and products will now be tagged automatically. To tag content you have already published, use the "Tag Existing Content" section on the same page.

To install manually, upload the plugin folder to the `/wp-content/plugins/` directory and activate the plugin from the Plugins page.

== Frequently Asked Questions ==

= Does the plugin turn every word into a tag? =

No. Common English words such as "the", "and" and "for" are skipped. You can also add your own words to ignore, set a minimum word length and set a maximum number of tags per item.

= Will the plugin remove my existing tags? =

Only if you turn on "Overwrite Previous Tag". When this option is off, which is the default, your existing tags are kept and the new tags are added to them.

= When are tags added? =

Tags are added when a post or product is published and each time a published item is updated. Drafts and autosaves are not tagged. Scheduled posts are tagged when they go live.

= Can I add tags to posts and products I have already published? =

Yes. Go to Tag Setting and use the "Tag Existing Content" section. It tags all published posts or products using your saved settings.

= Does it work with WooCommerce? =

Yes. Turn on product tagging in the settings and the plugin will create product tags from product titles and product categories. The Products option in "Tag Existing Content" appears when WooCommerce is active.

= Does it work with custom post types? =

Not at the moment. The plugin supports posts and WooCommerce products. If you need it for a custom post type, please [contact us](https://www.ecreateinfotech.com/contact/?utm_source=wp-plugin&utm_medium=readme&utm_campaign=auto-tag-creator).

= Why do some tags not use the letter case I selected? =

If a tag with the same name already exists, WordPress uses the existing tag as it is. For example, if your site already has the tag "tomato", a new post will use "tomato" even when Title Case is selected.

= Are my settings removed when I deactivate the plugin? =

No. Settings are kept when the plugin is deactivated. They are removed only when you delete the plugin.

== Screenshots ==

1. Settings page with all tagging options.
2. Confirmation before tagging existing content.
3. Tagging existing posts and products with a progress bar.
4. Posts list with tags created from titles and categories.
5. WooCommerce products list with tags created from product names and categories.

== Changelog ==

= 1.1.1 =
* Added: "More free plugins by Ecreate" panel on the settings page, kept up to date from WordPress.org.
* Changed: Plugin URI updated to the new services page.
* Changed: Minimum WordPress version is now 4.7, which the plugin already required.
* Removed: Unnecessary translation loading. WordPress loads plugin translations automatically.

= 1.1.0 =
* Added: Built-in list of common English words to skip.
* Added: Tag letter case setting (as written, lowercase or Title Case). Tags now keep their original case by default.
* Added: Maximum number of new tags per post or product.
* Added: Bulk tagging for existing posts and products.
* Added: Settings link on the Plugins page.
* Improved: New design for the settings page.
* Fixed: Category tags were missing when publishing from the block editor.
* Fixed: Tags added by the author in the block editor removed the generated tags.
* Fixed: No tags were created for products added through the WooCommerce REST API, imports or other plugins.
* Fixed: Words with apostrophes created broken tags.
* Fixed: Settings are no longer removed when the plugin is deactivated, only when it is deleted.
* Fixed: Minimum word length now counts characters correctly for non-English words.
* Tested up to WordPress 7.1.

= 1.0.3 =
* Security: Only administrators can save the settings, and the settings form is now protected with a security check (nonce).
* Security: Improved sanitizing of saved settings and escaping of output on the settings page.
* Security: Plugin files can no longer be opened directly.
* Improved: Special characters are removed from words before they are turned into tags.
* Tested up to WordPress 6.9.

= 1.0.2 =
* Fixed: Titles containing special characters such as "&" created incorrect tags.
* Tested up to WordPress 6.0.2.

= 1.0.1 =
* Fixed: Loading image on the settings page was not displayed.
* Tested up to WordPress 5.8.1.

= 1.0.0 =
* First version.

== Upgrade Notice ==

= 1.1.0 =
Adds bulk tagging, common word filtering, tag letter case and tag limit settings, and fixes tagging in the block editor and for WooCommerce products.
