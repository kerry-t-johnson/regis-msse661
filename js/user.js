
class CurrentUser {

    constructor() {
        let user = $.cookie('user');
        user = user ? JSON.parse(user) : null;
        console.log(user);

        if(user) {
            this.onLoginSuccessful(user);
        }
        else {
            this.onLogoutSuccessful();
        }
    }

    tags(callback) {
        $.ajax({
            url: '/index.php?route=api/user/' + this.id + '/tag',
            type: 'GET',
            success: function(data) {
                callback(data);
            }
        });
    }

    onLoginSuccessful(userData) {
        console.log('User logged in: ' + userData.email);

        Object.assign(this, userData);
        $.cookie('user', JSON.stringify(userData));
    }

    fullname() {
        return this._user.first_name + ' ' + this._user.last_name;
    }

    onLogoutSuccessful() {
        console.log('User logged out.');

        $.cookie('user', '');
    }


}

var currentUser = new CurrentUser();

class UserManager {

    constructor() {
        this._users = [];
    }

    user(id, callback) {
        if(this._users[id]) {
            setTimeout(callback, 0, this._users[id]);
        }
        else {
            this.fetchUser(id, callback);
        }
    }

    fetchUser(id, callback = 0) {
        $.ajax({
            url: '/api.php?route=api/user/' + id,
            type: 'GET',
            context: this,
            dataType: 'json',
            success: this.onUserReceived.bind(this, callback)
        });
    }

    onUserReceived(callback, data) {
        console.log('UserManager.onUserReceived');
        console.log(data);

        this._users[data.id] = new User(data);
        if(callback) {
            callback(this._users[data.id]);
        }
    }
}

var userManager = new UserManager();


$('#upload-form-wrapper').modal();
$('#modify-form-wrapper').modal();
$('#delete-form-wrapper').modal();

$(function(){
    if($('#user-content-wrapper').is(':visible')) {

        function onContentEditClick(event) {
            event.preventDefault();
            contentManager.fetch($(this).data('content-uuid'), onContentRetrievedForEdit);
        }

        function onContentDeleteClick(event) {
            event.preventDefault();
            console.log(this);
            let content_uuid = $(this).data('content-uuid');
            $('#delete-form-wrapper h2').text('Delete ' + "'" + $(this).data('content-title') + "'?");
            $('#delete-form-submit').click(function(event) { onContentDeleteSubmit(event, content_uuid); });
            $('#delete-form-wrapper').modal('open');
        }

        function onContentRetrievedForEdit(c) {
            $('#modify-form-wrapper h2').text(`Update '${c.title}'`);
            $('#modify-form #modify-title').val(c.title);
            $('#modify-form #modify-description').val(c.description);
            $('#modify-form [type=checkbox]').each(function(index, element){
                $(element).prop('checked', c.hasTag($(element).val()));
            });
            M.textareaAutoResize($('#modify-description'));
            M.updateTextFields();
            $('#modify-form-submit').click(function(event) { onContentEditSubmit(event, c); });
            $('#modify-form-wrapper').modal('open');
        }

        function onContentEditSubmit(event, c) {
            console.log(c);
            event.preventDefault();
            let tags = [];
            $('#modify-form [type=checkbox]').each(function(index, element){
                if($(element).prop('checked')){
                    tags.push($(element).val());
                }
            });

            $.ajax({
                url: '/index.php?route=api/content/' + c.id + '/edit',
                type: 'put',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({
                    id: c.id,
                    title: $('#modify-title').val(),
                    description: $('#modify-description').val(),
                    users: currentUser.id,
                    tags: tags
                }),
                success: onContentEditSuccess,
                error: function(jqXhr, errMsg, err) {
                    console.log('error');
                }
            });
        } // onContentEditSubmit

        function onContentDeleteSubmit(event, content_uuid) {
            event.preventDefault();
            contentManager.delete(content_uuid, function(id) {
                let p = $('#' + id + '-item').parent();
                $(p).remove();
                $('#delete-form-wrapper').modal('close');
            });
        }

        function onContentEditSuccess(data) {
            console.log('onContentEditSuccess');
            $('#modify-form-wrapper').modal('close');
            $('#' + data.id + '-item').replaceWith($.templates.content_admin_item.render(data));
            $('.edit-content').click(onContentEditClick);
            $('.delete-content').click(onContentDeleteClick);
            $('.collapsible').collapsible();
        }

        function updateUserTagPreference() {
            $.ajax({
                url: 'index.php?route=api/user/' + currentUser.id + '/tag',
                type: 'POST',
                data: JSON.stringify([{ uuid: this.value, value: this.checked }]),
                dataType: "json",
                context: this,
                success: function(data){
                    let checked = false;
                    let checkbox = this;
                    data.forEach(function(element){
                        if(element.id == checkbox.value) {
                            checked = true;
                        }
                    });
                    this.checked = checked;
                    retrieveUserTaggedContent(data);
                },
                error: function(jqxhr, errMsg, errThrown) {
                    M.toast({html: errMsg});
                    console.log(errMsg);
                }
            });

        }

        function retrieveUserTaggedContent(tags) {
            contentManager.retrieveByTag(tags, function(content){
                $('#user-tagged-content').html($.templates.content_interest_list.render(content));
                $('.collapsible').collapsible();
            },
                'exclude-user=' + currentUser.id);
        }

        contentManager.retrieveByUser(currentUser.id, function (data) {
            console.log('got user content');
            console.log(data);
            $('#user-content-list').empty();
            $('#user-content-list').html($.templates.content_admin_list.render(data));
            $('.collapsible').collapsible();
            $('.edit-content').click(onContentEditClick);
            $('.delete-content').click(onContentDeleteClick);
        });

        $('form#user-tags :checkbox').change(updateUserTagPreference);

        currentUser.tags(retrieveUserTaggedContent);
    }

    if($('#user-register-form').is(':visible')) {
        $('#user-register-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: "/index.php?route=api/user/check"
                }
            },
            invalidHandler: function(event, validator) {
                console.log('invalid!!!');
                console.log(event);
                console.log(validator);
            }
        });
    }

});
