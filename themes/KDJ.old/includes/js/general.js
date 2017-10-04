(function ($) {
    "use strict";
    $(document).ready(function () {
        $("#main_menu > li > ul").prev("a").append('<span class="mmenu_dd_icon"></span>');

        $(".wishlist_table").addClass('table-order');
        $(".form-edit-adress-items p").each(function (index) {
            var placeholder=$(this).find('label').text();
            $(this).find('.input-text').addClass('input-add').attr('placeholder',placeholder);
            $(this).find('label').remove();
        });

        $('.big_slider').bxSlider({
            pager: false,
            speed: 700,
            mode: "fade",
	    auto: true,
            prevText: '',
            nextText: ''
        });

        $('.small_slider').bxSlider({
            pager: false,
            prevText: '',
            nextText: ''
        });

        $('.look_book_slider_1').bxSlider({
            pager: false,
            mode: "fade",
            prevText: '',
            nextText: ''
        });

        $('.look_book_slider_2').bxSlider({
            pager: false,
            mode: "fade",
            prevText: '',
            nextText: ''
        });
        
        $('.blog_whats_new').bxSlider({
            pager: false,
            mode: "fade",
            prevText: '',
            nextText: ''
        });


        $('#main_menu li').hover(function () {
            if ($('.hamburger_menu:visible')) {
                $('.hamburger_menu').removeClass('active');
            }
            if ($('.search_box:visible')) {
                $('.search_box').removeClass('active');
            }
        });

        $('.single-image-gallery').click(function () {
            var url = $(this).attr('href');
            $('#single-image-big img').attr('src', url);
            return false;
        });

        $('.search_icon').click(function () {
            $('.search_box').toggleClass('active');
            if ($('.search_box:visible')) {
                $('.search_box input[type="text"]').focus();
            } else {
                $('.search_box input[type="text"]').blur();
            }
            if ($('.hamburger_menu:visible')) {
                $('.hamburger_menu').removeClass('active');
            }
        });

        $('.si_create_account').click(function () {
            $(".sign_in_page_box").hide();
            $(".create_acc_page_box").show();
            return false;
        });

        $('.have_an_account').click(function () {
            $(".sign_in_page_box").show();
            $(".create_acc_page_box").hide();
            return false;
        });


        $('.hamburger_menu_icon').click(function () {
            $('.hamburger_menu').toggleClass('active');
            if ($('.search_box:visible')) {
                $('.search_box').removeClass('active');
            }
            return false;
        });

        $('.am-add-cart').click(function () {
            $('.cart').submit();
            return false;
        });


        function accordeonMenu(theTrigger, thePanel) {
            $(theTrigger).not('.locked').click(function (e) {
                var $self = $(this);
                var $expanded = $self.siblings(theTrigger + ' .expanded').not(this);

                function opentab() {
                    $self.next(thePanel).slideToggle(300, function () {
                        $self.toggleClass('expanded');
                        if (!$self.next(thePanel).is(':visible')) {
                            $self.next(thePanel).removeAttr('style');
                        }
                    });
                }

                if ($expanded.length > 0) {
                    $expanded.next(thePanel).slideUp(300, function () {
                        $expanded.removeClass('expanded');
                        opentab();
                    });
                } else {
                    opentab();
                }
                e.preventDefault();
            });
        }
        ;

        accordeonMenu('.sb_widget_tab_button', '.sb_widget_tab');
        accordeonMenu('.returns_tab', '.returns_content_box');
        accordeonMenu('.sort_refine_mob_siwtch', '.sort_refine_mob_tab');
        accordeonMenu('.shop_all_mob_switch', '.cat_sb_widget');


        if ($('.press_item_box').length > 0) {
            var $container = $('.press_item_box');
            $container.masonry({
                columnWidth: 1,
                itemSelector: '.press_item'
            });
            var send_reqest = false;
            var ajax_post_pages = 2;
            $(window).scroll(function () {
                if ($('#am-post-load').visible(true)) {
                    if (send_reqest == false) {
                        send_reqest = true;
                        var amq_data = $('#am-post-load').attr('amq-data');
                        $.ajax({
                            type: "post",
                            url: myAjax.ajaxurl,
                            data: {action: "am_load_more_post", page: ajax_post_pages, amq: amq_data},
                            success: function (response) {
                                $(".press_item_box").append(response);
                                send_reqest = false;
                                ajax_post_pages = ajax_post_pages + 1;
                                $container.masonry('reloadItems');
                                $container.masonry('layout');
                            }
                        })
                    }
                }
            });

        }


        if ($('.post_item').length > 0) {
            var send_reqest = false;
            var ajax_post_pages = 2;
            $(window).scroll(function () {
                if ($('#am-post-load').visible(true)) {
                    if (send_reqest == false) {
                        send_reqest = true;
                        var amq_data = $('#am-post-load').attr('amq-data');
                        $.ajax({
                            type: "post",
                            url: myAjax.ajaxurl,
                            data: {action: "am_load_more_blog_post", page: ajax_post_pages, amq: amq_data},
                            success: function (response) {
                                $("#posts").append(response);
                                send_reqest = false;
                                ajax_post_pages = ajax_post_pages + 1;
                            }
                        })
                    }
                }
            });

        }
        
        $('.footer_signup_link').click(function () {
            $('.footer_signup_link').css('z-index', 1);
        });

    });
    $(window).load(function () {


        var isBrowserOs = {
            Windows: function () {
                return navigator.userAgent.match(/Win/i);
            },
            MacOS: function () {
                return navigator.userAgent.match(/Mac/i);
            },
            UNIX: function () {
                return navigator.userAgent.match(/X11/i);
            },
            Linux: function () {
                return navigator.userAgent.match(/Linux/i);
            },
            iOs: function () {
                return navigator.userAgent.match(/(iPad|iPhone|iPod)/i);
            },
            Android: function () {
                return navigator.userAgent.match(/android/i);
            },
            BlackBerry: function () {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            Chrome: function () {
                return window.chrome;
            },
            Firefox: function () {
                return navigator.userAgent.match(/Firefox/i);
            },
            IE: function () {
                return navigator.userAgent.match(/MSIE/i);
            },
            Opera: function () {
                return (!!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0);
            },
            SeaMonkey: function () {
                return navigator.userAgent.match(/SeaMonkey/i);
            },
            Camino: function () {
                return navigator.userAgent.match(/Camino/i);
            },
            Safari: function () {
                return (Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0);
            }
        };

        var html_class = '';
        //OS
        if (isBrowserOs.Windows())
            html_class = 'win';
        if (isBrowserOs.UNIX())
            html_class = 'unix';
        if (isBrowserOs.MacOS())
            html_class = 'mac';
        if (isBrowserOs.Linux())
            html_class = 'linux';
        if (isBrowserOs.iOs())
            html_class = 'ios';
        if (isBrowserOs.Android())
            html_class = 'android';
        if (isBrowserOs.BlackBerry())
            html_class = 'blackberry';

        //Browser
        if (isBrowserOs.Chrome())
            html_class = html_class + ' chrome';
        if (isBrowserOs.Firefox())
            html_class = html_class + ' firefox';
        if (isBrowserOs.IE())
            html_class = html_class + ' ie';
        if (isBrowserOs.Opera())
            html_class = html_class + ' opera';
        if (isBrowserOs.SeaMonkey())
            html_class = html_class + ' seamonkey';
        if (isBrowserOs.Camino())
            html_class = html_class + ' camino';
        if (isBrowserOs.Safari())
            html_class = html_class + ' safari';

        $("html").addClass(html_class);
    });
})(jQuery);