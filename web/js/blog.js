var blogShowModal = $('#blog_show_modal');



$(function () {

    CKEDITOR.replace('post_blog',{toolbar:'Short',height:'500px'});
    blogShowModal.on('show.bs.modal', function (e) {
        $('#blog_submit').click(function () {
            if (CKEDITOR.instances.post_blog.getData() == '') {
                toastr.error('警告：内容不得为空！');
                CKEDITOR.instances.post_blog.focus();
                return false;
            }
            $.ajax({
                url: '/blog/add',
                type: "post",
                dataType: "json",
                data: {
                    'username':e.relatedTarget.id,
                    'title':$('#blog_title_name').val(),
                    'content': CKEDITOR.instances.post_blog.getData(),
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
                        blogShowModal.modal('hide');
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

})


