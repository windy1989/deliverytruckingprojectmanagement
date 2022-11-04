$(function() {
    $('.select2').select2({
        placeholder: '-- Pilih --'
    });

    $('.select2-modal').select2({
        placeholder: '-- Pilih --',
        dropdownParent: $('#form_modal')
    });

    $('.number').number(true);
});

lightbox.option({
    'resizeDuration': 200,
    'wrapAround': true
});

function loadingOpen(selector) {
    $(selector).waitMe({
        effect: 'ios',
        text: 'Mohon Tunggu ...',
        bg: 'rgba(255,255,255,0.7)',
        color: '#000',
        waitTime: -1,
        textPos: 'vertical'
    });
}

function loadingClose(selector) {
    $(selector).waitMe('hide');
}

function notif(text, background) {
    Snackbar.show({
        text: text,
        pos: 'top-center',
        actionTextColor: '#fff',
        backgroundColor: background
    });
}

function select2ServerSide(selector, endpoint, dropdown_parent = '') {
    if(dropdown_parent) {
        var dropdown = $(dropdown_parent);
    } else {
        var dropdown = '';
    }

    $(selector).select2({
        placeholder: '-- Pilih --',
        minimumInputLength: 3,
        allowClear: true,
        cache: true,
        dropdownParent: dropdown,
        ajax: {
            url: endpoint,
            type: 'POST',
            dataType: 'JSON',
            delay: 250,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function(params) {
                return {
                    search: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data.items
                }
            }
        }
    });
}

function previewImage(event, selector_img, selector_href) {
    if(event.files && event.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(selector_href).attr('href', e.target.result);
            $(selector_img).attr('src', e.target.result);
        }

        reader.readAsDataURL(event.files[0]);
    }
}
