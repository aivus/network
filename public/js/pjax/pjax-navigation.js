$(document).ready(function() {

    // Add data-pjax to all links after document load
    $('a:not(#userBoxLogout)').attr('data-pjax', '#pjax-container');

    $(document).pjax('a[data-pjax]', { container: '#pjax-container', timeout: 0})
        .on('pjax:success', function(event) {
            // If current target is calendar, call calendarReady function
            var isCalendar = event.relatedTarget.toString().search("calendar/calendar");

            if(isCalendar) {
                calendarReady();
            }

            /**
             * create typeahead list of the don't subscribed users
             */
            if ($('#not-member-list').length) {
                var tmp = new conversation_list();
                tmp.initTypeahead();
            }

            // Add data-pjax to all links
            $('a:not(#userBoxLogout)').attr('data-pjax', '#pjax-container');
        });
}); 