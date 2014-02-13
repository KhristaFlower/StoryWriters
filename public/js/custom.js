$(document).ready(function() {

    // Used to hide short informational messages such as log in/out success, etc.
    /*window.setTimeout(function(){
        $('.autoclose').fadeTo(2000, 0).slideUp(250, function() {
            $(this).alert('close');
        });
    }, 2000);*/

    // Provide toggle functionality for our custom control boxes.
    // TODO : Come up with a better way of handling this.
    // It works, but controls usually contain forms which display feedback that
    // would be hidden when redirected back to the page.

    /*$('.extra-control-toggle').click(function(e) {
        $(this).parent().parent().toggleClass('collapsed');
        return false;
    });*/

    var min = 0;
    var max = 0;
    // Load in the word counts if they are set.
    if ($('#segment-value').length != 0) {
        min = $('#segment-value').html();
        max = $('#segment-value').html();
    } else if ($('#segment-min').length != 0 && $('#segment-max').length != 0) {
        min = $('#segment-min').html();
        max = $('#segment-max').html();
    }

    // Segment size calculator.
    $('#segment').keyup(function() {
        var content = $(this).val();

        // Remove all HTML from the input.
        content = $('<div>'+content+'</div>').text();

        // Replace multiple spaces.
        content = content.replace(/[ ]+/g, " ");

        // Remove whitespace before and after the segment.
        content = content.trim();

        // Replace multiple line breaks with a single line break (preserve one and two breaks).
        content = content.replace(/\n\s*\n\s*\n/g, '\n\n');

        // Edit the content to determine the word count.
        if (content != null) {
            var words = content.replace(/\n+/g, " ").split(" ");
            var wordCount = (content.length == 0) ? 0 : words.length;
            $('#segment-output-wordcount').html(wordCount);

            // Change the info-box colour to reflect segment completion.
            var status = $('#segment-status');
            var input = $('#segment-input-group');
            if (wordCount < min) {
                if (status.hasClass('alert-danger') || status.hasClass('alert-success')) {
                    status.removeClass('alert-danger alert-success').addClass('alert-info');
                }
            } else if (wordCount >= min && wordCount <= max) {
                if (status.hasClass('alert-info') || status.hasClass('alert-danger')) {
                    status.removeClass('alert-info alert-danger').addClass('alert-success');
                }
            } else {
                if (status.hasClass('alert-success') || status.hasClass('alert-info')) {
                    status.removeClass('alert-success alert-info').addClass('alert-danger');
                }
            }

            // Show words that are stupidly long. Subject to change, currently 20.
            var lenProb = false;
            for (var i = 0; i < words.length; i++) {
                if (words[i].length > 20) {
                    lenProb = true;
                    $('#segment-misc').html(words[i].substring(0, 20)+"... is unreasonably large.");
                    status.removeClass().addClass('alert alert-danger');
                }
            }
            if (!lenProb) {
                $('#segment-misc').html("");
                input.removeClass('has-error');
            } else {
                input.addClass('has-error');
            }
        }

        // Convert newlines to breaks (this just helps for display purposes).
        content = nl2br(content, false);

        // Display the preview.
        $('#segment-output').html(content);
    });

    // Thanks, http://stackoverflow.com/a/2919363
    function nl2br (str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
    }

});
