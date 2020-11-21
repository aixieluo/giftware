define(['jquery', 'bootstrap', 'frontend'], function ($, undefined, Frontend) {
    var Controller = {
        index: function () {
            $('.carousel').carousel({
                interval: 5000 //changes the speed
            });
        },
    };

    return Controller;
});
