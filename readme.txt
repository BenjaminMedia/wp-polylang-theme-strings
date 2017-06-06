This is a fork of the following plugin:

=== Polylang Theme Strings ===
Contributors: modeewine
Donate link: http://modeewine.com/en-donation
Tags: extension, polylang, multilingual, translate, translation, language, multilanguage, international, localization
Requires at least: 3.8
Tested up to: 4.7
Stable tag: 3.4
License: GPL2

Automatic scanning of strings translation in the theme and registration of them in Polylang plugin. Extension for Polylang plugin.

== Description ==

= What is «Polylang Theme Strings» and what for is it?  =

This plugin gives additional features to the plugin Polylang. It automatically scans all templates files and scripts of the active WP theme for available strings that can be translated. For example:

* `pll__('...');`
* `pll_e('...');`

and adds them to the Polylang registery, after what you can manage the translation of finded strings using the administration panel. It will make your life easier for the development of multilanguage’s projects, because you will not need to enter the needed strings to translate manually – the plugin will do all the work for you.
Don’t forget that in the example are described the PHP-function calls, that is why they have to be inside of PHP-tags.

= How works «Polylang Theme Strings»? =

You have to install the plugins «Polylang» and «Polylang Theme Strings» on your multilanguage WordPress CMS project and they must be both active. When you are in the settings of plugin (Polylang) in the tab «Strings translation» the «Polylang Theme Strings» scans automatically the active theme of your project, find all the code strings that needed to be translated, adds them to the register, displays them on that page and gives to user the ability to translate these strings.

Like you can see, the «Polylang Theme Strings» is perfectly integrate with the «Polylang» plugin and works in automatically mode – it is comfortable, simple, and useful!

Learn more in <http://modeewine.com/en-polylang-theme-strings>.

== Installation ==

1. Make sure you are using WordPress 3.8 or later and that your server is running PHP 5.0 or later.
1. Install multilingual plugin «Polylang».
1. Install the plugin «Polylang Theme Strings».
1. Activate both plugins via the 'Plugins' menu in WordPress administration panel.
1. Go to the languages (Polylang) settings page.
1. When you are in «Strings translation» tab of plugin settings (Polylang) – «Polylang Theme Strings» starts scan the active theme of your project automatically, it finds all the code strings that needed to be translated, adds them to the register, displays them on that page and gives to user the ability to translate these strings.
1. Learn more in <http://modeewine.com/en-polylang-theme-strings>.

== Screenshots ==

1. Screen of «Polylang» strings translate page settings and when «Polylang Theme Strings» in action.

== Changelog ==

= 3.4 (2017-05-17) =

* Fixed small bug in the search-engine of strings-translation.
* Added file size limit in the theme-files search-system.

= 3.3.2 (2017-01-25) =

* Added compatibility with Polylang 2.1 (Thanks to Mike Ambukadze for the report).

= 3.3.1 (2017-01-24) =

* Fixed small bug in the theme-files search-system (Thanks to einicher).

= 3.3 (2017-01-20) =

* Added compatibility for strings with special characters.
* Tested and optimized compatibility with WordPress 4.7.
* Tested and optimized compatibility with Polylang 2.

= 3.2.1 (2016-07-21) =

* Fixed small bug (Thanks to Peter Bowyer).

= 3.2 (2016-06-28) =

* Improved search-engine of strings-translation.

= 3.1 (2016-06-01) =

* Improved code.
* Tested and optimized compatibility with PHP 7.

= 3.0 (2016-03-23) =

* Added new info area in strings-translations page (Polylang settings).
* Improved code and scanning strings engine.
* Tested and optimized compatibility with WordPress 4.5.

= 2.2.1 (2016-03-09) =

* Fixed small bug in search strings-translation.

= 2.2 (2016-01-25) =

* Optimized compatibility with Polylang 1.8.
* Improved code.

= 2.1.1 (2015-12-17) =

* Tested and optimized compatibility with WordPress 4.4.

= 2.1 (2015-09-01) =

* Absolute compatibility with WordPress 4.3.
* Partially improved code.

= 2.0 (2015-06-21) =

* Completely remade the search strings-translations logic in the themes.
* In the languages (Polylang) settings page: the search is performed on all themes in your project.
* Optimized initialization strings-translations for the active theme.
* Improved code.

= 1.1 (2015-06-12) =

* Fixed bug when removing the plugin from the admin panel.
* Improved code.

= 1.0 (2015-05-29) =

* First release.
