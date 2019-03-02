
$(document).ready(function() {


    /*---------------------------------------------*
     * Preloader
     ---------------------------------------------*/

    $(window).on('load', function () {
        $(".loaded").fadeOut();
        $(".preloader").delay(1000).fadeOut("slow");
    });

});
