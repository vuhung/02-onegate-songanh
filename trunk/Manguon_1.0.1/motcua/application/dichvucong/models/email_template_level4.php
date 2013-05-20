<?php
/**
 * send mail
 *  
 * @author baotq
 * @version 1.0
 */
if($coquan==""){
    $coquan = "Không cung cấp";
}
if($fax==""){
    $fax = "Không cung cấp";
}
if($dienthoaicodinh==""){
    $dienthoaicodinh = "Không cung cấp";
}
if($dienthoaididong==""){
    $dienthoaididong = "Không cung cấp";
}
if($ykienkhac==""){
    $ykienkhac = "Không có ý kiến";
}
$email_body = "
<html>
<head>
<meta http-equiv=Content-Type content='text/html; charset=utf-8'>
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:6.0pt;
	margin-right:0in;
	margin-bottom:6.0pt;
	margin-left:0in;
	font-size:14.0pt;
	font-family:'Times New Roman','serif';}
a:link, span.MsoHyperlink
	{color:blue;
	text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
	{color:purple;
	text-decoration:underline;}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{margin-top:6.0pt;
	margin-right:0in;
	margin-bottom:6.0pt;
	margin-left:.5in;
	font-size:14.0pt;
	font-family:'Times New Roman','serif';}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
	{margin-top:6.0pt;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:.5in;
	margin-bottom:.0001pt;
	font-size:14.0pt;
	font-family:'Times New Roman','serif';}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:0in;
	margin-left:.5in;
	margin-bottom:.0001pt;
	font-size:14.0pt;
	font-family:'Times New Roman','serif';}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
	{margin-top:0in;
	margin-right:0in;
	margin-bottom:6.0pt;
	margin-left:.5in;
	font-size:14.0pt;
	font-family:'Times New Roman','serif';}
@page WordSection1
	{size:595.45pt 841.7pt;
	margin:56.9pt 56.9pt 56.9pt 84.95pt;}
div.WordSection1
	{page:WordSection1;}
-->
</style>

</head>

<body lang=EN-US link=blue vlink=purple>

<form name='frmMailPreview' action='/motcua/motcua/savetrahoso/isSend/1/mahoso/".$hscv_info["MAHOSO"]."' method='post'>
<br>$buttonSubmit
<div id='emailcontent'>
$fileInForm
<div class=WordSection1>

<p class=MsoNormal align=center style='margin-bottom:0in;margin-bottom:.0001pt;
text-align:center'>$tenCoQuan THÀNH PHỐ ĐÀ NẴNG</p>

<p class=MsoNormal align=center style='margin-top:0in;text-align:center'><b>TỔ MỘT CỬA</b></p>

<p class=MsoNormal style='text-align:justify'><b>&nbsp;</b></p>

<p class=MsoNormal align=center style='text-align:center'><b>THÔNG BÁO</b></p>

<p class=MsoNormal style='text-align:justify'><i>Vào ngày $guiluc, chúng tôi đã nhận được hồ sơ đăng ký <b>”".$tenloaihoso."”</b> 
trực tuyến qua trang thông tin điện tử của chúng tôi tại địa chỉ </i><a
href='http://www.$website'><i>www.$website</i></a><i>
của quý Ông/Bà với các thông tin như sau:</i></p>

<p class=MsoNormal style='text-align:justify'><b>Mã số hồ sơ: $mahoso</b></p>
<p class=MsoNormal style='text-align:justify'><b>Thời gian đăng ký: $guiluc</b></p>

<p class=MsoNormal style='text-align:justify'><b>Thông tin đăng ký:</b></p>

<p class=MsoNormal style='text-align:justify'>Họ và tên:    $tentochuccanhan.      Email:
$emailrc</p>

<p class=MsoNormal style='text-align:justify'>Địa chỉ:        $diachi.      Số
fax: $fax</p>

<p class=MsoNormal style='text-align:justify'>Cơ quan:      $coquan.</p>

<p class=MsoNormal style='text-align:justify'>Điện thoại cố định: $dienthoaicodinh     Điện thoại di động:
$dienthoaididong.</p>

<p class=MsoNormal style='text-align:justify'>Ý kiến khác:
$ykienkhac.</p>

<p class=MsoNormal style='text-align:justify'><b>Hồ sơ đính kèm:</b></p>

$listNhanDuoc

<p class=MsoNormal style='text-align:justify'>&nbsp;</p>

<p class=MsoNormal style='text-align:justify'>Đến nay, ngày $ngay_tra hồ sơ của quý Ông/Bà<i> </i> đã được giải quyết. Kết quả giải quyết trong tập tin đính kèm sau:</p>

<p class=MsoNormal style='text-align:justify'><b>Kết quả đính kèm:</b></p>

$listKetqua

<p class=MsoNormal style='text-align:justify'>&nbsp;</p>

<p class=MsoNormal style='text-align:justify'>Cảm ơn quý Ông/Bà đã<a
name='_GoBack'></a> sử dụng dịch vụ công trực tuyến của chúng tôi.</p>

<p class=MsoNormal style='text-align:justify'>

<table cellpadding=0 cellspacing=0 align=left>
 <tr>
  <td width=72 height=207></td>
 </tr>
 <tr>
  <td></td>
  <td><img width=602 height=2
  src='Thong%20bao%20tra%20ket%20qua%20-%20Final_files/image001.png'></td>
 </tr>
</table>

 &nbsp;</p>

<p class=MsoNormal style='text-align:justify'>&nbsp;</p>

<br clear=ALL>

<p class=MsoNormal style='text-align:justify'><i><span style='font-size:10.0pt'>Trong quá trình thực hiện nếu có vướng mắc quý Ông/Bà có thể</span></i><span style='font-size:10.0pt'> <i>gởi email cho chúng tôi theo địa chỉ</i> <a
href='mailto:motcua-snv@danang.gov.vn'><i>$emailMocua</i></a>,
<i><a href='mailto:snv@danang.gov.vn'>snv@danang.gov.vn</a> hoặc gởi câu hỏi trực tiếp trên website ở mục Hỏi đáp hoặc liên hệ qua số điện thoại $phonedvc để được giải đáp thêm./.</i></span></p>

<p class=MsoNormal style='text-align:justify'>&nbsp;</p>

<p class=MsoNormal align=right style='text-align:right'><b>TỔ MỘT CỬA</b></p>

</div>

<div>				
</form>
</body>

</html>";
