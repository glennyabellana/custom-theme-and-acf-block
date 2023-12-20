/**
 * Theme Scripts
 */
(function ($) {

    /*-------------------------------------------------------------------
     *
     *  Variables
     *   
     ------------------------------------------------------------------- */

    // When the user scrolls the page, execute the function
    window.addEventListener('scroll', function () {
        var header = document.getElementById('masthead');
        var sticky = header.offsetTop;

        if (window.scrollY > sticky) {
            header.classList.add("site-header__sticky");
        } else {
            header.classList.remove("site-header__sticky");
        }
    });

})(jQuery);