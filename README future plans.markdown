## Recommendations for the future

MissionControl was built gradually to centralize work on many different websites, and as such is a little inconsistent but reasonably flexible. Ideas for the future are:

*	Tidy it up!
*	Edit-in-place ability where blocks on the page can be directly edited and dragged around to different content areas.
*	Make the various calls necessary in views (e.g. menus, content areas, custom fields) have a consistent interface via helpers. At the moment some are variables, some are elements and some are helper calls.
*	Use CakePHP 1.3 which has plugin-specific shell-based schema management and support for putting tinymce's assets inside the Core plugin.
*	Implement page caching. Cake has some powerful caching tools which are not used at all yet.
*	Move page templates to views/pages
*	Move as much required bootstrapping code into an include file in the core plugin.

---

# Stuff I need to write about in these readme files

*	example of menu stuff..
*	example of getting siblings
*	schema management
*	every plugin has SQL file
*	are the sql files up to date?
*	fixtures?
