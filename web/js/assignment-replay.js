var assignmentReplayModal = $('#assignment_replay_modal');

$(function () {

    CKEDITOR.replace('post_replay',{toolbar:'Short',height:'500px'});
    assignmentReplayModal.on('show.bs.modal', function (e) {
        $('#blog_submit').click(function () {
            if (CKEDITOR.instances.post_replay.getData() == '') {
                toastr.error('警告：内容不得为空！');
                CKEDITOR.instances.post_replay.focus();
                return false;
            }
            $.ajax({
                url: '/admin/assignment-replay/add',
                type: "post",
                dataType: "json",
                data: {
                    'uid':e.relatedTarget.id,
                    'assignment_id':e.relatedTarget.name,
                    'content': CKEDITOR.instances.post_replay.getData(),
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
                        assignmentReplayModal.modal('hide');
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
    //任务完成
    $("button[class='btn btn-white btn-sm assignment_done_btn']").click(function (e) {
        swal({
            title: "任务完成确定吗",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: "/admin/assignment/done",
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

    //刷新
    $('#refresh').click(function () {
        location.reload();
    })
})


