/**
 * Created by mahbub on 8/5/15.
 */
var Modal = {
    init: function () {
        this.initEditModal();
        this.initConfirmationModal();
    },
    initEditModal: function() {
        $(document).on('click', '.load-form-modal', function(event){
            console.log('Modal: '+ $(this).attr('data-url'));
            $('#form-modal .modal-body').load($(this).attr('data-url'));
            event.preventDefault();
        });
    },
    initConfirmationModal: function() {
        $(document).on('click', '.load-confirmation-modal', function(event){
             console.log('Modal: '+ $(this).attr('data-url'));
            $('#confirmation-modal form').attr('action', $(this).attr('data-url'));
            event.preventDefault();
        });
    }
};

Modal.init();

$(document).ready(function () {
    $('input:text').bind({
    });
    $("#auto").autocomplete({
    minLength:3,
    source: 'http://incidencias.ddns.net/getdata',
    select: function (event, item)
    {
        if (event.keyCode == 13){
            $('#myForm').submit()
        }
    }
    });
});

$('#msj-success').fadeIn();
$('#msj-success').delay(2000).fadeOut(800);
