
$(function () {
    if ($('#content-wrapper').is(':visible')) {

        function onCommentsReceived(comments, content_uuid) {
            console.log('onCommentsReceived');
            console.log(comments);
            $('#comments-wrapper').empty();
            $('#comments-wrapper').html($.templates.comments_list.render({ comments: comments, id: content_uuid }));
            $('.comment-submit-button').click(function() {
                let content_uuid = $(this).data('comment-content');
                $(`#comment-${content_uuid}-form`).validate({
                    submitHandler: onCommentSubmit
                });
            });
            $('.comment-response-submit-button').click(function() {
                $('.comment-reply-form').validate({
                    submitHandler: onCommentReplySubmit
                });
            });
            $('.dropdown-trigger').dropdown({ constrainWidth: false, closeOnClick: false });
            $(`#comment-${content_uuid}-close`).click(function(event){
                event.preventDefault();
                let content_uuid = $(this).data('target');
                $(`#comment-${content_uuid}-trigger`).dropdown('close');
            });
        }

        function onCommentReplySubmit(form, event) {
            console.log('onCommentReplySubmit');
            event.preventDefault();
            let comment_id      = $(form).data('comment-uuid')
            let comment_title   = $(form).data('comment-title')
            $.ajax({
                url: `/index.php?route=api/comment/${comment_id}/reply`,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    text: $(`#reply-to-${comment_id}`).val(),
                    users: currentUser.id,
                    title: `Re: ${comment_title}`,
                    parent: comment_id,
                    content: $(form).data('comment-content')
                }),
                success: onCommentReplySuccess
            });
        }

        function onCommentSubmit(form, event) {
            console.log('onCommentSubmit');
            event.preventDefault();
            let content_id      = $(form).data('comment-content');
            $.ajax({
                url: `/index.php?route=api/content/${content_id}/comment`,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    text: $(`#comment-text-for-${content_id}`).val(),
                    users: currentUser.id,
                    title: $(`#comment-title-for-${content_id}`).val(),
                    content: content_id
                }),
                success: onCommentSubmitSuccess
            });
        }

        function onCommentSubmitSuccess(data) {
            console.log('onCommentSubmitSuccess');
            console.log(data);
            loadCommentsForContent(data.content);
        }

        function onCommentReplySuccess(data) {
            console.log('onCommentReplySuccess');
            console.log(data);
            let id = data.id;
            let recent = ('recent_reply' in data) ? data.recent_reply.id : null;
            $(`#card-${id}`).replaceWith($.templates.comments_item.render(data));
            if(recent) {
                pulseItem($(`#comment-reply-${recent}`), 3);
            }
        }

        function loadCommentsForContent(content_uuid) {
            $.ajax({
                url: '/index.php?route=api/content/' + content_uuid + '/comments',
                type: 'GET',
                success: function(data) { onCommentsReceived(data, content_uuid); }
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
