

class TagManager {

    constructor() {
        this._tags = [];
        this.updateTags();
        setInterval(this.updateTags.bind(this), 5 * 60 * 1000);
    }

    tags() {
        return this._tags;
    }

    updateTags() {
        $.ajax({
            url: '/api.php?route=api/tag',
            type: 'GET',
            dataType: 'json',
            context: this,
            success: function (result) {
                this._tags = result;
            },
            error: function (xhr, resp, text) {
                console.log('xhr: ' + xhr);
                console.log('resp: ' + resp);
                console.log('text: ' + text);
            }
        });
    }

}

var tagManager = new TagManager();