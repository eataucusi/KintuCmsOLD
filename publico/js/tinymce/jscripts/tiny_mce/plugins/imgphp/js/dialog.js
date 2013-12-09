var ImgphpDialog = {
	init : function() {
		var f = document.forms[0];
		// Get the selected contents as text and place it in the input
		//f.someval.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
		f.usar_img.value = tinyMCEPopup.getWindowArg('some_custom_arg');
	},

	insert : function() {
		// Insert the contents from the input into the document
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, '<img src="' + document.forms[0].usar_img.value + '">');
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(ImgphpDialog.init, ImgphpDialog);