$(document).ready(function () {
    /*JS menu bars*/
    $('.navigation__bars').click(function () {
        $('.navigation__menu').toggleClass('show');
        $('body').addClass('fixed__scroll');
    });

    $('.navigation__close').click(function () {
        $('.navigation__menu').toggleClass('show');
        $('body').removeClass('fixed__scroll');
    });


    /*----------------------------Page Trang chủ--------------------------------*/
    /*JS Partners*/
   /* $('.banner__box').owlCarousel({
        navigation : true, // Show next and prev buttons
        slideSpeed : 500,
        dots:true,
        items : 1,
        loop:true,
    });*/

    /*Partner slider*/
    $('.partner__box').owlCarousel({
        navigation : true, // Show next and prev buttons
        slideSpeed : 500,
        autoWidth:true,
        dots:false,
        items : 4,
        nav : true,
        navText: ["<img src='images/right.svg'>","<img src='images/right.svg'>"],
        loop:true,
        responsive : {
            // breakpoint from 0 up
            0 : {
                items : 1,
                nav : false,
                dots:true
            },
            // breakpoint from 768 up
            768 : {
                items : 3,
            }
        }
    });

    /*reason slider*/
    if ( $(window).width() < 767 ) {
        reasonCarousel();
    }
    $(window).resize(function() {
        if ( $(window).width() < 767 ) {
            reasonCarousel();
        } else {
            stopCarousel();
        }
    });

    function reasonCarousel(){
        $(".reason__box").owlCarousel({
            navigation : true, // Show next and prev buttons
            slideSpeed : 500,
            autoWidth:true,
            dots:true,
            items : 1,
            loop:true,
        });
    }
    function stopCarousel() {
        var owl = $('.reason__box');
        owl.trigger('destroy.owl.carousel');
        owl.addClass('off');
    }

    /*JS FAQ*/
    $('.faq__up').click(function () {
        $(this).parents('.faq__item').toggleClass('active');
        $(this).parents('.faq__item').find('.faq__content').slideToggle();
    });
    $('.faq__title').click(function () {
        $(this).parents('.faq__item').toggleClass('active');
        $(this).parents('.faq__item').find('.faq__content').slideToggle();
    });

    /*----------------------------End Page Trang chủ--------------------------------*/

    /*----------------------------Page Cách sử dụng--------------------------------*/
    if ( $(window).width() < 767 ) {
        pointCarousel();
    }
    $(window).resize(function() {
        if ( $(window).width() < 767 ) {
            pointCarousel();
        } else {
            stopPointCarousel();
        }
    });

    function pointCarousel(){
        $(".point__box").owlCarousel({
            navigation : true, // Show next and prev buttons
            slideSpeed : 500,
            autoWidth:true,
            dots:true,
            items : 1,
            loop:true,
        });
    }
    function stopPointCarousel() {
        var owl = $('.point__box');
        owl.trigger('destroy.owl.carousel');
        owl.addClass('off');
    }

    /*Toggle box detail*/
    $('.point__item__number').click(function () {
        $(this).parent().find('.point__item__content__ex').slideToggle(500);
        $(this).parent().children('faq__up').toggleClass('active');
    });
    $('.point__item__icon').click(function () {
        $(this).parent().find('.point__item__content__ex').slideToggle(500);
        $(this).parent().children('faq__up').toggleClass('active');
    });
    $('.point__item__content').click(function () {
        $(this).parent().find('.point__item__content__ex').slideToggle(500);
        $(this).parent().children().find('.faq__up').toggleClass('active');
    });
    $('.point__item__icon_text').click(function () {
        $(this).parent().find('.point__item__content__ex').slideToggle(500);
        $(this).parent().children('faq__up').toggleClass('active');
    });
    /*----------------------------End Page cách sử dụng--------------------------------*/


    /*----------------------------Page đối tác--------------------------------*/
    if ( $(window).width() < 767 ) {
        startBeneCarousel();
    }

    $(window).resize(function() {
        if ( $(window).width() < 767 ) {
            startBeneCarousel();
        } else {
            stopBeneCarousel();
        }
    });

    function startBeneCarousel(){
        $(".partnersBenefits__box").owlCarousel({
            navigation : true, // Show next and prev buttons
            slideSpeed : 500,
            autoWidth:true,
            dots:true,
            items : 1,
            loop:true,
        });
    }
    function stopBeneCarousel() {
        var owl = $('.owl-carousel');
        owl.trigger('destroy.owl.carousel');
        owl.addClass('off');
    }
    /*----------------------------End Page đối tác--------------------------------*/

});
