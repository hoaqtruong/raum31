		jQuery(function() {
			//tabs
			jQuery('#tab-btn1').click(function(e) {
				jQuery('#tab1').fadeIn(500);
				jQuery('#tab2').fadeOut(500);
				jQuery('#tab3').fadeOut(500);
				jQuery('#tab4').fadeOut(500);
				jQuery('#tab5').fadeOut(500);
				jQuery('.services-sec .tab1 span i').addClass('open');
				jQuery('.services-sec .tab2 span i, .services-sec .tab3 span i, .services-sec .tab4 span i, .services-sec .tab5 span i').removeClass('open');
			});
			jQuery('#tab-btn2').click(function(e) {
				jQuery('#tab1').fadeOut(500);
				jQuery('#tab2').fadeIn(500);
				jQuery('#tab3').fadeOut(500);
				jQuery('#tab4').fadeOut(500);
				jQuery('#tab5').fadeOut(500);
				jQuery('.services-sec .tab2 span i').addClass('open');
				jQuery('.services-sec .tab1 span i, .services-sec .tab3 span i, .services-sec .tab4 span i, .services-sec .tab5 span i').removeClass('open');
			});
			jQuery('#tab-btn3').click(function(e) {
				jQuery('#tab1').fadeOut(500);
				jQuery('#tab2').fadeOut(100);
				jQuery('#tab3').fadeIn(500);
				jQuery('#tab4').fadeOut(500);
				jQuery('#tab5').fadeOut(500);
				jQuery('.services-sec .tab3 span i').addClass('open');
				jQuery('.services-sec .tab1 span i, .services-sec .tab2 span i, .services-sec .tab4 span i, .services-sec .tab5 span i').removeClass('open');
			});
			jQuery('#tab-btn4').click(function(e) {
				jQuery('#tab1').fadeOut(500);
				jQuery('#tab2').fadeOut(500);
				jQuery('#tab3').fadeOut(500);
				jQuery('#tab4').fadeIn(500);
				jQuery('#tab5').fadeOut(500);
				jQuery('.services-sec .tab4 span i').addClass('open');
				jQuery('.services-sec .tab1 span i, .services-sec .tab2 span i, .services-sec .tab3 span i, .services-sec .tab5 span i').removeClass('open');
			});
			jQuery('#tab-btn5').click(function(e) {
				jQuery('#tab1').fadeOut(500);
				jQuery('#tab2').fadeOut(500);
				jQuery('#tab3').fadeOut(500);
				jQuery('#tab4').fadeOut(500);
				jQuery('#tab5').fadeIn(500);
				jQuery('.services-sec .tab5 span i').addClass('open');
				jQuery('.services-sec .tab1 span i, .services-sec .tab2 span i, .services-sec .tab3 span i, .services-sec .tab4 span i').removeClass('open');
			});
			
			
			//add active class
			jQuery('.tabs-btn li a').click(function(e) {
				jQuery('.tabs-btn li a').removeClass('active');
				jQuery(this).addClass('active');
			});
			
			
			
			//top bar colors
			jQuery('.top-bar-colors li a#orange').click(function(e) {
				jQuery('.header-top').removeClass('green red blue purple');
				jQuery('.header-top').addClass('orange');
			});
			jQuery('.top-bar-colors li a#red').click(function(e) {
				jQuery('.header-top').removeClass('green orange blue purple');
				jQuery('.header-top').addClass('red');
			});
			jQuery('.top-bar-colors li a#green').click(function(e) {
				jQuery('.header-top').removeClass('orange red blue purple');
				jQuery('.header-top').addClass('green');
			});
			jQuery('.top-bar-colors li a#blue').click(function(e) {
				jQuery('.header-top').removeClass('green red orange purple');
				jQuery('.header-top').addClass('blue');
			});
			jQuery('.top-bar-colors li a#purple').click(function(e) {
				jQuery('.header-top').removeClass('green red blue orange');
				jQuery('.header-top').addClass('purple');
			});
			jQuery('.top-bar-colors li a#gray').click(function(e) {
				jQuery('.header-top').removeClass('green red blue orange purple');
			});
			
			//add active class in top bar
			jQuery('.top-bar-colors li a, .footer-colors li a').click(function(e) {
				jQuery('.top-bar-colors li a, .footer-colors li a').removeClass('active');
				jQuery(this).addClass('active');
			});
			
			
			//footer colors
			jQuery('.footer-colors li a#orange2').click(function(e) {
				jQuery('.footer-large').removeClass('green red blue purple');
				jQuery('.footer-large').addClass('orange');
			});
			jQuery('.footer-colors li a#red2').click(function(e) {
				jQuery('.footer-large').removeClass('green orange blue purple');
				jQuery('.footer-large').addClass('red');
			});
			jQuery('.footer-colors li a#green2').click(function(e) {
				jQuery('.footer-large').removeClass('orange red blue purple');
				jQuery('.footer-large').addClass('green');
			});
			jQuery('.footer-colors li a#blue2').click(function(e) {
				jQuery('.footer-large').removeClass('green red orange purple');
				jQuery('.footer-large').addClass('blue');
			});
			jQuery('.footer-colors li a#purple2').click(function(e) {
				jQuery('.footer-large').removeClass('green red blue orange');
				jQuery('.footer-large').addClass('purple');
			});
			jQuery('.footer-colors li a#gray2').click(function(e) {
				jQuery('.footer-large').removeClass('green red blue orange purple');
			});
			
			
			//add active class in footer
			jQuery('.footer-colors li a').click(function(e) {
				jQuery('.footer-colors li a').removeClass('active');
				jQuery(this).addClass('active');
			});
			
			//------------------------------------ EXPERIENCE -----------------------------------//
			
			jQuery('.experience .row .experience-content, .experience .row .devices').appear();
			jQuery(document.body).on('appear', '.experience .row .experience-content, .experience .row .devices', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			//------------------------------------ SERVICES -----------------------------------//
			
			jQuery('.services-sec .row').appear();
			jQuery(document.body).on('appear', '.services-sec .row', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).children('*').each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			
			//------------------------------------ TRI SECS -----------------------------------//
			
			jQuery('.tri-secs .col-md-6').appear();
			jQuery(document.body).on('appear', '.tri-secs .col-md-6', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			
			//------------------------------------ TEAM -----------------------------------//
			
			jQuery('.team .row .col-md-4').appear();
			jQuery(document.body).on('appear', '.team .row .col-md-4', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			//------------------------------------ TABLE -----------------------------------//
			
			jQuery('.tables .row .col-md-3').appear();
			jQuery(document.body).on('appear', '.tables .row .col-md-3', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			
			//------------------------------------ PORTFOLIO -----------------------------------//
			
			jQuery('#portfolio-items-wrap').appear();
			jQuery(document.body).on('appear', '#portfolio-items-wrap', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			//------------------------------------ SHOP -----------------------------------//
			
			jQuery('.products .product').appear();
			jQuery(document.body).on('appear', '.products .product', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			//------------------------------------ OUR CLIENTS -----------------------------------//
			
			jQuery('.our-clients .flipInY').appear();
			jQuery(document.body).on('appear', '.our-clients .flipInY', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			
			//------------------------------------ CONTACT US -----------------------------------//
			
			jQuery('.contact-us .form').appear();
			jQuery(document.body).on('appear', '.contact-us .form', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			//------------------------------------ SOCIAL -----------------------------------//
			
			jQuery('.social li').appear();
			jQuery(document.body).on('appear', '.social li', function(e, $affected) {
				var fadeDelayAttr;
				var fadeDelay;
				jQuery(this).each(function(){
				
	
					if (jQuery(this).data("delay")) {
						fadeDelayAttr = jQuery(this).data("delay")
						fadeDelay = fadeDelayAttr;				
					} else {
						fadeDelay = 0;
					}			
					jQuery(this).delay(fadeDelay).queue(function(){
						jQuery(this).addClass('animated').clearQueue();
					});			
				})			
			});
			
			
			
			
			
			//------------------------------------ SWITCHER -----------------------------------//
			
			//jQuery('#dark').click(function(e) {
//				jQuery(this).toggleClass('active');
//				if($('#dark').hasClass('active'))
//					$('#dark-css').attr("href", "css/dark_theme.css"),
//					$('.parallax-main-banner').css("background-position", "0 0");
//				else
//					$('#dark-css').attr("href", "");
//				});
			
			
			
			
		})