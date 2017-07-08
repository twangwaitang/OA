/**
 * Created by jiangcoco on 2017/4/12.
 */

var table =  $('#users'),
    basewebEditModal = $('#baseweb_edit_modal'),
    basewebAddForm = $('#baseweb_add_form'),
    basewebEditForm = $('#baseweb_edit_form'),
    groupMembersModal = $('#group_members_modal'),
    isPublicUrl= "/admin/baseweb/ispublic",
    addUrl = "/admin/baseweb/add",
    removeUrl = "/admin/baseweb/remove",
    updateUrl = "/admin/baseweb/update",
    uploadUrl= "/admin/baseweb/upload",
    getOneUrl= "/admin/baseweb/one";


$(function () {
    $.ajax({
        url: getOneUrl,
        type: "post",
        dataType: "json",
        beforeSend : function ()
        {
            $('#loading').modal('show');
        },
        success: function (data) {
            if(data.logo){
                var imgUrl = 'http://'+window.location.host+'/uploads/baseweb/'+data.logo;
                $('#file_path').html('<img src='+imgUrl+' width="200px" height="100px">');
                $('#file_name').val(data.logo);
            }
            $('#loading').modal('hide');
            $('#tid').val(data.id);
            $('#webtitle').val(data.webTitle);
            $('#footer').val(data.footer);
            $(":radio[name='isOpen'][value='" + data.isOpen + "']").prop("checked", "checked");
        },
        error: function (data) {
            $('#loading').modal('hide');
            toastr.error('code 500 获取数据失败');
        }
    })

    $('#fileupload').fileupload({
        url: uploadUrl,
        type:"post",
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(jpg|png)$/i,
        maxFileSize: 100*1024,
        maxNumberOfFiles:1,
        messages: {
            maxFileSize: '上传文件不得超过100KB',
            acceptFileTypes: '文件类型不正确'
        }
    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('上传')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        if (data.result.code){
            toastr.success(data.result.message);
            var imgUrl = 'http://'+window.location.host+'/uploads/webbase/'+data.result.fileName;
            $('#file_path').html('<img src='+imgUrl+' width="200px" height="100px">');
            $('#file_name').val(data.result.fileName);
        }else{
            toastr.error('上传失败! ' + data.result.message);
        }

    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('文件上传失败');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
    basewebAddForm.validate({
        submitHandler: function (form) {
            var param = $(form).serialize();
            if ($('#tid').val()) addUrl= updateUrl;
            $.ajax({
                url: addUrl,
                type: "post",
                dataType: "json",
                data: param,
                beforeSend : function ()
                {
                    $('#loading').modal('show');
                },
                success: function (data) {
                    $('#loading').modal('hide');
                    if(data.code === 0){
                        toastr.error(data.message);
                    }else if(data.code === 1){
                        toastr.success(data.message);
                        basewebAddForm.modal('hide');
                        location.reload();
                    }else{
                        toastr.error('code 501 未知错误');
                    }
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    toastr.error('code 500 未知错误');
                }
            })
        }
    })
    //add上传缩略图
    $('#fileupload').fileupload({
        url: uploadUrl,
        type:"post",
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(jpg|png)$/i,
        maxFileSize: 500*1024,
        maxNumberOfFiles:1,
        messages: {
            maxFileSize: '上传文件不得超过100KB',
            acceptFileTypes: '文件类型不正确'
        }
    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button')
                .text('上传')
                .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        if (data.result.code){
            toastr.success(data.result.message);
            var imgUrl = '../uploads/baseweb/'+data.result.fileName;
            $('#file_path').html('<img src='+imgUrl+' width="300px" height="200px">');
            $('#file_name').val(data.result.fileName);
        }else{
            toastr.error('上传失败! ' + data.result.message);
        }

    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('文件上传失败');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
    //刷新
    $('#refresh').click(function () {
        location.reload();
    })
})