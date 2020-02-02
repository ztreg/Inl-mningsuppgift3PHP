<?php
include('./includes/header.php');
?>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Email</th>
      <th scope="col">Remove</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">1</th>
      <td>Mark</td>
      <td>Otto</td>
      <td>halo@example.com</td>
      <td><button type="button" class="btn btn-danger">Remove</button></td>
    </tr>
    <tr>
      <th scope="row">2</th>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>jt@sweet.org</td>
      <td><button type="button" class="btn btn-danger">Remove</button></td>
    </tr>
    <tr>
      <th scope="row">3</th>
      <td>Larry</td>
      <td>the Bird</td>
      <td>bird@hotmail.com</td>
      <td><button type="button" class="btn btn-danger">Remove</button></td>
    </tr>
  </tbody>
</table>

<button type="button" class="btn btn-primary">Add</button>

<?php
include('./includes/footer.php');