$(function() {
    $('#role_id').change(function() {
        $('.ajax-padre').html($('.ventana').html());
        $.ajax({
            url: $('base').attr('href') + 'menu/ajaxSelect/' + $(this).val(),
            success: function(e) {
                $('.ajax-padre').html(e);
            }
        });
    });

    $('.elegirUrl').live('click',function(e) {
        $('#cargando').fadeIn(500);
         $('#marco').addClass('modal');
        $('#marco').show();        
        $.ajax({
            url: $('base').attr('href') + $(this).attr('href'),
            success: function(e) {
                $('#marco').html(e);
            }
        });
        e.preventDefault();
    });
    
    $('#paginacion .link').live('click',function(e) {
        $('#cargando').fadeIn(500);
         $('#marco').addClass('modal');
        $('#marco').show();        
        $.ajax({
            url: $(this).attr('href'),
            success: function(e) {
                $('#marco').html(e);
            }
        });
        e.preventDefault();
    });
    
    $('.click').live('click', function(e){
        $('#url').val($(this).attr('href'));
        $('#cargando').click();
        e.preventDefault();
    });

    $('#cargando').click(function() {
        $(this).fadeOut(500);
        $('#marco').html('');
        $('#marco').removeClass('modal');
        $('#marco').hide();
    });
});