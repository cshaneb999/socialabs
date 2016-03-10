<?php

namespace SociaLabs;

require_once((dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
$buttons = display("tinymce/buttons");
?>
setup: function (ed) {
    ed.on("init",function (ed, evt) {
        $(".tinymce_holder").animate({
            opacity:1
        });
    });
<?php echo $buttons; ?>
}