<?php
namespace Adebanjo;
use Adebanjo\CountryModel;
require __DIR__ . "/inc/bootstrap.php";
$countryModel = new CountryModel();
if(!empty($_POST)){
    
    if(isset($_POST["importCountriesSubmit"])){
        $response = $countryModel->readCountryRecords();
    }
    elseif (isset($_POST["importCurrenciesSubmit"])) {
        $response = $countryModel->readCurrencyRecords();
    }
}


// Get status message
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'success':
            $statusType = 'alert-success';
            $statusMsg = 'Data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Import CSV</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!--link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
$( document ).ready(function() {
    console.log("ready");
});
window.addEventListener("load", showData(elem = ''));

function showData(elem = '', page_number = 1) {
    const pageUrlWithParams = window.location.href;
    let pageUrl = pageUrlWithParams.split('?')[0];
    if(pageUrl[pageUrl.length-1] == "/"){
        pageUrl = pageUrl.slice(0, -1);
    }
    else if(pageUrl[pageUrl.length-1] == "#"){
        pageUrl = pageUrl.slice(0, -2);
    }
    console.log(elem);
        $.ajax({
            method: "GET",
            url: pageUrl + "/../api/country/list",
            data: {searchq: elem, page: page_number},
            dataType: "json"
        })
                .done(function (data) {
                    $("#total_data").html(data.total_data);
                    let countryRowsHtml = '';
                    $.each(data.data, function (key, val) {
                        countryRowsHtml += '<tr>';
                        countryRowsHtml += '<td>' + val.country_id + '</td>';
                        countryRowsHtml += '<td>' + val.common_name + '</td>';
                        countryRowsHtml += '<td>' + val.continent_code + '</td>';
                        countryRowsHtml += '<td>' + val.official_name + '</td>';
                        countryRowsHtml += '<td>' + val.cur_common_name + '</td>';
                        countryRowsHtml += '<td>' + val.currency_code + '</td>';
                        countryRowsHtml += '<td>' + val.symbol + '</td>';
                        countryRowsHtml += '</tr>';
                    });
                    $("#countryRows").html(countryRowsHtml);
                    $("#pagination_link").html(data.pagination);
                })
                .fail(function (data) {
        $("form").html(
          '<div class="alert alert-danger">Could not reach server, please try again later.</div>'
        );
      });
    }
</script>
<style type="text/css">
    .panel-heading a{float: right;}
    .importFrm{margin-bottom: 20px;}
    .importFrm input[type=file] {display: inline;}
    
    .importFrm{
        text-align: center;
        margin-bottom: 10px;
        padding: 10px;
        border: 2px dashed #007bff;
    }
</style>
</head>
<body>
<div class="container">
<!-- Display status message -->
<?php if(!empty($statusMsg)){ ?>
<div class="row mt-2">
<div class="col-xs-12">
    <div class="alert <?php echo $statusType; ?> alert-dismissible fade show" role="alert">
    <?php echo $statusMsg; ?>.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
</div>
<?php } ?>

<div class="row">
    <!-- Import link -->
    <div class="col-md-12 head">
        <div class="float-right mt-2">
            <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrmCountries');"><i class="fa fa-plus"></i> Import Countries</a>
            <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrmCurrencies');"><i class="fa fa-plus"></i> Import Currencies</a>
        </div>
    </div>
    <!-- CSV file upload form -->
    <div class="col-md-12 importFrm" id="importFrmCountries" style="display: none;">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <!--input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT"-->
            <input type="submit" class="btn btn-primary" name="importCountriesSubmit" value="IMPORT">
        </form>
    </div>
    <div class="col-md-12 importFrm" id="importFrmCurrencies" style="display: none;">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <!--input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT"-->
            <input type="submit" class="btn btn-primary" name="importCurrenciesSubmit" value="IMPORT">
        </form>
    </div>
    
    
</div>

<div class="mt-2">
<form action="">
    <div class="row">
        <div class="col-md-6">
        <div class="input-group mb-3">
        <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
        <input type="text" id="fname" name="fname" class="form-control" placeholder="search country..." onkeyup="showData(this.value)">
        </div>
       </div>
        
    </div>
    </form>
</div>
<?php if(!empty($countryModel->getAllCountry())){ ?>
<div class="row mb-3">
        <div class="col-2">
        Total Data <span id="total_data"></span>
        </div>
</div>
<?php } ?>
<!-- Data list table --> 
<div class="row">
    <div class="col-md-12">
    <?php  require_once __DIR__ . '/list.php';?>
    <?php if(empty($countryModel->getAllCountry())){
    echo "No data found";
    }?>
    <div id="pagination_link"></div>
</div>
</div>
</div>
<!-- Show/hide CSV upload form -->
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>