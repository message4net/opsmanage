<?php
class dfnvar extends strvrf{
	public function __construct(){//加载父类构造函数,创建数据库连接
	parent::__construct();
	}
/**
	*功能:构造数组变量
	*参数:1、数据库查询结果;2、参数数组名称
	*返回:数组
	*/
	public function StrctVar($result,$colname){
		if(count($result)==1) $arr=$result[0][$colname];
		else {
			$count=0;
			foreach($result as $val){
				$arr[]=$result[$count][$colname];
				$count++;
			}
		}
		return $arr;
	}
/**
	*功能:构造映射数组变量
	*参数:1、数据库查询结果;2、数组索引字段名称;3、数组值字段名称
	*返回:数组
	*/
	public function StrctRflctVar($data,$colname,$colname1){
		if(count($data)==1) $arr[$colname]=$data[0][$colname1];
		else {
			$count=0;
			foreach($data as $val){
				$arr[$data[$count][$colname]]=$data[$count][$colname1];
				$count++;
			}
		}
		return $arr;
	}
/**
	*功能:构造菜单类表格头
	*参数:1、表格菜单提示;2、表格菜单表头数组;3、表格菜单数组(链接，说明)
	*返回:html表格代码
	*/
	public function StrctTblMnStrt($tips,$tablearray,$tiparray){
			$num=count($tablearray)-2;
	        echo "<div class='content'>".$tips."</div><table><tr>";
		foreach($tablearray as $val)
			echo "<th>".$val."</th>";
		foreach($tiparray as $val)
			echo "</tr><tr><td>-</td><td colspan='".$num."'>-</td><td><a href='".$val[0]."'>".$val[1]."</a></td></tr>";
		unset($tablearray);
		unset($tiparray);
	}
/**
	*功能:构造创建类表格头
	*参数:1、表格提示;2、表格表头数组;3、表格链接;
	*返回:html表格代码
	*/
	public function StrctTblCrtStrt($tips,$tablearray,$link){
	        echo "<div class='content'>".$tips."</div>";
        	echo "<table><form action='".$link."' method='post'>";
		foreach($tablearray as $val)
			echo "<tr><td>".$val[0]."</td><td><input name='".$val[1]."' type='".$val[2]."' value='".$val[3]."'/></td></tr>";
		unset($tablearray);
	}
/**
	*功能:构造html单选代码
	*参数:1、表格提示;2、表格表头数组;3、表格链接;4、表格链接说明
	*返回:html表格代码
	*/
	public function StrctOptn($data,$tablearray,$flag,$flagarray){
		echo "<tr><td>".$tablearray[0]."</td><td><select name='".$tablearray[1]."'>";
		foreach($data as $val){
	                if($val[$flagarray[0]]==$flag)
	                        echo "<option value='".$val[$flagarray[0]]."' selected='selected'>".$val[$flagarray[1]]."</option>";
	                else
	                        echo "<option value='".$val[$flagarray[0]]."'>".$val[$flagarray[1]]."</option>";
		}
	        echo "</select></td></tr>";	
		unset($tablearray);
		unset($flagarray);
	}
/**
	*功能:构造html文本编辑器代码
	*参数:1、表格表头数组;
	*返回:html表格代码
	*/
	public function StrctEdtr($tablearray){
		echo "<tr><td>".$tablearray[0]."</td><td><textarea name='".$tablearray[1]."'>".$tablearray[2]."</textarea>";
		echo "                        <script type='text/javascript'>CKEDITOR.replace('".$tablearray[1]."',";
	        echo "{";
		echo "filebrowserBrowseUrl :  '".DftPth."ckfinder/ckfinder.html',";
		echo "filebrowserImageBrowseUrl :  '".DftPth."ckfinder/ckfinder.html?Type=Images',";
		echo "filebrowserFlashBrowseUrl :  '".DftPth."ckfinder/ckfinder.html?Type=Flash',";
		echo "filebrowserUploadUrl :  '".DftPth."ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',";
	        echo "filebrowserImageUploadUrl  :  '".DftPth."ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',";
		echo "filebrowserFlashUploadUrl  :  '".DftPth."ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'";
		echo "}";
		echo ");</script></td></tr>";
		unset($tablearray);
	}
/**
	*功能:构造html多选代码
	*参数:1、表格提示;2、表格表头数组;3、表格链接;4、表格链接说明
	*返回:html表格代码
	*/
	public function StrctChkbx($tips,$tablearray,$cntnr,$verifyarray){
		echo "<tr><td>".$tips."</td><td></td></tr>";
		foreach($tablearray as $key=>$val)
			if(in_array($key,$verifyarray) || $key==$verifyarray)
				echo "<tr><td></td><td><input name='".$cntnr."' type='checkbox' value='".$key."' checked='checked' />".$val['name']."</td></tr>";
			else
				echo "<tr><td></td><td><input name='".$cntnr."' type='checkbox' value='".$key."' />".$val['name']."</td></tr>";
		unset($tablearray);
		unset($verifyarray);
	}
/**
	*功能:构造表格菜单尾
	*参数:无
	*返回:html表格代码
	*/
	public function StrctTblEnd($type,$tips){
		switch ($type) {
		case "Mn":
			echo "</table>";
			break;
		case "Crt":
			echo "<tr><td colspan='2' align='center'><input name='sbmt' type='submit' value='".$tips."'/></td></tr>";
			echo "</form></table>";
			break;
		default:
		        echo "类型未知，请联系管理员";
		        break;
		}
	}
/**
	*功能:构造公告头
	*参数:1、公告标题;2、公告路径;3、公告文件名称;
	*返回:公告头部手机网页代码
	*/
	public function StrctNtcStrt($title,$filepath,$filename){
		$handles=fopen($filepath."UTF8".$filename,w);
		fwrite($handles,"<%@ include file=\"../../../waphead.jsp\" %>".CGRowChr);
		fwrite($handles,"<%@ page pageEncoding=\"gb2312\" %>".CGRowChr);
		fwrite($handles,"<!DOCTYPE wml PUBLIC '-//WAPFORUM//DTD WML 1.1//EN' 'http://www.wapforum.org/DTD/wml_1.1.xml'>".CGRowChr);
		fwrite($handles,"<wml>".CGRowChr);
		fwrite($handles,"<card title=\"".$title.WpHdRt.CGRowChr);
		fwrite($handles,"<p>".CGRowChr);
		fclose($handles);
	}
/**
	*功能:构造公告内容
	*参数:1、表格提示;2、表格表头数组;3、表格链接;
	*返回:公告尾部手机网页代码
	*/
	public function StrctNtcCntnt($content,$title,$filepath,$filename){
		$handles=fopen($filepath."TMPC".$filename,w);
		fwrite($handles,$content);
		fclose($handles);
		exec("sed -i 's/^/\&#160;\&#160;\&#160;\&#160;/g;s/alt=\"\"/alt=\"".$title."\"/g;s/\(.*src=\"\).*\(images\/[^\"]*\"\).*\(\/>\)/\\1..\/\\2\\3/g' ".$filepath."TMPC".$filename);
		$this->TrnltHtmlChrct($filepath,"TMPC".$filename);
		$handle1=file_get_contents($filepath."TMPC".$filename);
		file_put_contents($filepath."UTF8".$filename,$handle1,FILE_APPEND);
	}
/**
	*功能:构造公告尾
	*参数:1、表格提示;2、表格表头数组;3、表格链接;
	*返回:公告尾部手机网页代码
	*/
	public function StrctNtcEnd($filepath,$filename){
		file_put_contents($filepath."UTF8".$filename,"<br/><br/>".CGRowChr,FILE_APPEND);
		file_put_contents($filepath."UTF8".$filename,"<a href=\"<%=wap.encodeURL(\"http://wap.ljsy.net/index.jsp\")%>\">返回首页</a><br/>".CGRowChr,FILE_APPEND);
		file_put_contents($filepath."UTF8".$filename,"</p>".CGRowChr,FILE_APPEND);
		file_put_contents($filepath."UTF8".$filename,"</card>".CGRowChr,FILE_APPEND);
		file_put_contents($filepath."UTF8".$filename,"</wml>".CGRowChr,FILE_APPEND);
	}
/**
	*功能:发布公告
	*参数:1、表格提示;2、表格表头数组;3、表格链接;
	*返回:公告尾部手机网页代码
	*/
	public function PshNtc($content,$title,$ofilename,$imagename,$filepath,$filename,$noticeseq,$noticeid){
		$filename1="GBK".$filename;
		$this->StrctNtcStrt($title,$filepath,$filename);
		$this->StrctNtcCntnt($content,$title,$filepath,$filename);
		$this->StrctNtcEnd($filepath,$filename);
		$handle=file_get_contents($filepath."UTF8".$filename);
		$this->DeCd($handle,$filepath,$filename1);
		exec("scp ".$filepath.$filename1." ".SshUser."@".SshHost.":".RmtPth."article/".$filename);
		copy($filepath."UTF8".$filename,$filepath.$filename);
		exec("sed -i '/".$ofilename."/d' ".$filepath."../index1.jsp");
		exec("sed -i '".(IndxTtlRowNm-1+$noticeseq)."a\<a href=\"<%=wap.encodeURL(\"".StUrl."wap/0p5/article/".$filename."\")%>\">".$title."</a><br/>' ".$filepath."../index1.jsp");
		copy($filepath."../index1.jsp",$filepath."../UTF8index1.jsp");
		exec("iconv -f utf-8 -t gbk ".$filepath."../UTF8index1.jsp>".$filepath."../index1.jsp");
		exec("scp ".$filepath."../index1.jsp ".SshUser."@".SshHost.":".RmtPth."../index1.jsp");
		copy($filepath."../UTF8index1.jsp",$filepath."../index1.jsp");
		$this->update("update ".TblNmIndx." set title='".$title."',name='".$filename."' where name='".$ofilename."'");
		exec("sed -i 's/&#160;//g' ".$filepath."TMPC".$filename);
		$content1=file_get_contents($filepath."TMPC".$filename);
		if($this->StrNullVrf($noticeid)==0)
			$this->insert("insert into ".TblNmNtc." (name,title,content) values('".$filename."','".$title."','".$content1."')");
		else
			$this->update("update ".TblNmNtc." set name='".$filename."',title='".$title."',content='".$content1."' where id=".$noticeid);
		unlink($filepath.$filename1);
		unlink($filepath."UTF8".$filename);
		unlink($filepath."../UTF8index1.jsp");
		unlink($filepath."TMPC".$filename);
	}
}
?>
