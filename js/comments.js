
$(function () {
    if ($('#content-wrapper').is(':visible')) {

        function onCommentsReceived(comments) {
            console.log('onCommentsReceived');
            $('#comments-wrapper').html($.templates.comments_list.render(comments));
            $('.comment-response-submit-button').click(onCommentReplySubmit);
        }

        function onCommentReplySubmit(event) {
            console.log('onCommentReplySubmit');
            event.preventDefault();
            let comment_id      = $(this).data('comment-uuid')
            let comment_title   = $(this).data('comment-title')
            $.ajax({
                url: `/index.php?route=api/comment/${comment_id}/reply`,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    text: $(`#reply-to-${comment_id}`).val(),
                    users: currentUser.id,
                    title: `Re: ${comment_title}`,
                    parent: comment_id,
                    content: $(this).data('comment-content')
                }),
                success: onCommentReplySuccess
            });
        }

        function onCommentReplySuccess(data) {
            console.log('onCommentReplySuccess');
            console.log(data);
            let id = data.id;
            let recent = data.recent_reply.id;
            $(`#card-${id}`).replaceWith($.templates.comments_item.render(data));
            pulseItem($(`#comment-reply-${recent}`), 3);
        }

        function loadCommentsForContent(content_uuid) {
            $.ajax({
                url: '/index.php?route=api/content/' + content_uuid + '/comments',
                type: 'GET',
                success: onCommentsReceived
            });
        }

        loadCommentsForContent($('#comments-wrapper').data('content-uuid'));

    }
});


$.get('/html/comments-list.tmpl.html', function(tmpl) {
    $.templates('comments_list', tmpl);
});

$.get('/html/comments-item.tmpl.html', function(tmpl) {
    $.templates('comments_item', tmpl);
});
