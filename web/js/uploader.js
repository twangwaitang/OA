/**
 * Created by jiangcoco on 2017/4/26.
 */
$(function () {
    'use strict';
    if($('.table_upload').attr('id') === 'teacher'){
        var url = 'teacher/upload';
    }else if($('.table_upload').attr('id') === 'student'){
        var url = 'student/upload';
    }

    var uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function () {
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('取消')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
            });
    $('#fileupload').fileupload({
        url: url,
        type:"post",
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(xls|xlsx|cvs)$/i,
       maxFileSize: 500*1024,
        maxNumberOfFiles:1,
        messages: {
           maxFileSize: '上传文件不得超过500KB',
            acceptFileTypes: '文件类型不正确'
        }
    }).on('fileuploadadd', function (e, data) {
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            var node = $('<p/>')
                .append($('<span/>').text(file.name));
            if (!index) {
                node
                    .append('<br>')
                    .append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
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
            if ( $('.table_upload').attr('id') === 'teacher'){
                $('#teacher_upload').modal('hide');
                $('#teacher').bootstrapTable('refresh');
            }else if($('.table_upload').attr('id') === 'student'){
                $('#student_upload').modal('hide');
                $('#student').bootstrapTable('refresh');
            }
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
});