/**
 * Created by jiangcoco on 2017/4/12.
 */

var table =  $('#users'),
    groupDetailTable =  $('#group_detail'),
    studentGroupBtn =  $('#student_group'),
    teacherGroupBtn =  $('#teacher_group'),
    advisorGroupBtn =  $('#advisor_group'),
    addMemberToGroupBtn = $('#add_member_to_group'),
    groupDetailsModal = $('#group_details_modal'),
    groupCreateModal = $('#group_create_modal'),
    newsEditModal = $('#news_edit_modal'),
    newsAddForm = $('#news_add_form'),
    newsEditForm = $('#news_edit_form'),
    groupMembersModal = $('#group_members_modal'),
    isTopUrl= "/admin/news/istop",
    addUrl = "/admin/news/add",
    removeUrl = "/admin/news/remove",
    updateUrl = "/admin/news/update",
    getOneUrl= "/admin/news/one";


$(function () {
    CKEDITOR.replace('news_add_content',{toolbar:'Short',height:'500px'});
    CKEDITOR.replace('news_edit_content',{toolbar:'Short',height:'500px'});
    newsAddForm.validate({
        submitHandler: function () {
            if (CKEDITOR.instances.news_add_content.getData() == '') {
                toastr.error('警告：内容不得为空！');
                CKEDITOR.instances.news_add_content.focus();
                return false;
            }
            $.ajax({
                url: addUrl,
                type: "post",
                dataType: "json",
                data: {
                    'title':$('#news_add_title').val(),
                    'content': CKEDITOR.instances.news_add_content.getData(),
                    'type': $("input[name='news_type_add']:checked").val(),
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
                        newsAddForm.modal('hide');
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
    $("button[class='btn btn-white btn-sm remove_news_btn']").click(function (e) {
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
    $("button[class='btn btn-white btn-sm top_news_btn']").click(function (e) {
        swal({
            title: "您确定推荐这条信息吗",
            type: "success",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: isTopUrl,
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
    $("button[class='btn btn-white btn-sm cancel_top_news_btn']").click(function (e) {
        swal({
            title: "您确定取消推荐这条信息吗",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: isTopUrl,
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
    //
    //获取one数据
    function getOne(id) {
        $.ajax({
            url: getOneUrl,
            type: "post",
            dataType: "json",
            data: {
                'id':id,
            },
            beforeSend : function ()
            {
                $('#loading').modal('show');
            },
            success: function (data) {
                $('#loading').modal('hide');
                if(data){
                    $('#news_edit_title').val(data.title);
                    $(":radio[name='news_type_edit'][value='" + data.type + "']").prop("checked", "checked");
                    CKEDITOR.instances.news_edit_content.setData(data.content);
                } else{
                    toastr.error('code 501 获取数据失败');
                }
            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
        })
    }
    //编辑按钮
    newsEditModal.on('show.bs.modal', function (e) {
        getOne(e.relatedTarget.id);

        newsEditForm.validate({
            submitHandler: function () {
                if (CKEDITOR.instances.news_edit_content.getData() == '') {
                    toastr.error('警告：内容不得为空！');
                    CKEDITOR.instances.news_edit_content.focus();
                    return false;
                }
                $.ajax({
                    url: updateUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.relatedTarget.id,
                        'title':$('#news_edit_title').val(),
                        'content': CKEDITOR.instances.news_edit_content.getData(),
                        'type': $("input[name='news_type_edit']:checked").val(),
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
                            newsEditForm.modal('hide');
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

    //保存按钮
    addMemberToGroupBtn.click(function (e) {
        var rows = table.bootstrapTable('getSelections');
        if(rows.length == 0)  return toastr.error('请选择数据');
        var group_id = $(e.target).attr('data');
        if($('#add_member_to_group').attr('name')){
            var toGroupUrl = $('#add_member_to_group').attr('name');
        }else{
            var toGroupUrl = '/admin/student/studnettogroup';
        }
        $.ajax({
            url: toGroupUrl,
            type: "post",
            dataType: "json",
            data: {
                'group_id':group_id,
                'rows':rows,
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
                    //关闭窗口
                    groupMembersModal.modal('hide');
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

        //刷新
        $('#refresh').click(function () {
            location.reload();
        })
})