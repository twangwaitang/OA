/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    //config.enterMode = CKEDITOR.ENTER_BR;
    config.extraPlugins = 'confighelper';
    config.toolbar = 'Basic';
    config.toolbar_Basic = [
        //加粗     斜体，     下划线      穿过线
        ['Bold','Italic','Underline','Strike'],
        // 数字列表          实体列表            减小缩进    增大缩进
        ['NumberedList','BulletedList','-','Outdent','Indent'],
        //左对 齐             居中对齐          右对齐          两端对齐
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        //超链接  取消超链接 锚点
        ['Link','Unlink','Anchor'],
        //图片    flash    表格       水平线
        ['Image','Flash','Table','HorizontalRule','SpecialChar'],
        '/',
        // 样式       格式      字体    字体大小
        ['Styles','Format','Font','FontSize'],
        //文本颜色     背景颜色
        ['TextColor','BGColor']
    ];
    config.toolbar = 'Short';
    config.toolbar_Short = [
        //加粗     斜体，     下划线      穿过线
        ['Bold','Italic','Underline','Strike'],
        // 数字列表          实体列表
        ['NumberedList','BulletedList'],
        //左对 齐             居中对齐          右对齐          两端对齐
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],

        //图片        表格       水平线
        ['Image','Table','HorizontalRule','SpecialChar'],
        // '/',
        // // // 样式       格式      字体    字体大小
        // // ['Font','FontSize'],
        // // //文本颜色     背景颜色
        // // ['TextColor','BGColor']
    ];
    config.toolbar = 'Res';
    config.toolbar_Res = [
        //加粗     斜体，     下划线      穿过线
        ['Bold','Italic','Underline','Strike'],
        //超链接  取消超链接 锚点
        ['Link','Unlink','Anchor']
    ];
};
