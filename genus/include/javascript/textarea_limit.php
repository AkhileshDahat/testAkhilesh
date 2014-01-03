<?php
function TextAreaLimit($form_name,$textarea_name,$maxlength) {
	echo "<script language='JavaScript'>\n";
		echo "<!--\n";
		echo "function countMsg(theForm)\n";
		echo "{\n";
			echo "textCount = theForm.char_count;\n";
			echo "textMsg = theForm.".$textarea_name.";\n";
			echo "var maxlength = ".$maxlength.";\n";
			echo "var charRemain = 0;\n";
			echo "var charRemain = maxlength - textMsg.value.length;\n";
			echo "if (charRemain < 0 )  {\n";
				echo "textMsg.value = textMsg.value.substring(0,maxlength);\n";
				echo "charRemain = 0;\n";
			echo "}\n";
			echo "textCount.value = charRemain;\n";
		echo "}\n";
	echo "// -->\n";
	echo "</script>\n";
}
?>