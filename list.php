<?php
namespace Adebanjo;
$result = $countryModel->getAllCountry();
if (!empty($result)) {
    ?>
<table class="table table-striped table-bordered" id='userTable'>
    <thead class="thead-dark">
        <tr>
            <th>#ID</th>
            <th>Country</th>
            <th>Continent</th>
            <th>Currency</th>
            <th>Official Name</th>
            <th>Currency Name</th>
            <th>Symbol</th>
        </tr>
    </thead>
    <tbody id="txtHint">
        
     </tbody>
</table>
<?php } ?>