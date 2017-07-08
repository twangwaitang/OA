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
    groupEditModal = $('#group_edit_modal'),
    groupAddForm = $('#group_add_form'),
    groupEditForm = $('#group_edit_form'),
    groupMembersModal = $('#group_members_modal'),
    listUrl = '/admin/student/students',
    groupUrl = "/admin/group/onegroup",
    toGroupUrl = '/admin/student/studnettogroup',
    teacherToGroupUrl = '/admin/teacher/teachertogroup',
    advisorToGroupUrl= '/admin/advisor/advisortogroup',
    addUrl = "/admin/group/add",
    removeUrl = "/admin/group/remove",
    updateUrl = "/admin/group/update",
    removeOpt =  $('#teacher_remove');


$(function () {
//查看每个小组的学生
    groupDetailsModal.on('show.bs.modal', function (e) {
        groupDetailTable.bootstrapTable('refresh',{query: {'group_id': e.relatedTarget.name}});
        groupDetailTable.bootstrapTable({
            url: groupUrl,
            queryParams:{
                'group_id':e.relatedTarget.name
            },
            cache:false,
            striped: true,
            sortStable: true,
            clickToSelect: true,
            pagination: true, //分页
            pageNumber:1,                       //初始化加载第一页，默认第一页
            pageSize: 10,                       //每页的记录行数（*）
            pageList: [10, 25, 50, 100],
            columns: [{
                field: 'id',
                title: '编号',
                formatter: function (value, row,index) {
                    return (index+1);
                }
            }, {
                field: 'number',
                title: '学号',
                sortable:true
            },
                {
                    field: 'name',
                    title: '姓名',

                }],
        });
    })


    //所有学生数据
    groupMembersModal.on('show.bs.modal', function (e) {
        //设置group_id 到data属性
        addMemberToGroupBtn.attr('data',e.relatedTarget.name);
        table.bootstrapTable({
            url: listUrl,
            striped: true,
            sortStable: true,
            clickToSelect: true,
            pagination: true, //分页
            pageNumber:1,                       //初始化加载第一页，默认第一页
            pageSize: 10,                       //每页的记录行数（*）
            pageList: [10, 25, 50, 100],
            columns: [{
                checkbox:true,
            },{
                field: 'id',
                title: '编号',
                formatter: function (value, row,index) {
                    return '<a href="javascript:void(0)">'+(index+1)+'</a>';
                }
            }, {
                field: 'number',
                title: '编号',
                sortable:true
            },
                {
                    field: 'name',
                    title: '姓名',

                }],

        });
    })
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
    //编辑按钮
    groupEditModal.on('show.bs.modal', function (e) {
        $('#group_edit_name').val(e.relatedTarget.name);
        $('#group_edit_id').val(e.relatedTarget.id);
        groupEditForm.validate({
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
                            groupEditForm.modal('hide');
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
    //教师按钮
    teacherGroupBtn.click(function () {
        table.bootstrapTable('refresh',{url: '/admin/teacher/teachers'});
        $('#add_member_to_group').attr('name',teacherToGroupUrl);
    })
    //学生按钮
    studentGroupBtn.click(function () {
        table.bootstrapTable('refresh',{url: '/admin/student/students'});
        $('#add_member_to_group').attr('name',toGroupUrl);
    })
    //导师按钮
    advisorGroupBtn.click(function () {
        table.bootstrapTable('refresh',{url: '/admin/advisor/advisors'});
        $('#add_member_to_group').attr('name',advisorToGroupUrl);
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
    // groupMembersModal.validate({
    //     submitHandler: function (form) {
    //         var param = $(form).serialize();
    //         $.ajax({
    //             url: toGroupUrl,
    //             type: "post",
    //             dataType: "json",
    //             data: param,
    //             beforeSend : function ()
    //             {
    //                 $('#loading').modal('show');
    //             },
    //             success: function (data) {
    //                 $('#loading').modal('hide');
    //                 if(data.code === 0){
    //                     toastr.error(data.message);
    //                 }else if(data.code === 1){
    //                     toastr.success(data.message);
    //                     //关闭窗口
    //                     groupMembersModal.modal('hide');
    //                     location.reload();
    //                 }else{
    //                     toastr.error('code 501 未知错误');
    //                 }
    //             },
    //             error: function (data) {
    //                 $('#loading').modal('hide');
    //                 toastr.error('code 500 未知错误');
    //             }
    //         })
    //     }
    // })

// 新增
    groupAddForm.validate({
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
                            groupAddForm.modal('hide');
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