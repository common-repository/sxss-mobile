jQuery("#menu .icon").click(function () {
     
    // Set the effect type
    var effect = 'slide';
             
    // Set the options for the effect type chosen
    var options = { direction: 'left' };
             
    // Set the duration (default: 400 milliseconds)
    var duration = 700;
             
    // jQuery('#mobilemenu').toggle('slide');
    jQuery('#mobilemenu').fadeToggle( "slow");

});

/* DELETE OLD CODE AND USE .sxss-sidebar */

jQuery(".sxss-sidebar").click(function () {
     
    // Set the effect type
    var effect = 'slide';
             
    // Set the options for the effect type chosen
    var options = { direction: 'left' };
             
    // Set the duration (default: 400 milliseconds)
    var duration = 700;
             
    // jQuery('#mobilemenu').toggle('slide');
    jQuery('#mobilemenu').fadeToggle( "slow");

});