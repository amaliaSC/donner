$(function () {

    // *********************************** RESPONSIVE NAVBAR ***********************************

    if (window.matchMedia("(max-width: 767px)").matches) {
        $(".link").css("display", "none");

        //Au click sur le menu hamburger, on cache la liste menu si elle est visible et on la montre si elel est invisible
        $(".menu_hamburger i").click(function () {
            if ($(".link").css("display") == "none") {
                $(".link").css("display", "flex");
            } else {
                $(".link").css("display", "none");
            }
        });

        //Au click sur l'un des liens de la liste menu, on cache la liste menu
        $(".link a").click(function () {
            $(".link").css("display", "none");
        });
    }

});