<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyakit extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('file');
	}

	public function tambah() {
		$data = array('success' => false, 'messages' => array() );

		$this->form_validation->set_rules('kode_penyakit', 'Kode Penyakit',
			'trim|required|min_length[2]|max_length[12]|is_unique[penyakit.kode_penyakit]|regex_match[/^[a-zA-Z0-9\s]+$/]',
			array('required' => 'Harap isi kode penyakit',
				  'min_length' => 'Kode penyakit terlalu singkat',
				  'max_length' => 'Kode penyakit terlalu panjang',
				  'regex_match' => 'Harap masukkan karakter yang valid [a-z/A-Z/0-9]',
				  'is_unique' => 'Kode penyakit sudah digunakan'
		));

		$this->form_validation->set_rules('nama_penyakit', 'Nama Penyakit',
			'trim|required|min_length[5]|max_length[30]|regex_match[/^[a-zA-Z0-9\s]+$/]',
			array('required' => 'Harap isi kode penyakit',
				  'min_length' => 'Kode penyakit terlalu singkat',
				  'regex_match' => 'Harap masukkan karakter yang valid [a-z/A-Z/0-9]',
				  'max_length' => 'Kode penyakit terlalu panjang'
		));

		$this->form_validation->set_rules('deskripsi_penyakit', 'Deskripsi Penyakit',
			'trim|required|min_length[5]|max_length[300]|regex_match[/^[a-zA-Z0-9\s]+$/]',
			array('required' => 'Harap isi kode penyakit',
				  'min_length' => 'Kode penyakit terlalu singkat',
				  'regex_match' => 'Harap masukkan karakter yang valid [a-z/A-Z/0-9]',
				  'max_length' => 'Kode penyakit terlalu panjang'
		));

		$this->form_validation->set_rules('id_solusi', 'Solusi',
			'required',
			array('required' => 'Harap pilih solusi penyakit'
		));

		$this->form_validation->set_rules('foto', 'Foto', 'callback_file_check');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

		if ($this->form_validation->run($this) == TRUE) {
			if(!empty($_FILES['foto']['name'])) {
				$penyakit = array('kode_penyakit' => $this->input->post('kode_penyakit'),
								  'nama_penyakit' => $this->input->post('nama_penyakit'),
								  'deskripsi_penyakit' => $this->input->post('deskripsi_penyakit'),
								  'id_solusi' => $this->input->post('id_solusi'),
								  'foto' => $this->upload_image()
							);
				$this->M_penyakit->add_penyakit($penyakit);
			}
			$data['success'] = true;
		} else {
			foreach ($_POST as $key => $value) {
				$data['messages'][$key] = form_error($key);
				$data['messages']['foto'] = form_error('foto');
			}
		}
		echo json_encode($data);
	}

	public function file_check($str) {
		if(!empty($_FILES['foto']['name'])) {
			$allowed = array('image/jpg', 'image/jpeg', 'image/png');
			$mime = get_mime_by_extension($_FILES['foto']['name']);
			if(in_array($mime, $allowed)) {
				return true;
			} else {
				$this->form_validation->set_message(__FUNCTION__, 'Ekstensi foto tidak valid (hanya jpeg/jpg/png)');
				return false;
			}
		} else {
			$this->form_validation->set_message(__FUNCTION__, 'Harap isi foto penyakit');
			return false;
		}
	}

	public function upload_image() {
		if(!empty($_FILES['foto'])) {
			$ekstensi = explode('.', $_FILES['foto']['name']);
			$new_name = substr(md5(rand()),0,8).'.'.$ekstensi[1];
			move_uploaded_file($_FILES['foto']['tmp_name'], 'assets/img/'.$new_name);
			return $new_name;
		}
	}

}

/* End of file Penyakit.php */
/* Location: ./application/controllers/Penyakit.php */
