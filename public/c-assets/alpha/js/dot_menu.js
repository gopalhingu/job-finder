function showDotMenu(id) {
    "use strict";
    document.getElementById(id).classList.toggle("show");
}
window.onclick = function(event) {
    "use strict";
    if (!event.target.matches('.dotMenudropbtn, .dotMenudropbtn li')) {
        var dotmenus = document.getElementsByClassName("dotmenu-content");
        var i;
        for (i = 0; i < dotmenus.length; i++) {
            var openDotMenu = dotmenus[i];
            if (openDotMenu.classList.contains('show')) {
                openDotMenu.classList.remove('show');
            }
        }
    }
}
