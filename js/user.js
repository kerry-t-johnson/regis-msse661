class User {

    constructor(data) {
        this._data = data;
    }

    uuid() {
        return this._data.uuid;
    }



}

class CurrentUser {

    constructor() {
        let user = $.cookie('user');
        user = user ? JSON.parse(user) : null;
        console.log(user);

        $('a#user-logout').click(this.onLogoutClick.bind(this));
        $('a#user-login').click(this.onLoginRegisterClick.bind(this));

        if(user) {
            this.onLoginSuccessful(user);
        }
        else {
            this.onLogoutSuccessful();
        }
    }

    onLoginRegisterClick() {
        if($('#user-login-register-form').length) {
            this.showLogin();
        }
        else {
            $.ajax({
                url: '/html/login-register.form.html',
                type: 'GET',
                context: this,
                success: function(result) {
                    this.onFormRetrieved(result)
                },
                error: function (xhr, resp, text) {
                    console.log('xhr: ' + xhr);
                    console.log('resp: ' + resp);
                    console.log('text: ' + text);
                }
            });
        }
    }

    onFormRetrieved(html) {
        $('#register-login-form-wrapper').html(html);

        $('#login-form').submit(this.onLoginFormSubmit.bind(this));
        $('#register-form').submit(this.onRegisterFormSubmit.bind(this));
        $('a#login-form-cancel').click(this.onCancelFormClick.bind(this));
        $('a#register-form-cancel').click(this.onCancelFormClick.bind(this));

        $('.register-tab').click(function(){
            this.showRegister();
        }.bind(this));

        $('.login-tab').click(function(){
            this.showLogin();
        }.bind(this));

        this.showLogin();
    }

    onLoginSuccessful(userData) {
        console.log('User logged in: ' + userData.email);

        this._user = userData;
        $.cookie('user', JSON.stringify(userData));
        $('#user-login').addClass('hide');
        $('#user-logout').removeClass('hide');
        $('#user-profile')
            .text('Welcome, ' + this._user.first_name)
            .removeClass("hide");
        $('#portfolio').addClass('user').removeClass('no-user');
        this.hideAll();
    }

    fullname() {
        return this._user.first_name + ' ' + this._user.last_name;
    }

    hideAll() {
        $('#login-form-show-hide').addClass('hide');
        $('#register-form-show-hide').addClass('hide');
    }

    showLogin() {
        $('#login-form-show-hide').removeClass('hide');
        $('#register-form-show-hide').addClass('hide');
    }

    showRegister() {
        $('#login-form-show-hide').addClass('hide');
        $('#register-form-show-hide').removeClass('hide');
    }

    uuid() {
        return this._user.id;
    }

    onLogoutSuccessful() {
        console.log('User logged out.');

        this._user = null;
        $.cookie('user', '');
        $("#register-login-form-show-hide").hide();
        $('#user-login').removeClass('hide');
        $('#user-logout').addClass('hide');
        $('#portfolio').removeClass('user').addClass('no-user');
        $('#user-profile')
            .text('')
            .addClass('hide');
    }

    ajaxUserSubmit(formData, url, func) {
        $.ajax($.extend({}, {
            url: url,
            type: "POST",
            dataType: "json",
            context: this,
            data: formData ? formData : undefined,
            success: function (result) {
                func.call(this, result);
            },
            error: function (xhr, resp, text) {
                console.log('xhr: ' + xhr);
                console.log('resp: ' + resp);
                console.log('text: ' + text);
            }
        }));
    }

    onRegisterFormSubmit(e) {
        e.preventDefault();
        this.ajaxUserSubmit($('#register-form').serialize(),
            '/api/user/register',
            this.onLoginSuccessful.bind(this));
    }

    onLoginFormSubmit(e) {
        e.preventDefault();
        this.ajaxUserSubmit($('#login-form').serialize(), '/api/user/login', this.onLoginSuccessful.bind(this));
    }

    onCancelFormClick() {
        this.hideAll();
    }

    onLogoutClick() {
        this.ajaxUserSubmit(null, '/api/user/logout', this.onLogoutSuccessful.bind(this));
        return false;
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
            url: '/api/user/' + id,
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
