/**
 * Created by jiangcoco on 2017/4/12.
 */
var advisorTable =  $('#advisor'),
    advisoraddForm = $('#advisor_add_form'),
    advisortoolBar = '#advisor_toolbar',
    advisorlistUrl = '/admin/advisor/advisors',
    advisoraddUrl = "/admin/advisor/add",
    advisorremoveUrl = "/admin/advisor/remove",
    advisorupdateUrl = "/admin/advisor/update",
    advisorexportUrl = '/admin/advisor/export',
    advisoruploader = $('#uploader'),
    advisorremoveOpt =  $('#advisor_remove');
    advisorexportOpt =  $('#advisor_export');


$(function () {
    $('#advisor_tab').on('shown.bs.tab', function (e) {
        advisorTable.bootstrapTable({
            url: advisorlistUrl,
            striped: true,
            sortStable: true,
            toolbar: advisortoolBar,
            clickToSelect: true,
            pagination: true, //分页
            pageNumber:1,                       //初始化加载第一页，默认第一页
            pageSize: 15,                       //每页的记录行数（*）
            pageList: [10, 25, 50, 100],
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
                field: 'number',
                title: '工号',
                sortable:true
            },
                {
                    field: 'name',
                    title: '姓名',
                    editable: {
                        type: 'text',
                        title: '姓名',
                        validate: function (value) {
                            if (!$.trim(value)) return '姓名不能为空';
                            if (value.length < 2) return '姓名不能小于2位';
                            if (value.length > 10) return '姓名不能大于10位';
                        }
                    }
                },
                {
                    field: 'gendar',
                    title: '性别',
                    sortable:true,
                    editable: {
                        type: 'select',
                        display: function(value) {
                            $(this).text(value);
                        },
                        title: '性别',
                        source:[{value:"男",text:"男"},{value:"女",text:"女"}]
                    }
                }, {
                    field: 'tel',
                    title: '联系方式',
                    sortable:true,

                    editable: {
                        type: 'text',
                        title: '联系方式',
                        validate: function (value) {
                            if (isNaN(value)) return '手机号码必须是数字';
                            if (value.length < 11) return '手机号码必须为11位';
                            if (value.length > 11) return '手机号码必须为11位';
                        }
                    }
                },
                {
                    field: 'created_time',
                    title: '创建时间',
                    sortable:true,
                },
                {
                    field: 'isTop',
                    title: '推荐到首页',
                    sortable:true,
                    editable: {
                        type: 'select',
                        display: function(value) {
                            if (!value ){
                                $(this).text('不推荐');
                            }else{
                                $(this).text('推荐');
                            }
                        },
                        title: '性别',
                        source:[{value:"0",text:"不推荐"},{value:"1",text:"推荐"}]
                    }
                }
            ],
            //编辑
            onEditableSave: function (field, row, oldValue, $el) {
                $.ajax({
                    type: "post",
                    url: advisorupdateUrl,
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
        advisoraddForm.validate({
            submitHandler: function (form) {
                var param = $(form).serialize();
                $.ajax({
                    url: advisoraddUrl,
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
                            advisoraddForm.modal('hide');
                            //reload table
                            advisorTable.bootstrapTable('refresh');
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
//remove
        advisorremoveOpt.click(function () {
            var rows = advisorTable.bootstrapTable('getSelections');
            if (!rows.length) {
                toastr.error('请选择删除数据');
            } else if (rows.length > 0) {
                ids = $.map(advisorTable.bootstrapTable('getSelections'), function (row) {
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
                        url: advisorremoveUrl,
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
                    advisorTable.bootstrapTable('remove', {
                        field: 'id',
                        values: ids
                    });
                });
            }
        })
        //刷新

        $('#advisor_refresh').click(function () {
            location.reload();
        })
    })
})
