<?php
/**
 * @author baotq
 * @version 1.0
 * Xu ly cac du lieu lien quan adapter
 *
 */
require_once 'Common/adapterHSCV.php';
class adapter {
	public function insertHSMCService($masohoso,$tentochuccanhan,$tenhoso,$trangthai,$ghichu,$dienthoai,$ngaynop){
		$adapter = new adapterHSCV();
        $adapter->setMaSoHoSo($masohoso);
        $adapter->setTrangThai($trangthai);
        $adapter->setTenToChucCaNhan($tentochuccanhan);
        $adapter->setTenHoSo($tenhoso);
        $adapter->setGhiChu($ghichu);
        $adapter->setNgayNop($ngaynop);
        $adapter->setDienThoai($dienthoai);
        
        try{
            $vem = $adapter->send();
        }catch(Exception $ex){
            echo $ex->getMessage();
            exit;
        }
	}
}

?>
