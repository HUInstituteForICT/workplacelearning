$(document).ready( function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })

    if (window.location.hostname !== 'localhost') {
        $.ajax({
            url: '/log',
            type: 'post',
            data: {
                'width': screen.width,
                'height': screen.height,
                'agent': navigator.userAgent,
                'OS': navigator.appVersion,
                'url': location.href
            },
        });
    }
});
