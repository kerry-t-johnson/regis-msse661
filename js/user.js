

class User {

    constructor() {
        let user = $.cookie('user');
        user = user ? JSON.parse(user) : null;
        console.log(user);

        $('#login-form').submit(this.onLoginFormSubmit.bind(this));
        $('#register-form').submit(this.onRegisterFormSubmit.bind(this));
        $('a#user-logout').click(this.onLogoutClick.bind(this));
        $('a#user-login').click(this.onToggleFormClick.bind(this));
        $('a#login-form-cancel').click(this.onToggleFormClick.bind(this));
        $('a#register-form-cancel').click(this.onToggleFormClick.bind(this));

        $('.register-tab').click(function(){
            $('#register-form').addClass('register-form-active');
        });

        $('.login-tab').click(function(){
            $('#register-form').removeClass('register-form-active');
        });

        if(user) {
            this.onLoginSuccessful(user);
        }
        else {
            this.onLogoutSuccessful();
        }
    }

    onLoginSuccessful(userData) {
        console.log('User logged in: ' + userData.email);

        this._user = userData;
        $.cookie('user', JSON.stringify(userData));
        $("#register-login-form-show-hide").hide();
        $('#user-login').addClass('hide');
        $('#user-logout').removeClass('hide');
        $('#user-profile')
            .text('Welcome, ' + this._user.first_name)
            .removeClass("hide");
    }

    fullname() {
        return this._user.first_name + ' ' + this._user.last_name;
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

    onToggleFormClick() {
        $("#register-login-form-show-hide").slideToggle();
        return false;
    }

    onLogoutClick() {
        this.ajaxUserSubmit(null, '/api/user/logout', this.onLogoutSuccessful.bind(this));
        return false;
    }
}

var pianoUser = new User();

