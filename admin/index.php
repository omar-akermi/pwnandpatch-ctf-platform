<?php
include('../functions.php');
$db = mysqli_connect('localhost', 'root', '', 'pwnpatch');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.0/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
    $('#datatableid').DataTable({
      "pagingType":"full_numbers",
      "lengthMenu":[
        [10,25,50,-1],
        [10,25,50,"All"]
      ],
      responsive:true,
      language:{
        search:"_INPUT_",
        searchPlaceholder:"Search records",
      }
    }
    );
} );
</script>
	<style>

.btn-checkbox-distance{
  margin-left :30px;
}
.btn-primary,
.btn-primary:active,
.btn-primary:visited,
.btn-primary:focus {
    background-color: #2A4798
;
    border-color: #2A4798
;
}


.btn-primary:hover{
    background-color: #375ec8;
    border-color: #375ec8;

}
	.header {
		background: #003366;
	}
	button[name=register_btn] {
		background: #003366;
	}
	</style>
</head>
<body>

<!-- ADD USER -->
<!-- Modal -->
<div class="modal fade" id="add-data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form  method="post" action="index.php">
      <div class="modal-body">
            <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name ="username" id="Username">
            </div>
            <div class="form-group">
            <label>Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
            </div>
            
            <div class="form-group">
                <label> Select user type: </label>
            <select class="form-select" name="user_type" aria-label="Default select example">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="password_1" id="Password">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1" class="form-label">Re-enter Password</label>
                <input type="password" class="form-control" name="password_2" id="Password">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="register_btn" class="btn btn-primary">Save changes</button>
      </div>
</form>
    </div>
  </div>
</div>
<!-- END ADD USER -->

<!-- ADD TASK -->
<!-- Modal -->
<div class="modal fade" id="add-task" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form  method="post" action="index.php">
      <div class="modal-body">
            <div class="form-group">
            <label>Task Name : </label>
            <input type="text" class="form-control" name ="name" id="name">
            </div>
            <br>
            <div class="mb-3">
                <label for="task_description" class="form-label">Task Description : </label>
                <textarea class="form-control" name ="task_description" id="task_description" rows="4"></textarea>
            </div>
            <div class="form-group">
            <label>Task Points :</label>
            <input type="text" class="form-control" name ="points" id="points">
            </div>
            <div class="form-group">
            <label>Task Flag :</label>
            <input type="text" class="form-control" name ="flag" id="flag">
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="add_task_btn" class="btn btn-primary">Save changes</button>
      </div>
</form>
    </div>
  </div>
</div>
<!-- END ADD TASK -->


<!-- EDIT -->
<!-- Modal -->
<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form  method="post" action="index.php">
      <div class="modal-body">

          <input type="hidden" name="update_id" id="update_id">
          
            <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" name ="username" id="username">
            </div>
            <div class="form-group">
            <label>Email address</label>
            <input type="text" class="form-control" id="update_email" name="email">
            </div>
            
            <div class="form-group">
                <label> Select user type: </label>
            <select class="form-select" name="user_type" id="user_type" aria-label="Default select example">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="update_btn" class="btn btn-primary">Save changes</button>
      </div>
</form>
    </div>
  </div>
</div>

<!--end EDIT -->

<!-- ASSIGN TASKS -->
<!-- Modal -->
<div class="modal fade" id="assigntasks" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Assign tasks</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form  method="post" action="index.php">
      <div class="modal-body">
      
      <p class="h5">Please Select the tasks you want to assign : </p>
      <input type=hidden name="assigntasks_id" id="assigntasks_id">
      </div>
          <div class="form-group">
          <?php
                $query = "select * from tasks";
                $result=mysqli_query($db,$query);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                      ?>
                        <input type="checkbox" class="btn-check" id="<?php echo $row["name"]?>" name="<?php echo $row["name"]?>" value="1">
                        <label class="btn btn-outline-secondary btn-checkbox-distance" for="<?php echo $row["name"]?>"><?php echo $row["name"]?></label>
                    
                    
                    <?php
                  }
                  }
            ?>
      
                  <br><br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="assign_tasks_btn" class="btn btn-primary">Save changes</button>
      </div>
</form>
    </div>
  </div>
</div>

<!--end assign tasks -->
        <div class="container">
            <div class="jumbotron">
            
                <div class="card">
                <center>
                <a href="../index.php"><img src="../logo.png" class="img-fluid" alt="https://www.pwnandpatch.com/" width="350px"></a>
                <h1 class="display-3">ADMIN PANEL</h1>
                </center>
        </div>
        <div class="card">
            <div class="card-body"><center>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-data">ADD NEW USER</button>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-task">ADD NEW TASK</button>
            <a href="index.php?logout='1'"><button type="button" class="btn btn-danger">Logout</button></a>
                </center>
            </div>
            </div>
            <div class="card">
            <div class="card-body">

<?php
                $query = "select * from users";
                $query_run=mysqli_query($db,$query);?>
            <table id="datatableid" class="table table-hover ">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Username</th>
                <th scope="col">Email</th>
                <th scope="col">User Type</th>
                <th scope="col">Total Points</th>
                <th scope="col"></th>
                <th scope="col"></th>
                </tr>
            </thead>

            <tbody>
            <?php
                if($query_run){
                    foreach($query_run as $row)
                    {
            ?>
                <tr>
                <td><?php echo $row['id']?></td>
                <td class="table-striped"><?php echo $row['username']?></td>
                <td><?php echo $row['email']?></td>
                <td><?php echo $row['user_type']?></td>
                <td><?php echo $row['total_points']?></td>
                <td>
                        <button type="button" class="btn btn-primary editbtn" >Edit</button>
                        
                </td>
                <td><button type="button" class="btn btn-primary assigntasks" >Assign Tasks</button></td>
                </tr>
                <?php
                }
                }
                    else{
                        echo 'no record';
                    }
                
                ?>
            </tbody>
            
            </table>
            </div>
        </div>

        </div>
        </div>

        <script>
                $(document).ready(function(){
                    $('.editbtn').on('click',function(){
                        $('#editmodal').modal('show')

                        $tr=$(this).closest('tr');

                        var data=$tr.children("td").map(function(){
                            return $(this).text();
                        }).get();
                        console.log(data);
                        $('#update_id').val(data[0]);
                        $('#username').val(data[1]);
                        $('#update_email').val(data[2]);
                        $('#user_type').val(data[3]);
                    });
                    $('.assigntasks').on('click',function(){
                        $('#assigntasks').modal('show')

                        $tr=$(this).closest('tr');

                        var data=$tr.children("td").map(function(){
                            return $(this).text();
                        }).get();
                        console.log(data);
                        $('#assigntasks_id').val(data[0]);

                    });
                    
                });

        </script>
        </body>

