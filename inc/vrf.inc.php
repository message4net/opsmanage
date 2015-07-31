<?php
class strvrf extends DBSQL{
	public function __construct(){//加载父类构造函数,创建数据库连接
	parent::__construct();
	$strflag=0;
	$arrflag=0;
	$eqflag=0;
	$pageno=1;
	}
/**
	*功能:验证参数是否为真
	*参数:任意变量
	*返回:数值0或1，0为真，1为假
	*/
	public function SgStrNullVrf($str){
		if($str) $arrflag=1;
		else $arrflag=0;
		return $arrflag;
	}
/**
	*功能:验证参数是否为数组
	*参数:数据库查询结果，或者通过$_GET,$_POST方法获得的变量
	*返回:数值0或1，0为变量，1为数组
	*/
	public function IsArrVrf($arr){
		if(is_array($arr)) $arrflag=1;
		else $arrflag=0;
		return $arrflag;
	}
/**
	*功能:验证参数是否均为真
	*参数:数据库查询结果，或者通过$_GET,$_POST方法获得的变量
	*返回:数值0或1，0为有假，1为全真
	*/
	public function StrNullVrf($strarr){
		if($this->IsArrVrf($strarr)==1){
			foreach($strarr as $val){
				$flag=$this->SgStrNullVrf($val);
				if($flag==0) return $flag;
			}
		} else {
			$flag=$this->SgStrNullVrf($strarr);
			if($flag==0) return $flag;
		}
		unset($strarr);
		return $flag;
	}
/**
	*功能:验证参数是否相等
	*参数:任意2个变量
	*返回:数值0或1，0为不等，1为相等
	*/
	public function SgStrEqVrf($str,$str1){
		if($str==$str1){
			$eqflag=1;
		}else{
			$eqflag=0;
		}
		return $eqflag;
	}
/**
	*功能:验证参数是否对应相等
	*参数:数据库查询结果，或者通过$_GET,$_POST方法获得的变量
	*返回:数值0或1，0为有假，1为全真
	*/
	public function StrEqVrf($strarr,$strarr1){
		if($this->IsArrVrf($strarr)==$this->IsArrVrf($strarr1)){
			$tmp=$this->IsArrVrf($strarr);
			if($tmp==1){
				if(count($strarr)==count($strarr1)){
					$tmp1=count($strarr);
					for($i=0;$i<$tmp1;$i++){
						$flag=$this->SgStrEqVrf($strarr[$i],$strarr1[$i]);
						if($flag==0){
							$this->DfnErrMsg($strarr1[$i].'有误，请确认');
							return $flag;
						}
					}
				}else{
					$this->DfnErrMsg('判断处理有误，请联系管理员');
					$flag=0;
					return $flag;
				}
			}else{
				$flag=$this->SgStrEqVrf($strarr,$strarr1);
				if($flag==0){
					$this->DfnErrMsg($strarr1[$i].'有误，请确认');
					return $flag;
				}
			}
		} else {
			$this->DfnErrMsg('判断处理有误，请联系管理员');
			$flag=0;
			return $flag;
		}
		if($this->IsArrVrf($strarr)==1) unset($strarr);
		if($this->IsArrVrf($strarr1)==1) unset($strarr1);
		return $flag;
	}
/**
	 *功能:描述参数状态
	 *参数:参数具体状态的描述
	 *返回:参数具体状态的描述
	 */
	public function DfnErrMsg($errmsg){
		if(!$_SESSION['errmsg']) $_SESSION['errmsg']=$errmsg;
		else $_SESSION['errmsg'].='###'.$errmsg;
	}
/**
	 *功能:参数状态的描述信息打印
	 *参数:参数具体状态的描述
	 *返回:参数具体状态的描述
	 */
	public function PrntErrMsg($errmsg){
		//              echo $_SESSION['errmsg'];
		echo "<div>".$_SESSION['errmsg']."</div>";
		unset($_SESSION['errmsg']);
	}
/**
        *功能:转换文件编码(gbk->utf-8)
        *参数:1、文件内容;2、文件路径
        *返回:无
        */
        public function EnCd($content,$filepath,$filename){
                if($content!==iconv('gbk','utf-8',iconv('utf-8','gbk',$content))){
                        $handles=fopen($filepath.$filename,w);
                        fwrite($handles,iconv('gbk','utf-8',$content));
                        fclose($filepath.$filename);
                }
        }
/**
        *功能:转换文件编码(utf-8->gbk)
        *参数:1、文件内容;2、文件路径
        *返回:无
        */
        public function DeCd($content,$filepath,$filename){
                if($content!==iconv('utf-8','gbk',iconv('gbk','utf-8',$content))){
                        $handles=fopen($filepath.$filename,w);
                        fwrite($handles,iconv('utf-8','gbk',$content));
                        fclose($filepath.$filename);
                }
        }
/**
        *功能:翻译html的符号编码
        *参数:1、内容;
        *返回:翻译后的内容
        */
        public function TrnltHtmlChrct($filepath,$filename){
		exec("sed -i 's/&nbsp;/\&#160;/g' ".$filepath.$filename);
		exec("sed -i 's/&mdash;/—/g' ".$filepath.$filename);
		exec("sed -i 's/&ldquo;/“/g' ".$filepath.$filename);
		exec("sed -i 's/&rdquo;/”/g' ".$filepath.$filename);
		exec("sed -i 's/<p>//g' ".$filepath.$filename);
		exec("sed -i 's/<\/p>/<br\/><br\/>/g' ".$filepath.$filename);
//		exec("sed -i 's/<\/p>/<br\/>/g' ".$filepath.$filename);
        }
/**
        *功能:分页显示记录
        *参数:1、目标页数;2、总页数;3、表名
        *返回:含有目标页内容、分页导航条的数组
        */
        public function DsplByPg($currentpagenum,$tablename,$link,$formlink,$perpagenum,$privilegeid){
		if($this->StrNullVrf($perpagenum)==0)
			$perpagenum=7;
		if($this->IsArrVrf($tablename)==0){
			$data=$this->select("select count(*) cnt from ".$tablename);
			$recordnum=$data[0][cnt];
		}else{
			$recordnum=count($tablename);
		}
		if($recordnum==0)
			$totalpagenum=1;
		else
			$totalpagenum=ceil($recordnum/$perpagenum);
		if($totalpagenum<$currentpagenum)
			$pageno=$totalpagenum;
		else
			$pageno=$currentpagenum;
		$recordstartnum=($pageno-1)*$perpagenum;
		if($this->IsArrVrf($tablename)==0){
			$data=$this->select("select * from ".$tablename." order by id desc limit ".$recordstartnum.",$perpagenum");
		}else{
			for($i=0;$i<$perpagenum;$i++){
				if($this->StrNullVrf($tablename[($i+$recordstartnum)])==1)
					$data[($i+$recordstartnum)]=$tablename[($i+$recordstartnum)];
			}
		}
		$pageinfobar="<table><td>当前页数/总页数:".$pageno."/".$totalpagenum."&nbsp;&nbsp;";
		if($pageno==1){
			if($pageno<>$totalpagenum)
				$pageinfobar.="<a href='".$link.($pageno+1)."'>下一页</a>&nbsp;&nbsp;<a href='".$link.$totalpagenum."'>尾页</a>&nbsp;&nbsp;";
		}else{
			if($pageno<>$totalpagenum)
				$pageinfobar.="<a href='".$link."1'>首页</a>&nbsp;&nbsp;<a href='".$link.($pageno-1)."'>上一页</a>&nbsp;&nbsp;<a href='".$link.($pageno+1)."'>下一页</a>&nbsp;&nbsp;<a href='".$link.$totalpagenum."'>尾页</a>&nbsp;&nbsp;";
			else
				$pageinfobar.="<a href='".$link."1'>首页</a>&nbsp;&nbsp;<a href='".$link.($pageno-1)."'>上一页</a>&nbsp;&nbsp;";
		}
		$pageinfobar.="</td><td><form action='".$formlink."'>跳转至<input type='text' name='pgnm' value='请输入阿拉伯数字'/>页<input type='hidden' name='prvlg' value='".$privilegeid."'><input type='submit' value='点击跳转'/></form></td></table>";
		if($this->IsArrVrf($tablename)==1)
			unset($tablename);
		return array($pageinfobar,$data);
        }
}
?>
