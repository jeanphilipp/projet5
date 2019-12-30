$(document).ready(function() {
    $('.burger').click(function() {
        $('ul').toggleClass('active');
    })
})

$(document).ready(function() {
    $('#span_comment').click(function () {
        $('form').toggle();
    })
})