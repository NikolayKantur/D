var suggestToUserClass = function () {

    /**
     * 
     * @type {suggestToUserClass}
     */
    var self = this;

    /**
     *
     */
    this.addUser =  function () {

    };

    /**
     *
     */
    this.showModal = function () {

    };

    this.sendAjax = function (data, callback) {
        var url = diductioObject.ajax_path;
        jQuery.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (result) {
                callback(result);
            },
            dataType: 'json'
        });
    };

    /**
     *
     */
    this.init = function(){
        jQuery('#save-subscribers').click(function(){
            self.save(jQuery(this));
        });
    };

    this.save = function (target) {
        var users = [];
        jQuery(target).text('Подождите...');

        // collect data
        jQuery(".suggested-user").each(function(){
            var user = {};
            user.id = jQuery(this).data('user');
            user.wasChecked = jQuery(this).data('haschecked') == 1;
            user.alreadyHas = jQuery(this).is(':checked') == 1;
            users.push(user);
        });
        var data =  {
            action: "suggestUsers",
            users: users,
            postid: jQuery('#postid').val()
        };

        // send ajax
        if(users) {
            self.sendAjax(data, function () {
                jQuery('#suggestUser').modal('toggle');
                window.location.reload();
                jQuery(target).text('Сохранить');
            });
        }
    };
};

// initialize object
var suggestToUser = new suggestToUserClass();
suggestToUser.init();