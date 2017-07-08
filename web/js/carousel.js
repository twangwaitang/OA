/**
 * Created by jiangcoco on 2017/4/12.
 */

var carouselAddForm = $('#carousel_add_form'),
    isPublicUrl= "/admin/links/ispublic",
    addUrl = "/admin/carousel/add",
    removeUrl = "/admin/links/remove",
    updateUrl = "/admin/links/update",
    uploadUrl= "/admin/carousel/upload",
    getOneUrl= "/admin/links/one";


$(function () {

    carouselAddForm.validate({
        submitHandler: function (form) {
            var param = $(form).serialize();
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
                        carouselAddForm.modal('hide');
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
    //删除按钮
    $("button[class='btn btn-white btn-sm remove_links_btn']").click(function (e) {
        swal({
            title: "您确定要删除这条信息吗",
            text: "删除后将无法恢复，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: removeUrl,
                type: "post",
                dataType: "json",
                data: {
                    'id':e.target.name,
                },
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
        })

    })
    //推荐按钮
    $("button[class='btn btn-white btn-sm top_links_btn']").click(function (e) {
        swal({
            title: "您确定推荐这条信息吗",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: isPublicUrl,
                type: "post",
                dataType: "json",
                data: {
                    'id':e.target.name,
                },
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
        })

    })
    //取消推荐按钮
    $("button[class='btn btn-white btn-sm cancel_top_links_btn']").click(function (e) {
        swal({
            title: "您确定取消推荐这条信息吗",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: isPublicUrl,
                type: "post",
                dataType: "json",
                data: {
                    'id':e.target.name,
                    'type':'cancel'
                },
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
        })

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
            var imgUrl = '../uploads/carousel/'+data.result.fileName;
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