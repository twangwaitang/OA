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
    evaluationEditModal = $('#evaluation_edit_modal'),
    evaluationAddForm = $('#evaluation_add_form'),
    evaluationEditForm = $('#evaluation_edit_form'),
    groupMembersModal = $('#group_members_modal'),
    listUrl = '/admin/student/students',
    startUrl = "/admin/evaluation/start",
    toGroupUrl = '/admin/student/studnettogroup',
    teacherToGroupUrl = '/admin/teacher/teachertogroup',
    advisorToGroupUrl= '/admin/advisor/advisortogroup',
    addUrl = "/admin/evaluation/add",
    removeUrl = "/admin/group/remove",
    updateUrl = "/admin/evaluation/update",
    removeOpt =  $('#teacher_remove');


$(function () {

    //删除按钮
    $("button[class='btn btn-white btn-sm remove_group_btn']").click(function (e) {
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
                    'group_id':e.target.name,
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

    })
    //开放按钮
    $("button[class='btn btn-white btn-sm start_evaluation_btn']").click(function (e) {
        swal({
            title: "您确定吗？",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确定",
            closeOnConfirm: true,
        }, function () {
            $.ajax({
                url: startUrl,
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

    })
    //编辑按钮
    evaluationEditModal.on('show.bs.modal', function (e) {
        $('#evaluation_edit_name').val(e.relatedTarget.name);
        $('#evaluation_edit_id').val(e.relatedTarget.id);
        evaluationEditForm.validate({
            submitHandler: function (form) {
                var param = $(form).serialize();
                $.ajax({
                    url: updateUrl,
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
                            //关闭窗口
                            evaluationEditModal.modal('hide');
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

// 新增
    evaluationAddForm.validate({
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
                            //关闭窗口
                            evaluationAddForm.modal('hide');
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
    //编辑

//remove
        removeOpt.click(function () {
            var rows = table.bootstrapTable('getSelections');
            if (!rows.length) {
                toastr.error('请选择删除数据');
            } else if (rows.length > 0) {
                ids = $.map(table.bootstrapTable('getSelections'), function (row) {
                    return row.id
                });
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
                        data: {
                            ids:ids.join(',')
                        },
                        beforeSend: function () {
                            $('#loading').modal('show');
                        },
                        success: function (data) {
                            $('#loading').modal('hide');
                            if (data.code === 1) {
                                toastr.success(data.message);
                                location.reload();
                            } else {
                                toastr.error('code 501 未知错误');
                            }
                        },
                        error: function (data) {
                            $('#loading').modal('hide');
                            toastr.error('code 500 未知错误');
                        }
                    })
                    table.bootstrapTable('remove', {
                        field: 'id',
                        values: ids
                    });
                });
            }
        })
        //刷新

        $('#refresh').click(function () {
            location.reload();
        })
})