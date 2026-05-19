window.addEventListener('beforeunload', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});

window.addEventListener('load', function() {
    const scrollPosition = localStorage.getItem('scrollPosition');

    if (scrollPosition !== null) {
        window.scrollTo({
            top: parseInt(scrollPosition),
            behavior: 'smooth'
        });
    }
});