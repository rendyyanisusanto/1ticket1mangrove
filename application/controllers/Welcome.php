<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Xendit\Xendit;
class Welcome extends CI_Controller {

	var $wedding_id = 1;
	var $api = "http://localhost/apiyukflix/";
	var $tok = "";

	var $nmid = "ID1022232518236";
	var $terminalid = "A01";
	var $title = "1Ticket1Mangrove";
	function get_set_token(){
		$data['curl']  = json_decode($this->curl->simple_post($this->api.'api/register/getToken', array('username'=>'rendy','password'=>'rendy'), array(CURLOPT_BUFFERSIZE => 10))); 

		if (strlen($this->tok) == 0) {
			$this->tok = $data['curl']->values->token;
		}

	}
    function post_yukflix_api($url,$post)
    {

		$authorization = "Authorization: Bearer ".$this->tok;

		return json_decode($this->curl->simple_post($this->api.'api/register/register_active', $post, array(CURLOPT_BUFFERSIZE => 10))); 
    }
	function get_data_api(){

		$authorization = "Authorization: Bearer ".$this->tok;
		$ch = curl_init($this->api.'api/register/data');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result);
	}
	public function index($backlink = "")
	{
        if ($backlink != "") {
        	$query = $this->db->get_where("qris", ['backlink'=>$backlink])->row_array();
        	if (!empty($query)) {
        		
	        	$this->nmid = $query['nmid'];
	        	$this->terminalid = $query['terminalid'];
	        	$this->title = $query['title'];
        	}
        }
        $data = [
        	'title'	=> $this->title,
        	'nmid'	=>	$this->nmid,
        	'terminalid'	=>	$this->terminalid
        ];


		$this->load->view('add', $data);
	}
	function new_submit()
	{
		$jml = $_POST['jumlah']* 50000;
		$uid = 'KNR'.rand(0,999999).$_POST['no_hp'];
		$post_db = [
			'nama' 				=> $_POST['nama'],
			'no_hp'				=> $_POST['no_hp'],
			'email'				=> $_POST['email'],
			'jumlah' 			=> $_POST['jumlah'],
			'cara_pembayaran'	=> $_POST['cara_pembayaran'],
			'instansi'			=> $_POST['instansi'],
			'status' 			=> 0,
			'uid' 				=> $uid,
			'total_pembayaran' 	=> $jml
		];

		$this->db->insert('donasi', $post_db);

		$post_api = [
			    "nmid"=> $_POST['nmid'],
			    "terminal"=> $_POST['terminalid'],
			    "type"=> "D",
			    "inquiry"=> "F",
			    "amount"=> (int) $jml,
			    "transaction_id"=> $uid
		];
		// $data['api'] = $this->curl->simple_post('https://wlgiro.posindonesia.co.id:4443/merchant_qr_generator/qris/api/v2/', $post, array(
		// 	CURLOPT_BUFFERSIZE => 10, 
		// 	CURLOPT_HTTPAUTH=>CURLAUTH_ANY,
		// 	CURLOPT_USERPWD=>"busdev:busdev123"
		// ));

		// $authorization = "Authorization: Basic ".base64_encode('busdev:busdev123');
		$ch = curl_init("https://wlgiro.posindonesia.co.id:4443/merchant_qr_generator/qris/api/v2/");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_api));
      	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, "busdev:busdev123");
		$result = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
		curl_close($ch);

		$data = [
			'post_db' => $post_db,
			'post_api' => $post_api,
			'result_api' => json_decode($result),

        	'title'	=> $_POST['title'],
        	'nmid'	=>	$_POST['nmid'],
        	'terminalid'	=>	$_POST['terminalid']
		];
		// print_r($data);
		// print_r($post);
		// $data['ans'] = $ans;
		$this->load->view('process', $data);
	}

	function submit()
	{
		$ans=[];
		$jml = $_POST['jumlah'] * 50000 + 7000;
		Xendit::setApiKey('xnd_production_1OQfafbBjHazDqYOO88aSSN9vjj6mx90kPfiYq4QiNNXyniH9qkWmAGw8APOsf');
		if ($_POST['cara_pembayaran'] == "briva") {
			
			$params = ["external_id" => "VA_fixed-12341234",
			   "bank_code" => "BRI",
			   "name" => "Rendy",
	  			"is_closed"=> true,
			   "suggested_amount" =>  $jml,
			   "expected_amount"=> $jml,
			   "description"=> "#1Ticket1Mangrove Donasi ".$_POST['jumlah']." Mangrove, sertifikat akan secara otomatis dikirimkan melalui WA tertera"
			];

			$ans = \Xendit\VirtualAccounts::create($params);
			// var_dump($ans);
		}elseif($_POST['cara_pembayaran'] == "bni"){
			$params = ["external_id" => "VA_fixed-12341234",
			   "bank_code" => "BNI",
			   "name" => "Rendy",
	  			"is_closed"=> true,
			   "expected_amount"=> $jml,
			];

			$ans = \Xendit\VirtualAccounts::create($params);
		}elseif($_POST['cara_pembayaran'] == "bsi"){
			$params = ["external_id" => "VA_fixed-12341234",
			   "bank_code" => "BSI",
			   "name" => "Rendy",
	  			"is_closed"=> true,
			   "expected_amount"=> $jml,
			];

			$ans = \Xendit\VirtualAccounts::create($params);
		}elseif($_POST['cara_pembayaran'] == "mandiri"){
			$params = ["external_id" => "VA_fixed-12341234",
			   "bank_code" => "MANDIRI",
			   "name" => "Rendy",
	  			"is_closed"=> true,
			   "expected_amount"=> $jml,
			];

			$ans = \Xendit\VirtualAccounts::create($params);
		
		}elseif($_POST['cara_pembayaran'] == "dana"){
			$params = [
				    'reference_id' => 'test-reference-id',
				    'currency' => 'IDR',
				    'amount' =>  $jml,
				    'checkout_method' => 'ONE_TIME_PAYMENT',
				    'channel_code' => 'ID_DANA',
				    'channel_properties' => [
				        'success_redirect_url' => 'https://myber.web.id',
				    ],
				    'metadata' => [
				        'branch_code' => 'tree_branch'
				    ]
				];

				$ans = \Xendit\EWallets::createEWalletCharge($params);
				// var_dump($ans);
		}elseif($_POST['cara_pembayaran'] == "ovo"){
			$params = [
				    'reference_id' => 'test-reference-id',
				    'currency' => 'IDR',
				    'amount' =>  $jml,
				    'checkout_method' => 'ONE_TIME_PAYMENT',
				    'channel_code' => 'ID_OVO',
				    'channel_properties' => [
				    	'mobile_number' =>	$this->phone_number($_POST['no_hp']),
				        'success_redirect_url' => 'https://myber.web.id',
				        'failure_redirect_url' => 'https://myber.web.id',
				    ],
				    'metadata' => [
				        'branch_code' => 'tree_branch'
				    ]
				];

				$ans = \Xendit\EWallets::createEWalletCharge($params);
				// var_dump($ans);
		}
		
		$data = [
			'nama' 				=> $_POST['nama'],
			'no_hp'				=> $_POST['no_hp'],
			'email'				=> $_POST['email'],
			'jumlah' => $_POST['jumlah'],
			'cara_pembayaran'=> $_POST['cara_pembayaran'],
			'status' => 0,
			'ref_id' => $ans['id'],
			'total_pembayaran' => $jml
		];
		// print_r($ans);
		$this->db->insert('donasi', $data);

		$data['ans'] = $ans;
		$this->load->view('process', $data);
	}

	function get_chat(){
		$chat = json_decode($this->curl->simple_get($this->api.'/api/chat/data/'.$this->wedding_id));
		$data['chat']	=	$chat->data;
		$this->load->view('chat', $data);
	}
	function post_chat(){
		$data	=	[
			'name'		=>	$_POST['name'],
			'is_confirm'=>	$_POST['is_confirm'],
			'phone_number'=>	$_POST['phone_number'],
			'chat'		=>	$_POST['chat'],
			'wedding_id'=> $this->wedding_id
		];
		$this->curl->simple_post($this->api.'/api/chat/data', $data, array(CURLOPT_BUFFERSIZE => 10)); 
	}
	function callback()
	{
		// Ini akan menjadi Token Verifikasi Callback Anda yang dapat Anda peroleh dari dasbor.
		// Pastikan untuk menjaga kerahasiaan token ini dan tidak mengungkapkannya kepada siapa pun.
		// Token ini akan digunakan untuk melakukan verfikasi pesan callback bahwa pengirim callback tersebut adalah Xendit
		$xenditXCallbackToken = '9kZlmn9Sm6MwVLQvurd8yCz5W22QBhu0m9obOVaKHPe9Tq6k';

			
		// Bagian ini untuk mendapatkan Token callback dari permintaan header, 
		// yang kemudian akan dibandingkan dengan token verifikasi callback Xendit
		$reqHeaders = getallheaders();

		$xIncomingCallbackTokenHeader =$reqHeaders['X-CALLBACK-TOKEN'];
		// print_r($reqHeaders['X-CALLBACK-TOKEN']);
		// Untuk memastikan permintaan datang dari Xendit
		// Anda harus membandingkan token yang masuk sama dengan token verifikasi callback Anda
		// Ini untuk memastikan permintaan datang dari Xendit dan bukan dari pihak ketiga lainnya.
		if($xIncomingCallbackTokenHeader === $xenditXCallbackToken){
		  // Permintaan masuk diverifikasi berasal dari Xendit
		    
		  // Baris ini untuk mendapatkan semua input pesan dalam format JSON teks mentah
		  $rawRequestInput = file_get_contents("php://input");
		  // Baris ini melakukan format input mentah menjadi array asosiatif
		  $arrRequestInput = json_decode($rawRequestInput, true);
		  // print_r($arrRequestInput);
		  $status = 1;
		  
		  $_id = (isset($arrRequestInput['id'])) ? $arrRequestInput['id'] : $arrRequestInput['data']['id'];
		  if (isset($arrRequestInput['bank_code'] )) {
		  	if ($arrRequestInput['bank_code'] == 'BRI') {
			  	$_id = $arrRequestInput['callback_virtual_account_id'];
			  }
		  }

		  $data = $this->db->get_where('donasi', ['ref_id'=>$_id]);
		  if ($data->num_rows() > 0 ) {
		  		if ($data->row_array()['cara_pembayaran'] == 'ovo' || $data->row_array()['cara_pembayaran'] == 'dana' ) {
		  			if ($arrRequestInput['data']['status'] != "SUCCEEDED") {
		  				$status = 0;
		  			}
		  		}
		  		if ($status == 1) {
		  			if ($data->row_array()['status'] == 0 ) {
			  			$this->db->where('ref_id', $_id)->update('donasi', ['status'=>1]);
						$this->curl->simple_post('https://yukflix.id/frontend/register_api/'.$data->row_array()['nama'].'/'.$data->row_array()['email'].'/'.$data->row_array()['no_hp'], [], array(CURLOPT_BUFFERSIZE => 10)); 

						$this->post_yukflix_api('api/register/register_active', [
							'nama' 				=> $data->row_array()['nama'],
							'no_hp'				=> $data->row_array()['no_hp'],
							'email'				=> $data->row_array()['email'],
						]);
			  			$this->send_wa_new($data->row_array());
			  		}
		  		}
		  		
		  		
		  }

		  // Kamu bisa menggunakan array objek diatas sebagai informasi callback yang dapat digunaka untuk melakukan pengecekan atau aktivas tertentu di aplikasi atau sistem kamu.
		  http_response_code(200);
		}else{
		  // Permintaan bukan dari Xendit, tolak dan buang pesan dengan HTTP status 403
		  http_response_code(403);
		}
	}
	function phone_number($nohp) {
	    $nohp = str_replace(" ","",$nohp);
	    $nohp = str_replace("(","",$nohp);
	    $nohp = str_replace(")","",$nohp);
	    $nohp = str_replace(".","",$nohp);

	    // cek apakah no hp mengandung karakter + dan 0-9
	    if(!preg_match('/[^+0-9]/',trim($nohp))){
	        // cek apakah no hp karakter 1-3 adalah +62
	        if(substr(trim($nohp), 0, 3)=='+62'){
	            $hp = trim($nohp);
	        }
	        // cek apakah no hp karakter 1 adalah 0
	        elseif(substr(trim($nohp), 0, 1)=='0'){
	            $hp = '+62'.substr(trim($nohp), 1);
	        }
	    }
	    return $hp;
	}
	 public function send_wa_new($data)
    {
        $curl = curl_init();
        $token = "ZnLosUrJe9hhcAz22VLPW7WTigkvifChOwppHXCKwE2kO7bpb2ckFQ0IoDAjff7t";
        $data = [
            'phone' => $data['no_hp'],
            'message' => 'Hai '.$data['nama'].'... '.PHP_EOL.' Terimakasih telah berdonasi mangrove dengan jumlah '.$data['jumlah'].PHP_EOL.' untuk mendownload e-Certificate + twibbon. Silahkan klik link. '.PHP_EOL.' https://mangrove.kinarya-bersemi.com/welcome/download_pdf/'.(base64_encode($data['id_donasi'])).' (e-Certificate). '.PHP_EOL.' https://mangrove.kinarya-bersemi.com/public/twibbon.PNG'.' (Twibbon). ',
            'secret' => false, // or true
            'priority' => false, // or true
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_URL,  "https://kudus.wablas.com/api/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

    }

    public function download_pdf($data)
	{

        $data_set = $this->db->get_where('donasi', ['id_donasi'=>base64_decode($data)])->row_array();
		error_reporting(0); // AGAR ERROR MASALAH VERSI PHP TIDAK MUNCUL
 		$this->load->library('Pdf');
        $pdf = new FPDF('L', 'mm','A4');
        $pdf->AddPage();

 		$pdf->AddFont('Montserrat-Black','','Montserrat-Black.php');
 		$pdf->AddFont('Montserrat-Bold','','Montserrat-Bold.php');
 		$pdf->AddFont('Montserrat-Regular','','Montserrat-Regular.php');
        $pdf->Image(base_url('public/1.jpg'), 0, 0,297,210);
         $pdf->SetFont('Montserrat-Bold','',30);
        $pdf->text(100,110,$data_set['nama']);

        
      
        $pdf->Output();
	}
}
