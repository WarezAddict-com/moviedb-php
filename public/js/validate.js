// Form Validate

/**
 * <form action="" method="post" id="my_form" novalidate>
 * <label><span>Name: </span><input type="text" name="name" required="true" /></label>
 * <label><span>Email: </span><input type="email" name="email" required="true" /></label>
 * <label><span>Message: </span><textarea name="message" required="true"></textarea></label>
 * <label><input type="submit" value="Submit"></label>
 * </form>
**/

$(document).ready( function() {

    $("#my_form").submit(function(event) {

        var proceed = true;

        $("#my_form input[required=true], #my_form textarea[required=true]").each(function() {

            $(this).css('border-color','');

            // If Empty
            if (!$.trim($(this).val())) {
                $(this).css('border-color','red');
                proceed = false;
            }

            // Check Email
            var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

            if ($(this).attr("type") == "email" && !email_reg.test($.trim($(this).val()))) {
                $(this).css('border-color','red');
                proceed = false;
            }
        });

        if (proceed) {
            return true;
        }

        event.preventDefault();
    });

     // Reset border and message on keyup
    $("#my_form input[required=true], #my_form textarea[required=true]").keyup(function() {
        $(this).css('border-color','');
        $("#result").slideUp();
    });

});
