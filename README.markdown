# MissionControl Readme

MissionControl is a simple CMS built on the [CakePHP][1] framework (specifically version 1.2, *not* 1.3)

## Requirements

*	HTTP Server. Tested with Apache 1.2
*	Mod-rewrite configured via .htaccess files in `webroot` and the root application directory if the docroot is pointed there instead of `webroot`. See [here][3] for info.
*	PHP 4.3.2+. Tested on PHP 5 so not sure how compatible it is with 4.
*	MySQL 4+
*	Make sure the `tmp` and `webroot/files` directories are writable
*	The core CakePHP v1.2 `cake` directory which should be placed alongside this directory. If placed elsewhere, the `webroot/index.php` file must be updated to point at the new location.
*	tinymce in the webroot
*	PHP-GD image library
*	For the developer only: Console access to the `cake` command, for managing access-control and fixtures. See [here for help setting this up][2].

## Directory Structure

All of MissionControl's functionality is packaged as a suite of CakePHP "plugins", stored in `missioncontrol/plugins`, which is the only non-cake-standard directory. This directory mirrors the standard `plugins` directory. When looking for files, cake will look first in the standard `plugins` directory and fall back to the `missioncontrol/plugins` directory.

This allows us to override certain MissionControl functionality on a site-by-site basis by duplicating a file to a mirrored path inside `plugins` and editing it. This should be used sparingly though – it's better to try and improve the configurability of the plugins than duplicate and change. However, it is useful sometimes for overriding some core view files with site-specific markup.

Any site-specific logic would usually be manifested in the creation of new models/views/controllers outside the plugin directories in the usual CakePHP way.

Administrative pages are found via the `/admin/` route prefix. This does not correspond to a directory - instead controller methods that perform administrative functions are prefixed with `admin_` in the CakePHP way.


## Bootstrap

The site's root `config/bootstrap.php` file contains some essential logic and configuration necessary for tying everything together. (TODO: explain more)

Each plugin contains a `config/routes.php` file which defines extra routes specific to that plugin.


## Access Control

MissionControl uses CakePHP's standard Auth and ACL components which use a list of ACOs, AROs and a linking table.

*	__ACOs__ represent controllers and their actions as children. If you enable access to a whole controller node, permission to access all its children is implied.

*	__AROs__ represent User Groups. There are six pre-defined groups in MissionControl (managed by the `users` plugin): Guest, User, Contributor, Editor, Administrator and Superadministrator. Each inherits permissions from the previous, (except Superadministrator can always do anything). "Guest" is supposed to represent a non-authenticated anonymous user, but usually Authentication is just turned off for public actions (`$this->Auth->allow(XXX)`)

Because the `acos`, `aros` and `aros_acos` tables are MPTT trees, this means they can't be edited manually in the database because the `lft` and `rght` fields will become invalid. Instead there are a couple of console shell commands that should be used to manage permissions. Before running them please `cd` to the application folder (the one that contains `controllers`, `models` etc).

There is a `permissions` shell in `missioncontrol/plugins/core/vendors/shells/permissions.php`. When run on the command-line, it will allow you to perform the following two actions:

	cake permissions rebuild_aros

This will empty the `aros` table, run through the available user `Group` records and create corresponding AROs for each. Note that individual Users are not represented with AROs, only Groups are, so it is not possible to assign individuals unique permissions.

	cake permissions reset

This will look for available CakePHP shell `tasks` _(named ?)_, which are each responsible for setting permissions for one part of the app. e.g.:

*	`vendors/shells/tasks/app_permissions` - this shell contains commands to create any application-specific permissions that are not covered by the plugin permissions already.
*	`missioncontrol/plugins/core/vendors/shells/tasks/core_permissions.php` - this contains most of the core permissions for MissionControl, essentially gives "Contributor" and above users access to all main page editing actions.
*	`missioncontrol/plugins/file_library/vendors/shells/tasks/file_library_permissions.php` - as above, but for the file library plugin.
*	`missioncontrol/plugins/news/vendors/shells/tasks/news_permissions.php` - as above but for news
*	`missioncontrol/plugins/file_library/vendors/shells/users/users_permissions.php` - this gives administrators access to user-management actions, and all users access to change their password and view their profile etc.

There is a separate `acl_extras` shell for managing ACOs. When adding new controller actions, the ACO table will need to be updated to include the new action(s) at the correct point in the tree. You can do so by running:

	cake acl_extras aco_update

or

	cake acl_extras aco_sync
	
The latter also removes dead entries caused by deleting or renaming controller methods.

### Adding/Modifying Group IDs:

_If you add new Groups, the constants that hold the group IDs will become invalid, and therefore the permission update console tasks that use them will also be broken._

## MissionControl Core Plugin Readme

All MissionControl sites require this `core` plugin which takes care of the following:

*	__Pages__ - these represent the main pages of the site. The core plugin takes over the standard CakePHP `/pages/*` route.

*	__Layouts__ - Each page uses a certain "layout", which contains the outer markup of the finished page, e.g. HTML `<head>`, plus usually the header, main menu and footer of a page. These should be placed in `views/layouts` and named with a `.ctp` extension. They should contain the following line to output the inner "template" content in the right place:
	
		<?php echo $content_for_layout ?>
	
*	__Templates__ -  These contain the main markup of each page, and, like layouts, can be shared between as many pages as you like. They live in `plugins/core/views/core_pages`, and should be named with a `.ctp` extension.

*	__Content Areas__ - these are marked parts of a page layout or template that can have content added in the CMS. They are defined by a call to the `content_area` element and given a unique (within the page) name called a "slug", like so:
	
		<?php echo $this->element('content_area', array('slug' => 'callout')); ?>

*	__Blocks__ - All content lives within a "block", which can be placed inside any of the available Content Areas of a page. NOTE: Blocks were conceived to allow different types of content to be inserted into a Content Area, e.g. A text block and then a contact form block, but at present only "text" blocks are available so you wouldn't usually bother to place more than one block in each content area.

*	__Custom Fields__ - these are like Wordpress's custom fields, and let you create arbitrary name-value pairs of data that will be passed to the view and can be used in the template like so:

		<?php echo $layout->getCustomField($data,'box4_title') ?>

*	__Search__ - I'm not sure how 'finished' this is, but the idea is that (via the Searchable plugin) any record can be indexed into a search index table which has a fulltext index for searching.

*	__Email-sending__ via the SwiftEmail library via a component which extends the cake standard Email library which wasn't able to communicate with secure email servers (e.g. Gmail).

*	__Rich-text editing__ via TinyMCE which has to be placed in the `webroot/js/tiny_mce` directory as cake plugins don't support mixing JS and CSS files together in their asset folders unfortunately.

*	__Menu construction__ Pages exist within a single hierarchical, ordered list and can be set to be visible or invisible within the main menu. A limitation of MissionControl is that there is only one master menu/page structure unlike Drupal for instance where menus are independent from pages.

*	__Developer command-line tools__ for managing ACLs and fixtures.

*	__CoreAppController__ - the class inside `missioncontrol/plugins/core/core_app_controller.php` holds some logic that is necessary for almost all requests in a MissionControl site, and therefore all other plugin controllers extend this controller (and are therefore dependant on it). It extends AppController so will inherit any Application-specific code. You may wish any new application-specific controllers to extend CoreAppController rather than AppController directly.


## More Info

Please see the readme file inside each plugin for more info.




[1]: http://cakephp.org
[2]: http://book.cakephp.org/view/108/The-CakePHP-Console
[3]: http://book.cakephp.org/view/37/Apache-and-mod_rewrite-and-htaccess


Copyright (c) 2009-2010 Jamie Mill - jamiermill/a/gmail.com