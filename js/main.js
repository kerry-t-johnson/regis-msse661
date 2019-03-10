
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

function clearZ(item) {
    $(item).removeClass (function (index, className) {
        return (className.match (/(^|\s)z-depth-\S+/g) || []).join(' ');
    });
}

function setZ(item, i) {
    let zDepth = 'z-depth-' + i;
    console.log('setting z index: ' + zDepth);
    clearZ(item);
    $(item).addClass(zDepth);
}

function pulseItem(item, loops) {
    const delay = 200;
    let currentDelay = delay;

    loops = (typeof loops == 'undefined') ? 1 : loops;

    for(let lp = 0; lp < loops; ++lp) {
        for (let i = 1; i <= 5; i++) {
            setTimeout(function () {
                setZ(item, i);
            }, currentDelay);
            currentDelay += delay;
        }
        for (let i = 5; i >= 1; i--) {
            setTimeout(function () {
                setZ(item, i);
            }, currentDelay);
            currentDelay += delay;
        }
    }
    setTimeout(function() { clearZ(item); }, currentDelay);
}

function prettyPrintDate(date) {
    return $.format.prettyDate(date);
}

$.views.helpers({'prettyPrintDate': prettyPrintDate});

