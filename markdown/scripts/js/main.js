/*************************************************************************
    > File Name: js/preview.js
    > Author: Kee
    > Mail: chinboy2012@gmail.com 
    > Created Time: 2016年10月22日 星期六 13时18分41秒
 ************************************************************************/
// 为了避免修改flowChart.js和sequence-diagram.js的源码，所以使用Zepto.js时想支持flowChart/sequenceDiagram就得加上这一句
var jQuery = Zepto;

$(function() {
	var markdown = $("#markdown-text").html();
	var EditormdView;
	EditormdView = editormd.markdownToHTML("editormd-view", {
		markdown        : markdown ,//+ "\r\n" + $("#append-test").text(),
		htmlDecode      : true,       // 开启 HTML 标签解析，为了安全性，默认不开启
		//htmlDecode      : "style,script,iframe",  // you can filter tags decode
		//toc             : false,
		tocm            : true,    // Using [TOCM]
		tocContainer    : "#custom-toc-container", // 自定义 ToC 容器层
		//gfm             : false,
		//tocDropdown     : true,
		markdownSourceCode : true, // 是否保留 Markdown 源码，即是否删除保存源码的 Textarea 标签
		//emoji           : true,
		taskList        : true,
		tex             : true,  // 默认不解析
		flowChart       : true,  // 默认不解析
		sequenceDiagram : true,  // 默认不解析
	});
	//console.log("返回一个 jQuery 实例 =>", testEditormdView);
	// 获取Markdown源码
	//console.log(testEditormdView.getMarkdown());
	//alert(testEditormdView.getMarkdown());
});
