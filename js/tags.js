

class TagManager {

    static fetchTags(callback) {
        $.ajax({
           url: '/api/tags',
           type: 'GET',
           dataType: 'json',
           success: function (result) {
               callback(result);
           }
        });
    }

}

var tagManager = new TagManager();