$(function() {
    var resumen = $('#resumenA');
    if (resumen.length === 1) {
        resumen.tinymce({
            mode: "exact",
            convert_urls: false,
            theme: "advanced",
            language: "es",
            plugins: "table,fullscreen,imgphp,precode",
            theme_advanced_buttons1: "example,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontsizeselect",
            theme_advanced_buttons2: "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,forecolor,|,imgphp,precode",
            theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,|,charmap,iespell,|,fullscreen"

        });
    }
    var cuerpo = $('#cuerpoA');
    if (cuerpo.length === 1) {
        cuerpo.tinymce({
            mode: "exact",
            convert_urls: false,
            theme: "advanced",
            language: "es",
            plugins: "table,fullscreen,imgphp,precode",
            theme_advanced_buttons1: "example,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontsizeselect",
            theme_advanced_buttons2: "bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,forecolor,|,imgphp,precode",
            theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,|,charmap,iespell,|,fullscreen"

        });
    }
});