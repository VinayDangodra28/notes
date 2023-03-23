<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "Notes";
$connection = mysqli_connect($servername, $username, $password, $database);

$delete = false;
$insert = false;
$update = false;


if(!$connection){
    die("Failed to connect to the database : ". mysqli_connect_error());
}

if(isset($_GET['delete'])){
  $id_delete = $_GET['delete'];
  $sql_query = "DELETE FROM `MyNotes` WHERE `ID` = $id_delete";
  $result = mysqli_query($connection, $sql_query);
  if($result){
    echo"<div class='alert alert-primary alert-dismissible fade show' role='alert'>
    <strong>Note deleted successfully</strong>
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
  </div>";
  }
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['id_edit'])){
      $topic_edit = $_POST['topic_edit'];
      $description_edit = $_POST['description_edit'];
      $id_edit = $_POST['id_edit'];
      $sql_query = "UPDATE `MyNotes` SET `topic` = '$topic_edit', `description` = '$description_edit' WHERE `MyNotes`.`ID` = $id_edit;";
      $result = mysqli_query($connection, $sql_query);
      if($result){
        echo"<div class='alert alert-primary alert-dismissible fade show' role='alert'>
        <strong>Note edited successfully</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
      }
    }
    else{
      $topic = $_POST["topic"];
      $description = $_POST["description"];
    
      $sql_query = "INSERT INTO `MyNotes` (`topic`, `description`) VALUES ('$topic', '$description')";
      $result = mysqli_query($connection, $sql_query);
      if($result){
        echo"<div class='alert alert-primary alert-dismissible fade show' role='alert'>
        <strong>Note added successfully</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editModalLabel">Edit Note</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form action="/dvinay/index.php" method="post">
        <input type="hidden" id="id_edit" name="id_edit">
        <div class="mb-3">
          <label for="topic_edit" class="form-label">Topic</label>
          <input type="text" class="form-control" id="topic_edit" name="topic_edit">
        </div>
        <div class="mb-3">
          <label for="description_edit" class="form-label">Example textarea</label>
          <textarea class="form-control" id="description_edit" name="description_edit" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      </div>
    </div>
  </div>
</div>

    <?php
   $sql_query = "SELECT * from `MyNotes`";
   $result = mysqli_query($connection, $sql_query);
   
    ?>
<div class="container">
<form action="/dvinay/index.php" method="post">
<div class="mb-3">
  <label for="topic" class="form-label">Topic</label>
  <input type="text" class="form-control" id="topic" name="topic">
</div>
<div class="mb-3">
  <label for="description" class="form-label">Example textarea</label>
  <textarea class="form-control" id="description" name="description" rows="3"></textarea>
</div>
<button type="submit" class="btn btn-primary">Submit</button>
</form>

<table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">Sno.</th>
      <th scope="col">Topic</th>
      <th scope="col">Description</th>
      <th scope="col">Date</th>
      <th scope="col">Options</th>
    </tr>
  </thead>
  <tbody>
    
    <?php
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
        $i++;
        echo "<tr id='{$row['ID']}'>
        <th scope='row'>{$i}</th>
        <td>{$row['Topic']}</td>
        <td>{$row['Description']}</td>
        <td>{$row['Date_of_entry']}</td>
        <td><button type='button' class='edit btn btn-primary'>edit</button> <button type='button' class='delete btn btn-primary'>delete</button></td>
      </tr>";
       }
    ?>
    
  </tbody>
</table>
    </div>
    <script
  src="https://code.jquery.com/jquery-3.6.4.js"
  integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
  function addEditButtonListeners() {
    // console.log("function added");
    edits = document.getElementsByClassName("edit");
    Array.from(edits).forEach((element)=>{
      // console.log(element);
      element.addEventListener("click", (e)=>{
        console.log('edit', e.target.parentNode.parentNode);
        tr = e.target.parentNode.parentNode;
        id = e.target.parentNode.parentNode.id;
        topic = tr.getElementsByTagName("td")[0].innerText;
        description = tr.getElementsByTagName("td")[1].innerText;
        console.log(id);
        topic_edit.value = topic;
        description_edit.value = description;
        id_edit.value = id;
        $('#editModal').modal('toggle')
      })
    });
  }

  function addDeleteButtonListeners() {
    // console.log("function added");
    edits = document.getElementsByClassName("delete");
    Array.from(edits).forEach((element)=>{
      console.log(element);
      element.addEventListener("click", (e)=>{
        console.log('delete', e.target.parentNode.parentNode);
        tr = e.target.parentNode.parentNode;
        id = e.target.parentNode.parentNode.id;
        if(confirm("Do you really want to delete this note?")){
          window.location = "/dvinay/index.php?delete="+id;
        }
        else{
          console.log("canceled");
        }
      })
    });
  }
</script>
<script>
  let table = new DataTable('#myTable', {
    "drawCallback": function () {
      addEditButtonListeners();
      addDeleteButtonListeners();
    }
  });
</script>
</body>
</html>