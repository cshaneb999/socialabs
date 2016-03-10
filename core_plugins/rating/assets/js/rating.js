var rating = {
    init: function () {
        rating.interact();
        rating.display();
    },
    interact: function () {
        if ($(".rating_container").length > 0) {
            $(".rating").on("click", function () {
                var count = 0;
                var rating = $(this);
                var index = rating.index();
                var container = rating.parent();
                if (container.hasClass("disabled")) {
                    return;
                }
                container.children(".rating").removeClass("fa-star").addClass("fa-star-o");
                while (count < index + 1) {
                    container.children(".rating").eq(count).removeClass("fa-star-o").addClass("fa-star");
                    count++;
                }
                container.children(".value").val(index + 1);
            });
        }
    },
    display: function () {
        if ($(".rating_container").length > 0) {
            $(".rating_container").each(function () {
                var con = $(this);
                var count = 0;
                var value = con.children(".value").eq(0).val();
                con.children(".rating").removeClass("fa-star").addClass("fa-star-o");
                while (count < value) {
                    con.children(".rating").eq(count).removeClass("fa-star-o").addClass("fa-star");
                    count++;
                }
            });
        }
    }
};