$('.ui.vertical.stripe.segment')
    .visibility({
        once: false,
        // update size when new content loads
        observeChanges: true,
        // load content on bottom edge visible
        onBottomVisible: function () {
            
        }
    })
    ;