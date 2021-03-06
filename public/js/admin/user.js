$(function(){

    /**
     * Block/unblock user
     */
    $(document).on({
        click: function() {
            var obj        = $(this);
            var id         = obj.attr('data-id');
            var is_block   = obj.hasClass('btn-warning') ? 1 : 0;
            var first_name = $('#'+id+'_first_name').text().toString().trim();
            var last_name  = $('#'+id+'_last_name').text().toString().trim();
            if (confirm('Do you really wanna ' + (is_block ? 'block' : 'unblock') + ' this user: "' + first_name + ' ' + last_name + '"?')) {
                $.ajax({
                    url:  '/admin/user-block',
                    type: 'POST',
                    data: {
                        id_edit:  id,
                        is_block: is_block
                    },
                    success: function(response, textStatus) {

                        if (is_block) {
                            obj.removeClass('btn-warning')
                               .addClass('btn-info')
                               .html('Unblock account');
                            obj.parent().parent().addClass('danger');
                        } else {
                            obj.removeClass('btn-info')
                               .addClass('btn-warning')
                               .html('Block account');
                            obj.parent().parent().removeClass('danger');
                        }
                    },
                    error: function(error) {
                        alert(error.statusText);
                    }
                });
            }
        }
    }, 'button[name="user-block"]');

    /**
     * Delete user
     */
    $(document).on({
        click: function() {
            var obj        = $(this);
            var id         = obj.attr('data-id');
            var first_name = $('#'+id+'_first_name').text().toString().trim();
            var last_name  = $('#'+id+'_last_name').text().toString().trim();
            if (confirm('Do you really wanna delete forever this user: "' + first_name + ' ' + last_name + '"?')) {
                $.ajax({
                    url:  '/admin/user-delete',
                    type: 'POST',
                    data: {
                        id_edit: id
                    },
                    success: function(response, textStatus) {
                        if ('ok' == response) {
                            var row = obj.parent().parent();
                            row.hide('blind');
                            setTimeout(function() {
                                row.remove();
                            },1000)
                        } else {
                            alert('Sorry, we have some problems with deleting this user. Reload this page and try again');
                        }
                    },
                    error: function(error) {
                        alert(error.statusText);
                    }
                });
            }
        }
    }, 'button[name="user-delete"]');

    /**
     * load data for edit user
     */
    $(document).on({
        click: function() {
            $.ajax({
                url:  '/admin/user-edit',
                type: 'POST',
                data: {
                  id_edit: $(this).attr('data-id')
                },
                success: function(response, textStatus) {
                    var result = $.parseJSON(response);
                    if('ok' == result['status']) {
                        $('#user-modal').html(result['html']).modal('show');
                    } else if ('redirect' == result['status']) {
                        window.location = result['redirect'];
                    }
                },
                error: function(error) {
                    alert(error.statusText);
                }
            });
        }
    }, 'button[name="user-edit"]');

    /**
     * save data of user
     */
    $(document).on({
        click: function (event) {
            var obj         = $(this);
            var id          = obj.attr('data-id');
            var data        = obj.parents('form').serialize();
            data['id_edit'] = id;
            var errors = new messages();
            $.ajax({
                url:  '/admin/user-save',
                type: 'POST',
                data: data,
                beforeSend: function() {
                    errors.hideErrors(obj.parents('form'));
                },
                success: function(response, textStatus) {
                    var result = $.parseJSON(response);

                    if ('error' == result['status']) {
                        for (var i in result['errors']) {
                            errors.showErrors(i, result['errors'][i])
                        }
                    } else {
                        for (var i in result['user']) {
                            $('#'+id+'_'+i).html(result['user'][i]);
                        }
                        $('#user-modal').modal('hide');
                    }
                },
                error: function(error) {
                    alert(error.statusText);
                }
            });
            return false;
        }
    }, 'button[name="user-save"]');
});