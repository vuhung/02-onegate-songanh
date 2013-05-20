<?php

$html = '<html xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>

<body>
<table>
	<tr>
		<td nowrap align=center>'.$config->sys_info->unit.'</td>
		<td width=100% align=center></td>
		<td nowrap align=center>Cộng Hòa Xã Hội Chủ Nghĩa Việt Nam</td>
	</tr>
	<tr>
		<td nowrap align=center>'.$config->sys_info->company.'</td>
		<td width=100% align=center></td>
		<td nowrap align=center>Độc lập - Tự do - Hạnh phúc</td>
	</tr>
	<tr>
		<td nowrap align=center>------------</td>
		<td width=100% align=center></td>
		<td nowrap align=center>------------</td>
	</tr>
	<tr>	
		<td colspan=3 width=100% align=center><h1>LỊCH CÔNG TÁC TUẦN '.substr($week,4,2).' NĂM '.substr($week,0,4).'<h1></td>
	</tr>
</table>
<table border=1 bordercolor="black" cellpadding="0" cellspacing="0" style="border: 1px solid black; border-collapse: collapse" width="100%">
 <tr style="font-weight: bold;text-align: center;">
  <td colspan="2" nowrap="nowrap">Ngày tháng</td>
  <td width="33%">N&#7897;i dung</td>
  <td width="33%">&#272;&#7883;a &#273;i&#7875;m</td>
  <td width="33%">Thành ph&#7847;n</td>
 </tr>
';
	function countbuoi($data1,$posdata1,$data2,$buoi,$currentdate){
		//dem data1 neu gio bat dau trong buoi sdang (<12h)
		$indate = 0;
		$alldate = 0;
		for($i=0;$i<count($data1[$posdata1]);$i++){
			$begintime = getdate(strtotime($data1[$posdata1][$i]['BEGINTIME']));
			$endtime = getdate(strtotime($data1[$posdata1][$i]['ENDTIME']));
			if($buoi==1 && $begintime['hours']<12){
				$indate++;
			}else if($buoi==2 && $begintime['hours']>=12){
				$indate++;
			}else if($buoi==1 && $endtime['hours']<12){
				$indate++;
			}else if($buoi==2 && $endtime['hours']>=12){
				$indate++;
			}
		}
		for($i=0;$i<count($data2);$i++){
			//check ngay dang chon so voi ngay trong list
			$begintime = date("Y-m-d",strtotime($data2[$i]['BEGINTIME']));
			$endtime = date("Y-m-d",strtotime($data2[$i]['ENDTIME']));
			$currentdate1 = date("Y-m-d",$currentdate);
			//neu ngay hien tai = ngay bat dau
			$ok=true;
			if($currentdate1==$begintime){
				$begintime = getdate(strtotime($data2[$i]['BEGINTIME']));
				$endtime = getdate(strtotime($currentdate1." 23:59:59"));
			}else if($currentdate1>$begintime && $currentdate1<$endtime){
				$begintime = getdate(strtotime($currentdate1." 00:00:00"));
				$endtime = getdate(strtotime($currentdate1." 23:59:59"));
			}else if($currentdate1==$endtime){
				$begintime = getdate(strtotime($currentdate1." 00:00:00"));
				$endtime = getdate(strtotime($data2[$i]['ENDTIME']));
			}else{
				$ok=false;
			}
			if($ok){
				if($buoi==1 && $begintime['hours']<12){
					$indate++;
				}else if($buoi==2 && $begintime['hours']>=12){
					$indate++;
				}else if($buoi==1 && $endtime['hours']<12){
					$indate++;
				}else if($buoi==2 && $endtime['hours']>=12){
					$indate++;
				}
			}
			//$alldate++;
		}
		if($indate==0){
			return 1;
		}
		return $indate;
	}
	function getbuoi($data1,$posdata1,$data2,$buoi,$currentdate){
		//dem data1 neu gio bat dau trong buoi sdang (<12h)
		$indate = array();
		for($i=0;$i<count($data1[$posdata1]);$i++){
			$begintime = getdate(strtotime($data1[$posdata1][$i]['BEGINTIME']));
			$endtime = getdate(strtotime($data1[$posdata1][$i]['ENDTIME']));
			if($buoi==1 && $begintime['hours']<12){
				$indate[]['CONTENT'] = $data1[$posdata1][$i]['CONTENT'];
				$indate[count($indate)-1]['THANHPHAN'] = $data1[$posdata1][$i]['THANHPHAN'];
				$indate[count($indate)-1]['DIADIEM'] = $data1[$posdata1][$i]['DIADIEM'];
			}else if($buoi==2 && $begintime['hours']>=12){
				$indate[]['CONTENT'] = $data1[$posdata1][$i]['CONTENT'];
				$indate[count($indate)-1]['THANHPHAN'] = $data1[$posdata1][$i]['THANHPHAN'];
				$indate[count($indate)-1]['DIADIEM'] = $data1[$posdata1][$i]['DIADIEM'];
			}else if($buoi==1 && $endtime['hours']<12){
				$indate[]['CONTENT'] = $data1[$posdata1][$i]['CONTENT'];
				$indate[count($indate)-1]['THANHPHAN'] = $data1[$posdata1][$i]['THANHPHAN'];
				$indate[count($indate)-1]['DIADIEM'] = $data1[$posdata1][$i]['DIADIEM'];
			}else if($buoi==2 && $endtime['hours']>=12){
				$indate[]['CONTENT'] = $data1[$posdata1][$i]['CONTENT'];
				$indate[count($indate)-1]['THANHPHAN'] = $data1[$posdata1][$i]['THANHPHAN'];
				$indate[count($indate)-1]['DIADIEM'] = $data1[$posdata1][$i]['DIADIEM'];
			}
		}
		for($i=0;$i<count($data2);$i++){
		//check ngay dang chon so voi ngay trong list
			$begintime = date("Y-m-d",strtotime($data2[$i]['BEGINTIME']));
			$endtime = date("Y-m-d",strtotime($data2[$i]['ENDTIME']));
			$currentdate1 = date("Y-m-d",$currentdate);
			//neu ngay hien tai = ngay bat dau
			$ok=true;
			if($currentdate1==$begintime){
				$begintime = getdate(strtotime($data2[$i]['BEGINTIME']));
				$endtime = getdate(strtotime($currentdate1." 23:59:59"));
			}else if($currentdate1>$begintime && $currentdate1<$endtime){
				$begintime = getdate(strtotime($currentdate1." 00:00:00"));
				$endtime = getdate(strtotime($currentdate1." 23:59:59"));
			}else if($currentdate1==$endtime){
				$begintime = getdate(strtotime($currentdate1." 00:00:00"));
				$endtime = getdate(strtotime($data2[$i]['ENDTIME']));
			}else{
				$ok=false;
			}
			if($ok){
				if($buoi==1 && $begintime['hours']<12){
					$indate[]['CONTENT'] = $data2[$i]['CONTENT'];
					$indate[count($indate)-1]['THANHPHAN'] = $data2[$i]['THANHPHAN'];
					$indate[count($indate)-1]['DIADIEM'] = $data2[$i]['DIADIEM'];
				}else if($buoi==2 && $begintime['hours']>=12){
					$indate[]['CONTENT'] = $data2[$i]['CONTENT'];
					$indate[count($indate)-1]['THANHPHAN'] = $data2[$i]['THANHPHAN'];
					$indate[count($indate)-1]['DIADIEM'] = $data2[$i]['DIADIEM'];
				}else if($buoi==1 && $endtime['hours']<12){
					$indate[]['CONTENT'] = $data2[$i]['CONTENT'];
					$indate[count($indate)-1]['THANHPHAN'] = $data2[$i]['THANHPHAN'];
					$indate[count($indate)-1]['DIADIEM'] = $data2[$i]['DIADIEM'];
				}else if($buoi==2 && $endtime['hours']>=12){
					$indate[]['CONTENT'] = $data2[$i]['CONTENT'];
					$indate[count($indate)-1]['THANHPHAN'] = $data2[$i]['THANHPHAN'];
					$indate[count($indate)-1]['DIADIEM'] = $data2[$i]['DIADIEM'];
				}
			}
		}
		if($indate==0 && $alldate==0){
			$indate[] = array();
		}
		return $indate;
	}
	//var_dump($this->view->rangedate);
	for($i=0;$i<count($this->view->date);$i++){
		$onedate = $this->view->date[$i];
		$countsang = countbuoi($this->view->data,$i,$this->view->rangedate,1,$onedate);
		$countchieu = countbuoi($this->view->data,$i,$this->view->rangedate,2,$onedate);
		$sang = getbuoi($this->view->data,$i,$this->view->rangedate,1,$onedate);
		$chieu = getbuoi($this->view->data,$i,$this->view->rangedate,2,$onedate);
		$countall = $countsang+$countchieu;
		//var_dump($chieu);
$html.='
 <tr>
  <td nowrap="nowrap" rowspan='.$countall.'>'.date("d/m",$onedate).'</td>
  <td nowrap="nowrap" rowspan="'.$countsang.'">S</td>
  <td>&nbsp;'.$sang[0]["CONTENT"].'</td>
  <td>&nbsp;'.$sang[0]["DIADIEM"].'</td>
  <td>&nbsp;'.$sang[0]["THANHPHAN"].'</td>
 </tr>
';

		for($j=1;$j<$countsang;$j++){
$html.='
 <tr>
  <td>&nbsp;'.nl2br(htmlspecialchars($sang[$j]["CONTENT"])).'</td>
  <td>&nbsp;'.nl2br(htmlspecialchars($sang[$j]["DIADIEM"])).'</td>
  <td>&nbsp;'.nl2br(htmlspecialchars($sang[$j]["THANHPHAN"])).'</td>
 </tr>
 ';

		}
$html.='
 <tr>
  <td nowrap="nowrap" rowspan="'.$countchieu.'">C</td>
  <td>&nbsp;'.nl2br(htmlspecialchars($chieu[0]["CONTENT"])).'</td>
  <td>&nbsp;'.nl2br(htmlspecialchars($chieu[0]["DIADIEM"])).'</td>
  <td>&nbsp;'.nl2br(htmlspecialchars($chieu[0]["THANHPHAN"])).'</td>
 </tr>
';
		for($j=1;$j<$countchieu;$j++){
$html.='
 <tr>
  <td>&nbsp;'.nl2br(htmlspecialchars($chieu[$j]["CONTENT"])).'</td>
  <td>&nbsp;'.nl2br(htmlspecialchars($chieu[$j]["DIADIEM"])).'</td>
  <td>&nbsp;'.nl2br(htmlspecialchars($chieu[$j]["THANHPHAN"])).'</td>
 </tr>
';
		}

	}
$html.='
</table>
</body>
</html>
';