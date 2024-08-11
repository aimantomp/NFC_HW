$(document).ready(function($){
         // Open popup if there's a notification message
         if (notificationMessage) {
             $('#notification-message').text(notificationMessage);
             $('.cd-popup').addClass('is-visible');
         }
    
         // Close popup when clicking the close button or outside the popup
         $('.cd-popup').on('click', function(event){
            if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
                 event.preventDefault();
                 $(this).removeClass('is-visible');
             }
         });
    
         // Close popup when clicking the esc keyboard button
         $(document).keyup(function(event){
             if(event.which == '27'){
                 $('.cd-popup').removeClass('is-visible');
             }
         });
    });