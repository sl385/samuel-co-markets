
jQuery(document).ready(function($) {

    var last_insert_point = "";
   
    $('.view_progression_data_toggle').bind('click', function(e){

        var insert_point = $(this).parent().parent().next();
        if( last_insert_point ) {
            last_insert_point.html("");
        }

        last_insert_point = insert_point;
        

        var user = $(this).data('user');
        var course = $(this).data('course');

        if( user && course ) {

             // -- Video has finished
            $.ajax({
                type : "POST",
                url : ajaxurl,
                data : {
                    action: "user_lesson_progress",
                    course: course,
                    user: user,
                },
                success: function(response) {
                
                    insert_point.html("<td colspan='3'>" + response + "</td>");
                
                },
                error: function(error) {
                    alert("Whoops! There was an error. Please refresh the page when ready");
                }
            });   

        } else {
            alert( "Unable to load data at this time")
        }

        

       return false;

       
    });

});