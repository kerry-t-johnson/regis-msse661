RegExp.escape = function(str) {
    return str.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1");
};

class Content {

    constructor(content) {
        if(typeof content === 'string') {
            this._id            = content;
            this._fullHtml      = null;
            this._portfolioHtml = null;

            this.fetchSelf();
        }
        else if(typeof content === 'object') {
            Object.assign(this, content);
            this._id            = content.id;
            this._fullHtml      = null;
            this._portfolioHtml = null;
        }
    }

    hasTag(nameOrUuid) {
        let result = false;
        this.tags.forEach(function (element) {
            if(element.id == nameOrUuid || element.name == nameOrUuid) {
                result = true;
            }
        })

        return result;
    };

    fetchSelf() {
        $.ajax({
            url: '/api.php?route=api/content/' + this._id,
            type: 'GET',
            dataType: 'json',
            context: this,
            success: this.onContentReceived.bind(this)
        });
    }

    onContentReceived(data) {
        Object.assign(this, data);
        console.log(this);
        userManager.fetchUser(data.users, this.onUserReceived.bind(this));

        if(this.mime_type === 'text/html' || this.mime_type === 'text/plain') {
            console.log('Retrieving HTML from ' + this.path);
            $.ajax({
                url: this.path,
                dataType: 'html',
                context: this,
                success: function(result) {
                    console.log(this);
                    this.htmlContent = result;
                    this.show();
                }.bind(this)
            });
        }
        else {
            this.show();
        }
    }

    show() {
        console.log('Content.show');
        console.log(this);
        if(!Content.fullTemplate) {
            if(!Content.fullTemplateRequested) {
                Content.requestFullTemplate();
            }

            setTimeout(this.show.bind(this), 1000);
            return;
        }

        if(!this._fullHtml) {
            this._fullHtml = Content.fullTemplate;
            for (let [key, value] of Object.entries(this._data)) {
                let re = new RegExp(RegExp.escape('${' + key + '}'), "g");

                this._fullHtml = this._fullHtml.replace(re, value ? value : '');
            }
        }

        $('#content-container').html(this._fullHtml);

        $("body, html").animate({
            scrollTop: $('#content-container').offset().top
        }, 600);
    }

    renderPortfolio(element) {
        $(element).html($.templates.content_portfolio.render(this));
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
        dataType: 'html',
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
        $('.content-full-link').click(this.onContentClick.bind(this));
        this._content = {};
    }

    onContentClick(event) {
        let contentId = $(event.currentTarget).data('content');
        this._content[contentId] = new Content(contentId);
    }

    fetch(content_uuid, callback) {
        $.ajax({
            url: '/index.php?route=api/content/' + content_uuid,
            type: 'GET',
            success: function(data) {
                let c = new Content(data);
                contentManager._content[content_uuid.id] = c;
                callback(c);
            }
        });
    }

    delete(content_uuid, callback) {
        $.ajax({
            url: '/index.php?route=api/content/' + content_uuid + '/delete',
            type: 'DELETE',
            success: function(data) {
                console.log(data);
                callback(content_uuid);
            }
        });
    }

    retrieveByTag(tags, callback, addlQuery) {
        if(tags.length > 0) {
            let query   = (typeof addlQuery !== 'undefined') ? ('&' + addlQuery) : '';
            let join    = '&tag=';
            tags.forEach(function (element) {
                query   += join;
                query   += element.id;
            });
            console.log(query);
            $.ajax({
                url: '/index.php?route=api/content' + query,
                type: 'GET',
                context: this,
                success: function(data) {
                    console.log(data);
                    let returnedContent = [];
                    data.forEach(function(content) {
                        let c = new Content(content);
                        contentManager._content[content.id] = c;
                        returnedContent.push(c);
                    });
                    callback(returnedContent);
                },
                error: function(jqXhr, errMsg, err) {
                    console.log('retrieveByTag.error');
                }
            });
        }
        else {
            setTimeout(callback([]), 0);
        }
    }

    retrieveByUser(user_uuid, callback) {
        $.ajax({
            url: '/index.php?route=api/user/' + user_uuid + '/content',
            type: 'GET',
            context: this,
            success: function(data) {
                let returnedContent = [];
                data.forEach(function(content) {
                    let c = new Content(content);
                    contentManager._content[content.id] = c;
                    returnedContent.push(c);
                });
                callback(returnedContent);
            },
            error: function(jqXhr, errMsg, err) {
                console.log('retrieveByUser.error');
            }
        });
    }
}

var contentManager = new ContentManager();
if(!Content.fullTemplate && !Content.fullTemplateRequested) {
    Content.requestFullTemplate();
}

// init Isotope
var grid = $('.content-one').isotope({
    itemSelector:   '.content-item',
    layoutMode:     'fitRows'
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

$.get('/html/content-admin-list.tmpl.html', function(tmpl) {
   $.templates('content_admin_list', tmpl);
});

$.get('/html/content-admin-item.tmpl.html', function(tmpl) {
    $.templates('content_admin_item', tmpl);
});

$.get('/html/content-interest-list.tmpl.html', function(tmpl) {
    $.templates('content_interest_list', tmpl);
});

$.get('/html/content-interest-item.tmpl.html', function(tmpl) {
    $.templates('content_interest_item', tmpl);
});

$.get('/html/content-focus.tmpl.html', function(tmpl) {
    $.templates('content_focus', tmpl);
});

function truncate(text, length) {
    if(text.length > length) {
        text = text.substr(0, length - 3);
        text += '...';
    }

    return text;
}

$.views.helpers({'truncate': truncate});

$(function () {
    if($('#content-wrapper').is(':visible')) {

        function onContentReceived(content) {
            $('#content-wrapper').html($.templates.content_focus.render(content));
            $('.collapsible').collapsible();
        }

        function loadContent(content_uuid) {
            contentManager.fetch(content_uuid, onContentReceived);
        }

        loadContent($('#content-wrapper').data('content-uuid'));
    }
});
