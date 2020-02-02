<?php
include_once("./includes/header.php");
if(isset($_POST['name']) && isset($_POST['amount']))
{
  
  include('./includes/footer.php');
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
    type: "PUT",
    url: "http://localhost/PHPinl%C3%A4mning3/Inl-mningsuppgift3PHP/api/update.php",
    data: dataToSend,
    cache: false,
    success: function(){
      //check if what response is
     
      
    } 
  });

</script>
<?php
} 
