# page-author-wp
Adds Author functionality to WordPress Pages. Works with most templates (even grumpy ones).


The plugin is simple and works by asking the database for information, the ones defined are currently: `author_avatar`, `author_name` and `author_linkedin`.

When you enable the plugin, you'll see two new options on your website:
- Selecting an Author on Pages (includes Editors)
- Adding a Widget called "Custom Page Author Widget"

  The latter is useful for putting into places like sidebars, or bottom of the page with the correct `<div>` if wished.


The plugin unfortunately does not have a fancy interface to modify it, and needs to be done with the provided CSS file (or copying the CSS into Custom CSS in WordPress Theme). As is, the Plugin will also display the last date when the Page was modified, giving it more of a "blog post" situationship.

I strongly suggest you modify this Plugin to suit the needs of your site, mainly editing out if "last modified" is needed or more importantly, fancy colors and layout of the Author tab (do this in `css/style.css`

You can also change/add Social media links, or just remove it completely. Look for this part of `inc/functions.php`:

83: `$linkedin_url = get_user_meta($author_id, 'linkedin', true);` You may change this to for example Facebook, Twitter, or add on more (remember to change line `89` if you do this.)
