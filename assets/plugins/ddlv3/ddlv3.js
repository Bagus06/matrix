$(function () {
    // whenever we hover over a menu item that has a submenu
    $('li.dd-parent').on('mouseover', function (e) {
        let $menuItem = $(this),
            $submenuWrapper = $('> .dd-wrapper', $menuItem);
        $submenuWrapper.css({
            top: $menuItem.position().top,
            left: $menuItem.position().left + ($menuItem.width())
        })
    });


    $('.btn-dd').on('click', function (e) {
        if ($(".dd").is(":hidden")) {
            $('.dd').show()
        } else {
            $('.dd').hide()
        }
        $('.dd').css({
            top: ($(this).offset().top + 30),
            left: $(this).offset().left
        })

        e.stopPropagation();
    })

    $(window).click(function (e) {
        if ($(".dd").is(":visible")) {
            $('.dd').hide()
        }
    })
});