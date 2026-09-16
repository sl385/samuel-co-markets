
jQuery(document).ready(function($) {

    var loader = $('.loader');
    var buttonNext = $('#button-next');
    let videoType;
    var current_lesson = "";
    var current_module = "";

    // -- Vimeo Player
    var iframe;
    var vimeo_player;
    var status = $('.status');
    var vimeoElapsed = 0;
    var videoLoaded = false;


    function loadCourseDetails( module, lesson  ) {

        //console.log( module, lesson );

        showLoader();

        // current_lesson
        if( videoLoaded ) {
            try {
                $('#video').pause();
            } catch (error) {

            }
           
        }
       //

        // -- Pass Course ID, Module Index, Lesson Index
        $.ajax({
            type : "POST",
            dataType : "json",
            url : sco.ajaxurl,
            data : {
                action: "get_course_details",
                course: sco.course,
                course_nonce: sco.nonce,
                module: module,
                lesson: lesson
            },
            success: function(response) {
               hideLoader();
              // buttonNext.attr("disabled", "disabled");
               var response_data = JSON.parse(response.data.data);
               
               $("html, body").animate({ scrollTop: 0 }, "slow");

               my_module = (response_data.module+1);
               my_lesson = response_data.lesson;
               lesson_guid = response_data.guid;

               //console.log( response_data );

                // -- type swithcer
                videoType = response_data.type;
                if( videoType != 3 ) {
                    $('[data-platform="html5"]').show();
                    $('[data-platform="vimeo"]').hide();
                    paintUI( response_data );
                } else {
                    $('[data-platform="html5"]').hide();
                    $('[data-platform="vimeo"]').show();
                    vimeoElapsed = 0;
                    initVimeoPlayer( response_data );
                }

            },
            error: function(error) {
                hideLoader();
                alert("Whoops! There was an error. Please refresh the page when ready");
            }
       });   

        // -- Get The content back and then render it
        

    }


    function initVimeoPlayer( response ) {

        $('#lesson_name').html(response.lesson_title);
        $('#lesson_content').html(response.lesson_description);

        iframe = $('#player1');
        iframe.attr('src', response.video_url + '?title=0&byline=0&portrait=0&sidedock=0' );
         // -- Vimeo Player
        
        vimeo_player = new Vimeo.Player(iframe);
        //var status = $('.status');

        // -- Vimeo Player Controls

        vimeo_player.on('pause', function() {
            //status.text('paused');
            $.ajax({
                type : "POST",
                dataType : "json",
                url : sco.ajaxurl,
                data : {
                    action: "update_lesson_progress",
                    course: sco.course,
                    course_nonce: sco.nonce,
                    module: my_module,
                    lesson: my_lesson,
                    time: vimeoElapsed,
                    lesson_guid: lesson_guid
                },
                success: function(response) {
    
                }
            });
        });

        vimeo_player.on('ended', function() {
            $.ajax({
                type : "POST",
                dataType : "json",
                url : sco.ajaxurl,
                data : {
                    action: "update_lesson_progress",
                    course: sco.course,
                    course_nonce: sco.nonce,
                    module: my_module,
                    lesson: my_lesson,
                    time: "Complete",
                    lesson_guid: lesson_guid
                },
                success: function(response) {
    
                }
            });
        });

        vimeo_player.on('timeupdate', function(data) {
            vimeoElapsed = data.seconds; 
        });

     
    }

   
    buttonNext.bind('click', function(e){

       my_lesson = my_lesson+1;

       loadCourseDetails( my_module, my_lesson  );

       return false;
       
    });

    $('.course__module header').bind('click', function(e){
        $(this).parent().toggleClass('active');
    });

    $('.module__lessons li a').bind('click', function(e){

        $('#video-container').removeClass('readytoplay');

        loadCourseDetails( $(this).data('module'), $(this).data('lesson') );
        return false;
    });
   


    function paintUI( response ) {
        //console.log( response );
        $('#lesson_name').html(response.lesson_title);
        $('#lesson_content').html(response.lesson_description);
        if( response.video_url ) {
            $('.video-container').show();
            $('#video').attr('src', response.video_url);
            // -- Right so we need now tell the video to change its icon.
            videoLoaded = true;
        } else {
           // buttonNext.removeAttr("disabled");
            $('.video-container').hide();
            videoLoaded = false;
        }

    }


    function showLoader() {
        loader.addClass('active');
    }

    function hideLoader() {
        loader.removeClass('active');
    }

   
    // -- Load Course Details
    //console.log( my_module, my_lesson );
    loadCourseDetails( my_module, my_lesson  );


    addEventListener('lesson_video_finished', function(e){ 

       
        
        $.ajax({
            type : "POST",
            dataType : "json",
            url : sco.ajaxurl,
            data : {
                action: "update_lesson_progress",
                course: sco.course,
                course_nonce: sco.nonce,
                module: my_module,
                lesson: my_lesson,
                time: "Complete",
                lesson_guid: lesson_guid
            },
            success: function(response) {

            }
        });
        

       // 

       // buttonNext.removeAttr("disabled");

    }, false);


    addEventListener('pauseVideoEvent', function(e){ 

        var pause_time = e.detail.myParam;

       // console.log( pause_time );

        $.ajax({
            type : "POST",
            dataType : "json",
            url : sco.ajaxurl,
            data : {
                action: "update_lesson_progress",
                course: sco.course,
                course_nonce: sco.nonce,
                module: my_module,
                lesson: my_lesson,
                time: pause_time,
                lesson_guid: lesson_guid
            },
            success: function(response) {

            }
        });
        
       
    }, false);


    
   

});


// ------------------ Vimeo Player 
