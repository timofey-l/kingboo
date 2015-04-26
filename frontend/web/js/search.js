function get_date(date) {
    var day = date.getDate().toString();
    if (day.length == 1) day = '0' + day;
    var month = (date.getMonth() + 1).toString();
    if (month.length == 1) month = '0' + month;
    var year = date.getFullYear();
    return year + '-' + month + '-' + day;
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
    var hotelImages = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        slidesPerView: 'auto',
        centeredSlides: true,
        paginationClickable: true,
        slideToClickedSlide: true,
        spaceBetween: 10,
        onSlideChangeStart: function (swiper) {
            $('#hotelImagesBig .big-image-div').css({
                opacity: 0
            });
        },
        onSlideChangeEnd: changeSlider
    });
    changeSlider(hotelImages);
    $('#hotel-images-container .swiper-button-prev').click(function () {
        hotelImages.slidePrev();
    });
    $('#hotel-images-container .swiper-button-next').click(function () {
        hotelImages.slideNext();
    });

    var resize = function () {
        var el = $('#hotelImagesBig');
        el.height(el.width() / 1.7);
    };

    window.onresize = resize;
    resize();
    $('.swiper-slide a').colorbox({
        maxWidth: '100%'
    });
    /* Слайдер картинки отеля */

    $('.input-daterange').datepicker({
        language: LANG,
        format: t('datesFormat')
    });

});