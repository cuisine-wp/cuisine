### 1.5.7: 2016-02-12

* Added a Logger
* Update to RepeaterFields: they can now provide a cut-and-dry array of field-values on saving.
* Big (breaking) fixes to Editor field handling
* Field engine update (classes & validation)
* Bugfix for the SettingsPageBuilder when no parent was set.
* Minor bugfixes.


### 1.5.6: 2015-12-21

* Fontawesome updated to 4.5
* Custom submenu pages support added for the SettingsPage class
* Added support for objects in Sort::byField
* Refactored Relative dates
* Bugfix for the datepicker ui
* Minor bugfixes.


### 1.5.5: 2015-12-21
* Date-picker support to the datefield added
* Added a RootPostId function to the session class, to get the post ID from $wp_the_query
* Adds easy access to User attributes
* Lots of other little improvements to the User class
* Script::analytics now works with the modern ga notation
* Minor bugfixes.


### 1.5.4: 2015-11-25
* Added the option to pass parameters to templates
* Browsersync support for requirejs
* Taxonomy highlighting in the Nav class
* Session crawler checker added
* Added a new "File" field-type
* Bugfix to the field-class system.
* Minor bugfixes.


### 1.5.3: 2015-10-05
* Checkboxes choice-fields support added
* Support for editor-fields in repeater fields added. 
* Slug validation to the Validate class added.
* Chef Related template-support added.
* Field classes bugfix when there's no defaul value.
* Bugfix in script-autoloaded.
* Minor bugfixes.


### 1.5.2: 2015-09-08
* Padding dropdown error fixed.
* Removing an image with an image field
* Removed an Exception error in Image.php


### 1.5.1: 2015-08-20
* Added page-template support to the template-engine
* Added a fresh-forms wp cli command
* Added a getProperty method for Fields
* Added the ability to create SettingsPages 
* Minor bugfixes


### 1.5.0: 2015-08-12
* Added the Date Utility class
* Added the Number Utility class
* Added single-post pagination
* Added a save-field filter
* Fixes to the repeaterfield when saving with ajax
* Fixes a routing error in the template engine


### 1.4.9: 2015-08-11
* Added the Template::section selector
* Added Date-field support
* Fixed some big pagination bugs
* Minor bugfixes


### 1.4.8: 2015-08-06
* Added Nav active-state setting
* Added SVG upload support
* Minor bugfixes


### 1.4.7: 2015-08-05
* Editor hotfix; the value remained empty
* Added WP CLI support for Sass-refreshing


### 1.4.6: 2015-08-03
* Repeaterfield fixes
* Media Library incremental IDs

### 1.4.5: 2015-07-28
* Big media-library update
* Filter for scripts added
* Some more editor bugfixes
* Saving single checkboxes who are not checked
* Added a simple template-finder
* Added some more JS vars
* Fixes to the current user variable



### 1.4.4: 2015-07-07
* Mobile Nav support
* Json validation added
* Fontawesome shortcut added
* Calling single sections from the Loop class
* Fix for the media-library when no thumbnail is set


### 1.4.3: 2015-07-03

* Editor refreshing fixes
* Readme updates
* Media library bugfixes


### 1.4.2: 2015-07-02

* Refreshing WP editors from javascript bugfix


### 1.4.2: 2015-06-30

* First public release
