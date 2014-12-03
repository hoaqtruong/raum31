(function($) {
  Drupal.behaviors.rockox_theme = {
    attach: function(context, settings) {
      $('.popup-youtube, .popup-vimeo, .popup-gmaps, .popup-video').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        fixedContentPos: false,
        fixedBgPos: false,
        removalDelay: 300,
        mainClass: 'mfp-fade',
        preloader: false
      });

    }
  };
})(jQuery);

(function($) {

  $(document).ready(function() {

    new WOW({
      boxClass: 'wow', // default
      animateClass: 'animated', // default
      offset: 100 // default
    }).init();




    /* Begin home slider text carousel */
    jQuery('.carousel-slider-vertial').jcarousel({
      vertical: true,
      // wrap: 'circular'
    });
    /* End home slider text carousel */

    /** make page builder section hight 100% when has class : hight100 */

    if ($(".page-builder-row-section.height100").length > 0) {
      $(".page-builder-row-section.height100").each(function() {
        var $this = $(this);
        $("#wrapper, body").css("height", "100%");
        var menuHeight = 0;
        menuHeight = $('.navbar.navbar-default').height();
        var sliderHeight = $('#wrapper').height() - menuHeight;
        var $slider = $this.find(".flexslider");

        $($this, $slider).css("height", sliderHeight);
      });
    }


    /* begin pie charts	*/
    jQuery('.pie-charts').waypoint(function(direction) {
      jQuery('.chart').easyPieChart({
        barColor: "#5ecae6",
        onStep: function(from, to, percent) {
          jQuery(this.el).find('.percent').text(Math.round(percent));
        }
      });
    }, {
      offset: function() {
        return jQuery.waypoints('viewportHeight') - jQuery(this).height() + 100;
      }
    });

    jQuery('.pie-charts').waypoint(function(direction) {
      jQuery('.green-circle').easyPieChart({
        barColor: "#bed431",
        onStep: function(from, to, percent) {
          jQuery(this.el).find('.percent').text(Math.round(percent));
        }
      });
    }, {
      offset: function() {
        return jQuery.waypoints('viewportHeight') - jQuery(this).height() + 100;
      }

    });

    jQuery('.pie-charts').waypoint(function(direction) {
      jQuery('.red-circle').easyPieChart({
        barColor: "#e11e24",
        onStep: function(from, to, percent) {
          jQuery(this.el).find('.percent').text(Math.round(percent));
        }
      });
    }, {
      offset: function() {
        return jQuery.waypoints('viewportHeight') - jQuery(this).height() + 100;
      }

    });

    jQuery('.pie-charts').waypoint(function(direction) {
      jQuery('.yellow-circle').easyPieChart({
        barColor: "#f6c715",
        onStep: function(from, to, percent) {
          jQuery(this.el).find('.percent').text(Math.round(percent));
        }
      });
    }, {
      offset: function() {
        return jQuery.waypoints('viewportHeight') - jQuery(this).height() + 100;
      }

    });

    jQuery('.pie-charts').waypoint(function(direction) {
      jQuery('.purple-circle').easyPieChart({
        barColor: "#7251a2",
        onStep: function(from, to, percent) {
          jQuery(this.el).find('.percent').text(Math.round(percent));
        }
      });
    }, {
      offset: function() {
        return jQuery.waypoints('viewportHeight') - jQuery(this).height() + 100;
      }

    });


    /*End pie charts*/

    jQuery('.our-clients .flipInY').appear();
    jQuery(document.body).on('appear', '.our-clients .flipInY', function(e, $affected) {
      var fadeDelayAttr;
      var fadeDelay;
      jQuery(this).each(function() {


        if (jQuery(this).data("delay")) {
          fadeDelayAttr = jQuery(this).data("delay")
          fadeDelay = fadeDelayAttr;
        } else {
          fadeDelay = 0;
        }
        jQuery(this).delay(fadeDelay).queue(function() {
          jQuery(this).addClass('animated').clearQueue();
        });
      })
    });

// contact form error vaidation hide error span
    $('.form-item.webform-component').click(function() {
      $(this).find('span.error').hide();
    });
    // contact map
    $(".map-title h4 span").click(function(e) {
      $(".map iframe").slideToggle();
      $(".map-title h4 span i").toggleClass("fa-angle-up fa-angle-down");
    });

    jQuery(window).scroll(function() {
      if (jQuery(window).scrollTop() > 200) {
        jQuery("#back-to-top").fadeIn(200);
      } else {
        jQuery("#back-to-top").fadeOut(200);
      }
    });

    jQuery('#back-to-top, .back-to-top').click(function() {
      jQuery('html, body').animate({scrollTop: 0}, '800');
      return false;
    });



    /** ===================== Menu =====================*/
    var onepagerNavClass = "navbar-fixed-top";
    var $onepagerNav = $(".main-navigation");

    var scrollOffset = 0;
    var navHeightSpecial = 0;
    navHeightSpecial = parseInt($('nav.navbar').height());

    if (!($.browser.mobile)) {

      // ipad landscape
      if ($(window).width() < 800) {
        navHeightSpecial = parseInt($('nav.navbar').height());
      }

    } else {
      scrollOffset = parseInt($('nav.navbar').height());
    }
    $('.main-navigation ul.menu li a').click(function() {

      // if mobile and menu open - hide it after click
      var $togglebtn = $("#toggle")

      if (($togglebtn.is(":checked"))) {
        $(".top-bar label.toggle").trigger("click");
      }

      var $this = $(this);

      var content = $this.attr('href');
      if (content.indexOf("#") > -1) {

        var myUrl = content.substring(content.indexOf("#") + 1);

        var $content_id = $('#' + myUrl);

        if ($($content_id).length > 0) {

          if (myUrl !== '') {

            if ($.browser.mobile) {

              navHeightSpecial = parseInt($('nav.navbar').height());
            }

            var goPosition = $content_id.offset().top + scrollOffset - navHeightSpecial;

            $('html,body').stop().animate({scrollTop: goPosition}, 1000, 'easeInOutExpo', function() {
              $this.closest("li").addClass("active");
            });


          } else {
            window.location = content;
          }

          return false;
        }
      }
    });





    $(window).on('scroll', function() {

      var menuEl, mainMenu = $onepagerNav.find('ul.menu'), mainMenuHeight = mainMenu.outerHeight() + 5;
      var menuElements = mainMenu.find('a');

      var scrollElements = menuElements.map(function() {

        var $this = $(this);
        var content = $this.attr('href');

        var myUrl;
        if (content.indexOf("#") > -1) {
          myUrl = content.substring(content.indexOf("#") + 1);
          if (myUrl !== '') {
            myUrl = '#' + myUrl;
            var item = $(myUrl);
            if (item.length) {
              return item;
            }

          }
        }

      });

      var fromTop = $(window).scrollTop() + mainMenuHeight;

      var currentEl = scrollElements.map(function() {
        if ($(this).offset().top < fromTop) {
          return this;
        }
      });

      currentEl = currentEl[currentEl.length - 1];
      var id = currentEl && currentEl.length ? currentEl[0].id : "";

      if (menuEl !== id && id) {
        mainMenu.find('li.active-trail').removeClass('active-trail');
        menuElements.parent().removeClass("active").end().filter("a[href$='" + id + "']").parent().addClass("active");
      }

      var scroll = $(window).scrollTop();


    });

    /*===========end menu========*/

    /* scrollto function*/

    $('.scrollto').bind('click.scrollto', function(e) {
      e.preventDefault();

      var target = this.hash,
              $target = $(target);

      $('html, body').stop().animate({
        'scrollTop': $target.offset().top - 0
      }, 900, 'swing', function() {
        window.location.hash = target;
      });
    });
    /* end scrollto function */



    //YOUR CUSTOM CODE HERE...



    /*============== Begin portfolio loading ajax =================================*/

    $('a.node-ajax-click').click(function() {
      var node_url = $(this).attr('href');
      var current_item = $(this);
      rockox_node_ajax_load(current_item, node_url);
      return false;
    });
    $('.node-ajax-button-close').live('click', function() {
      var $node_ajax_loader = $(this).parents('.node-ajax-loader');
      $node_ajax_loader.find('#node-ajax-wrapper').addClass('animated bounceOutDown');
      $node_ajax_loader.hide('slow');
      $node_ajax_loader.parents('.page-builder-row-section').find('.isotope-element').removeClass('active-ajax');
      $node_ajax_loader.parents('.page-builder-row-section').find('.views-row').removeClass('active-ajax');
    });


    /*================ End portfolio loading via ajax ==========================*/



    /*============== Begin Shop, product loading ajax =================================*/
    $('.product a').click(function() {
      var node_url = $(this).attr('href');
      var current_item = $(this);
      loadProduct(current_item, node_url);
      return false;

    });

    $('.product-close').live('click', function() {
      $('#product-ajax-load').find('#product-content-wrapper').addClass('animated bounceOutDown');
      $('#product-ajax-load').hide('slow');
      $('.products').find('.product').removeClass('active-ajax');
    });


  });

  function rockox_node_ajax_load(current_item, node_url) {
    var $node_ajax_loader = current_item.parents('.page-builder-row-section').find('.node-ajax-loader');
    $node_ajax_loader.html('<div class="ajax-spin-wrapper"><span class="fa-li fa fa-spinner fa-spin"></span></div>');
    $node_ajax_loader.show();
    $node_ajax_loader.load(node_url + ' #node-ajax-wrapper', function(xhr, statusText, request) {
      if (statusText == "success") {
        Drupal.attachBehaviors();
        $node_ajax_loader.find('#node-ajax-wrapper').addClass('container animated bounceInUp');
        $node_ajax_loader.fadeIn(400);
        current_item.parents('.page-builder-row-section').find('.isotope-element').removeClass('active-ajax');
        current_item.parents('.page-builder-row-section').find('.views-row').removeClass('active-ajax');

        current_item.parents('.views-row').addClass('active-ajax');
        current_item.parents('.isotope-element').addClass('active-ajax');
        $('<div class="node-close-wrapper"><a href="javascript:void(0);" class="node-ajax-button-close fa fa-times"></a></div>').appendTo($node_ajax_loader);
      }

      if (statusText == "error") {
        current_item.parents('.page-builder-row-section').find('.isotope-element').removeClass('active-ajax');
        current_item.parents('.page-builder-row-section').find('.views-row').removeClass('active-ajax');
      }

    });
  }
  function loadProduct(current_item, url) {
    var $product_ajax_load = $('#product-ajax-load');
    $product_ajax_load.html('<div class="ajax-spin-wrapper"><span class="fa-li fa fa-spinner fa-spin"></span></div>');
    $product_ajax_load.show();

    $product_ajax_load.load(url + ' #product-content-wrapper', function(xhr, statusText, request) {
      if (statusText == "success") {
        Drupal.attachBehaviors();
        $product_ajax_load.find('#product-content-wrapper').addClass('animated bounceInUp');
        $product_ajax_load.fadeIn(400);
        $('.products').find('.product').removeClass('active-ajax');
        current_item.parents('.product').addClass('active-ajax');
        $('<div class="product-close-wrapper"><a href="javascript:void(0);" class="product-close fa fa-times"></a></div>').appendTo($product_ajax_load);
      }

      if (statusText == "error") {
        current_item.parents('.products').find('.product').removeClass('active-ajax');
      }

    });

  }



})(jQuery);