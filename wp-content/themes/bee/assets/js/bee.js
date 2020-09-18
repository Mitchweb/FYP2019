jQuery(document).ready(function($) {
    $("header").on("click", "#header__open-nav-button", function() {
        var a = $("#header__nav-container");
        a.addClass("is-visible");
        a.removeClass("is-not-visible");
    });
    $("header").on("click", "#header__close-nav-button", function() {
        var a = $("#header__nav-container");
        a.addClass("is-not-visible");
        a.removeClass("is-visible");
    });
});