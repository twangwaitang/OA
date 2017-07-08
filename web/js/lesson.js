
$(function () {
    var commentForm = $('#comment_add_form');
    var notesForm = $('#notes_add_form');
    var replyForm = $('#reply_add_form');
    var player = videojs('lesson-video', {
    });
    //发布
    commentForm.validate({
        submitHandler: function (form) {
            var param = $(form).serialize();
            $.ajax({
                url: "/comment/add",
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
                        $('#comment_modal').modal('hide');
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
    //发布笔记
    notesForm.validate({
        submitHandler: function (form) {
            var param = $(form).serialize();
            $.ajax({
                url: "/notes/add",
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
                        $('#notes_modal').modal('hide');
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
    //回复
    $('#reply_modal').on('show.bs.modal', function (e) {
        $('#commentId').val(e.relatedTarget.name);
        replyForm.validate({
            submitHandler: function (form) {
                var param = $(form).serialize();
                $.ajax({
                    url: "/comment/reply/add",
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
                            $('#reply_modal').modal('hide');
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
    })
    //设计精华笔记
    $("a[class='good_note']").click(function (e) {
        $.ajax({
            url: '/notes/isgood',
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
    //取消精华笔记
    $("a[class='cancel_good_note']").click(function (e) {
        $.ajax({
            url: '/notes/isgood',
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

    //删除按钮
    $("a[class='remove_comment']").click(function (e) {
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
                url: '/comment/remove',
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

    //显示答案
    $("button[class='btn btn-primary show_answer_btn']").click(function (e) {
        $("div[class='answer hidden']").removeClass('hidden');
    })


})


