RegExp.escape = function(str) {
    return str.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1");
};

class Content {

    constructor(contentId) {
        this._id    = contentId;
        this._html  = null;
        this._data  = null

        if(!Content.fullTemplate && !Content.fullTemplateRequested) {
            Content.requestFullTemplate();
        }

        this.fetchSelf();
    }

    fetchSelf() {
        $.ajax({
            url: '/api/content/' + this._id,
            type: 'GET',
            dataType: 'json',
            context: this,
            success: this.onContentReceived.bind(this)
        });
    }

    onContentReceived(data) {
        this._data = data;
        console.log(this._data);
        userManager.fetchUser(data.users, this.onUserReceived.bind(this));
        this.show();
    }

    show() {
        console.log('Content.show');
        console.log(this);
        if(!Content.fullTemplate) {
            if(!Content.fullTemplateRequested) {
                Content.requestFullTemplate();
            }

            setTimeout(this.show.bind(this), 1000);
        }

        if(!this._html) {
            this._html = Content.fullTemplate;
            for (let [key, value] of Object.entries(this._data)) {
                let re = new RegExp(RegExp.escape('${' + key + '}'), "g");

                this._html = this._html.replace(re, value ? value : '');
            }
        }

        console.log(this._html);
        $('#content-container').html(this._html);

        $("body, html").animate({
            scrollTop: $('#content-container').offset().top
        }, 600);
    }

    onUserReceived(user) {
        console.log('Content.onUserReceived');
        console.log(user);
    }

}

Content.fullTemplate = null;
Content.fullTemplateRequested = false;
Content.requestFullTemplate = function() {
    Content.fullTemplateRequested = true;

    $.ajax({
        url: '/html/content-focus.tmpl.html',
        type: 'GET',
        context: this,
        success: function(result) {
            Content.fullTemplate = result;
        },
        error: function() {
            Content.fullTemplateRequested = false;
        }
    });
}


class ContentManager {

    constructor() {
        $('#publish').click(this.onPublishClick.bind(this));
        $('.content-full-link').click(this.onContentClick.bind(this));
        this._content = {};
    }

    onContentClick(event) {
        let contentId = $(event.currentTarget).data('content');
        this._content[contentId] = new Content(contentId);
    }

    onPublishClick() {
        if($('#content-upload-form').length) {
            this.showForm();
        }
        else {
            $.ajax({
                url: '/html/content-upload.form.html',
                type: 'GET',
                context: this,
                success: function (result) {
                    this.onFormRetrieved(result);
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
        // Add the retrieved form to the DOM
        $('#content-upload-form-show-hide').html(html);

        this.fileInput  = $('#file-to-upload');
        this.fileLabel  = $(this.fileInput).next();
        this.original   = $(this.fileLabel).text();

        $('#content-upload').submit(this.onPublishSubmit.bind(this));
        $('a#content-upload-form-cancel').click(this.onCancelFormClick.bind(this));
        $(this.fileInput).change(this.onFileInputChanged.bind(this));
        $('#content-upload-user-uuid').val(currentUser.uuid());

        $('#file-to-upload').fileupload({
            url: '/api/content/upload',
            dataType: 'json',
            add: function(event, data) { /* Do nothing until submit */ }
        });

        $.each(tagManager.tags(), function(index, tag) {
            $('#content-tags').append($('<option>', { value: tag.id, text: tag.name }));
        });
    }

    onFileInputChanged(event) {
        console.log(this.fileInput);
        let fileName    = $(this.fileInput).val().split( '\\' ).pop();
        console.log(fileName);
        fileName        = fileName.split('/').pop();

        $(this.fileLabel).text(fileName ? fileName : this.original);

        // Line length hack
        if(fileName.length > 25) {
            $(this.fileLabel).addClass('two-rows');
        }
        else {
            $(this.fileLabel).removeClass('two-rows');
        }
    }

    showForm() {
        $('#content-upload-form').slideToggle();
    }

    onCancelFormClick() {
        this.reset();
    }

    reset() {
        $(this.fileInput).val(null);
        $(this.fileInput).change();
        $('#content-upload-form').slideToggle();
    }

    onPublishSubmit(event) {
        event.preventDefault();

        let formValues = [];
        $('#content-upload :input').each(function() {
            if(this.name && this.name !== 'file-to-upload') {
                formValues.push({'name': this.name, 'value': $(this).val()});
            }
        });

        let jqXHR = $('#file-to-upload').fileupload('send', {
            files: this.fileInput.prop('files'),
            url: '/api/content/upload',
            formData: formValues,
            dataType: 'json',
        });

        jqXHR.done(this.onFileUploadSuccess.bind(this));
        jqXHR.fail(this.onFileUploadError.bind(this));
    }

    onFileUploadSuccess(data, textStatus, jqXHR) {
        console.log('onFileUploadSuccess[data]:');
        console.log(data);
        this.reset();
    }

    onFileUploadError(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    }
}

var contentManager = new ContentManager();

// init Isotope
var grid = $('.portfolio-one').isotope({
    itemSelector: '.portfolio-item',
    layoutMode: 'fitRows'
});

$('.filters-button-group').on('click', 'button', function () {
    let filterValue = $(this).attr('data-filter');
    grid.isotope({filter: filterValue});
});
// change is-checked class on buttons
$('.button-group').each(function (i, buttonGroup) {
    let buttonGroupJq = $(buttonGroup);
    buttonGroupJq.on('click', 'button', function () {
        buttonGroupJq.find('.is-checked').removeClass('is-checked');
        $(this).addClass('is-checked');
    });
});
