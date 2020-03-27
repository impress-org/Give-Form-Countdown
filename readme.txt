=== Form Countdown for GiveWP ===
Contributors: givewp, webdevmattcrom, ravinderk, dlocc
Donate link: https://github.com/impress-org/Give-Form-Countdown
Tags: givewp, donation, donations, time limit, end campaign, duration achieved, close form, donation limit
Requires at least: 4.8
Tested up to: 5.4
Stable tag: 2.0.0
Requires Give: 2.5.0
Requires PHP: 7.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.en.html

A GiveWP add-on which allows you to display a countdown on your donation forms until a certain date/time. Various actions are available when the countdown expires.

== Description ==

Form Countdown is a [GiveWP](https://givewp.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=Free_Addons&utm_content=Countdown_Timer) add-on allows you to display a countdown on your donation forms until a certain date/time. Various actions are available when the countdown expires.

Limiting your donation campaigns to a certain time-frame is useful for communicating a sense of urgency. When your donors know that their window to donate is limited to a certain timeframe, they are less likely to postpone giving to a later time.

**FEATURES**

* Display the countdown until a specific date/time.
* When the form is closed, display a custom message to your donors thanking them for their generosity. Or show/hide the form or the countdown clock.
* Choose from dark, light, or custom color schemes. 

**BASIC USAGE**

All of the Form Countdown settings are per form, so you will not find anything in "Donations > Settings". Instead, go to "Donations > All Forms" and click on your form.

There you'll see our tabbed "Donation Form Options" interface. Towards the bottom is a new tab called "Form Countdown". There you'll have the following options:

* **Show Countdown** -- Enable or Disable your the countdown clock to appear on this form. All other fields will appear when you select "Enable".
* **End Date** -- Set the end date that the countdown clock is counting down toward.
* **End Time** -- Set the time of day for which the countdown clock will end on the designated "End Date".
* **Clock Color Scheme** -- Choose from "Dark", "Light", or "Custom" color schemes for your countdown clock.
* **Pick Your Custom Color** -- A Colorpicker field to choose your custom color. Only appears when "Custom" is selected for the "Clock Color Scheme" setting. 
* **Countdown Achieved Action** -- Chose from a list of different actions to happen when the countdown clock reaches its end. These options allow you to show/hide the clock, the form, or a custom message. 
* **End Message** -- Show a message when the countdown clock reaches its end. Only shows for relevant settings of the "Countdown Achieved Action" setting.

**ABOUT OUR FREE ADD-ONS**
Add-ons like "Form Countdown for GiveWP" are a way that we are giving back to the WordPress community. Check out our [announcement about this add-on](https://givewp.com/free-givewp-addon-countdown-timer/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=Free_Addons&utm_content=Countdown_Timer) to learn more about all the great and free add-ons we're creating.

**ABOUT GIVEWP**
> [GiveWP](https://givewp.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=Free_Addons&utm_content=Countdown_Timer) is the most robust WordPress plugin available for accepting online donations. GiveWP provides you with powerful features helping you raise more funds for your cause from one, effective, platform.
> 
> If you are enjoying Give Form Countdown please consider giving us your feedback and rating.

== Frequently Asked Questions ==

= This sounds great, but what is GiveWP? =

[GiveWP](https://givewp.com/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=Free_Addons&utm_content=Countdown_Timer) is the most robust WordPress plugin available for accepting online donations. GiveWP provides you with powerful features helping you raise more funds for your cause from one, effective, platform.

= My form also has a Goal. How do these work together? =

The goal and the countdown clock work indepent from each other, generally speaking. This allows you to show the clock for as long as your goal is active, or even after the goal is reached. The one exception is if you chose to close the form after your goal is achieved. In that case the countdown clock will not appear, and instead your goal achieved message will appear alone. 

= How can I test the completion message? =

The easiest way is to manually change the date of your duration to be in the past. If you are comfortable with code, there is a line in the main template file intended just for testing which sets the countdown clock at 5 seconds from the present. 

= Where can I submit Support Questions? =

If you have purchased any of our Premium Add-ons, we can provide with your [Priority Support here](https://givewp.com/support/?utm_source=wordpress.org&utm_medium=referral&utm_campaign=Free_Addons&utm_content=Countdown_Timer).

If you are a free GiveWP user and have a general question about GiveWP, submit a ticket here.

Otherwise, if your question is specific to GiveWP Form Countdown we're happy to answer your questions [here](https://wordpress.org/support/plugin/form-countdown-for-givewp).

= I have a feature request, or would like to contribute to this plugin. Where can I do that? =

Form Countdown for GiveWP is hosted publicly on Github. We'd love your feedback and suggestions [there](https://github.com/impress-org/Give-Form-Countdown/issues).

== Installation ==

**Minimum Requirements**

* WordPress 4.8 or greater
* PHP version 5.6 or greater
* MySQL version 5.6 or greater

**Automatic installation**

**NOTE:** Before installing Form Countdown, you must have the free [GiveWP Donation plugin](https://go.givewp.com/download) installed and activated on your website.

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your web browser. To install Give Form Countdown, login to your WordPress dashboard, navigate to the Plugins menu and click "Add New".

In the search field type "Form Countdown for GiveWP" and click "Search Plugins." Once you have found the plugin you can view details about it such as the the point release, ratings and description. Most importantly of course, you can install it by simply clicking "Install Now".

**Manual installation**

The manual installation method involves downloading the plugin and uploading it to your server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

**Updating**

Automatic updates should work like a charm; as always though, ensure you backup your site before doing any plugin or theme updates just in case. If you have any trouble with an update, try out our [WP-Rollback plugin](https://wordpress.org/plugins/wp-rollback) which lets you revert to previous versions of WordPress plugins or themes with just a couple clicks.

== Screenshots ==

1. The Form Countdown Clock on a donation form.
2. The Form Countdown Settings.

== Changelog ==

### 2.0.0 March 27, 2020 ###
Revamped and Improved!
* Refactored flip clock with [Flipdown.js](https://github.com/PButcher/flipdown)
* Refactored form settings for more clarity and simplicity of use
* Separated the goal settings from the countdown settings for more flexibility in use
* Added new color scheme options, including custom colors. 

= 1.0.1: July 5th, 2018 =
* Fix: Compatibility with Give 2.1+.
* Fix: Improved compatibility with PHP 5.3
* Fix: Better form default settings.

= 1.0 =
* Initial release