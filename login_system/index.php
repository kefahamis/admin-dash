<?php
//index.php
include("database_connection.php");
if(!isset($_SESSION["type"]))
{
 header("location:login.php");
}

?>
<!DOCTYPE html>
<html>
 <head>
  <title>admin</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br />
  <div class="container">
   <h2 align="center"> Dashboard</h2>
   <br />
   <div align="right">
    <a href="logout.php">Logout</a>
   </div>
   <br />
   <?php
   if($_SESSION["type"] == "user")
   {
    echo ' <div class="panel panel-default">
    <div class="panel-heading">User Status Details</div>
    <div class="panel-body">
     <span id="message"></span>
     <div class="table-responsive" id="user_data"></div></div></div></div>';
   }
   else
   {
   ?>
   <div class="panel panel-default">
    <div class="panel-heading">User Status Details</div>
    <div class="panel-body">
     <span id="message"></span>
     <div class="table-responsive" id="user_data">
      
     </div>
     <script>
     $(document).ready(function(){
      
      load_user_data();
      
      function load_user_data()
      {
       var action = 'fetch';
       $.ajax({
        url:'action.php',
        method:'POST',
        data:{action:action},
        success:function(data)
        {
         $('#user_data').html(data);
        }
       });
      }
      
      $(document).on('click', '.action', function(){
       var id = $(this).data('id');
       var user_status = $(this).data('status');
       var action = 'change_status';
       $('#message').html('');
       if(confirm("Are you Sure you want to change status of this User?"))
       {
        $.ajax({
         url:'action.php',
         method:'POST',
         data:{id:id, user_status:user_status, action:action},
         success:function(data)
         {
          if(data != '')
          {
           load_user_data();
           $('#message').html(data);
          }
         }
        });
       }
       else
       {
        return false;
       }
      });
      
     });
     </script>
    </div>
   </div>
   <?php
   }
   ?>
  </div>
 </body>
</html>
