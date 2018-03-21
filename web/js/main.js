/* go to top */
window.onscroll = function() {scrollFunction()};

if(document.querySelector(".goToTop")) {
    document.querySelector(".goToTop").addEventListener('click', function (e) {
        topFunction();
    });
}


function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.querySelector('.goToTop').classList.remove('down');
        $('.goHome').removeClass('large');
    } else {
        document.querySelector('.goToTop').classList.add('down');
        $('.goHome').addClass('large');
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}


/* SMOOTH SCROLLING */

$('a[href*="#"]')           // Select all links with hashes
    .not('[href="#"]')      // Remove links that don't actually link to anything
    .not('[href="#0"]')     //
    .click(function(event) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname
        ) {
            var target = $(this.hash);  // Figure out element to scroll to
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

            if (target.length) {// Does a scroll target exist?
                event.preventDefault();// Only prevent default if animation is actually gonna happen
                var headerOffset = ($('header').css('position') === 'fixed') ? $('header').css('height').slice(0, -2) : 0;
                $('html, body').animate({
                    scrollTop: target.offset().top - headerOffset
                }, 500, function() {
                });
            }
        }
    });
