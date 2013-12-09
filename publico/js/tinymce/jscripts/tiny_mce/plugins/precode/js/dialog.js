var PrecodeDialog = {
    init : function() {
        var f = document.forms[0];
        f.pre_code.value = tinyMCEPopup.editor.selection.getContent({format : 'text'});
    },
    insert : function() {
        // Insert the contents from the input into the document
        tinyMCEPopup.editor.execCommand('mceInsertContent', false, '<pre>' + document.forms[0].pre_code.value + '</pre>');
        tinyMCEPopup.close();
    }
};
tinyMCEPopup.onInit.add(PrecodeDialog.init, PrecodeDialog);