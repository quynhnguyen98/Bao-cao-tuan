<?php
use ConvertApi\ConvertApi;
class DA_Baocaotuan {
	public $bao_cao_ngay_table_name = 'da_bao_cao_ngay';
	public $bao_cao_tuan_table_name = 'da_bao_cao_tuan';
	public $bao_cao_ngay_nhan_su_thoi_tiet_table_name = 'da_bao_cao_ngay_nhan_su_thoi_tiet';
	public $bao_cao_ngay_lap_dung_table_name = 'da_bao_cao_ngay_lap_dung';
	public $bao_cao_ngay_kho_khan_table_name = 'da_bao_cao_ngay_kho_khan';
	public $ke_hoach_nghiem_thu_va_thanh_toan_table_name = 'da_ke_hoach_nghiem_thu_va_thanh_toan_bct';
	public $dong_tien_du_an_table_name = 'da_dong_tien_du_an_bct';
	public $tien_do_du_an_table_name = 'da_tien_do_du_an_bct';
	public $phat_sinh_du_an_table_name = 'da_phat_sinh_du_an_bct';
	public $rui_ro_du_an_table_name = 'da_rui_ro_du_an_bct';
	public $ncr_du_an_table_name = 'da_ncr_du_an_bct';
	public $nhan_vien_table_name = 'da_nhan_vien';
	public $bao_cao_ngay_nhan_luc_table_name = 'da_bao_cao_ngay_nhan_luc';
	public $bao_cao_ngay_thoi_tiet_table_name = 'da_bao_cao_ngay_thoi_tiet';
	public $bao_cao_ngay_thiet_bi_table_name = 'da_bao_cao_ngay_thiet_bi';
	public $uoc_tinh_don_gia_thiet_bi_table_name = 'da_uoc_tinh_don_gia_thiet_bi_bct';
	public $thiet_bi_table_name = 'da_thiet_bi';
	public $bao_cao_ngay_nhan_luc_thiet_bi_table_name = 'da_bao_cao_ngay_nhan_luc_thiet_bi';


	public $danh_gia_tien_do = array(
		1  => "Đúng tiến độ",
		2  => "Vượt tiến độ",
		-2 => "Trễ tiến độ"
	);
	public $sow = array(
		"design"   		 => "Design", 
		"erection" 		 => "Erection", 
		"manufacturing"  => "Manufacturing", 
		"transportation" => "Transportation",
	);
	public $ovs_dmt = array(
		"domestic" => "Domestic", 
		"oversea"  => "Oversea", 
	);

	public $nhom_rui_ro = array(
		1  => "Phạm vi công việc Scope",
		2  => "Bản vẽ Drawings",
		3  => "Vật tư Materials",
		4  => "Sản xuất Fabrication",
		5  => "Cảng hàng Delivery",
		6  => "Lắp dựng Erection",
		7  => "Tiến dộ Schedule",
		8  => "Chất lượng Quality",
		9  => "Thanh toán và dòng tiền Payment and Cashflow",
		10 => "Hợp đồng Contract",
		11 => "Các bên liên quan Stakeholders",
		12 => "Khác Others" 
	);

	public $muc_do_nghiem_trong = array(
		"KC" 	=> "Khẩn cấp",
		"NTKS" 	=> "Nằm trong kiểm soát",
		"MKS" 	=> "Mất kiểm soát",
		"TD" 	=> "Theo dõi"
	);

	public $chuyen_de = array(
		"ncr"         => "NCR",
		"rui_ro"      => "Rủi ro",
		"phat_sinh"   => "Phát sinh",
		"tien_do"     => "Trễ tiến độ",
		"ngan_sach"   => "Ngân sách",
	);

	// ap dung cho key chuyen_de[ngan_sach]
	public $ngan_sach = array(
		'10' => 'Code 10',
		// '20' => 'Code 20',
		// '30' => 'Code 30',
		// '40' => 'Code 40',
		'50' => 'Code 50',
		'60' => 'Code 60',
		// '70' => 'Code 70',
	);

	public function __construct(){

	}

	public function bao_cao_tuan_select_box($params = array()) {
		$name 		= !empty($params['name']) && $params['name'] ? sanitize_text_field($params['name']) : '';
		$selected 	= !empty($params['selected']) ? $params['selected'] : '';
		$class 		= !empty($params['class']) ? $params['class'] : 'form-control';
		$id 		= !empty($params['id']) ? $params['id'] : '';

		$s = "<select name='{$name}' id='{$id}' class='{$class}'>";
		$s .= '<option value="" selected>' . 'Chọn copy' . '</option>';

		$list = $this->danh_sach(array(
			'ma_du_an' => $params['ma_du_an'],
			//'trang_thai' => 1,
		));

		foreach ($list as $k => $v) {
			if ($selected == $v->id && $selected != '') {
				$s .= '<option value="' . $v->id . '" selected>' . $v->tieu_de . '</option>';
			} else
				$s .= '<option value="' . $v->id . '">' . $v->tieu_de . '</option>';
		}
		
		$s .= '</select>';
		echo $s;

	}

	public function danh_sach_filter_bao_cao_tuan(){
		global $wpdb;

		$sql = "SELECT tieu_de, ngay_bat_dau, ngay_ket_thuc
				FROM `da_bao_cao_tuan`
				GROUP BY tieu_de, ngay_bat_dau, ngay_ket_thuc";
		
		return $wpdb->get_results($sql);
	}

	public function filter_bao_cao_tuan_select_box($params = array()) {
		$name 		= !empty($params['name']) && $params['name'] ? sanitize_text_field($params['name']) : '';
		$selected 	= !empty($params['selected']) ? $params['selected'] : '';
		$class 		= !empty($params['class']) ? $params['class'] : 'form-control';
		$id 		= !empty($params['id']) ? $params['id'] : '';

		$s = "<select name='{$name}' id='{$id}' class='{$class}'>";

		$list = $params['list'];
		$list_cook = array_chunk($list, 4);
		$list_cook = array_reverse($list_cook);
		
        foreach ($list_cook as $index => $cum_array) {
            $tieu_de_cum = '';
            $ngay_bat_dau_cum = $cum_array[0]->ngay_bat_dau;
            $ngay_ket_thuc_cum = $cum_array[count($cum_array) - 1]->ngay_ket_thuc;

            // Tạo tiêu đề cho cụm
            foreach ($cum_array as $bao_cao_tuan) {
                $tieu_de_cum .= esc_html($bao_cao_tuan->tieu_de) . ', ';
            }

            $tieu_de_cum = rtrim($tieu_de_cum, ', ');

			if ($selected == $index+1 && $selected != '') {
				$s .= '<option value="' . esc_attr($index+1) . '" selected>' . esc_html('Báo cáo từ ' . date("d/m/Y",strtotime($ngay_bat_dau_cum)) . ' đến ' . date("d/m/Y",strtotime($ngay_ket_thuc_cum))) . '</option>';
			} else
				$s .= '<option value="' . esc_attr($index+1) . '">' . esc_html('Báo cáo từ ' . date("d/m/Y",strtotime($ngay_bat_dau_cum)) . ' đến ' . date("d/m/Y",strtotime($ngay_ket_thuc_cum))) . '</option>';
        }
		
		$s .= '</select>';
		echo $s;

	}

	public function danh_sach($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->bao_cao_tuan_table_name;

		$where = array();

		if (isset($params['ma_du_an']) && $params['ma_du_an']) 					 $where[] = "ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['tieu_de']) && $params['tieu_de']) 					 $where[] = "tieu_de = '".sanitize_text_field($params['tieu_de'])."'";
		if (isset($params['ma_nhan_vien']) && $params['ma_nhan_vien']) 			 $where[] = "ma_nhan_vien = '".sanitize_text_field($params['ma_nhan_vien'])."'";
		if (isset($params['ma_nguoi_duyet']) && $params['ma_nguoi_duyet']) 		 $where[] = "ma_nguoi_duyet = '".sanitize_text_field($params['ma_nguoi_duyet'])."'";
		if (isset($params['ma_nguoi_theo_doi']) && $params['ma_nguoi_theo_doi']) $where[] = "ma_nguoi_theo_doi = '".sanitize_text_field($params['ma_nguoi_theo_doi'])."'";
		if (isset($params['ngay_bat_dau']) && $params['ngay_bat_dau']) 			 $where[] = "ngay_bat_dau = '".sanitize_text_field($params['ngay_bat_dau'])."'";
		if (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) 		 $where[] = "ngay_ket_thuc = '".sanitize_text_field($params['ngay_ket_thuc'])."'";
		if (isset($params['trang_thai']) && $params['trang_thai']) 				 $where[] = "trang_thai = '".sanitize_text_field($params['trang_thai'])."'";
		if (isset($params['danh_gia_tien_do']) && $params['danh_gia_tien_do']) 	 $where[] = "danh_gia_tien_do = '".sanitize_text_field($params['danh_gia_tien_do'])."'";
		if (isset($params['ngay']) && $params['ngay']) 							 
			$where[] = "ngay_bat_dau BETWEEN '".sanitize_text_field( $params['ngay']['ngay_bat_dau'])."' AND '".sanitize_text_field($params['ngay']['ngay_ket_thuc'])."' AND ngay_ket_thuc BETWEEN '".sanitize_text_field( $params['ngay']['ngay_bat_dau'])."' AND '".sanitize_text_field($params['ngay']['ngay_ket_thuc'])."'";

		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY ngay_tao DESC';

		return $wpdb->get_results($sql);
	}

	public function thong_tin($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->bao_cao_tuan_table_name;

		$where = array();

		if (isset($params['id']) && $params['id']) 								 $where[] = "id = '".intval($params['id'])."'";
		if (isset($params['ma_du_an']) && $params['ma_du_an']) 					 $where[] = "ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['tieu_de']) && $params['tieu_de']) 					 $where[] = "tieu_de = '".sanitize_text_field($params['tieu_de'])."'";
		if (isset($params['ma_nhan_vien']) && $params['ma_nhan_vien']) 			 $where[] = "ma_nhan_vien = '".sanitize_text_field($params['ma_nhan_vien'])."'";
		if (isset($params['ma_nguoi_duyet']) && $params['ma_nguoi_duyet']) 		 $where[] = "ma_nguoi_duyet = '".sanitize_text_field($params['ma_nguoi_duyet'])."'";
		if (isset($params['ma_nguoi_theo_doi']) && $params['ma_nguoi_theo_doi']) $where[] = "ma_nguoi_theo_doi = '".sanitize_text_field($params['ma_nguoi_theo_doi'])."'";
		if (isset($params['ngay_bat_dau']) && $params['ngay_bat_dau']) 			 $where[] = "ngay_bat_dau = '".sanitize_text_field($params['ngay_bat_dau'])."'";
		if (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) 		 $where[] = "ngay_ket_thuc = '".sanitize_text_field($params['ngay_ket_thuc'])."'";
		if (isset($params['trang_thai']) && $params['trang_thai']) 				 $where[] = "trang_thai = '".sanitize_text_field($params['trang_thai'])."'";
		if (isset($params['danh_gia_tien_do']) && $params['danh_gia_tien_do'])   $where[] = "danh_gia_tien_do = ".intval($params['danh_gia_tien_do']);

		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		return $wpdb->get_row($sql);
	}

	public function thong_tin_bao_cao_ngay($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->bao_cao_ngay_table_name;

		$where = array();

		if (isset($params['ma_du_an']) && $params['ma_du_an']) 		   $where[] = "ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['ngay_bao_cao']) && $params['ngay_bao_cao']) $where[] = "ngay_bao_cao = '".sanitize_text_field($params['ngay_bao_cao'])."'";
		if (isset($params['nguoi_tao']) && $params['nguoi_tao']) 	   $where[] = "nguoi_tao = ".intval($params['nguoi_tao']);
		if (isset($params['trang_thai']) && $params['trang_thai'])     $where[] = "trang_thai = ".intval($params['trang_thai']);
		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY ngay_bao_cao DESC';

		return $wpdb->get_row($sql);
	}

	public function thong_tin_uoc_tinh_don_gia_thiet_bi($params = array()){
		global $wpdb;

		$sql = "SELECT * FROM ".$this->uoc_tinh_don_gia_thiet_bi_table_name;

		$where = array();

		if (isset($params['id']) && $params['id']) 							 $where[] = "id = '".sanitize_text_field($params['id'])."'";
		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".sanitize_text_field($params['bao_cao_tuan_id'])."'";
		if (isset($params['thiet_bi_id']) && $params['thiet_bi_id']) 		 $where[] = "thiet_bi_id = '".sanitize_text_field($params['thiet_bi_id'])."'";
		// if (isset($params['ngay_bao_cao']) && $params['ngay_bao_cao']) $where[] = "ngay_bao_cao = '".sanitize_text_field($params['ngay_bao_cao'])."'";
		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		return $wpdb->get_row($sql);
	
	}

	public function danh_sach_ke_hoach_nghiem_thu_va_thanh_toan($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->ke_hoach_nghiem_thu_va_thanh_toan_table_name;

		$where = array();

		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".sanitize_text_field($params['bao_cao_tuan_id'])."'";
		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY khntvtt_thu_tu ASC';

		return $wpdb->get_results($sql);
	}
	public function danh_sach_dong_tien_du_an($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->dong_tien_du_an_table_name;

		$where = array();

		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".sanitize_text_field($params['bao_cao_tuan_id'])."'";

		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY dong_tien_du_an_thu_tu ASC';

		return $wpdb->get_results($sql);
	}
	public function danh_sach_tien_do_du_an($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->tien_do_du_an_table_name;

		$where = array();

		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".sanitize_text_field($params['bao_cao_tuan_id'])."'";

		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY tien_do_du_an_thu_tu ASC';

		return $wpdb->get_results($sql);
	}
	public function danh_sach_phat_sinh_du_an($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->phat_sinh_du_an_table_name;

		$where = array();

		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".sanitize_text_field($params['bao_cao_tuan_id'])."'";

		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY phat_sinh_du_an_thu_tu ASC';

		return $wpdb->get_results($sql);
	}
	public function danh_sach_rui_ro_du_an($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->rui_ro_du_an_table_name;

		$where = array();

		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".sanitize_text_field($params['bao_cao_tuan_id'])."'";

		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY rui_ro_du_an_thu_tu ASC';

		return $wpdb->get_results($sql);
	}
	public function danh_sach_ncr_du_an($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->ncr_du_an_table_name;

		$where = array();

		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".sanitize_text_field($params['bao_cao_tuan_id'])."'";

		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		$sql .= ' ORDER BY ncr_du_an_thu_tu ASC';

		return $wpdb->get_results($sql);
	}

	public function danh_sach_bao_cao_ngay_trong_tuan_nhan_luc($params = array()) {
		global $wpdb;

		$sql = "SELECT *, bcn.id AS ma_bao_cao FROM ".$this->bao_cao_ngay_table_name. " AS bcn";
		$sql .= " JOIN ".$this->bao_cao_ngay_nhan_luc_table_name." AS bcn_nl ON bcn.id = bcn_nl.ma_bao_cao";

		$where = array();

		if (isset($params['ma_du_an']) && $params['ma_du_an']) 																			  $where[] = "bcn.ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if ((isset($params['ngay_bat_dau']) && $params['ngay_bat_dau']) && (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc'])) $where[] = "bcn.ngay_bao_cao BETWEEN '".sanitize_text_field($params['ngay_bat_dau'])."' AND '".sanitize_text_field($params['ngay_ket_thuc'])."'";
		if (isset($params['nguoi_tao']) && $params['nguoi_tao'])   																		  $where[] = "bcn.nguoi_tao = ".intval($params['nguoi_tao']);
		if (isset($params['trang_thai']) && $params['trang_thai']) 																		  $where[] = "bcn.trang_thai = ".intval($params['trang_thai']);
		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		return $wpdb->get_results($sql);
	}

	public function danh_sach_bao_cao_ngay_trong_tuan_thiet_bi($params = array()) {
		global $wpdb;

		$sql = "SELECT *, bcn.id AS ma_bao_cao FROM ".$this->bao_cao_ngay_table_name. " AS bcn";
		$sql .= " JOIN ".$this->bao_cao_ngay_thiet_bi_table_name." AS bcn_tb ON bcn.id = bcn_tb.ma_bao_cao";

		$where = array();

		if (isset($params['ma_du_an']) && $params['ma_du_an']) 																			  $where[] = "bcn.ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if ((isset($params['ngay_bat_dau']) && $params['ngay_bat_dau']) && (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc'])) $where[] = "bcn.ngay_bao_cao BETWEEN '".sanitize_text_field($params['ngay_bat_dau'])."' AND '".sanitize_text_field($params['ngay_ket_thuc'])."'";
		if (isset($params['nguoi_tao']) && $params['nguoi_tao']) 																		  $where[] = "bcn.nguoi_tao = ".intval($params['nguoi_tao']);
		if (isset($params['trang_thai']) && $params['trang_thai']) 																		  $where[] = "bcn.trang_thai = ".intval($params['trang_thai']);
		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		return $wpdb->get_results($sql);
	}

	public function tinh_tong_nhan_su($params = array()){
		global $wpdb;
	
		$sql = "SELECT SUM(COALESCE(tong_gio_cong,0)) as tong_gio_cong_tuan_nay, SUM(COALESCE(gio_cong_ca_ngay,0)) as tong_gio_ca_ngay_tuan_nay, SUM(COALESCE(gio_cong_tang_ca,0)) as tong_gio_tang_ca_tuan_nay";
		$sql .= " FROM ".$this->bao_cao_ngay_thoi_tiet_table_name." AS bcn_tt";
		$sql .= " JOIN ".$this->bao_cao_ngay_table_name." AS bcn ON bcn.id = bcn_tt.ma_bao_cao";
	
		$where = array();

		$where[] = "bcn.trang_thai = 1";
	
		if (isset($params['ma_du_an']) && $params['ma_du_an']) 			 $where[] = "bcn_tt.ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) $where[] = "bcn_tt.ngay_bao_cao <= '".sanitize_text_field($params['ngay_ket_thuc'])."'";
	
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);
		
		$sql .= " GROUP BY bcn_tt.ngay_bao_cao"; 
	
		return $wpdb->get_results($sql);
	}

	public function tinh_tong_thiet_bi($params = array()){
		global $wpdb;
	
		$sql = "SELECT SUM(COALESCE(bcn_tb.ca_ngay,0)) as tong_hanh_chinh, SUM(COALESCE(bcn_tb.tang_ca,0)) as tong_tang_ca, SUM(COALESCE(bcn_tb.ca_ngay,0)) + SUM(COALESCE(bcn_tb.tang_ca,0)) as tong_cong";
		$sql .= " FROM ".$this->bao_cao_ngay_thiet_bi_table_name." AS bcn_tb";
		$sql .= " JOIN ".$this->bao_cao_ngay_table_name." AS bcn ON bcn.id = bcn_tb.ma_bao_cao";
	
		$where = array();

		$where[] = "bcn.trang_thai = 1";
	
		if (isset($params['ma_du_an']) && $params['ma_du_an']) 			 $where[] = "bcn_tb.ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['id_thiet_bi']) && $params['id_thiet_bi']) 	 $where[] = "bcn_tb.id_thiet_bi = '".sanitize_text_field($params['id_thiet_bi'])."'";
		if (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) $where[] = "bcn_tb.ngay_bao_cao <= '".sanitize_text_field($params['ngay_ket_thuc'])."'";
	
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);
		
		$sql .= " GROUP BY bcn_tb.id_thiet_bi"; 
	
		return $wpdb->get_results($sql);
	}

	public function tinh_tong_theo_doi_tuong($params = array()){
		global $wpdb;
	
		$sql = "SELECT SUM(COALESCE(ca_ngay,0)) as tong_ca_ngay, SUM(COALESCE(tang_ca,0)) as tong_tang_ca, bcn_nl.id_loai_nhan_luc as id_loai_nhan_luc , SUM(COALESCE(ca_ngay,0)) + SUM(COALESCE(tang_ca,0)) as tong_nhan_luc";
		$sql .= " FROM ".$this->bao_cao_ngay_nhan_luc_table_name." AS bcn_nl";
		$sql .= " JOIN ".$this->bao_cao_ngay_table_name." AS bcn ON bcn.id = bcn_nl.ma_bao_cao";
	
		$where = array();

		$where[] = "bcn.trang_thai = 1";
	
		if (isset($params['id_loai_nhan_luc']) && $params['id_loai_nhan_luc'] && is_array($params['id_loai_nhan_luc']))
			$where[] = "bcn_nl.id_loai_nhan_luc IN (".sanitize_text_field(implode(" ,", $params['id_loai_nhan_luc'])).")";
		else if(isset($params['id_loai_nhan_luc']) && $params['id_loai_nhan_luc']) 
			$where[] = "bcn_nl.id_loai_nhan_luc = ".intval($params['id_loai_nhan_luc']);

		if (isset($params['ma_du_an']) && $params['ma_du_an']) 			 $where[] = "bcn_nl.ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) $where[] = "bcn_nl.ngay_bao_cao <= '".sanitize_text_field($params['ngay_ket_thuc'])."'";
	
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);
		
		$sql .= " GROUP BY bcn_nl.id_loai_nhan_luc"; 
	
		return $wpdb->get_results($sql);
	}
	

	public function trang_thai_highlight($trang_thai) {
		$html ='';
		switch($trang_thai) {
			case 1:
				$html = '<span class="badge badge-pill badge-success">Đã duyệt</span>';
			break;
			case -1:
				$html = '<span class="badge badge-pill badge-warning">Chờ duyệt</span>';
			break;
			case -2:
				$html = '<span class="badge badge-pill badge-danger">Từ chối</span>';
			break;
			case -3:
				$html = '<span class="badge badge-pill badge-light">Lưu nháp</span>';
			break;
		}
		return $html;
	}

	public function danh_gia_tien_do_select_box($params = array()) {

		$required = isset($params['required']) && $params['required'] ? 'required' : '';
		$selected = isset($params['selected']) ? $params['selected'] : '';
		$empty    = isset($params['empty']) ? $params['empty'] : true;
	
		$s = '<select name="danh_gia_tien_do" class="form-control" '.$required.'>';
		if ($empty) $s .= '<option value="">Chọn tiến độ</option>';

		$danh_sach = $this->danh_gia_tien_do;

		foreach ($danh_sach as $key => $tien_do) {
			if ($selected == $key)
				$s .= '<option value="'.$key.'" selected>'.$tien_do.'</option>';
			else 
				$s .= '<option value="'.$key.'">'.$tien_do.'</option>';
		}
		$s .= '</select>';

		echo $s;

	}
	public function chuyen_de_select_box($params = array()) {

		$required = isset($params['required']) && $params['required'] ? 'required' : '';
		$selected = isset($params['selected']) ? $params['selected'] : '';
		$empty    = isset($params['empty']) ? $params['empty'] : true;
	
		$s = '<select name="chuyen_de" class="form-control" '.$required.'>';
		if ($empty) $s .= "<option value=''>".__ul('Chọn chuyên đề', false)."</option>";

		$danh_sach = $this->chuyen_de;

		foreach ($danh_sach as $key => $chuyen_de) {
			if ($selected == $key)
				$s .= '<option value="'.$key.'" selected>'.$chuyen_de.'</option>';
			else 
				$s .= '<option value="'.$key.'">'.$chuyen_de.'</option>';
		}
		$s .= '</select>';

		echo $s;

	}
	public function sow_select_box($params = array()) {

		$required = isset($params['required']) && $params['required'] ? 'required' : '';
		$selected = isset($params['selected']) ? $params['selected'] : '';
		$empty    = isset($params['empty']) ? $params['empty'] : true;
	
		$s = '<select name="sow" class="form-control" '.$required.'>';
		if ($empty) $s .= '<option value="">Tất cả</option>';

		$danh_sach = $this->sow;

		foreach ($danh_sach as $key => $tien_do) {
			if ($selected == $key)
				$s .= '<option value="'.$key.'" selected>'.$tien_do.'</option>';
			else 
				$s .= '<option value="'.$key.'">'.$tien_do.'</option>';
		}
		$s .= '</select>';

		echo $s;

	}

	// Vi tri
	public function ovs_dmt_select_box($params = array()) {

		$required = isset($params['required']) && $params['required'] ? 'required' : '';
		$selected = isset($params['selected']) ? $params['selected'] : '';
		$empty    = isset($params['empty']) ? $params['empty'] : true;
	
		$s = '<select name="ovs_dmt" class="form-control" '.$required.'>';
		if ($empty) $s .= '<option value="">Tất cả</option>';

		$danh_sach = $this->ovs_dmt;

		foreach ($danh_sach as $key => $vi_tri) {
			if ($selected == $key)
				$s .= '<option value="'.$key.'" selected>'.$vi_tri.'</option>';
			else 
				$s .= '<option value="'.$key.'">'.$vi_tri.'</option>';
		}
		$s .= '</select>';

		echo $s;

	}
	public function nhom_rui_ro_select_box($params = array()) {

		$required = isset($params['required']) && $params['required'] ? 'required' : '';
		$selected = isset($params['selected']) ? $params['selected'] : '';
		$empty    = isset($params['empty']) ? $params['empty'] : true;
	
		$s = '<select name="rui_ro_du_an_nhom_rui_ro[]" class="form-control" '.$required.'>';
		if ($empty) $s .= '<option value="">Chọn rũi ro</option>';

		$danh_sach = $this->nhom_rui_ro;

		foreach ($danh_sach as $key => $rui_ro) {
			if ($selected == $key)
				$s .= '<option value="'.$key.'" selected>'.$rui_ro.'</option>';
			else 
				$s .= '<option value="'.$key.'">'.$rui_ro.'</option>';
		}
		$s .= '</select>';

		echo $s;

	}
	public function muc_do_nghiem_trong_select_box($params = array()) {

		$required = isset($params['required']) && $params['required'] ? 'required' : '';
		$selected = isset($params['selected']) ? $params['selected'] : '';
		$empty    = isset($params['empty']) ? $params['empty'] : true;
	
		$s = '<select name="rui_ro_du_an_muc_do_nghiem_trong[]" class="form-control" '.$required.'>';
		if ($empty) $s .= '<option value="">Chọn mức độ nghiêm trọng</option>';

		$danh_sach = $this->muc_do_nghiem_trong;

		foreach ($danh_sach as $key => $muc_do) {
			if ($selected == $key)
				$s .= '<option value="'.$key.'" selected>'.$muc_do.'</option>';
			else 
				$s .= '<option value="'.$key.'">'.$muc_do.'</option>';
		}
		$s .= '</select>';

		echo $s;

	}

	// tao bao cao hang tuan vao T7-T6
	public function cron_tao_bao_cao_tuan($params = array()) {

		global $wpdb;
		if (!(isset($params['trang_thai']) && in_array(intval($params['trang_thai']), array(-1, 1)))) exit;
	
		$cd = new DA_Caidat();
		$da = new DA_Duan();
	
		$ds_du_an = $da->danh_sach(array(
			'trang_thai' => intval($params['trang_thai']), // 1.Dang trien khai, -1. Cho quyet toan
			'unlimit'    => true
		));
	
		$week = intval(date('W')) < 10 ? '0' . intval(date('W')) : date('W');
		$year = date('Y');
	
		// Start date and end date calculations
		$start_date = date("Y-m-d", strtotime($year . "W" . $week . "6"));
		$end_date = date("Y-m-d", strtotime($start_date . " +6 days"));

		$tieu_de = "Báo cáo tuần " . ($week + 1) . " (" . date('d/m/Y', strtotime($start_date)) . " - " . date('d/m/Y', strtotime($end_date)) . ")";
		foreach ($ds_du_an as $du_an) {
			$sql = "SELECT id FROM " . $this->bao_cao_tuan_table_name . " WHERE ma_du_an = '" . $du_an->ma_du_an . "' AND ngay_tao = '" . current_time('Y-m-d') . "'";
			$ke_hoach = $wpdb->get_row($sql);
			if ($ke_hoach) continue;
	
			$data[] = array(
				'tieu_de'       => $tieu_de,
				'ma_du_an'      => $du_an->ma_du_an,
				'ngay_bat_dau'  => $start_date,
				'ngay_ket_thuc' => $end_date,
				'ngay_tao'      => current_time('Y-m-d')
			);
		}
	
		$cd->multi_insert($this->bao_cao_tuan_table_name, $data);
	
		return true;
	}

	public function ajax_thong_tin_nhan_vien() {
		global $wpdb;

		$ma_nhan_vien = $_REQUEST['ma_nhan_vien'];

		$sql = "SELECT * FROM ".$this->nhan_vien_table_name." WHERE ma_nhan_vien = '".sanitize_text_field($ma_nhan_vien)."'";
		$result = (array)$wpdb->get_row($sql);	

		//Luu y chi duoc them info khong duoc remove
		$reponse = array(
			'ten_phong_ban' => $result['ten_phong_ban'] ?? null,
			'vai_tro' 		=> $result['vi_tri'] ?? null,
			'ho_ten' 		=> $result['ho_ten'] ?? null,
			'chuc_vu' 		=> $result['chuc_vu'] ?? null,
			'ma_nhan_vien' 	=> $result['ma_nhan_vien'] ?? null,
			'ten_don_vi' 	=> $result['ten_don_vi'] ?? null,
		);

		echo json_encode($reponse);
		die();
	
	}

	public function ajax_danh_sach_nhan_vien() {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->nhan_vien_table_name;

		$where = array();

		$where[] = "(ho_ten LIKE '%".sanitize_text_field($_REQUEST['ho_ten'])."%'". " OR ". "ma_nhan_vien LIKE '%".sanitize_text_field($_REQUEST['ma_nhan_vien'])."%')";
		$where[] = "loai_nhan_vien != 'Nghỉ việc'";

		$sql .= " WHERE ".implode(" AND ", $where);
		$sql .= " ORDER BY ma_nhan_vien ASC";
		$sql .= " LIMIT 20";

		$danh_sach = $wpdb->get_results($sql);	

		// Limit in group member
		$group_members = array();
		if (isset($_REQUEST['group_id']) && $_REQUEST['group_id']) {
			$g = new DA_Group();
			$group = $g->info($_REQUEST['group_id']);
			if ($group) {
				$group_members = explode(',', $group->assignees);
				
				// Extra group owner
				$group_members[] = $group->owner_id;
			}
		}	

		// Limit in project member
		$project_members = array();
		if (isset($_REQUEST['project_id']) && $_REQUEST['project_id']) {
			$p = new DA_Project();
			$project = $p->info($_REQUEST['project_id']);
			if ($project) {
				$project_members = explode(',', $project->assignees);
				$project_members[] = $project->owner_id;
			}
		}	

		// Convert to array
		$response = array();
		foreach($danh_sach as $item) {
			if (count($group_members) || count($project_members)) {
				if (in_array($item->ma_nhan_vien, $group_members))
					$response[] = $item->ho_ten. ' ('.$item->ma_nhan_vien.')';

				if (in_array($item->ma_nhan_vien, $project_members))
					$response[] = $item->ho_ten. ' ('.$item->ma_nhan_vien.')';
			}
			else
				$response[] = $item->ho_ten. ' ('.$item->ma_nhan_vien.')';
		}

		echo json_encode($response);
		die();
	}


	public function cap_nhat_trang_thai_bao_cao_tuan($params = array()) {
		global $wpdb;

		$data = array(
			'id' 		 => intval($params['id']),
			'trang_thai' => intval($params['trang_thai'])
		);
		$wpdb->update($this->bao_cao_tuan_table_name, $data, array('id' => $data['id']));
	}

	public function cap_nhat_bao_cao_tuan($params=array()){
		global $wpdb;

		if (isset($params['thao_tac']) && $params['thao_tac'] == 'thu_hoi'){
			$bao_cao = $this->thong_tin(array('id' => $params['id']));
			// cập nhật trạng thái báo cáo tuần và ngày trình duyệt
			$data_bao_cao_tuan = array(
				'trang_thai' 	   => -3,
				'ngay_trinh_duyet' => null
			);
			$wpdb->update($this->bao_cao_tuan_table_name, $data_bao_cao_tuan, array('id' => $params['id']));

			do_action('hook_after_thu_hoi_bao_cao_tuan', array('bao_cao' => $bao_cao));
			return;
		}

		$ma_nguoi_duyet = sanitize_text_field(lay_ma_nhan_vien($params['ma_nguoi_duyet']));
		$ds_ma_nguoi_theo_doi = $params['ma_nguoi_theo_doi'] ? sanitize_text_field(implode(',', lay_danh_sach_ma_nhan_vien($params['ma_nguoi_theo_doi']))) : '';

		$data = array(
			'ma_du_an' 					=> sanitize_text_field($params['ma_du_an']),
			'ma_nhan_vien' 				=> sanitize_text_field($params['ma_nhan_vien']),
			'ma_nguoi_duyet' 			=> $ma_nguoi_duyet,
			'ma_nguoi_theo_doi' 		=> $ds_ma_nguoi_theo_doi,
			'uoc_tinh_don_gia_nhan_su'  => str_replace(',', '', sanitize_text_field($params['uoc_tinh_don_gia_nhan_su'])),
			'dktt_theo_hop_dong' 		=> sanitize_textarea_field($params['dktt_theo_hop_dong']),
			'danh_gia_tien_do' 			=> sanitize_text_field($params['danh_gia_tien_do']),
			'so_ngay_tre' 				=> (int)sanitize_text_field($params['so_ngay_tre']),
			'ly_do_tre_tien_do' 		=> sanitize_textarea_field($params['ly_do_tre_tien_do']),
			'de_xuat_giai_phap' 		=> sanitize_textarea_field($params['de_xuat_giai_phap']),
			'danh_gia_chung' 			=> sanitize_textarea_field($params['danh_gia_chung']),
		);

		if (isset($params['id']) && $params['id']) {
			$wpdb->update($this->bao_cao_tuan_table_name, $data, array('id' => $params['id']));
		} else {
			// neu don chua tao moi
			return false;
		}

		// Upload hinh anh
		$att = new DA_Attachment();
		$att->upload(array(
			'object_id' 		=> $params['id'],
			'object_type' 		=> 'bao_cao_tuan_hinh_anh',
			'owner_id' 			=> $params['ma_nhan_vien'],
			'name_attachments' 	=> 'hinh_anh'
		));

		$att->upload(array(
			'object_id' 		=> $params['id'],
			'object_type' 		=> 'bao_cao_tuan_san_luong',
			'owner_id' 			=> $params['ma_nhan_vien'],
			'name_attachments' 	=> 'san_luong'
		));

		$this->uoc_tinh_don_gia_thiet_bi($params);
		$this->ke_hoach_nghiem_thu_va_thanh_toan($params);
		$this->dong_tien_du_an($params);
		$this->tien_do_du_an($params);
		$this->phat_sinh_du_an($params);
		$this->rui_ro_du_an($params);
		$this->ncr_du_an($params);

		if($params['trinh_duyet'] == -1 && isset($params['thao_tac']) && $params['thao_tac'] == 'trinh_duyet') {
			// // Tạo đề nghị duyệt
			$dn = new DA_Denghi();
			$dn->tao_de_nghi(array(
				'tieu_de' 				=> $params['tieu_de'] ?? "",
				'ma_nhan_vien' 			=> $params['ma_nhan_vien'] ?? "",
				'ma_doi_tuong' 			=> $params['id'],
				'kieu_doi_tuong' 		=> 'bao_cao_tuan',
				'ma_nguoi_duyet' 		=> $ma_nguoi_duyet,
				'vai_tro_nguoi_duyet' 	=> null,
				'ma_du_an' 				=> $params['ma_du_an'],
				'ngay_tao' 				=> current_time('Y-m-d H:i:s'), // ngay trinh duyet cua de nghi
				'trang_thai' 			=> -1,
				'tien_do_xu_ly' 		=> 10
			));

			// cập nhật trạng thái báo cáo tuần và ngày trình duyệt
			$data_bao_cao_tuan = array(
				'trang_thai' 		=> -1,
				'ngay_trinh_duyet' 	=> current_time('Y-m-d')
			);
			$wpdb->update($this->bao_cao_tuan_table_name, $data_bao_cao_tuan, array('id' => $params['id']));


			if(isset($params['ma_nguoi_theo_doi']) && $params['ma_nguoi_theo_doi']) {
				// Gui thong bao cho danh sach theo doi
				$tn = new DA_Tinnhan();
				$tn->tao_bao_cao_tuan(array('id' => $params['id'], 'ds_ma_nguoi_theo_doi' => $ds_ma_nguoi_theo_doi));
			}

		}

		return $wpdb->insert_id;
	}

	public function uoc_tinh_don_gia_thiet_bi($params){
		global $wpdb;

		$bao_cao_tuan_id 			= (int)$params['id'];
		$uoc_tinh_don_gia_thiet_bi 	= explode(',', $params['ds_id_thiet_bi']);

		if(!empty($bao_cao_tuan_id)) $wpdb->query("DELETE FROM ".$this->uoc_tinh_don_gia_thiet_bi_table_name." WHERE bao_cao_tuan_id = $bao_cao_tuan_id");

		foreach ($uoc_tinh_don_gia_thiet_bi as $key => $value) {
			$ten_thiet_bi = $wpdb->get_var("SELECT ten_thiet_bi FROM ".$this->thiet_bi_table_name." WHERE id = $value");
			$data = array(
				'thiet_bi_id' 		=> $value,
				'ten_thiet_bi' 		=> $ten_thiet_bi,
				'bao_cao_tuan_id' 	=> $bao_cao_tuan_id,
				'uoc_tinh_don_gia' 	=> str_replace(',', '', sanitize_text_field($params['uoc_tinh_don_gia_'.$value])),
			);
			$wpdb->insert($this->uoc_tinh_don_gia_thiet_bi_table_name, $data);
		}
	
	}

	public function ke_hoach_nghiem_thu_va_thanh_toan($params){
		global $wpdb;

		$bao_cao_tuan_id 				= (int)$params['id'];
		$khntvtt_noi_dung_thanh_toan 	= $params['khntvtt_noi_dung_thanh_toan'];
		$khntvtt_ngay_chot_khoi_luong 	= $params['khntvtt_ngay_chot_khoi_luong'];
		$khntvtt_gia_tri_thanh_toan 	= $params['khntvtt_gia_tri_thanh_toan'];
		$khntvtt_tinh_trang 			= $params['khntvtt_tinh_trang'];
		$khntvtt_thu_tu 				= $params['khntvtt_thu_tu'];

		if(!empty($bao_cao_tuan_id)) $wpdb->query("DELETE FROM ".$this->ke_hoach_nghiem_thu_va_thanh_toan_table_name." WHERE bao_cao_tuan_id = $bao_cao_tuan_id");

		foreach ($khntvtt_noi_dung_thanh_toan as $key => $value) {
			$data = array(
				'bao_cao_tuan_id' 				=> $bao_cao_tuan_id,
				'khntvtt_noi_dung_thanh_toan' 	=> sanitize_text_field($khntvtt_noi_dung_thanh_toan[$key]),
				'khntvtt_ngay_chot_khoi_luong' 	=> !empty($khntvtt_ngay_chot_khoi_luong[$key]) ? sanitize_text_field($khntvtt_ngay_chot_khoi_luong[$key]) : NULL,
				'khntvtt_gia_tri_thanh_toan' 	=> str_replace(',', '', sanitize_text_field($khntvtt_gia_tri_thanh_toan[$key])),
				'khntvtt_tinh_trang' 			=> sanitize_text_field($khntvtt_tinh_trang[$key]),
				'khntvtt_thu_tu' 				=> sanitize_text_field($khntvtt_thu_tu[$key]),
			);
			$wpdb->insert($this->ke_hoach_nghiem_thu_va_thanh_toan_table_name, $data);
		}
	}

	public function dong_tien_du_an($params){
		global $wpdb;

		$bao_cao_tuan_id 					= (int)$params['id'];
		$dong_tien_du_an_noi_dung_tong_thau = $params['dong_tien_du_an_noi_dung_tong_thau'];
		$dong_tien_du_an_gia_tri_tong_thau 	= $params['dong_tien_du_an_gia_tri_tong_thau'];
		$dong_tien_du_an_noi_dung_thau_phu 	= $params['dong_tien_du_an_noi_dung_thau_phu'];
		$dong_tien_du_an_gia_tri_thau_phu 	= $params['dong_tien_du_an_gia_tri_thau_phu'];
		$dong_tien_du_an_cpi 				= $params['dong_tien_du_an_cpi'];
		$dong_tien_du_an_tinh_trang 		= $params['dong_tien_du_an_tinh_trang'];
		$dong_tien_du_an_thu_tu 			= $params['dong_tien_du_an_thu_tu'];

		if(!empty($bao_cao_tuan_id)) $wpdb->query("DELETE FROM ".$this->dong_tien_du_an_table_name." WHERE bao_cao_tuan_id = $bao_cao_tuan_id");

		foreach ($dong_tien_du_an_noi_dung_tong_thau as $key => $value) {
			$data = array(
				'bao_cao_tuan_id' => $bao_cao_tuan_id,
				'dong_tien_du_an_noi_dung_tong_thau' 	=> sanitize_text_field($dong_tien_du_an_noi_dung_tong_thau[$key]),
				'dong_tien_du_an_gia_tri_tong_thau' 	=> str_replace(',', '', sanitize_text_field($dong_tien_du_an_gia_tri_tong_thau[$key])),
				'dong_tien_du_an_noi_dung_thau_phu' 	=> sanitize_text_field($dong_tien_du_an_noi_dung_thau_phu[$key]),
				'dong_tien_du_an_gia_tri_thau_phu' 		=> str_replace(',', '', sanitize_text_field($dong_tien_du_an_gia_tri_thau_phu[$key])),
				'dong_tien_du_an_cpi' 					=> str_replace(',', '', sanitize_text_field($dong_tien_du_an_cpi[$key])),
				'dong_tien_du_an_tinh_trang' 			=> sanitize_text_field($dong_tien_du_an_tinh_trang[$key]),
				'dong_tien_du_an_thu_tu' 				=> sanitize_text_field($dong_tien_du_an_thu_tu[$key]),
			);
			$wpdb->insert($this->dong_tien_du_an_table_name, $data);
		}
	}

	public function tien_do_du_an($params){
		global $wpdb;

		$bao_cao_tuan_id 						= (int)$params['id'];
		$tien_do_du_an_bat_dau_theo_hop_dong 	= $params['tien_do_du_an_bat_dau_theo_hop_dong'];
		$tien_do_du_an_ket_thuc_theo_hop_dong 	= $params['tien_do_du_an_ket_thuc_theo_hop_dong'];
		$tien_do_du_an_thuc_te_bat_dau 			= $params['tien_do_du_an_thuc_te_bat_dau'];
		$tien_do_du_an_thuc_te_ket_thuc 		= $params['tien_do_du_an_thuc_te_ket_thuc'];
		$tien_do_du_an_thu_tu 					= $params['tien_do_du_an_thu_tu'];

		if(!empty($bao_cao_tuan_id)) $wpdb->query("DELETE FROM ".$this->tien_do_du_an_table_name." WHERE bao_cao_tuan_id = $bao_cao_tuan_id");

		foreach ($tien_do_du_an_bat_dau_theo_hop_dong as $key => $value) {
			$data = array(
				'bao_cao_tuan_id' => $bao_cao_tuan_id,
				'tien_do_du_an_bat_dau_theo_hop_dong' 	=> !empty($tien_do_du_an_bat_dau_theo_hop_dong[$key]) ? sanitize_text_field($tien_do_du_an_bat_dau_theo_hop_dong[$key]) : NULL,
				'tien_do_du_an_ket_thuc_theo_hop_dong' 	=> !empty($tien_do_du_an_ket_thuc_theo_hop_dong[$key]) ? sanitize_text_field($tien_do_du_an_ket_thuc_theo_hop_dong[$key]) : NULL,
				'tien_do_du_an_thuc_te_bat_dau' 		=> !empty($tien_do_du_an_thuc_te_bat_dau[$key]) ? sanitize_text_field($tien_do_du_an_thuc_te_bat_dau[$key]) : NULL,
				'tien_do_du_an_thuc_te_ket_thuc' 		=> !empty($tien_do_du_an_thuc_te_ket_thuc[$key]) ? sanitize_text_field($tien_do_du_an_thuc_te_ket_thuc[$key]) : NULL,
				'tien_do_du_an_thu_tu' 					=> !empty($tien_do_du_an_thu_tu[$key]) ? sanitize_text_field($tien_do_du_an_thu_tu[$key]) : NULL,
			);
			$wpdb->insert($this->tien_do_du_an_table_name, $data);
		}
	}

	public function phat_sinh_du_an($params){
		global $wpdb;

		$bao_cao_tuan_id 			= (int)$params['id'];
		$phat_sinh_du_an_noi_dung 	= $params['phat_sinh_du_an_noi_dung'];
		$phat_sinh_du_an_gia_tri 	= $params['phat_sinh_du_an_gia_tri'];
		$phat_sinh_du_an_tinh_trang = $params['phat_sinh_du_an_tinh_trang'];
		$phat_sinh_du_an_thu_tu 	= $params['phat_sinh_du_an_thu_tu'];

		if(!empty($bao_cao_tuan_id)) $wpdb->query("DELETE FROM ".$this->phat_sinh_du_an_table_name." WHERE bao_cao_tuan_id = $bao_cao_tuan_id");

		foreach ($phat_sinh_du_an_noi_dung as $key => $value) {
			$data = array(
				'bao_cao_tuan_id' 				=> $bao_cao_tuan_id,
				'phat_sinh_du_an_noi_dung' 		=> sanitize_text_field($phat_sinh_du_an_noi_dung[$key]),
				'phat_sinh_du_an_gia_tri' 		=> str_replace(',', '', sanitize_text_field($phat_sinh_du_an_gia_tri[$key])),
				'phat_sinh_du_an_tinh_trang' 	=> sanitize_text_field($phat_sinh_du_an_tinh_trang[$key]),
				'phat_sinh_du_an_thu_tu' 		=> sanitize_text_field($phat_sinh_du_an_thu_tu[$key]),
			);
			$wpdb->insert($this->phat_sinh_du_an_table_name, $data);
		}
	}

	public function rui_ro_du_an($params){
		global $wpdb;

		$bao_cao_tuan_id 					= (int)$params['id'];
		$rui_ro_du_an_nhom_rui_ro 			= $params['rui_ro_du_an_nhom_rui_ro'];
		$rui_ro_du_an_noi_dung 				= $params['rui_ro_du_an_noi_dung'];
		$rui_ro_du_an_ke_hoach_doi_ung 		= $params['rui_ro_du_an_ke_hoach_doi_ung'];
		$rui_ro_du_an_nguoi_thuc_hien 		= $params['rui_ro_du_an_nguoi_thuc_hien'];
		$rui_ro_du_an_muc_do_nghiem_trong 	= $params['rui_ro_du_an_muc_do_nghiem_trong'];
		$rui_ro_du_an_thu_tu 				= $params['rui_ro_du_an_thu_tu'];

		if(!empty($bao_cao_tuan_id)) $wpdb->query("DELETE FROM ".$this->rui_ro_du_an_table_name." WHERE bao_cao_tuan_id = $bao_cao_tuan_id");

		foreach ($rui_ro_du_an_nhom_rui_ro as $key => $value) {
			$data = array(
				'bao_cao_tuan_id' 					=> $bao_cao_tuan_id,
				'rui_ro_du_an_nhom_rui_ro' 			=> sanitize_text_field($rui_ro_du_an_nhom_rui_ro[$key]),
				'rui_ro_du_an_noi_dung' 			=> sanitize_text_field($rui_ro_du_an_noi_dung[$key]),
				'rui_ro_du_an_ke_hoach_doi_ung' 	=> sanitize_text_field($rui_ro_du_an_ke_hoach_doi_ung[$key]),
				'rui_ro_du_an_nguoi_thuc_hien' 		=> sanitize_text_field(lay_ma_nhan_vien($rui_ro_du_an_nguoi_thuc_hien[$key])),
				'rui_ro_du_an_muc_do_nghiem_trong' 	=> sanitize_text_field($rui_ro_du_an_muc_do_nghiem_trong[$key]),
				'rui_ro_du_an_thu_tu' 				=> sanitize_text_field($rui_ro_du_an_thu_tu[$key]),
			);
			$wpdb->insert($this->rui_ro_du_an_table_name, $data);
		}
	}

	public function ncr_du_an($params){
		global $wpdb;

		$bao_cao_tuan_id 				= (int)$params['id'];
		$ncr_du_an_noi_dung 			= $params['ncr_du_an_noi_dung'];
		$ncr_du_an_uoc_tinh_gia_tri_ncr = $params['ncr_du_an_uoc_tinh_gia_tri_ncr'];
		$ncr_du_an_thuc_te_gia_tri_ncr 	= $params['ncr_du_an_thuc_te_gia_tri_ncr'];
		$ncr_du_an_tinh_trang 			= $params['ncr_du_an_tinh_trang'];
		$ncr_du_an_thu_tu 				= $params['ncr_du_an_thu_tu'];
		$ncr_du_an_don_vi_tien_te 		= $params['ncr_du_an_don_vi_tien_te'];

		if(!empty($bao_cao_tuan_id)) $wpdb->query("DELETE FROM ".$this->ncr_du_an_table_name." WHERE bao_cao_tuan_id = $bao_cao_tuan_id");
				
		foreach ($ncr_du_an_noi_dung as $key => $value) {
			$data = array(
				'bao_cao_tuan_id' 					=> $bao_cao_tuan_id,
				'ncr_du_an_noi_dung' 				=> sanitize_text_field($ncr_du_an_noi_dung[$key]),
				'ncr_du_an_don_vi_tien_te'		 	=> sanitize_text_field($ncr_du_an_don_vi_tien_te[$key]),
				'ncr_du_an_uoc_tinh_gia_tri_ncr' 	=> str_replace(',', '', sanitize_text_field($ncr_du_an_uoc_tinh_gia_tri_ncr[$key])),
				'ncr_du_an_thuc_te_gia_tri_ncr' 	=> str_replace(',', '', sanitize_text_field($ncr_du_an_thuc_te_gia_tri_ncr[$key])),
				'ncr_du_an_tinh_trang' 				=> sanitize_text_field($ncr_du_an_tinh_trang[$key]),
				'ncr_du_an_thu_tu' 					=> sanitize_text_field($ncr_du_an_thu_tu[$key]),
			);
			$wpdb->insert($this->ncr_du_an_table_name, $data);
		}
	}

	public function export_gui_mail_bao_cao_tuan($params = array()) {
		global $wpdb;
		$att = new DA_Attachment();

		if($params['thao_tac'] == 'export_word'){
			$this->export_bao_cao_tuan(array(
				'id' 		=> $params['id'],
				'thao_tac' 	=> 'export_word'
			));
			return;
		}

		// Xoa ho so
		if(isset($params['danh_sach_ho_so_del']) && $params['danh_sach_ho_so_del']) {
			$att->delete($params['danh_sach_ho_so_del']);
		}

		// Upload ho so
		$att->upload(array(
			'owner_id' => $params['owner_id'],
			'owner_name' => $params['owner_name'],
			'object_id' => $params['id'],
			'object_type' => 'gui_mail_bao_cao_tuan',
			'name_attachments' => 'danh_sach_ho_so',
			'upload_dir' => DA_PROJECT_RELATIVE_PATH.'/'.$params['ma_du_an'].'/'.'Gui_mail_bao_cao_tuan'.'/'.current_time('d-m-Y')
		));


		$thong_tin = $this->thong_tin(array('id' => $params['id']));
		$mail = new DA_Email();

		$attachments = $att->list(array(
			'object_id'   => $thong_tin->id,
			'object_type' => 'gui_mail_bao_cao_tuan',
		));
		$url_file = $thong_tin->bao_cao_tuan_url;

		// // Send mail
		$tieu_de = sanitize_text_field($params['tieu_de']);
		$noi_dung = $params['soan_thao_email'];
		if($attachments){
			$ds_dinh_kem = array();
			foreach ($attachments as $attachment){
				$ds_dinh_kem[] = $attachment->path;
			}
		}else{
			$noi_dung .= 'Vui lòng bấm vào đường link sau để xem:';
			$noi_dung .= '<a href="'.$url_file.'">['.$thong_tin->tieu_de.']</a><br />';
		}

		$to = array();
		if ($params['send_to']){
			$to[] = $params['send_to'];
		}
		if ($params['send_cc']) {
			$cc = explode(',', $params['send_cc']);
			$to = array_merge($to, $cc);
		}
		$mail->add_queue(array(
			'to' => $to,
			'subject' => $tieu_de,
			'content' => $noi_dung,
			'attachments' => $ds_dinh_kem,
		));

		// dung de test
		// $mail->send(array(
		// 	'to' => $to,
		// 	'subject' => $tieu_de,
		// 	'content' => $noi_dung,
		// 	'attachments' => serialize($ds_dinh_kem),
		// ));
	}

	public function ajax_export_bao_cao_tuan($params = array()) {
		global $wpdb;
		$pdf = $this->export_bao_cao_tuan(array('id' => $_REQUEST['id']));

		$data = array(
			'id' 				=> $_REQUEST['id'],
			'bao_cao_tuan_url' 	=> $pdf['pdf_url'],
			'bao_cao_tuan_path' => $pdf['pdf_path'],
		);
		$wpdb->update($this->bao_cao_tuan_table_name, $data, array('id' => $data['id']));

		echo $data['bao_cao_tuan_url'];
		die();
	}

	public function luy_ke_tong_ca($params = array()){
		global $wpdb;
	
		$sql = "SELECT object_id, object_type, object_name, SUM(COALESCE(tong_ca,0)) as luy_ke_ca_den_hom_nay";
		$sql .= " FROM ".$this->bao_cao_ngay_nhan_luc_thiet_bi_table_name;
	
		$where = array();
	
		if (isset($params['ma_du_an']) && $params['ma_du_an']) 			 $where[] = "ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['ma_doi_tac']) && $params['ma_doi_tac']) 		 $where[] = "ma_doi_tac = '".sanitize_text_field($params['ma_doi_tac'])."'";
		if (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) $where[] = "ngay_bao_cao <= '".sanitize_text_field($params['ngay_ket_thuc'])."'";
	
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);
		
		$sql .= " GROUP BY object_id, object_type, object_name"; 
	
		return $wpdb->get_results($sql);
	}
	public function uoc_tinh_gia_tri_luy_ke_den_tuan_nay($params = array()){
		global $wpdb;
	
		$sql = "SELECT object_id, object_type, object_name, SUM(COALESCE(tong_ca,0) * (COALESCE(don_gia,0) + COALESCE(phu_cap,0))) as uoc_tinh_gia_tri_luy_ke_den_tuan_nay";
		$sql .= " FROM ".$this->bao_cao_ngay_nhan_luc_thiet_bi_table_name;
	
		$where = array();
	
		if (isset($params['ma_du_an']) && $params['ma_du_an']) 			 $where[] = "ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['ma_doi_tac']) && $params['ma_doi_tac']) 		 $where[] = "ma_doi_tac = '".sanitize_text_field($params['ma_doi_tac'])."'";
		if (isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) $where[] = "ngay_bao_cao <= '".sanitize_text_field($params['ngay_ket_thuc'])."'";
	
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);
		
		$sql .= " GROUP BY object_id, object_type, object_name"; 
	
		return $wpdb->get_results($sql);
	}

	public function danh_sach_bao_cao_ngay_thoi_tiet($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM ".$this->bao_cao_ngay_thoi_tiet_table_name;

		$where = array();

		if (isset($params['ma_du_an']) && $params['ma_du_an']) 																			$where[] = "ma_du_an = '".sanitize_text_field($params['ma_du_an'])."'";
		if (isset($params['ngay_bat_dau']) && $params['ngay_bat_dau'] && isset($params['ngay_ket_thuc']) && $params['ngay_ket_thuc']) 	$where[] = "ngay_bao_cao BETWEEN '".sanitize_text_field($params['ngay_bat_dau'])."' AND '".sanitize_text_field($params['ngay_ket_thuc'])."'";
		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		return $wpdb->get_results($sql);
	}

	// export word bao cao tuan
	public function export_bao_cao_tuan($params = array()) {
		global $wpdb;

		$da 	= new DA_Duan();
		$bcn 	= new DA_Baocaongay();
		$bct 	= new DA_Baocaotuan();
		$att 	= new DA_Attachment();
		$nv 	= new DA_Nhanvien();
		$ns 	= new DA_Nhansu();
		$cd		= new DA_Caidat();

		$upload_dir  = wp_upload_dir();

		$bao_cao = $this->thong_tin(array(
			'id' => $params['id'],
		));
		$du_an = $da->thong_tin($bao_cao->ma_du_an);

		if (!$bao_cao) return;

		//Load PHPDoc
		include ABSPATH.'/vendor/autoload.php';

		$bieu_mau = DRAGONADDON_PLUGIN_DIR.'assets/files/bao-cao-tuan-report.docx';
		
		$ngay_bat_dau 	= strtotime($bao_cao->ngay_bat_dau);
		$ngay_ket_thuc 	= strtotime($bao_cao->ngay_ket_thuc);

		//Load PHPDoc
		//include ABSPATH.'/vendor/autoload.php';

		$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($bieu_mau);

		$templateProcessor->setValue('ten_du_an', clean_data_export_form($du_an->ten_du_an));
		$templateProcessor->setValue('ngay_bat_dau', date('d/m/Y', $ngay_bat_dau));
		$templateProcessor->setValue('ngay_ket_thuc', date('d/m/Y', $ngay_ket_thuc));

		// NHAN LUC VA THIET BI ATAD
		$ds_nhan_luc_thiet_bi           = array();
		$ds_nhan_luc_thiet_bi_filter    = array();
		$tong_cong                      = array("uoc_tinh_gia_tri_trong_tuan" => 0, "uoc_tinh_gia_tri_luy_ke_den_tuan_nay" => 0);
	
		// danh sach thiet bi nhan luc atad
		$ds_nhan_luc_thiet_bi = $bcn->danh_sach_nhan_luc_thiet_bi_bao_cao(array(
			'ma_du_an'      => $bao_cao->ma_du_an,
			'ngay_bat_dau'  => $bao_cao->ngay_bat_dau,
			'ngay_ket_thuc' => $bao_cao->ngay_ket_thuc,
			'ma_doi_tac'    => '0000001100',
		));
	
		if(!empty($ds_nhan_luc_thiet_bi)){
			// loc lai danh sach de hien thi theo ngay 
			foreach($ds_nhan_luc_thiet_bi as $i => $nhan_luc_thiet_bi){
				$ds_nhan_luc_thiet_bi_filter[$nhan_luc_thiet_bi->object_name][$nhan_luc_thiet_bi->ngay_bao_cao] = $nhan_luc_thiet_bi;
			}
	
			// lay luy ke tong ca den hom nay
			$ds_luy_ke_tong_ca = $bct->luy_ke_tong_ca(array(
				'ma_du_an'      => $bao_cao->ma_du_an,
				'ngay_ket_thuc' => $bao_cao->ngay_ket_thuc,
				'ma_doi_tac'    => '0000001100',
			));
	
			foreach($ds_luy_ke_tong_ca as $i => $luy_ke_tong_ca){
				if(!array_key_exists($luy_ke_tong_ca->object_name,  $ds_nhan_luc_thiet_bi_filter)) continue;
				$ds_nhan_luc_thiet_bi_filter[$luy_ke_tong_ca->object_name]['luy_ke_ca_den_hom_nay'] = $luy_ke_tong_ca->luy_ke_ca_den_hom_nay;
			}

			// uoc tinh gia tri luy ke den tuan nay
			$ds_uoc_tinh_gia_tri_luy_ke_den_tuan_nay = $bct->uoc_tinh_gia_tri_luy_ke_den_tuan_nay(array(
				'ma_du_an'      => $bao_cao->ma_du_an,
				'ngay_ket_thuc' => $bao_cao->ngay_ket_thuc,
				'ma_doi_tac'    => '0000001100',
			));
			foreach($ds_uoc_tinh_gia_tri_luy_ke_den_tuan_nay as $i => $uoc_tinh_gia_tri_luy_ke_den_tuan_nay){
				$ds_nhan_luc_thiet_bi_filter[$uoc_tinh_gia_tri_luy_ke_den_tuan_nay->object_name]['uoc_tinh_gia_tri_luy_ke_den_tuan_nay'] = $uoc_tinh_gia_tri_luy_ke_den_tuan_nay->uoc_tinh_gia_tri_luy_ke_den_tuan_nay;
			}
		}

		$stt = 0;
		for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
			$ngay_thang = date("d/m", $ngay);
			$templateProcessor->setValue('ngay_'.$stt, $ngay_thang);
			$stt++;
		}

		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		// xu ly cho nhan luc va thiet bi atad
		$export_ds_nhan_luc = array();
		$key = 0;
		foreach($ds_nhan_luc_thiet_bi_filter as $object_name => $nhan_luc_thiet_bi){
			$stt = 0;
			$uoc_tinh_gia_tri_trong_tuan 			 = 0;
			$export_ds_nhan_luc[$key]['object_name'] = clean_data_export_form($object_name ?? '');
			$export_ds_nhan_luc[$key]['tong_ca'] 	 = 0;

			for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
				$ngay_thang_nam                      = date("Y-m-d", $ngay);
				$so_gio_ca_theo_ngay               	 = $nhan_luc_thiet_bi[$ngay_thang_nam]->tong_ca ?? 0;
				$export_ds_nhan_luc[$key]['tong_ca'] += $so_gio_ca_theo_ngay;
				$uoc_tinh_gia_tri_trong_tuan         += $so_gio_ca_theo_ngay * ($nhan_luc_thiet_bi[$ngay_thang_nam]->don_gia + $nhan_luc_thiet_bi[$ngay_thang_nam]->phu_cap);

				$export_ds_nhan_luc[$key]['ca_gio_'.$stt] = clean_data_export_form($so_gio_ca_theo_ngay);
				$stt++;
			}
			
			// luy ke den tuan truoc
			$export_ds_nhan_luc[$key]['lkdtt'] = $nhan_luc_thiet_bi['luy_ke_ca_den_hom_nay'] - $export_ds_nhan_luc[$key]['tong_ca'];
			
			// luy ke den hom nay
			$export_ds_nhan_luc[$key]['lkdhn'] = $nhan_luc_thiet_bi['luy_ke_ca_den_hom_nay'];

			$uoc_tinh_gia_tri_luy_ke_den_tuan_nay               = $nhan_luc_thiet_bi["uoc_tinh_gia_tri_luy_ke_den_tuan_nay"];
			$tong_cong['uoc_tinh_gia_tri_trong_tuan']           += $uoc_tinh_gia_tri_trong_tuan;
			$tong_cong['uoc_tinh_gia_tri_luy_ke_den_tuan_nay']  += $uoc_tinh_gia_tri_luy_ke_den_tuan_nay;

			// uoc tinh gia tri trong tuan
			$export_ds_nhan_luc[$key]['utgttt'] = clean_data_export_form(number_format($uoc_tinh_gia_tri_trong_tuan));

			// uoc tinh gia tri luy ke den tuan nay
			$export_ds_nhan_luc[$key]['utgtlkdtn'] = clean_data_export_form(number_format($uoc_tinh_gia_tri_luy_ke_den_tuan_nay));
			$key++;
		}
		// dd($export_ds_nhan_luc);
		$templateProcessor->cloneRowAndSetValues('object_name', $export_ds_nhan_luc);

		// tong uoc tinh gia tri trong tuan
		$templateProcessor->setValue('tutgttt',clean_data_export_form(number_format($tong_cong['uoc_tinh_gia_tri_trong_tuan'])));

		// tong uoc tinh gia tri luy ke den tuan nay
		$templateProcessor->setValue('tutgtlkdtn',clean_data_export_form(number_format($tong_cong['uoc_tinh_gia_tri_luy_ke_den_tuan_nay'])));



		// NHAN LUC VA THIET BI DOI TAC
		$ds_doi_tac_du_an = $da->danh_sach_doi_tac_du_an(array(
			'ma_du_an' => $du_an->ma_du_an
		));
		$templateProcessor->cloneRow('clone_rows', count($ds_doi_tac_du_an));

		$stt_clone = 1;
		foreach ($ds_doi_tac_du_an as $doi_tac){
			$export_ds_nhan_luc = array();
			$ds_nhan_luc_thiet_bi           = array();
			$ds_nhan_luc_thiet_bi_filter    = array();
			$tong_cong                      = array("uoc_tinh_gia_tri_trong_tuan" => 0, "uoc_tinh_gia_tri_luy_ke_den_tuan_nay" => 0);
	
			// danh sach thiet bi nhan luc nha thau
			$ds_nhan_luc_thiet_bi = $bcn->danh_sach_nhan_luc_thiet_bi_bao_cao(array(
				'ma_du_an'      => $bao_cao->ma_du_an,
				'ngay_bat_dau'  => $bao_cao->ngay_bat_dau,
				'ngay_ket_thuc' => $bao_cao->ngay_ket_thuc,
				'ma_doi_tac'    => $doi_tac->id,
			));
	
			if(!empty($ds_nhan_luc_thiet_bi)){
				// loc lai danh sach de hien thi theo ngay 
				foreach($ds_nhan_luc_thiet_bi as $i => $nhan_luc_thiet_bi){
					$ds_nhan_luc_thiet_bi_filter[$nhan_luc_thiet_bi->object_name][$nhan_luc_thiet_bi->ngay_bao_cao] = $nhan_luc_thiet_bi;
				}
	
				// lay luy ke tong ca den hom nay
				$ds_luy_ke_tong_ca = $bct->luy_ke_tong_ca(array(
					'ma_du_an'      => $bao_cao->ma_du_an,
					'ngay_ket_thuc' => $bao_cao->ngay_ket_thuc,
					'ma_doi_tac'    => $doi_tac->id,
				));
	
				foreach($ds_luy_ke_tong_ca as $i => $luy_ke_tong_ca){
					if(!array_key_exists($luy_ke_tong_ca->object_name,  $ds_nhan_luc_thiet_bi_filter)) continue;
					$ds_nhan_luc_thiet_bi_filter[$luy_ke_tong_ca->object_name]['luy_ke_ca_den_hom_nay'] = $luy_ke_tong_ca->luy_ke_ca_den_hom_nay;
				}

				// uoc tinh gia tri luy ke den tuan nay
				$ds_uoc_tinh_gia_tri_luy_ke_den_tuan_nay = $bct->uoc_tinh_gia_tri_luy_ke_den_tuan_nay(array(
					'ma_du_an'      => $bao_cao->ma_du_an,
					'ngay_ket_thuc' => $bao_cao->ngay_ket_thuc,
					'ma_doi_tac'    => $doi_tac->id,
				));
				foreach($ds_uoc_tinh_gia_tri_luy_ke_den_tuan_nay as $i => $uoc_tinh_gia_tri_luy_ke_den_tuan_nay){
					$ds_nhan_luc_thiet_bi_filter[$uoc_tinh_gia_tri_luy_ke_den_tuan_nay->object_name]['uoc_tinh_gia_tri_luy_ke_den_tuan_nay'] = $uoc_tinh_gia_tri_luy_ke_den_tuan_nay->uoc_tinh_gia_tri_luy_ke_den_tuan_nay;
				}
			}

			$key = 0;
			foreach($ds_nhan_luc_thiet_bi_filter as $object_name => $nhan_luc_thiet_bi){
				$stt = 0;
				$uoc_tinh_gia_tri_trong_tuan = 0;
				$export_ds_nhan_luc[$key]["object_name_dt#{$stt_clone}"] = clean_data_export_form($object_name ?? '');
				$export_ds_nhan_luc[$key]["tong_ca_dt#{$stt_clone}"] 	 = 0;

				for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
					$ngay_thang_nam                           = date("Y-m-d", $ngay);
					$so_gio_ca_theo_ngay               	 	  = $nhan_luc_thiet_bi[$ngay_thang_nam]->tong_ca ?? 0;
					$export_ds_nhan_luc[$key]["tong_ca_dt#{$stt_clone}"] += clean_data_export_form($so_gio_ca_theo_ngay);
	
					$export_ds_nhan_luc[$key]["ca_gio_dt_{$stt}#{$stt_clone}"] = clean_data_export_form($so_gio_ca_theo_ngay);
					$uoc_tinh_gia_tri_trong_tuan              += $so_gio_ca_theo_ngay * $nhan_luc_thiet_bi[$ngay_thang_nam]->don_gia;
					$stt++;
				}
				
				// luy ke den tuan truoc
				$export_ds_nhan_luc[$key]["lkdtt_dt#{$stt_clone}"] = clean_data_export_form($nhan_luc_thiet_bi['luy_ke_ca_den_hom_nay'] - $export_ds_nhan_luc[$key]["tong_ca_dt#{$stt_clone}"]);
				
				// luy ke den hom nay
				$export_ds_nhan_luc[$key]["lkdhn_dt#{$stt_clone}"] = clean_data_export_form($nhan_luc_thiet_bi['luy_ke_ca_den_hom_nay']);
	
				$uoc_tinh_gia_tri_luy_ke_den_tuan_nay               = $nhan_luc_thiet_bi["uoc_tinh_gia_tri_luy_ke_den_tuan_nay"];
				$tong_cong['uoc_tinh_gia_tri_trong_tuan']           += $uoc_tinh_gia_tri_trong_tuan;
				$tong_cong['uoc_tinh_gia_tri_luy_ke_den_tuan_nay']  += $uoc_tinh_gia_tri_luy_ke_den_tuan_nay;
	
				// uoc tinh gia tri trong tuan
				$export_ds_nhan_luc[$key]["utgttt_dt#{$stt_clone}"] = clean_data_export_form(number_format($uoc_tinh_gia_tri_trong_tuan));
	
				// uoc tinh gia tri luy ke den tuan nay
				$export_ds_nhan_luc[$key]["utgtlkdtn_dt#{$stt_clone}"] = clean_data_export_form(number_format($uoc_tinh_gia_tri_luy_ke_den_tuan_nay));
				$key++;
			}

			$templateProcessor->cloneRowAndSetValues("object_name_dt#{$stt_clone}", $export_ds_nhan_luc);

			// // tong uoc tinh gia tri trong tuan
			$templateProcessor->setValue("tutgttt_dt#{$stt_clone}",clean_data_export_form(number_format($tong_cong['uoc_tinh_gia_tri_trong_tuan'])));
	
			// // tong uoc tinh gia tri luy ke den tuan nay
			$templateProcessor->setValue("tutgtlkdtn_dt#{$stt_clone}",clean_data_export_form(number_format($tong_cong['uoc_tinh_gia_tri_luy_ke_den_tuan_nay'])));
			
			$templateProcessor->setValue("ten_doi_tac#{$stt_clone}",clean_data_export_form('Nhân sự / Thiết bị ' . " " . $da->loai_nha_thau[$doi_tac->ma_loai_nha_thau] . " " . $doi_tac->ten_cong_ty));
			$stt_clone++;
		}


		// THOI TIET
		$ds_thoi_tiet = $bct->danh_sach_bao_cao_ngay_thoi_tiet(array(
			'ma_du_an'      => $bao_cao->ma_du_an,
			'ngay_bat_dau'  => $bao_cao->ngay_bat_dau,
			'ngay_ket_thuc' => $bao_cao->ngay_ket_thuc,
		));

		$ds_thoi_tiet_filter = array();
		foreach($ds_thoi_tiet as $i => $thoi_tiet){
			$ds_thoi_tiet_filter[$thoi_tiet->ngay_bao_cao] = $thoi_tiet;
		}

		// ca ngay
		$stt_ca_ngay = 0;
		for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
			$ngay_thang_nam = date("Y-m-d", $ngay);
			$templateProcessor->setValue("ca_ngay_{$stt_ca_ngay}", $ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_ca_ngay ? $cd->tinh_trang_thoi_tiet[$ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_ca_ngay] ?? $ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_ca_ngay : "");
			$stt_ca_ngay++;
		}

		// ca dem
		$stt_ca_dem = 0;
		for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
			$ngay_thang_nam = date("Y-m-d", $ngay);
			$templateProcessor->setValue("ca_dem_{$stt_ca_dem}", $ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_ca_dem ? $cd->tinh_trang_thoi_tiet[$ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_ca_dem] ?? $ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_ca_dem : "");
			$stt_ca_dem++;
		}

		// tang ca
		$stt_tang_ca = 0;
		for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
			$ngay_thang_nam = date("Y-m-d", $ngay);
			$templateProcessor->setValue("tang_ca_{$stt_tang_ca}", $ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_tang_ca ? $cd->tinh_trang_thoi_tiet[$ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_tang_ca] ?? $ds_thoi_tiet_filter[$ngay_thang_nam]->thoi_tiet_tang_ca : "");
			$stt_tang_ca++;
		}



		// DIEU KHOAN THANH TOAN THEO HOP DONG
		$templateProcessor->setValue('dieu_khoan_thanh_toan', clean_data_export_form($bao_cao->dktt_theo_hop_dong));



		//KE HOACH NGHIEM THU VA THANH TOAN
		$export_ds_khntvtt = array();
		$danh_sach_ke_hoach_nghiem_thu_va_thanh_toan = $bct->danh_sach_ke_hoach_nghiem_thu_va_thanh_toan(array(
			'bao_cao_tuan_id' => $params['id'],
		));
		foreach($danh_sach_ke_hoach_nghiem_thu_va_thanh_toan as $item) {
			$export_ds_khntvtt[] = array(
				'khntvtt_thu_tu'				=> clean_data_export_form($item->khntvtt_thu_tu ?? 1),
				'khntvtt_noi_dung_thanh_toan'	=> clean_data_export_form($item->khntvtt_noi_dung_thanh_toan ?? ''),
				'khntvtt_ngay_chot_khoi_luong'	=> clean_data_export_form($item->khntvtt_ngay_chot_khoi_luong ?? ''),
				'khntvtt_gia_tri_thanh_toan'	=> $item->khntvtt_gia_tri_thanh_toan ? number_format($item->khntvtt_gia_tri_thanh_toan): '',
				'khntvtt_tinh_trang'			=> clean_data_export_form($item->khntvtt_tinh_trang ?? ''),
			);
		}
		$templateProcessor->cloneRowAndSetValues('khntvtt_thu_tu', $export_ds_khntvtt);	


		//DONG TIEN DU AN
		$export_ds_dtda = array();
		$danh_sach_dong_tien_du_an = $bct->danh_sach_dong_tien_du_an(array(
			'bao_cao_tuan_id' => $params['id'],
		));
		foreach($danh_sach_dong_tien_du_an as $item) {
			$export_ds_dtda[] = array(
				'dong_tien_du_an_noi_dung_tong_thau'	=> clean_data_export_form($item->dong_tien_du_an_noi_dung_tong_thau ?? ''),
				'dong_tien_du_an_gia_tri_tong_thau'		=> $item->dong_tien_du_an_gia_tri_tong_thau ? number_format($item->dong_tien_du_an_gia_tri_tong_thau): '',
				'dong_tien_du_an_noi_dung_thau_phu'		=> clean_data_export_form($item->dong_tien_du_an_noi_dung_thau_phu ?? ''),
				'dong_tien_du_an_gia_tri_thau_phu'		=> $item->dong_tien_du_an_gia_tri_thau_phu ? number_format($item->dong_tien_du_an_gia_tri_thau_phu): '',
				'dong_tien_du_an_cpi'					=> clean_data_export_form($item->dong_tien_du_an_cpi ?? ''),
				'dong_tien_du_an_tinh_trang'			=> clean_data_export_form($item->dong_tien_du_an_tinh_trang ?? ''),
			);
		}
		$templateProcessor->cloneRowAndSetValues('dong_tien_du_an_noi_dung_tong_thau', $export_ds_dtda);	


		//SAN LUONG THI CONG
		$ds_hinh_anh = $att->list(array(
			'object_type' => 'bao_cao_tuan_san_luong',
			'object_id' => $params['id'],
		));
		$bct_san_luong = array();
		if(!empty($ds_hinh_anh))
			foreach($ds_hinh_anh as $hinh_anh)
				$bct_san_luong[] = $hinh_anh->path;
		// Tao cac placeholder dong va chen vao template
		$placeholders = '';
		if (!empty($bct_san_luong)) {
			foreach ($bct_san_luong as $index => $image_path) {
				$placeholder = 'bct_san_luong' . ($index + 1);
				$placeholders .= '${' . $placeholder . '}';
				// Xuong dong sau moi hinh anh
				$placeholder .= '</w:t><w:br/><w:t>'.'</w:t><w:br/><w:t>';
			}
		} else {
			$placeholders = 'Không có hình ảnh nào được chèn vào báo cáo.';
		}
		// Doc template Word va chen cac placeholder vao vi tri thich hop
		$templateProcessor->setValue('bct_san_luong', $placeholders);
		// Chen hinh anh vao cac placeholder dong
		if (!empty($bct_san_luong)) {
			try {
				foreach ($bct_san_luong as $index => $image_path) {
					$placeholder = 'bct_san_luong' . ($index + 1); // Tạo placeholder động
					$templateProcessor->setImageValue($placeholder, array('path' => $image_path, 'width' => 500, 'height' => 500, 'ratio' => true));
				}
			}
			catch (Exception $e) {
				// Xu ly loi xay ra
			}
		}


		//TIEN DO DU AN
		$danh_sach_tien_do_du_an = $bct->danh_sach_tien_do_du_an(array(
			'bao_cao_tuan_id' => $params['id'],
		));
		$export_ds_tdda = array();
		foreach($danh_sach_tien_do_du_an as $item) {
			$export_ds_tdda[] = array(
				'tien_do_du_an_bat_dau_theo_hop_dong'	=> clean_data_export_form($item->tien_do_du_an_bat_dau_theo_hop_dong ?? ''),
				'tien_do_du_an_ket_thuc_theo_hop_dong'	=> clean_data_export_form($item->tien_do_du_an_ket_thuc_theo_hop_dong ?? ''),
				'tien_do_du_an_thuc_te_bat_dau'			=> clean_data_export_form($item->tien_do_du_an_thuc_te_bat_dau ?? ''),
				'tien_do_du_an_thuc_te_ket_thuc'		=> clean_data_export_form($item->tien_do_du_an_thuc_te_ket_thuc ?? ''),
			);
		}
		$templateProcessor->cloneRowAndSetValues('tien_do_du_an_bat_dau_theo_hop_dong', $export_ds_tdda);	

		// danh gia tien do
		$templateProcessor->setValue('danh_gia_tien_do', $bao_cao->danh_gia_tien_do ? $this->danh_gia_tien_do[$bao_cao->danh_gia_tien_do] : '');
		$templateProcessor->setValue('ly_do_tre_tien_do',clean_data_export_form($bao_cao->ly_do_tre_tien_do && $bao_cao->danh_gia_tien_do == -2 ? str_replace('\\', '', $bao_cao->ly_do_tre_tien_do) : ' Không có'));
		if($bao_cao->danh_gia_tien_do == -2){
			$templateProcessor->setValue('so_ngay_tre',', Số ngày trễ: '.$bao_cao->so_ngay_tre ?? '' );
		}else{
			$templateProcessor->setValue('so_ngay_tre','');
		}

		$templateProcessor->setValue('de_xuat_giai_phap', clean_data_export_form($bao_cao->de_xuat_giai_phap ? str_replace('\\', '', $bao_cao->de_xuat_giai_phap) : ' Không có'));
		$templateProcessor->setValue('danh_gia_chung', clean_data_export_form($bao_cao->danh_gia_chung ? str_replace('\\', '', $bao_cao->danh_gia_chung) : ' Không có'));



		//PHAT SINH DU AN
		$danh_sach_phat_sinh_du_an = $bct->danh_sach_phat_sinh_du_an(array(
			'bao_cao_tuan_id' => $params['id'],
		));
		$export_ds_psda = array();
		$tong_gia_tri_phat_sinh = 0;
		foreach($danh_sach_phat_sinh_du_an as $item) {
			$export_ds_psda[] = array(
				'phat_sinh_du_an_thu_tu'		=> clean_data_export_form($item->phat_sinh_du_an_thu_tu ?? 1),
				'phat_sinh_du_an_noi_dung'		=> clean_data_export_form($item->phat_sinh_du_an_noi_dung ?? ''),
				'phat_sinh_du_an_gia_tri'		=> clean_data_export_form($item->phat_sinh_du_an_gia_tri ? number_format($item->phat_sinh_du_an_gia_tri) : ''),
				'phat_sinh_du_an_tinh_trang'	=> clean_data_export_form($item->phat_sinh_du_an_tinh_trang ?? ''),
			);
			$tong_gia_tri_phat_sinh += $item->phat_sinh_du_an_gia_tri;
		}
		$templateProcessor->cloneRowAndSetValues('phat_sinh_du_an_thu_tu', $export_ds_psda);	
		$templateProcessor->setValue('tong_gia_tri_phat_sinh',number_format($tong_gia_tri_phat_sinh));


		//RUI RO DU AN
		$danh_sach_rui_ro_du_an = $bct->danh_sach_rui_ro_du_an(array(
			'bao_cao_tuan_id' => $params['id'],
		));
		$export_ds_rrda = array();
		foreach($danh_sach_rui_ro_du_an as $item) {
			$rui_ro_du_an_nguoi_thuc_hien = $nv->thong_tin($item->rui_ro_du_an_nguoi_thuc_hien);
			$export_ds_rrda[] = array(
				'rui_ro_du_an_thu_tu'				=> clean_data_export_form($item->rui_ro_du_an_thu_tu ?? 1),
				'rui_ro_du_an_nhom_rui_ro'			=> $item->rui_ro_du_an_nhom_rui_ro ? $this->nhom_rui_ro[$item->rui_ro_du_an_nhom_rui_ro] : '',
				'rui_ro_du_an_noi_dung'				=> clean_data_export_form($item->rui_ro_du_an_noi_dung ?? ''),
				'rui_ro_du_an_ke_hoach_doi_ung'		=> clean_data_export_form($item->rui_ro_du_an_ke_hoach_doi_ung ?? ''),
				'rui_ro_du_an_nguoi_thuc_hien'		=> clean_data_export_form($rui_ro_du_an_nguoi_thuc_hien->ho_ten ? $rui_ro_du_an_nguoi_thuc_hien->ho_ten." (".$rui_ro_du_an_nguoi_thuc_hien->ma_nhan_vien.")":"" ),
				'rui_ro_du_an_muc_do_nghiem_trong'	=> $item->rui_ro_du_an_muc_do_nghiem_trong ? $this->muc_do_nghiem_trong[$item->rui_ro_du_an_muc_do_nghiem_trong] : '',
			);
		}
		$templateProcessor->cloneRowAndSetValues('rui_ro_du_an_thu_tu', $export_ds_rrda);


		//NCR DU AN
		$danh_sach_ncr_du_an = $bct->danh_sach_ncr_du_an(array(
			'bao_cao_tuan_id' => $params['id'],
		));
		$export_ds_ncrda = array();
		$tong_uoc_tinh_gia_tri_ncr = 0;
		$tong_thuc_te_gia_tri_ncr = 0;
		foreach($danh_sach_ncr_du_an as $item) {
			$export_ds_ncrda[] = array(
				'ncr_du_an_thu_tu'				=> clean_data_export_form($item->ncr_du_an_thu_tu ?? 1),
				'ncr_du_an_noi_dung'			=> clean_data_export_form($item->ncr_du_an_noi_dung ?? ''),
				'ncr_du_an_don_vi_tien_te'		=> clean_data_export_form($item->ncr_du_an_don_vi_tien_te ?? ''),
				'ncr_du_an_uoc_tinh_gia_tri_ncr'=> clean_data_export_form($item->ncr_du_an_uoc_tinh_gia_tri_ncr ? number_format($item->ncr_du_an_uoc_tinh_gia_tri_ncr) : ''),
				'ncr_du_an_thuc_te_gia_tri_ncr'	=> clean_data_export_form($item->ncr_du_an_thuc_te_gia_tri_ncr ? number_format($item->ncr_du_an_thuc_te_gia_tri_ncr) : ''),
				'ncr_du_an_tinh_trang'			=> clean_data_export_form( $item->ncr_du_an_tinh_trang ?? ''),
			);
			$tong_uoc_tinh_gia_tri_ncr += $item->ncr_du_an_uoc_tinh_gia_tri_ncr;
			$tong_thuc_te_gia_tri_ncr += $item->ncr_du_an_thuc_te_gia_tri_ncr;
		}
		$templateProcessor->cloneRowAndSetValues('ncr_du_an_thu_tu', $export_ds_ncrda);
		$templateProcessor->setValue('tong_uoc_tinh_gia_tri_ncr',number_format($tong_uoc_tinh_gia_tri_ncr));
		$templateProcessor->setValue('tong_thuc_te_gia_tri_ncr',number_format($tong_thuc_te_gia_tri_ncr));


		//HINH ANH
		$ds_hinh_anh = $att->list(array(
			'object_type' => 'bao_cao_tuan_hinh_anh',
			'object_id' => $params['id'],
		));
		$bct_hinh_anh = array();
		if(!empty($ds_hinh_anh))
			foreach($ds_hinh_anh as $hinh_anh)
				$bct_hinh_anh[] = $hinh_anh->path;
		// Tao cac placeholder dong va chen vao template
		$placeholders = '';
		if (!empty($bct_hinh_anh)) {
			foreach ($bct_hinh_anh as $index => $image_path) {
				$placeholder = 'bct_hinh_anh' . ($index + 1);
				$placeholders .= '${' . $placeholder . '}';
				// Xuong dong sau moi hinh anh
				$placeholder .= '</w:t><w:br/><w:t>'.'</w:t><w:br/><w:t>';
			}
		} else {
			$placeholders = 'Không có hình ảnh nào được chèn vào báo cáo.';
		}
		// Doc template Word va chen cac placeholder vao vi tri thich hop
		$templateProcessor->setValue('bct_hinh_anh', $placeholders);
		// Chen hinh anh vao cac placeholder dong
		if (!empty($bct_hinh_anh)) {
			try {
				foreach ($bct_hinh_anh as $index => $image_path) {
					$placeholder = 'bct_hinh_anh' . ($index + 1); // Tạo placeholder động
					$templateProcessor->setImageValue($placeholder, array('path' => $image_path, 'width' => 500, 'height' => 500, 'ratio' => true));
				}
			}
			catch (Exception $e) {
				// Xu ly loi xay ra
			}
		}


		// Ten phieu thu
		$word_name = 'BCT-'.time().'-PMD-FM06'.$bao_cao->ma_du_an.'-'.$bao_cao->ngay_bat_dau.'-'.$bao_cao->ngay_ket_thuc.'.docx';

		// $templateProcessor->saveAs('php://output');
		// $pdf_name = '['.$bao_cao->id.'] - '.$bao_cao->ho_ten.'-PHIEU-THU-'.time().'.pdf';
		$pdf_name = 'BCT-'.time().'-PMD-FM06'.$bao_cao->ma_du_an.'-'.$bao_cao->ngay_bat_dau.'-'.$bao_cao->ngay_ket_thuc.'.pdf';

		$word_path = $upload_dir['path'] . '/' . $word_name;
		$word_url = $upload_dir['url'] . '/' . $word_name;
		
		$pdf_path = $upload_dir['path'] . '/' . $pdf_name;
		$pdf_url = $upload_dir['url'] . '/' . $pdf_name;

		$templateProcessor->saveAs($word_path);

		//return array('pdf_url' => $word_url, 'pdf_path' => $word_path);

		if($params['thao_tac']=='export_word'){
			$word_path = (string)$word_path;
			header("Expires: 0");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
	
			$ext = pathinfo($word_path, PATHINFO_EXTENSION);
			$basename = pathinfo($word_path, PATHINFO_BASENAME);
	
			header("Content-type: application/".$ext);
			header('Content-length: '.filesize($word_path));
			header("Content-Disposition: attachment; filename=\"$basename\"");
			ob_end_clean();
			readfile($word_path);
			exit;
		}else{
			// $convert_api_secret = 'zjfSnrKN94WJdJm6';
			// ConvertApi::setApiSecret(CONVERT_API_SECRET);
			// $result = ConvertApi::convert('pdf', ['File' => $word_path]);
			// $pdf = $result->getFile();
			// $pdf->save($pdf_path);
		}

		// @unlink($word_path);
		//return array('pdf_url' => $pdf_url, 'pdf_path' => $pdf_path);

		return array('pdf_url' => $word_url, 'pdf_path' => $word_path);
	}

	public function ajax_upload_file_bao_cao_tuan() {
		global $wpdb;
		$att = new DA_Attachment();
		$da = new DA_Duan();

		$type = $_POST['type'] ?? '';
		$sub_dir = $_POST['tuan_bao_cao'] ?? '';
		$ma_du_an = $_POST['ma_du_an'] ?? '';
		$id = $_POST['id'] ?? '';
		// Kiem tra cac gia tri bat buoc
		if(empty($type) || empty($da->upload_dir[$type]) || empty($ma_du_an) || empty($id)) return false;
		// Dinh nghia duong dan luu tru
		$dir = $ma_du_an.'/'.$da->upload_dir[$type].'/'.$sub_dir;
		// Kiem tra va tao folder theo duong dan
		$path = duong_dan_thu_muc_du_an(array(
			'dir' => $dir
		));
		// Xoa du lieu cu
		$wpdb->delete($att->attachment_table_name, array(
			'object_id' => $id,
			'object_type' => $type,
		));
		// Xoa cac file trong thu muc theo duong dan path
		$files = scandir($path);
		foreach($files as $file) {
			if(is_file($path.'/'.$file)) unlink($path.'/'.$file);
		}
		// Them du lieu moi
		$att->upload(array(
			'object_id' 	=> $id,
			'object_type' 	=> $type,
			'owner_id' 		=> $_POST['ma_nhan_vien'] ?? '',
			'upload_dir'	=> DA_PROJECT_RELATIVE_PATH.'/'.$dir,
		));
		$response  = 'success';
		echo json_encode($response);
		die();
	}

	public function ajax_xoa_file_bao_cao_tuan() {
		global $wpdb;
		$att = new DA_Attachment();
		$da = new DA_Duan();

		$type = $_REQUEST['type'] ?? '';
		$sub_dir = $_REQUEST['tuan_bao_cao'] ?? '';
		$ma_du_an = $_REQUEST['ma_du_an'] ?? '';
		$id = $_REQUEST['id'] ?? '';
		// Kiem tra cac gia tri bat buoc
		if(empty($type) || empty($da->upload_dir[$type]) || empty($ma_du_an) || empty($id)) return false;
		// Dinh nghia duong dan luu tru
		$dir = $ma_du_an.'/'.$da->upload_dir[$type].'/'.$sub_dir;
		// Kiem tra va tao folder theo duong dan
		$path = duong_dan_thu_muc_du_an(array(
			'dir' => $dir
		));
		// Xoa du lieu cu
		$wpdb->delete($att->attachment_table_name, array(
			'object_id' => $id,
			'object_type' => $type,
		));
		// Xoa cac file trong thu muc theo duong dan path
		$files = scandir($path);
		foreach($files as $file) {
			if(is_file($path.'/'.$file)) unlink($path.'/'.$file);
		}
		$response  = 'success';
		echo json_encode($response);
		die();
	}

	public function export_bcn_bct($params = array()) {
		global $wpdb;
		
		$da = new DA_Duan();

		set_include_path(DRAGONADDON_PLUGIN_DIR.'class/');
		include 'PHPExcel/IOFactory.php';

		$template = DRAGONADDON_PLUGIN_DIR.'/assets/files/Template-cap-nhat-bcn-bct.xlsx';

		$upload_dir  = wp_upload_dir();
		$filename = 'Bao-cao-du-an-bcn-bct-'.time().'.xlsx';
		$filepath = $upload_dir['path'].'/'.$filename;

		$objPHPExcel = PHPExcel_IOFactory::load($template);
		$objPHPExcel->setActiveSheetIndex(0);
		$row = 6;
		$cell_tuan = 4;
		$cell_ngay = 5;
		$cell_trang_thai_ngay = 6;
		$cell_trang_thai_tuan = 7;

		$rich_text_chua_thiet_lap = new \PHPExcel_RichText();
		$text_chua_thiet_lap = $rich_text_chua_thiet_lap->createTextRun("Chưa thiết lập");
		$text_chua_thiet_lap->getFont()->setBold(false)->setItalic(false)->setName('Arial')->setSize(15)->getColor()->setARGB("FF2D2D");

		// $ds_du_an = $da->danh_sach(array(
		// 	'trang_thai' => '1',
		// 	'unlimit'    => true,
		// 	'sow'        => $params['sow'] ?? '',
		// 	'ovs_dmt'    => $params['ovs_dmt'] ?? '',
		// 	'ky_su_truong' => $params['ky_su_truong'] ? rtrim($params['ky_su_truong'], ', ') : '',
		// ));
		
		// $ds_filter_bao_cao = $this->danh_sach_filter_bao_cao_tuan();

		// // filter theo tien do du an neu co
		// if(isset($params['danh_gia_tien_do']) && $params['danh_gia_tien_do']){
		// 	// lat danh sach de lay tuan dang chon
		// 	$filter_tuan = array_chunk($ds_filter_bao_cao, 4);
		// 	$filter_tuan = array_reverse($filter_tuan);
		// 	$filter_tuan = $filter_tuan[$params['filter_tuan'] - 1];
		
		// 	$ngay_bat_dau  = strtotime($filter_tuan[0]->ngay_bat_dau);
		// 	$ngay_ket_thuc = strtotime($filter_tuan[count($filter_tuan) - 1]->ngay_ket_thuc);
		
		// 	$ds_du_an_theo_theo_tien_do = $this->danh_sach(array(
		// 		"ngay" => array(
		// 			"ngay_bat_dau" => date("Y-m-d", $ngay_bat_dau),
		// 			"ngay_ket_thuc"=> date("Y-m-d", $ngay_ket_thuc)
		// 		),
		// 		"danh_gia_tien_do" => $params['danh_gia_tien_do'],
		// 	));
		// 	$ds_du_an_theo_theo_tien_do = array_unique(array_column($ds_du_an_theo_theo_tien_do, 'ma_du_an'));
		// 	$ds_du_an = array_filter($ds_du_an, function($du_an) use ($ds_du_an_theo_theo_tien_do){
		// 		return in_array($du_an->ma_du_an, $ds_du_an_theo_theo_tien_do);
		// 	});
		// }

		$params['trang_thai'] = 1;
		$params['unlimit'] = true;
		$params['nhan_vien_bao_cao'] = isset($params['nhan_vien_bao_cao']) ? rtrim($params['nhan_vien_bao_cao'], ', ') : '';

		// echo '<pre>';
		// print_r($ds_du_an);
		// exit();

		// lat danh sach tuan sau do lay tuan moi nhat
		// $ds_tuan = array_chunk($ds_filter_bao_cao, 4);
		// $ds_tuan = array_reverse($ds_tuan);
		// $ds_tuan = $ds_tuan[$params['filter_tuan'] - 1];

		$std_tuan = new stdClass();
		$std_tuan->tieu_de = "Báo cáo Tuần ".$params['tuan']." (".date( "d/m", strtotime($params['nam_bao_cao']."W".($params['tuan']-1)."6") )." - ".date( "d/m", strtotime($params['nam_bao_cao']."W".$params['tuan']."5") ).")";
		$std_tuan->ngay_bat_dau = date( "Y-m-d", strtotime($params['nam_bao_cao']."W".($params['tuan']-1)."6") );
		$std_tuan->ngay_ket_thuc = date( "Y-m-d", strtotime($params['nam_bao_cao']."W".$params['tuan']."5") );
		$ds_tuan = array($std_tuan);


		$ds_du_an = $da->danh_sach($params);



		$objPHPExcel->getActiveSheet()->setCellValue("K".$cell_tuan, $ds_tuan[0]->tieu_de);

		// Header tuan
		$start_column = 'Q'; // Cột bắt đầu
		$merge_range = "K:Q";
		// Tính toán phạm vi merge cho cột hiện tại
		list($start_merge_column, $end_merge_column) = explode(':', $merge_range);
		$start_merge_index = \PHPExcel_Cell::columnIndexFromString($start_merge_column);
		$end_merge_index = \PHPExcel_Cell::columnIndexFromString($end_merge_column);


		foreach($ds_tuan as $index => $tuan) {
			if($index == 0) continue;
			$start_index = \PHPExcel_Cell::columnIndexFromString($start_column); // Lấy chỉ số của cột bắt đầu
			$current_column = \PHPExcel_Cell::stringFromColumnIndex($start_index);

			// Tính toán số cột đã merge
			$merge_column_count = $end_merge_index - $start_merge_index;
			
			// Xác định phạm vi merge mới cho cột hiện tại
			$newstart_merge_column = $current_column;
			$newend_merge_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($newstart_merge_column) + $merge_column_count - 1);

			$objPHPExcel->getActiveSheet()->mergeCells($newstart_merge_column . $cell_tuan . ':' . $newend_merge_column . $cell_tuan);

			// copy style K4
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('K4'), $newstart_merge_column . $cell_tuan);
			$objPHPExcel->getActiveSheet()->setCellValue($newstart_merge_column . $cell_tuan, $tuan->tieu_de);

			$start_merge_index = \PHPExcel_Cell::columnIndexFromString($newstart_merge_column);
			$end_merge_index = \PHPExcel_Cell::columnIndexFromString($newend_merge_column);
			$start_column = $newend_merge_column;
		}

		// Header ngay
		$start_column = 'K'; // Cột bắt đầu
		foreach($ds_tuan as $tuan){
			$ngay_bat_dau  = strtotime($tuan->ngay_bat_dau);
			$ngay_ket_thuc = strtotime($tuan->ngay_ket_thuc);
			for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
				$ngay_thang = date("d/m/Y", $ngay);
				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('K5'), $start_column.$cell_ngay);
				$objPHPExcel->getActiveSheet()->setCellValue($start_column.$cell_ngay, $ngay_thang);
				$start_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($start_column));
			}
		}
		
        // Danh sach du an
		foreach($ds_du_an as $du_an){
			// ma du an
			$objPHPExcel->getActiveSheet()->mergeCells("A$row:A".($row+1));
			$objPHPExcel->getActiveSheet()->setCellValue("A".$row, $du_an->ma_du_an);
			// ten du an
			// Tạo nội dung rich text
			$rich_du_an 	= new \PHPExcel_RichText();
			$text_ten_du_an = $rich_du_an->createTextRun($du_an->ten_du_an);
			$text_sow 	 	= $rich_du_an->createTextRun("\n".$du_an->sow);
		
			// Định dạng cho "Chưa có tiến độ"
			$text_ten_du_an->getFont()->setBold(false)->setItalic(false)->setName('Arial')->setSize(15)->getColor()->setARGB("000000");
			$text_sow->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(15)->getColor()->setARGB("E26B0A");
			
			// Đặt giá trị vào ô
			$objPHPExcel->getActiveSheet()->mergeCells("B$row:B".($row+1));
			// $objPHPExcel->getActiveSheet()->setCellValue("B".$row, $du_an->ten_du_an."\n".$du_an->sow);
			$objPHPExcel->getActiveSheet()->getCell("B".$row)->setValue($rich_du_an);

			// link bcn
			$objPHPExcel->getActiveSheet()->setCellValue("C".$row, home_url()."/du-an?view=chi-tiet&ma_du_an={$du_an->ma_du_an}&tab=bao-cao-ngay");
			$objPHPExcel->getActiveSheet()->getCell("C".$row)->getHyperlink()->setUrl(home_url()."/du-an?view=chi-tiet&ma_du_an={$du_an->ma_du_an}&tab=bao-cao-ngay");
			// link bct
			$objPHPExcel->getActiveSheet()->setCellValue("C".($row+1), home_url()."/du-an?view=chi-tiet&ma_du_an={$du_an->ma_du_an}&tab=bao-cao-tuan");
			$objPHPExcel->getActiveSheet()->getCell("C".($row+1))->getHyperlink()->setUrl(home_url()."/du-an?view=chi-tiet&ma_du_an={$du_an->ma_du_an}&tab=bao-cao-tuan");

			// ho va ten ds nhan vien bao cao ngay
			$ds_nv_bcn = $this->lay_ds_nhan_vien_theo_vai_tro(array(
				'ma_du_an' => $du_an->ma_du_an, 
				'ma_so'    => "bao_cao_ngay"
			));
			$objPHPExcel->getActiveSheet()->setCellValue("D".$row, $ds_nv_bcn == "<span class='badge badge-pill badge-warning'>Chưa thiết lập</span>" ? $rich_text_chua_thiet_lap : $ds_nv_bcn);
			// ho va ten ds nhan vien bao cao tuan
			$ds_nv_bct = $this->lay_ds_nhan_vien_theo_vai_tro(array(
				'ma_du_an' => $du_an->ma_du_an, 
				'ma_so'    => "bao_cao_tuan"
			));
			$objPHPExcel->getActiveSheet()->setCellValue("D".($row+1), $ds_nv_bct == "<span class='badge badge-pill badge-warning'>Chưa thiết lập</span>" ? $rich_text_chua_thiet_lap : $ds_nv_bct);

			// clone ô phan loại ngày giá trị và thuộc tính
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E6'), "E".$row);
			$objPHPExcel->getActiveSheet()->setCellValue("E".$row, $objPHPExcel->getActiveSheet()->getCell("E6")->getValue());
			// clone ô phan loại tuần giá trị và thuộc tính
			$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E7'), "E".($row+1));
			$objPHPExcel->getActiveSheet()->setCellValue("E".($row+1), $objPHPExcel->getActiveSheet()->getCell("E7")->getValue());

			// trang thai bcn
			$start_column = 'K'; // Cột bắt đầu
			foreach($ds_tuan as $tuan){
				$ngay_bat_dau  = strtotime($tuan->ngay_bat_dau);
				$ngay_ket_thuc = strtotime($tuan->ngay_ket_thuc);
				for ($ngay = $ngay_bat_dau; $ngay <= $ngay_ket_thuc; $ngay = strtotime("1 day", $ngay)){
					$ngay_thang = date("Y-m-d", $ngay);
					$tt_bcn = $this->thong_tin_bao_cao_ngay(array(
						'ma_du_an'      => $du_an->ma_du_an, 
						'ngay_bao_cao'  => $ngay_thang
					));
					if($tt_bcn->trang_thai == 1){
						// gắn màu xanh 
						$objPHPExcel->getActiveSheet()->getStyle($start_column.$cell_trang_thai_ngay)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('C6EFCE');
						$objPHPExcel->getActiveSheet()->setCellValue($start_column.$cell_trang_thai_ngay, "Đã báo cáo");
					}elseif($tt_bcn->trang_thai == -1){
						// gắn màu vang 
						$objPHPExcel->getActiveSheet()->getStyle($start_column.$cell_trang_thai_ngay)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFEB9C');
						$objPHPExcel->getActiveSheet()->setCellValue($start_column.$cell_trang_thai_ngay, "Đang chờ duyệt");
					}else{
						// gắn màu đỏ
						$objPHPExcel->getActiveSheet()->getStyle($start_column.$cell_trang_thai_ngay)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC7CE');
						$objPHPExcel->getActiveSheet()->setCellValue($start_column.$cell_trang_thai_ngay, "Chưa báo cáo");
					}
					$start_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($start_column));
				}
			}

			// trang thai bao cao tuan
			$start_column = 'J'; // Cột bắt đầu
			foreach($ds_tuan as $index => $tuan) {
				$start_index = \PHPExcel_Cell::columnIndexFromString($start_column); // Lấy chỉ số của cột bắt đầu
				$current_column = \PHPExcel_Cell::stringFromColumnIndex($start_index);
	
				// Tính toán số cột đã merge
				$merge_column_count = $end_merge_index - $start_merge_index;
				
				// Xác định phạm vi merge mới cho cột hiện tại
				$newstart_merge_column = $current_column;
				$newend_merge_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($newstart_merge_column) + $merge_column_count - 1);
	
				$objPHPExcel->getActiveSheet()->mergeCells($newstart_merge_column . $cell_trang_thai_tuan . ':' . $newend_merge_column . $cell_trang_thai_tuan);
	
				$tt_bct = $this->thong_tin(array(
					'ma_du_an'      => $du_an->ma_du_an, 
					'ngay_bat_dau'  => $tuan->ngay_bat_dau,
					'ngay_ket_thuc' => $tuan->ngay_ket_thuc
				));
				if($tt_bct->trang_thai == 1 || $tt_bct->trang_thai == -1){
					// gắn màu xanh 
					$objPHPExcel->getActiveSheet()->getStyle($newstart_merge_column.$cell_trang_thai_tuan)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('C6EFCE');
					
					// Tạo nội dung rich text
					$richText = new \PHPExcel_RichText();
					$text1 = $richText->createTextRun("Đã báo cáo, ");
					$text2 = $richText->createTextRun($this->danh_gia_tien_do[$tt_bct->danh_gia_tien_do]);
				
					// Định dạng cho $this->danh_gia_tien_do
					$text1->getFont()->setBold(false)->setItalic(false)->setName('Arial')->setSize(15)->getColor()->setARGB("000000");
					if($tt_bct->danh_gia_tien_do == -2){
						// tre tien do
						$text2->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(15)->getColor()->setARGB("FF2D2D");
					}else{
						$text2->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(15)->getColor()->setARGB("00B050");
					}
					
					// Đặt giá trị vào ô
					$objPHPExcel->getActiveSheet()->getCell($newstart_merge_column.$cell_trang_thai_tuan)->setValue($richText);
				
				} else {
					// gắn màu đỏ
					$objPHPExcel->getActiveSheet()->getStyle($newstart_merge_column.$cell_trang_thai_tuan)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFC7CE');
					
					// Tạo nội dung rich text
					$richText = new \PHPExcel_RichText();
					$text1 = $richText->createTextRun("Chưa báo cáo, ");
					$text2 = $richText->createTextRun("Chưa có tiến độ");
				
					// Định dạng cho "Chưa có tiến độ"
					$text1->getFont()->setBold(false)->setItalic(false)->setName('Arial')->setSize(15)->getColor()->setARGB("000000");
					$text2->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(15)->getColor()->setARGB("808080");
					
					// Đặt giá trị vào ô
					$objPHPExcel->getActiveSheet()->getCell($newstart_merge_column.$cell_trang_thai_tuan)->setValue($richText);
				}
				
				$start_merge_index = \PHPExcel_Cell::columnIndexFromString($newstart_merge_column);
				$end_merge_index = \PHPExcel_Cell::columnIndexFromString($newend_merge_column);
				$start_column = $newend_merge_column;
			}


			$row = $row + 2;
			$cell_trang_thai_ngay = $cell_trang_thai_ngay + 2;
			$cell_trang_thai_tuan = $cell_trang_thai_tuan + 2;
		}


		// Export
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		header("Expires: 0");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$ext = pathinfo($filepath, PATHINFO_EXTENSION);
		$basename = pathinfo($filepath, PATHINFO_BASENAME);

		header("Content-type: application/".$ext);
		header("Content-Disposition: attachment; filename=\"$basename\"");
		ob_end_clean();

		$objWriter->save('php://output');
	}
	public function export_bao_cao_nhap_lieu($params = array()) {
		global $wpdb;
		
		$da  = new DA_Duan();
		$ns  = new DA_Nhansu();
		$mn  = new DA_Menu();
		$dsb = new DA_Dashboard();
		$nv  = new DA_Nhanvien();

		set_include_path(DRAGONADDON_PLUGIN_DIR.'class/');
		include 'PHPExcel/IOFactory.php';

		$template = DRAGONADDON_PLUGIN_DIR.'/assets/files/Template-bao-cao-nhap-lieu.xlsx';

		$upload_dir  = wp_upload_dir();
		$filename = 'Bao-cao-nhap-lieu-'.time().'.xlsx';
		$filepath = $upload_dir['path'].'/'.$filename;

		$objPHPExcel = PHPExcel_IOFactory::load($template);
		$objPHPExcel->setActiveSheetIndex(0);
		$row = 7;
		$cell_lv1 = 4;
		$cell_lv2 = 5;
		$cell_lv3 = 6;
		$cell_data = 7;


		$params['trang_thai'] = 1;
		$params['unlimit'] = true;
		$ds_du_an = $da->danh_sach($params);

		// menu lv1
		foreach($mn->master_menu as $key => $menu_lv1){
			if(!$menu_lv1['show_dashboard']) continue;
			$ds_menu_lv2 = isset($menu_lv1['sub_menu']) && is_array($menu_lv1['sub_menu']) ? $menu_lv1['sub_menu'] : array(array('show_dashboard'=>true));
			$colspan_lv1 = 0;
			foreach($ds_menu_lv2 as $menu_lv2) {
				if(!$menu_lv2['show_dashboard']) continue;
				$ds_menu_lv3 = isset($menu_lv2['sub_menu']) && is_array($menu_lv2['sub_menu']) ? $menu_lv2['sub_menu'] : array(array('show_dashboard'=>true));
				$count = 0;
				foreach($ds_menu_lv3 as $menu_lv3) {
					if(!$menu_lv3['show_dashboard']) continue;
					$count++;
				}
				$colspan_lv1 += $count;
			}
			$color = $key % 2 == 0 ? '9CE5FF' : 'FFEB9C';
			$richtext_lv1 	= new \PHPExcel_RichText();
			$text_lv1 = $richtext_lv1->createTextRun($menu_lv1['label']);
			$text_lv1->getFont()->setBold(true)->setItalic(false)->setName('Arial')->setSize(16);

			if($key == 0){
				$start_col = "O"; 
				$start_col_index = \PHPExcel_Cell::columnIndexFromString($start_col);
				$end_col_index = $start_col_index + $colspan_lv1 - 1; 
				$end_col = \PHPExcel_Cell::stringFromColumnIndex($end_col_index - 1);
	
				$merge_range = $start_col.$cell_lv1.':'.$end_col.$cell_lv1;
				$start_column = $end_col;

				$objPHPExcel->getActiveSheet()->mergeCells($merge_range);
				$objPHPExcel->getActiveSheet()->setCellValue($start_col.$cell_lv1,$menu_lv1['label']);
				$objPHPExcel->getActiveSheet()->getCell($start_col.$cell_lv1)->setValue($richtext_lv1);
				
				$objPHPExcel->getActiveSheet()->getStyle($merge_range)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			}else{
				$start_index = \PHPExcel_Cell::columnIndexFromString($start_column);
				$current_column = \PHPExcel_Cell::stringFromColumnIndex($start_index);
	
				$newstart_merge_column = $current_column;
				$newend_merge_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($newstart_merge_column) + $colspan_lv1 - 2);
				$objPHPExcel->getActiveSheet()->mergeCells($newstart_merge_column . $cell_lv1 . ':' . $newend_merge_column . $cell_lv1);
	
				$objPHPExcel->getActiveSheet()->setCellValue($newstart_merge_column . $cell_lv1,$menu_lv1['label']);
				$objPHPExcel->getActiveSheet()->getCell($newstart_merge_column . $cell_lv1)->setValue($richtext_lv1);
				$objPHPExcel->getActiveSheet()->getStyle($newstart_merge_column . $cell_lv1 . ':' . $newend_merge_column . $cell_lv1)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);

				$start_column = $newend_merge_column;
			}
		}

		// menu lv2
		foreach($mn->master_menu as $key1 => $menu_lv1){
			if(!$menu_lv1['show_dashboard']) continue;
			$ds_menu_lv2 = isset($menu_lv1['sub_menu']) && is_array($menu_lv1['sub_menu']) ? $menu_lv1['sub_menu'] : array(array('show_dashboard'=>true));
			$color = $key1 % 2 == 0 ? '9CE5FF' : 'FFEB9C';
			foreach($ds_menu_lv2 as $key2 => $menu_lv2){
				if(!$menu_lv2['show_dashboard']) continue;
				$ds_menu_lv3 = isset($menu_lv2['sub_menu']) && is_array($menu_lv2['sub_menu']) ? $menu_lv2['sub_menu']: array(array('show_dashboard'=>true));
				$colspan_lv2 = 0;

				foreach($ds_menu_lv3 as $menu_lv3) {
					if(!$menu_lv3['show_dashboard']) continue;
					$colspan_lv2++;
				}

				$richtext_lv2 = new \PHPExcel_RichText();
				$text_lv2 = $richtext_lv2->createTextRun($menu_lv2['label']);
				$text_lv2->getFont()->setBold(true)->setItalic(false)->setName('Arial')->setSize(16);

				if($key2 == 0 && $key1 == 0){
					$start_col = "O"; 
					$start_col_index = \PHPExcel_Cell::columnIndexFromString($start_col);
					$end_col_index = $start_col_index + $colspan_lv2 - 1; 
					$end_col = \PHPExcel_Cell::stringFromColumnIndex($end_col_index - 1);
		
					$merge_range = $start_col.$cell_lv2.':'.$end_col.$cell_lv2;
					$start_column = $end_col;
	
					$objPHPExcel->getActiveSheet()->mergeCells($merge_range);
					$objPHPExcel->getActiveSheet()->setCellValue($start_col.$cell_lv2,$menu_lv2['label']);
					$objPHPExcel->getActiveSheet()->getCell($start_col.$cell_lv2)->setValue($richtext_lv2);
					
					$objPHPExcel->getActiveSheet()->getStyle($merge_range)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
				}else{
					$start_index = \PHPExcel_Cell::columnIndexFromString($start_column);
					$current_column = \PHPExcel_Cell::stringFromColumnIndex($start_index);
		
					$newstart_merge_column = $current_column;
					$newend_merge_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($newstart_merge_column) + $colspan_lv2 - 2);
					$objPHPExcel->getActiveSheet()->mergeCells($newstart_merge_column . $cell_lv2 . ':' . $newend_merge_column . $cell_lv2);
		
					$objPHPExcel->getActiveSheet()->setCellValue($newstart_merge_column . $cell_lv2,$menu_lv2['label']);
					$objPHPExcel->getActiveSheet()->getCell($newstart_merge_column . $cell_lv2)->setValue($richtext_lv2);
					$objPHPExcel->getActiveSheet()->getStyle($newstart_merge_column . $cell_lv2 . ':' . $newend_merge_column . $cell_lv2)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
	
					$start_column = $newend_merge_column;
				}

			}
		}

		// menu lv3
		foreach($mn->master_menu as $key1 => $menu_lv1){
			if(!$menu_lv1['show_dashboard']) continue;
			$ds_menu_lv2 = isset($menu_lv1['sub_menu']) && is_array($menu_lv1['sub_menu']) ? $menu_lv1['sub_menu'] : array(array('show_dashboard'=>true));
			$color = $key1 % 2 == 0 ? '9CE5FF' : 'FFEB9C';
			foreach($ds_menu_lv2 as $key2 => $menu_lv2){
				if(!$menu_lv2['show_dashboard']) continue; 
				$ds_menu_lv3 = isset($menu_lv2['sub_menu']) && is_array($menu_lv2['sub_menu']) ? $menu_lv2['sub_menu'] : array(array('show_dashboard'=>true));
				foreach(array_pad($ds_menu_lv3 ?? [],1,null) as $key3 => $menu_lv3){
					if(!$menu_lv3['show_dashboard']) continue;
					$colspan_lv3 = 1;

					$richtext_lv3 = new \PHPExcel_RichText();
					$text_lv3 = $richtext_lv3->createTextRun($menu_lv3['label']);
					$text_lv3->getFont()->setBold(true)->setItalic(false)->setName('Arial')->setSize(16);

					if($key2 == 0 && $key1 == 0 && $key3 == 0){
						$start_col = "O"; 
						$start_col_index = \PHPExcel_Cell::columnIndexFromString($start_col);
						$end_col_index = $start_col_index + $colspan_lv3 - 1; 
						$end_col = \PHPExcel_Cell::stringFromColumnIndex($end_col_index - 1);
			
						$merge_range = $start_col.$cell_lv3.':'.$end_col.$cell_lv3;
						$start_column = $end_col;
		
						$objPHPExcel->getActiveSheet()->mergeCells($merge_range);
						$objPHPExcel->getActiveSheet()->setCellValue($start_col.$cell_lv3,$menu_lv3['label']);
						$objPHPExcel->getActiveSheet()->getCell($start_col.$cell_lv3)->setValue($richtext_lv3);
						
						$objPHPExcel->getActiveSheet()->getStyle($merge_range)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
					}else{
						$start_index = \PHPExcel_Cell::columnIndexFromString($start_column);
						$current_column = \PHPExcel_Cell::stringFromColumnIndex($start_index);
			
						$newstart_merge_column = $current_column;
						$newend_merge_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($newstart_merge_column) + $colspan_lv3 - 2);
						$objPHPExcel->getActiveSheet()->mergeCells($newstart_merge_column . $cell_lv3 . ':' . $newend_merge_column . $cell_lv3);
			
						$objPHPExcel->getActiveSheet()->setCellValue($newstart_merge_column . $cell_lv3,$menu_lv3['label']);
						$objPHPExcel->getActiveSheet()->getCell($newstart_merge_column . $cell_lv3)->setValue($richtext_lv3);
						$objPHPExcel->getActiveSheet()->getStyle($newstart_merge_column . $cell_lv3 . ':' . $newend_merge_column . $cell_lv3)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
		
						$start_column = $newend_merge_column;
					}
				}
			}
		}

        // Danh sach du an
		foreach($ds_du_an as $du_an){
			// ma du an
			$objPHPExcel->getActiveSheet()->setCellValue("A".$row, $du_an->ma_du_an."\n".$du_an->ovs_dmt);
			$objPHPExcel->getActiveSheet()->getStyle("A".$row)->getAlignment()->setWrapText(true);

			// ten du an
			$rich_du_an 	= new \PHPExcel_RichText();
			$text_ten_du_an = $rich_du_an->createTextRun($du_an->ten_du_an);
			$text_sow 	 	= $rich_du_an->createTextRun("\n".$du_an->sow);
		
			$text_ten_du_an->getFont()->setBold(false)->setItalic(false)->setName('Arial')->setSize(15)->getColor()->setARGB("000000");
			$text_sow->getFont()->setBold(true)->setItalic(true)->setName('Arial')->setSize(15)->getColor()->setARGB("E26B0A");
			
			$objPHPExcel->getActiveSheet()->setCellValue("B".$row, $du_an->ten_du_an."\n".$du_an->sow);
			$objPHPExcel->getActiveSheet()->getCell("B".$row)->setValue($rich_du_an);

			$objPHPExcel->getActiveSheet()->setCellValue("C".$row, home_url()."/du-an?view=dashboard&ma_du_an={$du_an->ma_du_an}");
			$objPHPExcel->getActiveSheet()->getCell("C".$row)->getHyperlink()->setUrl(home_url()."/du-an?view=dashboard&ma_du_an={$du_an->ma_du_an}");

			$thong_ke_ho_so_phap_ly = $da->thong_ke_ho_so_phap_ly(array(
				'ma_du_an' => $du_an->ma_du_an
			));
			$khoi_luong = $thong_ke_ho_so_phap_ly['hop_dong']['khoi_luong'] + $thong_ke_ho_so_phap_ly['phu_luc_hop_dong']['khoi_luong'] + $thong_ke_ho_so_phap_ly['phat_sinh']['khoi_luong'];

			$objPHPExcel->getActiveSheet()->setCellValue("D".$row, number_format($khoi_luong,2));

			// Kiem tra phan quyen vao du an
			if (!$nv->kiem_tra_phan_quyen_vao_du_an($params['ma_nhan_vien'], $du_an->ma_du_an)) continue;
			
			$ds_nhan_su = $ns->danh_sach_nhan_su(array(
				'ma_du_an' => $du_an->ma_du_an,
				'vai_tro' => array('sales_engineer', 'sales_manager', 'project_engineer', 'project_manager', 'site_manager'),
				'trang_thai' => 1,
			));
			// Group by vai tro
			$ds_ten_nhan_su_by_vai_tro = array();
			if(!empty($ds_nhan_su)) {
				// Chia nhom nhan su theo vai tro
				$ds_nhom_nhan_su_theo_vai_tro = array_group_by($ds_nhan_su, 'vai_tro');
				// Lay danh sach ten, ma so cac nhan vien theo cung vai tro trong du an
				foreach($ds_nhom_nhan_su_theo_vai_tro as $vai_tro => $nhom_nhan_su_theo_vai_tro) {
					$ds_ten_ma_so = array();
					foreach($nhom_nhan_su_theo_vai_tro as $nhan_su_theo_vai_tro) {
						$ds_ten_ma_so[] = $nhan_su_theo_vai_tro->ho_ten;
					}
					$ds_ten_nhan_su_by_vai_tro[$vai_tro] = implode("\n", $ds_ten_ma_so);
				}
			}

			$objPHPExcel->getActiveSheet()->setCellValue("J".$row, $ds_ten_nhan_su_by_vai_tro['sales_manager'] ?? lay_ho_ten($du_an->sale_manager) ?? ' ');
			$objPHPExcel->getActiveSheet()->setCellValue("K".$row, $ds_ten_nhan_su_by_vai_tro['sales_engineer'] ?? lay_ho_ten($du_an->saleman) ??  ' ');
			$objPHPExcel->getActiveSheet()->setCellValue("L".$row, $ds_ten_nhan_su_by_vai_tro['project_manager'] ?? lay_ho_ten($du_an->truong_du_an) ?? ' ');
			$objPHPExcel->getActiveSheet()->setCellValue("M".$row, $ds_ten_nhan_su_by_vai_tro['project_engineer'] ?? lay_ho_ten($du_an->ky_su_truong) ?? ' ');
			$objPHPExcel->getActiveSheet()->setCellValue("N".$row, $ds_ten_nhan_su_by_vai_tro['site_manager'] ?? lay_ho_ten($du_an->chi_huy_truong) ?? ' ');

			$start_column = 'O';
			foreach($mn->master_menu as $menu_lv1){
				if(!$menu_lv1['show_dashboard']) continue;
				$ds_menu_lv2 = isset($menu_lv1['sub_menu']) && is_array($menu_lv1['sub_menu']) ? $menu_lv1['sub_menu'] : array(array('show_dashboard'=>true));

				foreach($ds_menu_lv2 as $menu_lv2){
					if(!$menu_lv2['show_dashboard']) continue;
					$ds_menu_lv3 = isset($menu_lv2['sub_menu']) && is_array($menu_lv2['sub_menu']) ? $menu_lv2['sub_menu'] : array(array('show_dashboard'=>true));
					foreach(array_pad($ds_menu_lv3 ?? [],1,null) as $menu_lv3){
						if(!$menu_lv3['show_dashboard']) continue;
						unset($menu_lv3['show_dashboard']);
						$data = $menu_lv3;
						if(empty($menu_lv3)){
							$data = $menu_lv2;
						}
						$data['ma_du_an']     = $du_an->ma_du_an;
						$data['btn_content']  = " ";
						$thong_tin = $mn->lay_thong_tin_theo_table($data);
						$ngay_cap_nhat = strip_tags($thong_tin);

						if(strpos($thong_tin, 'badge-success') !== false){
							$text = "Dữ liệu cập nhật <= 7 ngày";
							$color = 'C6EFCE';
						}elseif(strpos($thong_tin, 'badge-warning') !== false){
							$text = "Dữ liệu cập nhật <= 15 ngày";
							$color = 'FFEB9C';
						}elseif(strpos($thong_tin, 'badge-danger') !== false){
							$text = "Dữ liệu cập nhật > 15 ngày";
							$color = 'FFC7CE';
						}elseif(strpos($thong_tin, 'badge-primary') !== false){
							$text = "Đã nhập liệu";
							$color = '9CE5FF';
						}else{
							$text = "";
							$color = 'FFFFFF';
						}
						$ngay_cap_nhat = $text."\n".$ngay_cap_nhat;

                        $objPHPExcel->getActiveSheet()->setCellValue($start_column.$row, $ngay_cap_nhat);
                        $objPHPExcel->getActiveSheet()->getStyle($start_column.$row)->getAlignment()->setWrapText(true);
						$objPHPExcel->getActiveSheet()->getStyle($start_column.$row)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);

						$start_column = \PHPExcel_Cell::stringFromColumnIndex(\PHPExcel_Cell::columnIndexFromString($start_column));
					}
				}
			}

			$row = $row + 1;
			$cell_data = $cell_data + 1;
		}


		// Export
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		header("Expires: 0");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

		$ext = pathinfo($filepath, PATHINFO_EXTENSION);
		$basename = pathinfo($filepath, PATHINFO_BASENAME);

		header("Content-type: application/".$ext);
		header("Content-Disposition: attachment; filename=\"$basename\"");
		ob_end_clean();

		$objWriter->save('php://output');
	}

	public function lay_ds_nhan_vien_theo_vai_tro($params = array()){
		global $wpdb;
		$dsb = new DA_Dashboard();

		$ds_so_do_du_an = $dsb->thong_tin_so_do_du_an_by_params(array(
			'ma_so' => $params['ma_so']
		));
		$ds_vai_tro_phu_trach = !empty($ds_so_do_du_an->vai_tro_phu_trach) ? explode(",",$ds_so_do_du_an->vai_tro_phu_trach) : array();
		$sql = "SELECT * FROM da_nhan_su WHERE ma_du_an = '{$params['ma_du_an']}' AND vai_tro IN ('".implode("','",$ds_vai_tro_phu_trach)."') AND trang_thai = 1";
		$ds_nhan_su = $wpdb->get_results($sql);

		$ds_nhan_su = array_filter(array_column($ds_nhan_su, 'ho_ten'));

		if(empty($ds_nhan_su)){
			$ds_nhan_su = "<span class='badge badge-pill badge-warning'>Chưa thiết lập</span>";
		}else{
			$ds_nhan_su = implode(", ", $ds_nhan_su);
		}
		return $ds_nhan_su;
	}
	public function trang_thai_bao_cao_highlight($trang_thai) {
		$html = '';
		switch($trang_thai) {
			case 1:
				$html = '<span class="badge badge-primary">Xem báo cáo</span><br><span class="badge badge-pill badge-success">Đã duyệt</span>';
			break;
			case -1:
				$html = '<span class="badge badge-primary">Xem báo cáo</span><br><span class="badge badge-pill badge-warning">Chờ duyệt</span>';
			break;
			case -2:
				$html = '<span class="badge badge-pill badge-danger">Từ chối</span>';
			break;
			case -3:
				$html = '<span class="badge badge-pill badge-outline-light">Chưa báo cáo</span>';
			break;
		}
		return $html;
	}

	public function thong_ke_phan_tram_ngan_sach($params = array()){
		$phan_tram = 0;
		switch($params['ma_code']){
			case 10:
				$ds_chi_tiet_thiet_lap_ngan_sach = $params['c']->pivot_danh_sach_chi_tiet_thiet_lap_ngan_sach_code(array(
					'ngan_sach_du_an_id' => $params['tt_ngan_sach']->id,
					'object_uid'         => '0000001100',
					'object_type'        => $params['object_type'],
				));
				foreach($ds_chi_tiet_thiet_lap_ngan_sach as $chi_tiet){
					$cook_ma_so    = explode("-", $chi_tiet->ma_so);
					$ma_so         = $cook_ma_so[1];
					$loai_nhan_luc = $cook_ma_so[0];

					$thong_tin_danh_muc = $params['c']->thong_tin_danh_muc(array(
						'ma_so'         => $ma_so,
						'loai_nhan_luc' => $loai_nhan_luc
					));

					$luy_ke_ca_hom_nay = $params['bcn']->tong_cong_nhan_luc_thiet_bi(array(
						'ma_du_an'    => $params['ma_du_an'],
						'object_id'   => $thong_tin_danh_muc->object_id,
						'object_type' => $thong_tin_danh_muc->object_type,
						'ma_doi_tac'  => '0000001100',
						'trang_thai'  => 1,
						'get_row'     => true,
					));

					$thanh_tien_luy_ke   = (doubleval($chi_tiet->don_gia) ?? 0 + doubleval($chi_tiet->phu_cap) ?? 0) * (doubleval($luy_ke_ca_hom_nay->tong_cong) ?? 0);

					$params['tong_cong']['tong_gia_tri_hop_dong']      += $chi_tiet->thanh_tien;
					$params['tong_cong']['tong_gia_tri_da_thanh_toan'] += $thanh_tien_luy_ke;
				}
				break;
			case 50:
				$ds_doi_tac_du_an = $params['da']->danh_sach_doi_tac_du_an(array(
					'ma_du_an'    => $params['ma_du_an'],
				));
		
				$call_api_payment = $params['c']->call_api_payment(array(
					'ma_du_an'   => $params['ma_du_an'],
					'ma_code'    => $params['ma_code'],
					'module'     => 'thanh_toan',
					'trang_thai_chi_tien' => '4,5', // 4: đã thanh toán, 5: thanh toán một phần
				));
				
				foreach($ds_doi_tac_du_an as $doi_tac){
					$so_tien_da_thanh_toan = 0;
					foreach ($call_api_payment->data as $key => $value) {
						if($value->ten_tai_khoan == $doi_tac->ten_cong_ty) {
							$so_tien_da_thanh_toan += $value->so_tien_da_thanh_toan;
						}
					}
		
					$params['tong_cong']['tong_gia_tri_hop_dong']      += $doi_tac->gia_tri_hop_dong;
					$params['tong_cong']['tong_gia_tri_da_thanh_toan'] += $so_tien_da_thanh_toan;
				}
				break;
			case 60:
				$tong_tien_da_chi  = 0;
				$tong_tien_hop_le  = 0;
				$call_api_payment = $params['c']->call_api_payment(array(
					'ma_du_an'   => $params['ma_du_an'],
					'ma_code'    => $params['ma_code'],
					'module'     => 'thanh_toan,de_nghi_tam_ung',
				));
				foreach ($call_api_payment->data as $key => $item){
					$tong_tien_da_chi += $item->so_tien_da_thanh_toan;
					$tong_tien_hop_le += $item->so_tien_da_hoan_ung;
				}

				$phan_tram = array(
					'phan_tram_thuc_nhan' => $params['tt_ngan_sach']->tong_tien ? round(($tong_tien_da_chi / $params['tt_ngan_sach']->tong_tien) * 100) : 0,
					'phan_tram_giai_chi'  => $params['tt_ngan_sach']->tong_tien ? round(($tong_tien_hop_le / $params['tt_ngan_sach']->tong_tien) * 100) : 0,
				);
				return $phan_tram;
		}

		if($params['tong_cong']['tong_gia_tri_hop_dong'] > 0){
			$phan_tram = round(($params['tong_cong']['tong_gia_tri_da_thanh_toan'] / $params['tong_cong']['tong_gia_tri_hop_dong']) * 100);
		}else{
			$phan_tram = -1; // show error
		}
		return $phan_tram;
	}

	public function tao_badge($params) {
		$gia_tri  = $params['gia_tri'] ?? 0;
		$tieu_de  = $params['tieu_de'] ?? '';
		$class    = '';
		$noi_dung = '';
	
		if ($gia_tri == -1) {
			$class    = 'badge-danger';
			$noi_dung = 'Lỗi';
		} elseif ($gia_tri > 100) {
			$class    = 'badge-outline-danger';
			$noi_dung = "{$gia_tri}%";
		} else {
			$class    = 'badge-outline-success';
			$noi_dung = "{$gia_tri}%";
		}
		$tooltip = $tieu_de ? "data-toggle='tooltip' title='{$tieu_de}'" : '';
		return "<span class='badge badge-pill {$class}' {$tooltip}>{$noi_dung}</span>";
	}

	public function trang_thai_chuyen_de_highlight($params = array()) {
		$c   = new DA_Code();
		$da  = new DA_Duan();
		$bcn = new DA_Baocaongay();

		$html           = '';
		$html_phan_tram = '';

		$active      = 0;
		$object_type = 'ma_cong_ty';
		$tong_cong   = array(
			'tong_gia_tri_hop_dong'      => 0,
			'tong_gia_tri_da_thanh_toan' => 0,
		);

		if($params['bao_cao_tuan_id']) {
			// ncr, phat sinh, rui ro
			$tt_chuyen_de = $this->thong_tin_theo_chuyen_de($params);

			// tre tien do
			$tt_bct = $this->thong_tin(array(
				'id' 			   => $params['bao_cao_tuan_id'],
				'danh_gia_tien_do' => -2
			));

			if($tt_chuyen_de || ($params['key_chuyen_de'] == 'tien_do' && $tt_bct)) $active = 1;
		}

		
		// ngan sach code
		if($params['key_chuyen_de'] == 'ngan_sach'){
			$ds_ngan_sach = array();
			foreach($this->ngan_sach as $ma_code => $ten_code) {
				$tt_ngan_sach = $c->thong_tin_thiet_lap_ngan_sach_code(array(
					'ma_du_an'   => $params['ma_du_an'],
					'ma_code'    => $ma_code,
					'trang_thai' => 1
				));

				$phan_tram = $this->thong_ke_phan_tram_ngan_sach(array(
					'da'  => $da,
					'c'   => $c,
					'bcn' => $bcn,
					'ma_du_an'     => $params['ma_du_an'],
					'tong_cong'    => $tong_cong,
					'ma_code'      => $ma_code,
					'object_type'  => $object_type,
					'tt_ngan_sach' => $tt_ngan_sach
				));

				if (is_array($phan_tram)) {
					$html_phan_tram = $this->tao_badge(['gia_tri' => $phan_tram['phan_tram_thuc_nhan'], 'tieu_de' => 'Phần trăm thực nhận']);
					$html_phan_tram .= $this->tao_badge(['gia_tri' => $phan_tram['phan_tram_giai_chi'], 'tieu_de' => 'Phần trăm giải chi']);
				} else {
					$html_phan_tram = $this->tao_badge(['gia_tri' => $phan_tram]);
				}
				
				if($tt_ngan_sach){
					$ds_ngan_sach[] = $ma_code;
					//trang thai
					switch($tt_ngan_sach->trang_thai){
						case 1:
							$badge = 'badge-primary';
							break;
						case -1:
							$badge = 'badge-warning';
							break;
						case -2:
							$badge = 'badge-danger';
							break;
						default:
							$badge = 'badge-light';
							break;
					}
					$html .= "<a class='d-block' href='". home_url() ."/du-an?view=chi-tiet&ma_du_an=". $params['ma_du_an'] ."&tab=code-". $ma_code ."' target='_blank'>";
					$html .= "<span class='badge {$badge}'>Xem {$ten_code}</span> {$html_phan_tram}</a>";
				}else{
					$html .= "<span class='badge badge-outline-light'>Chưa có {$ten_code}</span>";
				}
			}
			if(!empty($ds_ngan_sach)) $active = 2;
		}

		switch($active) {
			case 1:
				$html = "<a class='d-block' href='". home_url() ."/du-an?view=chi-tiet&ma_du_an=". $params['ma_du_an'] ."&tab=bao-cao-tuan&action=chi-tiet&id=". $params["bao_cao_tuan_id"] ."#". $params["key_chuyen_de"]."' target='_blank'>";
				$html .= "<span class='badge badge-primary'>Xem {$params['value_chuyen_de']}</span></a>";
				break;
			case 2:
				//ngan sach da co html
				break;
			default:
				$html = "<span class='badge badge-pill badge-outline-light'>Không có {$params['value_chuyen_de']}</span>";
				break;
		}
		return $html;
	}

	public function thong_tin_theo_chuyen_de($params = array()) {
		global $wpdb;

		$sql = "SELECT * FROM da_{$params['key_chuyen_de']}_du_an_bct";

		$where = array();

		if (isset($params['bao_cao_tuan_id']) && $params['bao_cao_tuan_id']) $where[] = "bao_cao_tuan_id = '".intval($params['bao_cao_tuan_id'])."'";

		$where[] = " ({$params['key_chuyen_de']}_du_an_noi_dung != '' OR {$params['key_chuyen_de']}_du_an_noi_dung != NULL)";
		
		if (count($where)) $sql .= " WHERE ".implode(" AND ", $where);

		return $wpdb->get_row($sql);
	}

	public function trang_thai_tien_do_highlight($trang_thai) {
		$html = '';
		switch($trang_thai) {
			case 1: 
			case 2:
				$html = '<span class="badge badge-pill badge-success">Đúng tiến độ</span>';
			break;
			case -2:
				$html = '<span class="badge badge-pill badge-danger">Trễ tiến độ</span>';
			break;
			default:
				$html = '<span class="badge badge-pill badge-outline-light">Chưa có tiến độ</span>';
		}
		return $html;
	}

}
