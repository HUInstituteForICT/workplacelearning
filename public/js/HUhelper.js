$(document).ready( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    })
    $.ajax({
        url: '/log',
        type: 'post',
        data: {
            'width'     : screen.width,
            'height'    : screen.height,
            'agent'     : navigator.userAgent,
            'OS'        : navigator.appVersion,
            'url'       : location.href,
        },
    });
});
