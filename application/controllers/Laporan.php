<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function laporan_buku()
    {
        $data['judul'] = 'Laporan Data Buku';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('buku/laporan_buku', $data);
        $this->load->view('templates/footer');
    }
    public function cetak_laporan_buku()
    {
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        $data['kategori'] = $this->ModelBuku->getKategori()->result_array();

        $this->load->view('buku/laporan_print_buku', $data);
    }
    public function laporan_buku_pdf()
    {
        $data['buku'] = $this->ModelBuku->getBuku()->result_array();
        // $this->load->library('dompdf_gen');
        $sroot = $_SERVER['DOCUMENT_ROOT'];
        include $sroot . "/pustaka-booking/application/third_party/dompdf/autoload.inc.php";

        $dompdf = new Dompdf\Dompdf();
        $this->load->view('buku/laporan_pdf_buku', $data);

        $paper_size = 'A4'; // ukuran kertas
        $orientation = 'landscape'; // tipe format kertas potrait atau landscape
        $html = $this->output->get_output();

        $dompdf->set_paper($paper_size, $orientation);
        // Convert to PDF
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream("laporan_data_buku.pdf", array('Attachment' => 0)); // nama file pdf yang dihasilkan
    }
    public function export_excel()
    {
        $data = array(
            'title' => 'Laporan Buku',
            'buku' => $this->ModelBuku->getBuku()->result_array()
        );

        $this->load->view('buku/export_excel_buku', $data);
    }
    public function laporan_pinjam()
    {
        // Setting the title of the page
        $data['judul'] = 'Laporan Data Peminjaman';

        // Fetching user data based on session email
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();

        // Query to fetch the report data
        $data['laporan'] = $this->db->query(
            "SELECT * FROM pinjam p
         JOIN detail_pinjam d ON p.no_pinjam = d.no_pinjam
         JOIN buku b ON d.id_buku = b.id
         JOIN user u ON p.id_user = u.id"
        )->result_array();

        // Loading the views with the respective data
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar', $data);
        $this->load->view('pinjam/laporan-pinjam', $data);
        $this->load->view('templates/footer');
    }

    public function cetak_laporan_pinjam()
    {
        $data['laporan'] = $this->db->query(
            "SELECT * FROM pinjam p
         JOIN detail_pinjam d ON p.no_pinjam = d.no_pinjam
         JOIN buku b ON d.id_buku = b.id
         JOIN user u ON p.id_user = u.id"
        )->result_array();

        $this->load->view('pinjam/laporan-print-pinjam', $data);
    }

    public function laporan_pinjam_pdf()
    {
        // Fetching the report data
        $data['laporan'] = $this->db->query(
            "SELECT * FROM pinjam p
         JOIN detail_pinjam d ON p.no_pinjam = d.no_pinjam
         JOIN buku b ON d.id_buku = b.id
         JOIN user u ON p.id_user = u.id"
        )->result_array();

        // Include the Dompdf library
        $sroot = $_SERVER['DOCUMENT_ROOT'];
        include $sroot . "/pustaka-booking/application/third_party/dompdf/autoload.inc.php";
        $dompdf = new Dompdf\Dompdf();

        // Load the view
        $this->load->view('pinjam/laporan_pdf_pinjam', $data);

        // Define paper size and orientation
        $paper_size = 'A4';
        $orientation = 'landscape';
        $html = $this->output->get_output();
        $dompdf->set_paper($paper_size, $orientation);

        // Convert to PDF
        $dompdf->load_html($html);
        $dompdf->render();

        // Stream the PDF to the browser
        $dompdf->stream("laporan_data_peminjaman.pdf", array('Attachment' => 0));
    }

    public function export_excel_pinjam()
    {
        // Fetching the report data
        $data = array(
            'title' => 'Laporan Data Peminjaman Buku',
            'laporan' => $this->db->query(
                "SELECT * FROM pinjam p
             JOIN detail_pinjam d ON p.no_pinjam = d.no_pinjam
             JOIN buku b ON d.id_buku = b.id
             JOIN user u ON p.id_user = u.id"
            )->result_array()
        );

        // Loading the view
        $this->load->view('pinjam/export-excel-pinjam', $data);
    }
    public function laporan_anggota()
    {
        $data['judul'] = 'Laporan Data Anggota';
        $data['user'] = $this->ModelUser->cekData(['email' => $this->session->userdata('email')])->row_array();
        $this->db->where('role_id', 1);
        $data['anggota'] = $this->db->get('user')->result_array();


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('anggota/laporan_anggota', $data);
        $this->load->view('templates/footer');
    }
    public function cetak_laporan_anggota()
{
    $data['anggota'] = $this->db->get('user')->result_array();
    $this->load->view('anggota/laporan_print_anggota', $data);
}

public function laporan_anggota_pdf()
{
    $data['anggota'] = $this->db->get('user')->result_array();
    
    $sroot = $_SERVER['DOCUMENT_ROOT'];
    include $sroot . "/pustaka-booking/application/third_party/dompdf/autoload.inc.php";
    
    $dompdf = new Dompdf\Dompdf();
    
    $this->load->view('anggota/laporan_pdf_anggota', $data);
    $html = $this->output->get_output();
    
    $dompdf->load_html($html);
    $dompdf->set_paper('A4', 'landscape'); // Ukuran kertas dan orientasi
    $dompdf->render();
    
    $dompdf->stream("laporan_data_anggota.pdf", array('Attachment' => 0)); // Nama file PDF yang dihasilkan
}

public function export_excel_anggota()
{
    $data = array(
        'title' => 'Laporan Data Anggota',
        'anggota' => $this->db->get('user')->result_array()
    );
    $this->load->view('anggota/laporan_excel_anggota', $data);
}



}
?>