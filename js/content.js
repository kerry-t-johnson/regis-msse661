
class Content {

}

class ContentManager {

    constructor() {

        $('#publish').click(this.onPublishClick.bind(this));
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
        $('#content-upload-user-uuid').val(pianoUser.uuid());

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