jQuery(document).ready(function() {
    // FitVids for all sermon videos
    jQuery('#pl-sermons-video').fitVids();
    jQuery('.pl-sermons-featured-box-embed').fitVids();

    jQuery('#pl-sermon-audio').mediaelementplayer({
        defaultAudioWidth: '100%',
    });

    // Single Sermon Accordion
	jQuery('.pl-sermons-single-accordion-title-click').on('click', function() {
        jQuery('.pl-sermons-single-accordion-content').slideUp(300);
        if (jQuery(this).parent().hasClass('active')){
            jQuery(this).parent().removeClass("active");
        } else {
            jQuery('.pl-sermons-single-accordion-title-click').removeClass("active");
            jQuery(this).parent().next('.pl-sermons-single-accordion-content').slideDown(300);
            jQuery(this).parent().addClass("active");
            return false;
        }
    });
});
