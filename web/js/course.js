/**
 * Created by jiangcoco on 2017/4/12.
 */

var table =  $('#users'),
    showCourseBtn =  $('#show_course_btn'),
    showCourseModal = $('#show_course_modal'),
    getOneUrl       = '/admin/course/one',

    studentGroupBtn =  $('#student_group'),
    teacherGroupBtn =  $('#teacher_group'),
    advisorGroupBtn =  $('#advisor_group'),
    addMemberToGroupBtn = $('#add_member_to_group'),

    courseAddModal = $('#course_add_modal'),

    courseEditModal = $('#course_edit_modal'),
    courseAddForm = $('#course_add_form'),
    courseEditForm = $('#course_edit_form'),
    groupMembersModal = $('#group_members_modal'),
    listUrl = '/admin/student/students',
    groupUrl = "/admin/group/onegroup",
    toGroupUrl = '/admin/student/studnettogroup',
    teacherToGroupUrl = '/admin/teacher/teachertogroup',
    advisorToGroupUrl= '/admin/advisor/advisortogroup',
    addUrl = "/admin/course/add",
    getOneUrl = "/admin/course/one",
    removeUrl = "/admin/course/remove",
    updateUrl = "/admin/course/update",
    uploadUrl= "/admin/course/upload",
    removeOpt =  $('#teacher_remove');


$(function () {
    //获取小组列表
    $.ajax({
        url: "/admin/group/ajaxgroups",
        dataType: "json",
        success: function (data) {
            $.each(data, function (index, objs) {
                $("#course_add_group").append("<option value="+objs.id+">" + objs.name + "</option>");
            });
        },
        error: function (data) {
            toastr.error('code 502 获取数据失败');
        }
    })
    //加载编辑器
    CKEDITOR.replace('course_plan',{toolbar:'Basic'});
    CKEDITOR.replace('course_goal',{toolbar:'Basic'});
    CKEDITOR.replace('course_info',{toolbar:'Basic'});
    CKEDITOR.replace('edit_course_info',{toolbar:'Basic'});
    CKEDITOR.replace('edit_course_plan',{toolbar:'Basic'});
    CKEDITOR.replace('edit_course_goal',{toolbar:'Basic'});
    //查看课程详情
    showCourseModal.on('show.bs.modal', function (e) {
        $.ajax({
            url: getOneUrl,
            type: "post",
            dataType: "json",
            data: {
                'id':e.relatedTarget.name,
            },
            beforeSend : function ()
            {
                $('#loading_data').show()
            },
            success: function (data) {
                $('#loading_data').hide();
                $('#show_course_title').html('<strong>课程名称:</strong><p>'+data.name +'('+data.teachHours+'课时)</p><hr>');
                $('#show_course_plan').html('<strong>课程计划:</strong>'+data.coursePlan + '<hr>');
                $('#show_course_info').html('<strong>课程简介:</strong>'+data.courseInfo + '<hr>');
                $('#show_course_goal').html('<strong>课程目标:</strong>'+data.courseGoal + '<hr>');
            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
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
            maxFileSize: '上传文件不得超过500KB',
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
            var imgUrl = '../uploads/course/'+data.result.fileName;
            $('#file_path').html('<img src='+imgUrl+' width="300px" height="200px">');
            $('#file_name').html(data.result.fileName);
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
    //edit upload
    $('#edit_fileupload').fileupload({
        url: uploadUrl,
        type:"post",
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(jpg|png)$/i,
        maxFileSize: 500*1024,
        maxNumberOfFiles:1,
        messages: {
            maxFileSize: '上传文件不得超过500KB',
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
        $('#edit_progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        if (data.result.code){
            toastr.success(data.result.message);
            var imgUrl = '../uploads/course/'+data.result.fileName;
            $('#edit_file_path').html('<img src='+imgUrl+' width="300px" height="200px">');
            $('#edit_file_name').html(data.result.fileName);
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

    //删除按钮
    $("button[class='btn btn-white btn-sm remove_course_btn']").click(function (e) {
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
    courseEditModal.on('show.bs.modal', function (e) {
        getGroups($(e.relatedTarget).data('groupname'),$(e.relatedTarget).data('groupid'));
        getOne(e.relatedTarget.id);
        courseEditForm.validate({
            submitHandler: function () {
                $.ajax({
                    url: updateUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'id':e.relatedTarget.id,
                        'name': $.trim($('#course_edit_name').val()),
                        'group_id': $('#course_edit_group').val(),
                        'is_finished': $('#course_edit_is_finished').val(),
                        'is_public': $('#course_edit_is_public').val(),
                        'teach_hours': $('#course_edit_teach_hours').val(),
                        'thumbnail':$('#edit_file_name').html(),
                        'course_plan':CKEDITOR.instances.edit_course_plan.getData(),
                        'course_goal':CKEDITOR.instances.edit_course_goal.getData(),
                        'course_info':CKEDITOR.instances.edit_course_info.getData()
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
                            courseEditModal.modal('hide');
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
    function getGroups(groupName,groupId) {
        $("#course_edit_group").empty();
        $.ajax({
            url: "/admin/group/ajaxgroups",
            dataType: "json",
            success: function (data) {
                $.each(data, function (index, objs) {
                    $("#course_edit_group").append("<option value="+objs.id+">" + objs.name + "</option>");
                });
                $("#course_edit_group").append("<option value="+groupId+">" + groupName + "</option>")
            },
            error: function (data) {
                toastr.error('code 502 获取小组数据失败');
            }
        })
    }

    function getOne(id) {
        $.ajax({
            url: getOneUrl,
            type: "post",
            dataType: "json",
            data: {
                'id':id
            },
            beforeSend : function ()
            {
                $('#loading').modal('show');
            },
            success: function (data) {
                $('#loading').modal('hide');
                $('#course_edit_name').val(data.name);
                $('#course_edit_teach_hours').val(data.teachHours);
                $('#course_edit_group').val(data.group.id);
                $('#course_edit_is_public').val(data.isPublic);
                $('#course_edit_is_finished').val(data.isFinished);
                CKEDITOR.instances.edit_course_info.setData(data.courseInfo);
                CKEDITOR.instances.edit_course_plan.setData(data.coursePlan);
                CKEDITOR.instances.edit_course_goal.setData(data.courseGoal);
                if(data.thumbnial){
                    var imgUrl = '../uploads/course/'+data.thumbnial;
                    $('#edit_file_path').html('<img src='+imgUrl+' width="300px" height="200px">');
                }
                $('#edit_file_name').html(data.thumbnial);
            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
        })
    }
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

// 新增
    courseAddForm.validate({
            submitHandler: function (form) {
                //alert(CKEDITOR.instances.course_plan.getData());
                var param = $(form).serialize();
                //console.log($(form));
               // param.course_plan = CKEDITOR.instances.course_plan.getData();
                $.ajax({
                    url: addUrl,
                    type: "post",
                    dataType: "json",
                    data: {
                        'name': $.trim($('#course_add_name').val()),
                        'group_id': $('#course_add_group').val(),
                        'is_finished': $('#course_add_is_finished').val(),
                        'is_public': $('#course_add_is_public').val(),
                        'teach_hours': $('#course_add_teach_hours').val(),
                        'thumbnail':$('#file_name').html(),
                        'course_plan':CKEDITOR.instances.course_plan.getData(),
                        'course_goal':CKEDITOR.instances.course_goal.getData(),
                        'course_info':CKEDITOR.instances.course_info.getData()
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
                            courseAddForm.modal('hide');
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