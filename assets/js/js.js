
jQuery(document).ready(function($) {

  // Act-now fix (docs/LEGACY-CODE-REVIEW.md 0.8): Slick is only enqueued on five
  // templates + products, but this file calls $(...).slick() unconditionally five
  // times. Calling an undefined jQuery plugin method throws, and since that happened
  // at the very first call (below), every handler after it in this ready callback
  // (team modal, mobile nav, enrol modal, matrix buttons, counters) silently never
  // bound on any other page. No-op the plugin when it isn't loaded instead.
  if (!$.fn.slick) { $.fn.slick = function () { return this; }; }

  /* === Smooth Scroll API ====== */
  // Removed (16 Sep 2026, at the user's request) — this intercepted every
  // same-page anchor click and ran a 1000ms jQuery scrollTop animation
  // instead of letting the browser jump straight there, which read as a
  // sluggish delay. `html{scroll-behavior:smooth}` (_base.scss) already
  // gives every anchor link — including the ones this used to handle,
  // like Get Funded's #programmes — a real native smooth scroll with no
  // JS at all, so this isn't needed instead of it.
  /*
  $('a[href*=\\#]:not([href=\\#])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          smoothScrollTo(this.hash.replace("#",""));
          //scrollSpy( window.scrollTop() )
          return false;
      }
  });


  function smoothScrollTo(target) {
      var _taget = $('#'+target);
      var offset = 50;
      var size = $(window).width();
      var innerHeight = $(window).height();
      if (_taget.length) {
          $('html,body').animate({
              scrollTop: (_taget.offset().top - offset)
              }, 1000);

      }
  }
  */

  $('.header__nav-toggle').bind('click', function(e){
     $('body').toggleClass('m-active');
     return false;
  });


  $('.m-cat-toggle').bind('click', function(e){
      $('.m-nav-toggle').toggleClass('active');
      return false;
  });

  // -- Card Toggler
 
  var cardsToToggle;
  $('.toggles-cards li a').bind('click', function(e){

    $('.toggles-cards li a').removeClass('active');

    $(this).addClass('active');

    // -- Selected Term
    var selected_term = $(this).parent().attr('class');



    // -- Cache the cards
    if( !cardsToToggle ) {
      cardsToToggle = $('.cards li');
    }

    if( !selected_term ) {
      cardsToToggle.each( function(i,e){
        $(this).removeClass('hidden');
    
      });
      return false;
    }

    // -- Turn off the relevant cards
    console.log( "===================" );
    cardsToToggle.each( function(i,e){
      var terms = $(this).attr('class');
    
      if( terms.indexOf(selected_term) == -1 ) {
          $(this).addClass('hidden');
      } else {
        $(this).removeClass('hidden');
        console.log( "Matched: " + $('h3', $(this)).text() );
      }


    });

    return false;
  });


  /// --- Logo Scroller 


  // -- Reviews Slider
  $('.stories-slider').slick({
      dots: false,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      slidesToScroll: 1,
      fade: true,
      prevArrow: '<button class="slick__prev"><i class="fas fa-arrow-left"><i></button>',
      nextArrow: '<button class="slick__next"><i class="fas fa-arrow-right"><i></button>',
      responsive: [
          {
            breakpoint: 1300,
            settings: {
              arrows: false,
              dots: true,
            }
          }
      ]
    });

  // -- Success Stories Sliders
  $('.testimonial--slider').slick({
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      arrows: false,
        
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
            infinite: true,
            variableWidth: false
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            variableWidth: false
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });


    // -- responsive sliders
    var team_modal = $('.team-modal');
    if( $(window).width() < 768 ) {
      $('.team__member').bind('click', function(e){

        var img = $('img', $(this)).attr('src');
        var h2 = $('h3', $(this)).text();
        var h4 = $('h4', $(this)).text();
        var socials = $('ul', $(this)).clone();
        var content = $('figcaption', $(this)).html();

        $('img', team_modal).attr('src', img);
        $('h2', team_modal).text( h2 );
        $('h3', team_modal).text( h4 );
        $('.team-modal__contents', team_modal).html( content );
        $('.team-modal__social', team_modal).html("");
        $('.team-modal__social', team_modal).append( socials );

        team_modal.addClass('active');
        return false;
      });
      $('.team__member--exit').bind('click', function(e){
        team_modal.removeClass('active');
        return false;
      });
    }


   

    if( $(window).width() < 1200 ) {

        $('.slick-m').slick({
          infinite: false,
          speed: 300,
          slidesToShow: 1,
          slidesToScroll: 2,
          arrows: false,
          dots: true,
            
          responsive: [
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: true,
                variableWidth: false
              }
            },
            {
              breakpoint: 620,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                variableWidth: false
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
          ]
        });

        $('.process--track').slick({
          infinite: false,
          speed: 300,
          slidesToShow: 2,
          slidesToScroll: 1,
          arrows: false,
          dots: true,
            
          responsive: [
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: false,
                variableWidth: false
              }
            },
            {
              breakpoint: 620,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: false,
                variableWidth: false
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
          ]
        });

        

      }


      $('.m-control').bind('click', function(e){

          // -- get the width of the table that is active
          var activeTable = $('.table--data table:not(.hidden)');
          var activeTableWidth = activeTable.width();

          

          // -- get current Scroll Left
          var scollLeft = $('.table--data').scrollLeft();

          console.log( scollLeft );


          if( scollLeft+170 > activeTableWidth ) {
            $('.table--data').animate({ scrollLeft : (activeTableWidth-170) });
          } else {
            $('.table--data').animate({ scrollLeft : (scollLeft+170) });
          }

          

          
      });



    // -- Logos Slider
    $('.logos').slick({
      speed: 5000,
      autoplay: true,
      autoplaySpeed: 0,
      centerMode: true,
      cssEase: 'linear',
      slidesToShow: 1,
      slidesToScroll: 1,
      variableWidth: true,
      infinite: true,
      initialSlide: 1,
      arrows: false,
      buttons: false
    });

    // -- Mobile Nav
    var linksAdded = false;
    function initMobileNav() {

      if( $(window).width() < 1190 ) {

   

          $('.header nav li.menu-item-has-children').each( function(i,e){

            // -- create new link
            if( ! linksAdded ) {
              var clone = $('> a', $(this)).clone();
              clone.text("All Products");
              var new_li = $("<li></li>");
              new_li.append( clone );
              $('> ul', $(this)).prepend( new_li );
            }
             
          

            // -- disable top level nav
            $('> a', $(this)).bind('click', function(e){
                $(this).parent().toggleClass('active');
                return false;
            })

          });

          linksAdded = true;
      }


     
    }


    $(window).resize( function(){
      initMobileNav();
    });

    initMobileNav();

   
    // -- Modal for enrol
    $('[data-action="enrol"]').bind('click', function(e){
       $('.modal').addClass('active');
       return false;
    });



    // -- Trade Table Matrix

    var tradeMatrix = $('.trade-matrix');

    $('.button--person').bind('click', function(e){
      $(this).addClass('active');
      $('.table-filter--amount .button').removeClass('active');
      $('.table-filter--amount .button').eq(1).addClass('active');
      $('.button--online').removeClass('active');
      tradeMatrix.addClass('trade-matrix--person');

      $('.table--data table').addClass('hidden');
      $('table.opt-person-25').removeClass('hidden');
      $('.opt-filter').addClass('hidden');
      $('.opt-filter.opt-online-25').removeClass('hidden');

      $('.opt-action').addClass('hidden');
      $('.opt-action.opt-person-25').removeClass('hidden');

      return false;
    });

    $('.button--online').bind('click', function(e){
      $(this).addClass('active');
      $('.button--person').removeClass('active');
      tradeMatrix.removeClass('trade-matrix--person');

      $('table.opt-person-25').addClass('hidden');
      $('.table--data table').eq(1).removeClass('hidden');
      $('.opt-filter').addClass('hidden');
      $('.opt-filter.opt-online-25').removeClass('hidden');

      $('.opt-action').addClass('hidden');
      $('.opt-action.opt-online-25').removeClass('hidden');

      return false;
    });


    $('.button-tenk').bind('click', function(e){

      $('.table-filter--amount .button').removeClass('active');
      $(this).addClass('active');
      $('.table--data table').addClass('hidden');
      $('.table--data table').eq(0).removeClass('hidden');
      $('.opt-filter').addClass('hidden');
      $('.opt-filter.opt-online-10').removeClass('hidden');

      $('.opt-action').addClass('hidden');
      $('.opt-action.opt-online-10').removeClass('hidden');

      return false;
    });


    $('.button-twentyk').bind('click', function(e){

      $('.table-filter--amount .button').removeClass('active');
      $(this).addClass('active');
      $('.table--data table').addClass('hidden');
      $('.opt-filter').addClass('hidden');

      if( $('.button--person').hasClass('active') ) {
        $('.opt-filter.opt-person-25').removeClass('hidden');
        $('.table--data table').eq(3).removeClass('hidden');

        $('.opt-action').addClass('hidden');
        $('.opt-action.opt-person-25').removeClass('hidden');
      } else {
        $('.opt-filter.opt-online-25').removeClass('hidden');
        $('.table--data table').eq(1).removeClass('hidden');

        $('.opt-action').addClass('hidden');
        $('.opt-action.opt-online-25').removeClass('hidden');
      }
      

      return false;
    });


    $('.button-fiftyk').bind('click', function(e){

      $('.table-filter--amount .button').removeClass('active');
      $(this).addClass('active');
      $('.table--data table').addClass('hidden');
      $('.table--data table').eq(2).removeClass('hidden');
      $('.opt-filter').addClass('hidden');
      $('.opt-filter.opt-online-50').removeClass('hidden');

      $('.opt-action').addClass('hidden');
      $('.opt-action.opt-online-50').removeClass('hidden');

      return false;
    });


    $('.modal .fa-times').bind('click', function(e){
      $('.modal').removeClass('active');
      return false;
    });

    $('.button-modal').bind('click', function(e){
      var type = $(this).data('type');
      var title = "";
      if( type == "online" ) {
        title = "Online Junior Trader Programme"
         $('[name="course-type"]').attr("value", "Online")
      } else {
        title = "In-Person Junior Trader Programme"
         $('[name="course-type"]').attr("value", "In Person")
      }

      $("#apply_title").text( title );

      $('.modal').addClass('active');
      return false;
    });



    // -- Loading Numbers 
    var numbersLoaded = false;
    function changeNumbers(){
       

        $('.social-box h3').each(function (index) {

          var $this = $(this);
          var dec = $this.data("format");

          $(this).prop('Counter',0).animate({
              Counter: $this.data("count")
          }, {
            duration: 1500,
            easing: 'swing',
            step: function (now) {
                if(dec === 10) {
                      $(this).text(Math.round(now*10)/10);
                    } else {
                       $(this).text(Math.round(now*1));
                    }
                  }
              });
          });
    }


  $.fn.isInViewport = function() {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();

    return elementBottom > viewportTop && elementTop < viewportBottom;
};


$(window).on('resize scroll', function() {
    
    if( numbersLoaded ) return;

    if( $('.social-boxes').size() ) {

      if ($('.social-boxes').isInViewport()) {
        changeNumbers();
        numbersLoaded = true;
        
      } else {
          // do something else
      }

    }
    
 });


});


/***********************************************************
* 
*   Google Maps
*   Outside of main on load as Google does a callback to Initalise
*  
************************************************************/



function ginit() {

  // -- Maps
  var mapDiv = jQuery('#map');
 
  var lng = mapDiv.attr('data-lng');
  var lat = mapDiv.attr('data-lat');

// var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});

 // -- Init Google Map
 var mapOptions = {
      zoom: 18,
      center: new google.maps.LatLng(lat, lng),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      panControl: true,
      scrollwheel: false,
      streetViewControl: false,
      scaleControl: true,
      disableDefaultUI: false,
      mapTypeControl: false
  };

  var icon;
  var _marker = mapDiv.attr('data-marker');
  if( _marker ) {
      
      icon = {
          url: _marker,
          scaledSize: new google.maps.Size(70, 92), // scaled size
          origin: null,
          anchor: null,
          scale: new google.maps.Size(70,92)
      }

  } else {
      icon = '';
  }

  map = new google.maps.Map(document.getElementById(mapDiv.attr('id')), mapOptions);              
 // map.mapTypes.set('map_style', styledMap);
   //       map.setMapTypeId('map_style');	
  var marker = new google.maps.Marker({
      position:new google.maps.LatLng(lat, lng),
      map: map,
      icon:icon
  });

}
