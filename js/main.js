
$(document).ready(function() {


    /*---------------------------------------------*
     * Preloader
     ---------------------------------------------*/

    let removePreloader = function () {
        $(".loaded").fadeOut();
        $(".preloader").delay(900).fadeOut("slow");
    }

    window.addEventListener
        ? window.addEventListener("load", removePreloader,false)
        : window.attachEvent && window.attachEvent("onload", removePreloader);

});
