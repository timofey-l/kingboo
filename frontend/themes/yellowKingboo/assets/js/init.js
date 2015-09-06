$(document).ready(function(){

    $('.mobile-menu-button').click(function(){
        $('.main-menu').toggleClass('show');
    });

    $('.logo').click(function(){
        window.location = "/";
    });

    // минимальная высота body
    var updateMinHeight = function() {
        var maxH = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        var contentH = maxH - ($('header').height() + $('footer').height());
        $('#content').css({'min-height': contentH});
    };

    var timeout = null;
    $(window).resize(function(){
        if (timeout)
            clearTimeout(timeout);
        timeout = setTimeout(updateMinHeight, 300);
    });

    updateMinHeight();

});
