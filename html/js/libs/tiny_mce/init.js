
$(
    function()
    {
        $('textarea.tinymce').tinymce
        (
            {
                // Location of TinyMCE script
                script_url : '/js/libs/tiny_mce/tiny_mce.js',

                // General options
                theme : "advanced",
                plugins : "autolink,lists,pagebreak,advhr,advimage,advlink,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,nonbreaking,xhtmlxtras,advlist",

                // Theme options
                theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fullscreen",
                theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,undo,redo,|,link,unlink,anchor,image,cleanup",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,

                // Example content CSS (should be your site CSS)
        //        content_css : "css/content.css",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url : "lists/template_list.js",
                external_link_list_url : "lists/link_list.js",
                external_image_list_url : "lists/image_list.js",
                media_external_list_url : "lists/media_list.js"

            }
        );
    }
);