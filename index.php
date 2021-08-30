<?php

	include('functions.php');
    if (!isLoggedIn()) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');   
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['user']);
        header("location: login.php");
    }
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>
<body>
<?php
$db = mysqli_connect('localhost', 'root', '', 'pwnpatch');

    if (!isLoggedIn()) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');   
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['user']);
        header("location: login.php");
    }
    
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>

	<style>
.btn-size{
	padding: 30px 70px;
}
.btn-primary,
.btn-primary:active,
.btn-primary:visited,
.btn-primary:focus {
    background-color: #2A4798
;margin-left :30px;
    border-color: #2A4798
;
}
.btn-primary:hover{
    background-color: #375ec8;
    border-color: #375ec8;

}   .form-control{
    width: 100%;
}
	button[name=register_btn] {
		background: #003366;
    	}

    
    .flex {
   display: flex;
   flex-direction: row;
}
	</style>
</head>
<body>
<div class="container">
            <div class="jumbotron">
            <div class="row">
            <div class="col-sm-6">
                <div >
                <p>  <p>     
                <a href="index.php"><img src="logo.png" class="img-fluid" alt="https://www.pwnandpatch.com/" width="350px"></a></div></div>
                <div class="col-sm-6">
                    
                    <center>
                <h1 class="display-3">Welcome <?php echo $_SESSION['user']['username']; ?></h1>
				<?php
				if($_SESSION['user']['user_type']=="admin"){
					echo '<a href="./admin/index.php"><button type="button" class="btn btn-success">Admin Panel</button></a>';
				} ?>
				<a href="index.php?logout='1'"><button type="button" class="btn btn-danger">Logout</button></a><p></center>
            
  </div>
		<div class="card">
            <div class="card-body">
			<h3 class="display-6">Your points :  
				<?php 
				$user=$_SESSION['user']['id'];
				$query = "SELECT total_points from users where id='$user' ;";
					$result = mysqli_query($db, $query);
					$row = mysqli_fetch_array($result);
					echo ' '.$row['total_points'].' ';
			?></h3>
</div>
</div>
        <div class="card card-height">
        <div class="accordion" id="accordionExample">
        <?php
					$user=$_SESSION['user']['id'];
					$query = "SELECT tasks.description,assigned_tasks.task_id,tasks.id, tasks.name, tasks.points,users.id FROM tasks,assigned_tasks,users where user_id='$user' and task_id=tasks.id and users.id=user_id;";
					$result = mysqli_query($db, $query);

				if ($result->num_rows > 0) {
				// output data of each row
					
				while($row = $result->fetch_assoc()) {
					$task_id=$row['task_id'];?>
  <div class="accordion-item">
  <form  method="post" action="index.php">
    <h2 class="accordion-header" id="heading<?php echo $task_id;?>">
    

      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $task_id;?>" aria-expanded="true" aria-controls="collapse<?php echo $task_id;?>">
      <?php if(verify_task_solve($task_id,$user)){
          echo 'Task name : '.$row['name'].' | Points : '.$row['points'].' | Not Solved ' ;}
          else{
            echo 'Task name : '.$row['name'].' | Points : '.$row['points'].' | Already Solved ' ;
          }
          ?>
      </button>
    </h2>
    <div id="collapse<?php echo $task_id;?>" class="accordion-collapse collapse show" aria-labelledby="heading<?php echo $task_id;?>" data-bs-parent="#accordionExample">
      <div class="accordion-body">
          <?php echo $row['description'];
          ?><br>
      <div class="flex">
      <div class="input-group">
      <input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id;?>">
     <input type="text" class="form-control" id="flag" name="flag" placeholder="Enter your flag here"></div>
     <button type="submit" name="submit_flag" class="input-group-addon btn btn-secondary">Submit Flag</button>
     </div>
   <div>

   </div>
</div>

                </form>
      </div>
    </div>
<?php
}
				} else {
				echo "no tasks assigned !";
				} ?>
  </div>
  </div>
</div>
 </div>

</body>
</html>
