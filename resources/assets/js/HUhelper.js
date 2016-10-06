$(document).ready( function() {
    $.ajax({
        url: 'commit.php',
        type: 'post',
        data: { 'width'     : screen.width,
            'height'    : screen.height,
            'agent'     : navigator.userAgent,
            'url'       : location.href,
            'recordSize' : 'true'
        },
        success: function(response) {
            console.log(response);
        }
    });
});
