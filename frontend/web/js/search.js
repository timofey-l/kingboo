function goWithPOST(url, data, formName) {
    var formString = "";
    formString += '<form style="display:block;position:absolute; width: 0px; height:0px; top:0; left:0;" action="' + url + '" method="POST">';
    for (var fieldName in data) {
        formString += '<input type="hidden" name="' + formName + '[' + fieldName + ']" value="' + data[fieldName] + '" />';
    }

    var csrf_n = $('meta[name=csrf-param]').attr('content');
    var csrf_v = $('meta[name=csrf-token]').attr('content');
    formString += '<input type="hidden" name="' + csrf_n + '" value="' + csrf_v + '" />';

    formString += '</form>';
    var form = $(formString);
    $('body').append(form);
    $(form).submit();
}

function get_date(date) {
    var day = date.getDate().toString();
    if (day.length == 1) day = '0' + day;
    var month = (date.getMonth() + 1).toString();
    if (month.length == 1) month = '0' + month;
    var year = date.getFullYear();
    return year + '-' + month + '-' + day;
}

function init_rooms_gallery() {
    $('[id^="hotel-room-"]').each(function (index, el) {
        var swiper = new Swiper(el, {
            paginationClickable: true,
            nextButton: $(el).find('.swiper-button-next')[0],
            prevButton: $(el).find('.swiper-button-prev')[0]
        });
        //$(el).find('.swiper-slide').click(function(e){
        //    e.preventDefault();
        //    var q = $(this).find('a');
        //    $.colorbox({
        //        maxWidth: '100%',
        //        maxHeight: '100%',
        //        href: $(q).attr('href'),
        //        rel: $(q).attr('rel')
        //    });
        //});
    });
    setTimeout(function () {
        window.onresize();
        $('[id^="hotel-room-"] .swiper-slide').colorbox({
            maxWidth: '100%',
            maxHeight: '100%'
        });
    }, 100);
}

$(document).ready(function () {
    /* Слайдер картинки отеля */
    var changeSlider = function (swiper) {
        var slide = swiper.slides[swiper.activeIndex];
        var url = $(slide).data('big-preview');
        $('#hotelImagesBig .big-image-div').css({
            'background-image': 'url(' + url + ')'
        });
        $('#hotelImagesBig .big-image-div')[0].onclick = function () {
            $(slide).find('a').trigger('click');
        };

        setTimeout(function () {
            $('#hotelImagesBig .big-image-div').css({
                opacity: 1
            });
        }, 100);
    };
    var hotelImages = new Swiper('#hotel-images-container', {
        pagination: '#hotel-images-container .swiper-pagination',
        slidesPerView: 'auto',
        centeredSlides: true,
        paginationClickable: true,
        slideToClickedSlide: true,
        spaceBetween: 10,
        nextButton: $('#hotel-images-container .swiper-button-next'),
        prevButton: $('#hotel-images-container .swiper-button-prev'),
        onSlideChangeStart: function (swiper) {
            $('#hotelImagesBig .big-image-div').css({
                opacity: 0
            });
        },
        onSlideChangeEnd: changeSlider
    });
    changeSlider(hotelImages);
    $('#hotel-images-container .swiper-button-prev').click(function () {
        //hotelImages.slidePrev();
    });
    $('#hotel-images-container .swiper-button-next').click(function () {
        //hotelImages.slideNext();
    });

    var resize = function () {
        $('#hotelImagesBig, [id^="hotel-room-"]').each(function (index, el) {
            $(el).height($(el).width() / 1.7);
        });
    };

    window.onresize = resize;
    resize();
    $('.swiper-slide a').colorbox({
        maxWidth: '100%',
        maxHeight: '100%'
    });
    /* Слайдер картинки отеля */

    $('.input-daterange').datepicker({
        language: LANG,
        format: t('datesFormat')
    });

    if (typeof window.dateFrom != 'undefined') {
        $('#dateFrom').datepicker('setDate', new Date(window.dateFrom));
        $('#dateFrom').datepicker('update');
    } else {
        $('#dateFrom').datepicker('setDate', new Date());
        $('#dateFrom').datepicker('update');
    }

    if (typeof window.dateTo != 'undefined') {
        $('#dateTo').datepicker('setDate', new Date(window.dateTo));
        $('#dateTo').datepicker('update');
    } else {
        $('#dateTo').datepicker('setDate', moment().add(7, 'days').toDate());
        $('#dateTo').datepicker('update');
    }

});