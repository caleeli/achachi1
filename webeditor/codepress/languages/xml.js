/*
 * CodePress regular expressions for XSL syntax highlighting
 * By RJ Bruneel
 */

Language.syntax = [ // XSL
	{
	input : /(&lt;[^!]*?&gt;)/g,
	output : '<b>$1</b>' // all tags
	},{
	input : /=(".*?")/g,
	output : '=<s>$1</s>' // atributes double quote
	},{
	input : /=('.*?')/g,
	output : '=<s>$1</s>' // atributes single quote
	},{
	input : /(&lt;!--.*?--&gt.)/g,
	output : '<ins>$1</ins>' // comments 
	}
];

Language.snippets = [
];
	
Language.complete = [ // Auto complete only for 1 character
];

Language.shortcuts = [];