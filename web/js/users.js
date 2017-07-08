/**
 * Created by jiangcoco on 2017/4/12.
 */
var table =  $('#users'),
    passwordReset = $('#password_reset'),
    groups = $('#user_group a');
var positionSource;

$(function () {
    //获取职位列表
    // $.ajax({
    //     url: "'/admin/positions",
    //     dataType: "json",
    //     success: function (data) {
    //         positionSource = data;
    //         console.log(positionSource);
    //         $.each(data, function (index, objs) {
    //             $("#positions").append("<option value="+objs.id+">" + objs.name + "</option>");
    //         });
    //     },
    //     error: function (data) {
    //         toastr.error('code 500 未知错误');
    //     }
    // })


    //分组
    groups.click(function (e) {
        var roles = $(e.target).attr('name');
        table.bootstrapTable('refresh',{query: {roles: roles}});
    })
    //密码重置
    passwordReset.click(function () {
        var rows = table.bootstrapTable('getSelections');
        if (!rows.length) {
            toastr.error('请选择数据');
        } else if (rows.length > 0) {
            ids = $.map(table.bootstrapTable('getSelections'), function (row) {
                return row.id
            });
            swal({
                title: "您确定要重置密码吗？",
                text: "重置密码位123456，请谨慎操作！",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "重置",
                closeOnConfirm: true,
            }, function () {
                $.ajax({
                    url: "/admin/resetpassword",
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
    table.bootstrapTable({
        url: '/admin/users',
        striped: true,
        sortStable: true,
        toolbar: '#userToolbar',
        clickToSelect: true,
        pagination: true, //分页
        pageNumber:1,                       //初始化加载第一页，默认第一页
        pageSize: 20,                       //每页的记录行数（*）
        pageList: [20, 50, 100],
        search:true,
        columns: [{
            checkbox:true,
        },{
            field: 'id',
            title: '编号',
            formatter: function (value, row,index) {
                return '<a href="javascript:void(0)">'+(index+1)+'</a>';
            }
        }, {
            field: 'username',
            title: '用户名'
        }, {
            field: 'roles',
            title: '角色',
            editable: {
                type: 'select',
                display: function(value) {
                    if (value === 'ROLE_STUDENT'){
                        $(this).text('学生');
                    }else if (value === 'ROLE_TEACHER'){
                        $(this).text('教师');
                    }else if (value === 'ROLE_ADVISOR'){
                        $(this).text('导师');
                    }else if(value === 'ROLE_ADMIN'){
                        $(this).text('管理员');
                    }
                },
                title: '角色',
                source:[{value:"ROLE_USER",text:"学生"},{value:"ROLE_TEACHER",text:"教师"},{value:"ROLE_ADVISOR",text:"导师"}]
            }
        }, {
                field: 'created_time',
                title: '创建时间',
                sortable:true,

            }],
        //编辑
        onEditableSave: function (field, row, oldValue, $el) {
            $.ajax({
                type: "post",
                url: "/admin/updateuser",
                data: row,
                dataType: 'JSON',
                beforeSend : function ()
                {
                    $('#loading').modal('show');
                },
                success: function (data) {
                    $('#loading').modal('hide');
                    if (data.code === 1) {
                        toastr.success(data.message);
                    }else if(data.code === 0){
                        toastr.error(data.message);
                    }else{
                        toastr.error('code 501 未知错误');
                    }
                },
                error: function () {
                    alertoastr.error('code 500 未知错误');
                },
                complete: function () {

                }

            });
        }
    });
// 新增
    var userAddForm = $("#userAddForm").validate({
        submitHandler: function (form) {
            var param = $(form).serialize();
            $.ajax({
                url: "/admin/addusers",
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
                        $('#modal-form').modal('hide');
                        //reload table
                        $('#users').bootstrapTable('refresh');
                        //reset form ?????没做出来

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
    $('#user_add').click(function () {
        userAddForm.resetForm();
    })

//remove
    $('#user_remove').click(function () {
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
                    url: "/admin/removeusers",
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


    $('#refresh').click(function () {
        location.reload();
    })
    
    
    
})