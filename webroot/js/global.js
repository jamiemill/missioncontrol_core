$().ready(function() {
	$('textarea.tinymce').tinymce({
		// Location of TinyMCE script
		script_url : baseURL+'js/tiny_mce/tiny_mce.js',

		// General options
		theme : "advanced",
		plugins : "safari,autosave,paste,table,fullscreen",

		// Theme options
		theme_advanced_buttons1 : "undo,redo,|,cut,copy,paste,pastetext,pasteword,|,tablecontrols,table,row_props,cell_props,delete_col,delete_row,col_after,col_before,row_after,row_before,row_after,row_before,split_cells,merge_cells,|,forecolor,backcolor,|,code,fullscreen",
		theme_advanced_buttons2 : "formatselect,styleselect,|,bold,italic,underline,removeformat,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,link,unlink,anchor,image",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",
		
		theme_advanced_blockformats: "p,h1,h2,h3,h4",
		file_browser_callback : 'myFileBrowser',
		convert_urls : false,

		// Drop lists for link/image/media/template dialogs
		// template_external_list_url : "lists/template_list.js",
		// external_link_list_url : "lists/link_list.js",
		// external_image_list_url : "lists/image_list.js",
		// media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
	
	$('a.external').click(function(){
		window.open(this.href);
	    return false;
	});
	
});

function myFileBrowser (field_name, url, type, win) {
    /* If you work with sessions in PHP and your client doesn't accept cookies you might need to carry
       the session name and session ID in the request string (can look like this: "?PHPSESSID=88p0n70s9dsknra96qhuk6etm5").
       These lines of code extract the necessary parameters and add them back to the filebrowser URL again. */

    //var cmsURL = window.location.toString();    // script URL - use an absolute path!
	var cmsURL = baseURL+"admin/file_library/file_library_files/index/context:popup/";    // script URL - use an absolute path!

   
    //add the type as the only query parameter
    cmsURL = cmsURL + "type:" + type;


    tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'File Browser',
        width : 1040,  // Your dimensions may differ - toy around with them!
        height : 600,
        resizable : "yes",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : "no",
		popup_css: false
    }, {
        window : win,
        input : field_name,
		type : type
    });
    return false;
}
