<?php
include("../includes/header.php");
include('../includes/footer.php');
if(isset($_POST['name']) && isset($_POST['amount']))
{
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING);
?>
<script>
    //let uri = "<?php echo $uri ?>";
    let name ="<?php echo $name ?>";
    let amount =<?php echo $amount ?>;
    console.log(name);
    let dataToSend = {
        name : name,
        amount : amount
    }

    //console.log(dataToSend);

    $.ajax({
    type: "POST",
    url: "http://localhost/PHPinl%C3%A4mning3/Inl-mningsuppgift3PHP/api/post.php",
    data: dataToSend,
    cache: false,
    success: function(){
      //check if what response is

      
    } 
  });

</script>
<?php
} else {
  echo "error";
}

