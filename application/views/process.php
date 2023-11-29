
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link rel="canonical" href="https://getbootstrap.com/docs/3.3/examples/starter-template/">

    <title>Donasi Mangrove</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          </button>
          <a class="navbar-brand" href="#">#1Ticket1Mangrove <?= $title ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <div class="starter-template" style="margin-top: 10%;">
                    <img src="<?= base_url('public/head.png') ?>" style="width: 100%;margin-bottom: 1%;">
                    <br>
        <div class="alert alert-warning"><b>Info !</b>, Setelah anda melakukan pembayaran maka sistem secara otomatis akan mengirimkan e-certificate + Twibbon ke No. WA yang tertera</div>
        <table class="table table-bordered">
            <tr>
                <td>No. Transaksi</td>
                <td>:</td>
                <td><?= $post_db['uid'] ?></td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?= $post_db['nama']?></td>
            </tr>
            <tr>
                <td>No. HP</td>
                <td>:</td>
                <td><?= $post_db['no_hp'] ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td><?= $post_db['email'] ?></td>
            </tr>
            <tr>
                <td>Jumlah Donasi Mangrove</td>
                <td>:</td>
                <td><?= $post_db['jumlah']?></td>
            </tr>
            <tr>
                <td>Total Pembayaran(<i><b>+Biaya Penanganan</b></i>)</td>
                <td>:</td>
                <td><b><?= number_format($post_db['total_pembayaran'], 0, '.','.'); ?></b></td>
            </tr>
            <tr>
                <td>Cara Pembayaran</td>
                <td>:</td>
                <td><?= strtoupper($post_db['cara_pembayaran']); ?></td>
            </tr>
            <tr>
                <td colspan="3">
                    <center>
                    <h3><b>Silahkan scan QR ini untuk melakukan pembayaran</b></h3><br>
                    <b><?= $title ?></b>
                    <p>(<?= $nmid ?>)</p>
                    <br>
                    <div id="qrcode"></div>
                    </center>
                </td>
            </tr>
        </table>
      </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="<?= base_url('public/jquery.qrcode.min.js'); ?>"></script>
    <script type="text/javascript">
        function myFunction(copyText) {
            navigator.clipboard.writeText(copyText);
            alert("Copied the text: " + copyText);
        }
        $('#qrcode').qrcode("<?= $result_api->data->qr_string ?>");
    </script>
  </body>
</html>
