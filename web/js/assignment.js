/**
 * Created by jiangcoco on 2017/4/12.
 */

var table =  $('#students'),
    assignmentEditModal = $('#assignment_edit_modal'),
    assignmentAddForm = $('#assignment_add_form'),
    assignmentEditForm = $('#assignment_edit_form'),
    assignmentModal = $('#assignment_assign_modal'),
    assignToStudentBtn = $('#assign_to_student'),

    addUrl = "/admin/assignment/add",
    removeUrl = "/admin/assignment/remove",
    updateUrl = "/admin/assignment/update",
    getGroupsUrl = "/admin/assignment/groups",
    groupUrl = "/admin/group/students",
    toStudentsUrl = "/admin/assignment/assignment-to-students",
    getOneUrl= "/admin/assignment/one";


$(function () {
    CKEDITOR.replace('assignment_add_content',{toolbar:'Short',height:'500px'});
    CKEDITOR.replace('assignment_edit_content',{toolbar:'Short',height:'500px'});
    //显示学生
    assignmentModal.on('show.bs.modal', function (e) {
        var assignment_id = e.relatedTarget.id;
        var group_id =  $("a[class='assignment']").eq(0).attr('name');
        $("a[class='assignment']").click(function (e) {
            var group_id = $(this).attr('name');
            table.bootstrapTable('refresh',{query: {'group_id': group_id}});
        })

        table.bootstrapTable({
            url: groupUrl,
            method:'POST',
            contentType: 'application/x-www-form-urlencoded',
            queryParams:{
                'group_id':group_id
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
                checkbox:true,
            }, {
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
        assignToStudent(assignment_id);
    })
    //分派任务按钮
    function assignToStudent(assignment_id) {
        assignToStudentBtn.click(function (e) {
            var rows = table.bootstrapTable('getSelections');
            if(rows.length == 0)  return toastr.error('请选择数据');
            $.ajax({
                url: toStudentsUrl,
                type: "post",
                dataType: "json",
                data: {
                    'id':assignment_id,
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

                        assignmentModal.modal('hide');
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
    }


    assignmentAddForm.validate({
        submitHandler: function () {
            if (CKEDITOR.instances.assignment_add_content.getData() == '') {
                toastr.error('警告：内容不得为空！');
                CKEDITOR.instances.assignment_add_content.focus();
                return false;
            }
            $.ajax({
                url: addUrl,
                type: "post",
                dataType: "json",
                data: {
                    'title':$('#assignment_add_title').val(),
                    'teacher_id':$('#teacher_id').val(),
                    'content': CKEDITOR.instances.assignment_add_content.getData(),
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
                        assignmentAddForm.modal('hide');
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
                    $('#assignment_edit_title').val(data.title);
                    CKEDITOR.instances.assignment_edit_content.setData(data.content);
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
    assignmentEditModal.on('show.bs.modal', function (e) {
        getOne(e.relatedTarget.id);
        assignmentEditForm.validate({
            submitHandler: function () {
                if (CKEDITOR.instances.assignment_edit_content.getData() == '') {
                    toastr.error('警告：内容不得为空！');
                    CKEDITOR.instances.assignment_edit_content.focus();
                    return false;
                }
                $.ajax({
                    url: updateUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.relatedTarget.id,
                        'title':$('#assignment_edit_title').val(),
                        'content': CKEDITOR.instances.assignment_edit_content.getData(),
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
                            assignmentEditForm.modal('hide');
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

    // //保存按钮
    // addMemberToGroupBtn.click(function (e) {
    //     var rows = table.bootstrapTable('getSelections');
    //     if(rows.length == 0)  return toastr.error('请选择数据');
    //     var group_id = $(e.target).attr('data');
    //     if($('#add_member_to_group').attr('name')){
    //         var toGroupUrl = $('#add_member_to_group').attr('name');
    //     }else{
    //         var toGroupUrl = '/admin/student/studnettogroup';
    //     }
    //     $.ajax({
    //         url: toGroupUrl,
    //         type: "post",
    //         dataType: "json",
    //         data: {
    //             'group_id':group_id,
    //             'rows':rows,
    //         },
    //         beforeSend : function ()
    //         {
    //             $('#loading').modal('show');
    //         },
    //         success: function (data) {
    //             $('#loading').modal('hide');
    //             if(data.code === 0){
    //                 toastr.error(data.message);
    //             }else if(data.code === 1){
    //                 toastr.success(data.message);
    //                 //关闭窗口
    //                 groupMembersModal.modal('hide');
    //                 location.reload();
    //             }else{
    //                 toastr.error('code 501 未知错误');
    //             }
    //         },
    //         error: function (data) {
    //             $('#loading').modal('hide');
    //             toastr.error('code 500 未知错误');
    //         }
    //     })
    //
    //
    // })

        //刷新
        $('#refresh').click(function () {
            location.reload();
        })
})