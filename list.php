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
            <th>Official Name</th>
            <th>Currency Name</th>
            <th>Currency Code</th>
            <th>Symbol</th>
        </tr>
    </thead>
    <tbody id="countryRows">
        
     </tbody>
</table>
<?php } ?>