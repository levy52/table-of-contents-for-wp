document.addEventListener("DOMContentLoaded", function() {
    var tocLinks = document.querySelectorAll('.table-of-contents__wrapper ul a');

    tocLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault(); 
            var targetId = link.getAttribute('href'); 
            var targetElement = document.querySelector(targetId);

            if (targetElement) {
                var targetOffset = targetElement.getBoundingClientRect().top + window.scrollY;
                var duration = 500; 

                function smoothScroll() {
                    var currentTime = 0;
                    var startScrollY = window.scrollY;
                    var distance = targetOffset - startScrollY;

                    function ease(t, b, c, d) {
                        t /= d / 2;
                        if (t < 1) return c / 2 * t * t + b;
                        t--;
                        return -c / 2 * (t * (t - 2) - 1) + b;
                    }

                    function animateScroll() {
                        currentTime += 20;
                        var val = ease(currentTime, startScrollY, distance, duration);
                        window.scrollTo(0, val);
                        if (currentTime < duration) {
                            requestAnimationFrame(animateScroll);
                        }
                    }

                    animateScroll();
                }

                smoothScroll();
            }
        });
    });
});