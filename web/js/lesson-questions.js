
/**
 * Created by jiangcoco on 2017/4/12.
 */

var table =  $('#questions'),
    selectQuestionsModal = $('#select_questions_modal'),
    addQuestionToLessonBtn = $('#add_question_to_lesson_btn'),
    questionShowModal = $('#question_show_modal'),
    getOneUrl = "/admin/questions/one",
    listUrl = '/admin/questions/list',
    questionToLessonUrl = '/admin/lesson/add_question_to_lesson',
    removeUrl = "/admin/lesson/remove_question_to_lesson",
    groupDetailTable =  $('#group_detail'),
    studentGroupBtn =  $('#student_group'),
    teacherGroupBtn =  $('#teacher_group'),
    advisorGroupBtn =  $('#advisor_group'),


    groupCreateModal = $('#group_create_modal'),
    groupEditModal = $('#group_edit_modal'),
    groupAddForm = $('#group_add_form'),
    groupEditForm = $('#group_edit_form'),
    groupMembersModal = $('#group_members_modal'),

    groupUrl = "/admin/group/onegroup",
    toGroupUrl = '/admin/student/studnettogroup',
    teacherToGroupUrl = '/admin/teacher/teachertogroup',
    advisorToGroupUrl= '/admin/advisor/advisortogroup',
    addUrl = "/admin/group/add",

    updateUrl = "/admin/group/update",
    removeOpt =  $('#teacher_remove');


$(function () {

    $('#single_chioce').click(function (e) {
        table.bootstrapTable('refresh',{query: {'courseId': e.target.name, 'type': 0}});
    })
    $('#multiple_chioce').click(function (e) {
        table.bootstrapTable('refresh',{query: {'courseId': e.target.name, 'type': 1}});
    })
    $('#true_or_false').click(function (e) {
        table.bootstrapTable('refresh',{query: {'courseId': e.target.name, 'type': 3}});
    })
    //所有习题库数据
    selectQuestionsModal.on('show.bs.modal', function (e) {
        table.bootstrapTable({
            url: listUrl,
            method:'POST',
            contentType: 'application/x-www-form-urlencoded',
            queryParams:{
                    'courseId': e.relatedTarget.name,
                    'type': 0
            },
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
            },
            {
                field: 'title',
                title: '题目',
            },
                {
                    field: 'level',
                    title: '难度',
                    sortable:true,
                    formatter: function (value, row,index) {
                        if(value == 0) return '简单';
                        if(value == 1) return '中等';
                        if(value == 2) return '困难';
                    }
                }
            ],

        });
    })

    //保存按钮
    addQuestionToLessonBtn.click(function (e) {
        var rows = table.bootstrapTable('getSelections');
        if(rows.length == 0)  return toastr.error('请选择数据');
        var lesson_id = e.target.name;
        $.ajax({
            url: questionToLessonUrl,
            type: "post",
            dataType: "json",
            data: {
                'lesson_id':lesson_id,
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
                    selectQuestionsModal.modal('hide');
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

    //显示
    questionShowModal.on('show.bs.modal', function (e) {
        $('#question_show_answers').empty();
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
                $('#question_show_title').html(data.title + '<hr>');
                for(var i=0;i<data.question_answer.length;i++){
                    var flag = String.fromCharCode(64 + parseInt(i+1));
                    var q = '<div>'+flag+'.'+data.question_answer[i]+'</div>';
                    $('#question_show_answers').append(q);
                }
                $('#question_answer').html('<strong>正确答案:</strong>【'+data.answer+'】');

            },
            error: function (data) {
                $('#loading').modal('hide');
                toastr.error('code 500 未知错误');
            }
        })
    })

    //remove
    $("button[class='btn btn-white btn-sm question_remove_btn']").click(function (e) {
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
                    'lesson_id':e.target.id,
                    'question_id':e.target.name,
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