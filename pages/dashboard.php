<?php include('header.php'); ?>
<?php include('../config.php'); ?>

<section class="main">
  <h1 id="main_title">VIEW EXPENSES</h1>
    <div class="container">

      <div class="box1">
        <h2>All Expenses Made With A Friend</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#friendModal">ADD EXPENSE</button>
      </div>
      <table class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original amount</th>
            <th>Amount to be paid</th>
            <th>Friend</th>
          </tr>
        </thead>
        <tbody>
          <?php

            $query = "SELECT * FROM `expenses` NATURAL JOIN `user_incurs_expense` NATURAL JOIN `befriends` WHERE `userid` = ".$_SESSION['user_id']."";
            $result = mysqli_query($mysqli, $query);

            if (!$result) {
              die("query failed".mysqli_error());
            } else {
              while($row = mysqli_fetch_assoc($result)){
                ?>
                  <tr>
                    <td><?php echo $row['expenseid'] ?></td>
                    <td><?php echo $row['expensename'] ?></td>
                    <td><?php echo $row['date_incurred'] ?></td>
                    <td><?php echo $row['original_amount'] ?></td>
                    <td><?php echo $row['amount'] ?></td>
                    <?php //get friendname using friendid
                      $friendid = $row['friendid'];
                      $friendName = "SELECT * FROM `users` WHERE `userid` = $friendid";
                      $friendResult = mysqli_query($mysqli, $friendName);
                    ?>
                    <td><?php 
                    $name = mysqli_fetch_assoc($friendResult);
                    echo $name['uname'];
                    ?></td>
                  </tr>
                <?php
              }
            }
          ?>
        </tbody>
    </table>
    
    <!-- get form validation message -->
    <?php
      if(isset($_GET['friend_message'])) {
        echo "<h6>".$_GET['friend_message']."</h6>";
      }
    ?>

    <!-- get insert successful message -->
    <?php
      if(isset($_GET['insert_msg'])) {
        echo "<h6>".$_GET['insert_msg']."</h6>";
      }
    ?>
  </div>

  <div class="container">
      <div class="box1">
        <h2>All Expenses Made With A Group</h2>
        <button class="btn btn-primary" id="2" data-bs-toggle="modal" data-bs-target="#groupModal">ADD EXPENSE</button>
      </div>
      <table class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original amount</th>
            <th>Amount to be paid</th>
            <th>Group</th>
          </tr>
        </thead>
        <tbody>
        <?php

          $query = "SELECT * FROM `expenses` NATURAL JOIN `is_member_of` WHERE `userid` = ".$_SESSION['user_id']."";
          $result = mysqli_query($mysqli, $query);

          if (!$result) {
            die("query failed".mysqli_error());
          } else {
            while($row = mysqli_fetch_assoc($result)){
              ?>
                <tr>
                  <td><?php echo $row['expenseid'] ?></td>
                  <td><?php echo $row['expensename'] ?></td>
                  <td><?php echo $row['date_incurred'] ?></td>
                  <td><?php echo $row['original_amount'] ?></td>
                  <td><?php echo $row['amount'] ?></td>
                  <?php 
                    $groupid = $row['groupid'];
                    $groupName = "SELECT * FROM `groups` WHERE `groupid` = $groupid";
                    $groupResult = mysqli_query($mysqli, $groupName);
                  ?>
                  <td><?php //get groupname using groupid
                    $name = mysqli_fetch_assoc($groupResult);
                    echo $name['groupname'];
                  ?></td>
                </tr>
              <?php
            }
          }
          ?>
        </tbody>
    </table>

    <!-- get form validation message -->
    <?php
      if(isset($_GET['group_message'])) {
        echo "<h6>".$_GET['group_message']."</h6>";
      }
    ?>
  </div>

  <!-- Friend Modal -->
  <form action="insert_expense_friend.php" method="post">
  <div class="modal fade" id="friendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add expense with a friend</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
              <label for="e_name">Expense name</label>
              <input type="text" name="e_name" class="form-control">
            </div>

            <div class="form-group">
              <label for="orig_amount">Original amount</label>
              <input type="number" name="orig_amount" class="form-control">
            </div>

            <!-- PAYER DROPDOWN -->
            <?php
              $queryNames = "SELECT u.userid, u.uname FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id']."";
              $resultNames = mysqli_query($mysqli, $queryNames);
            ?>

            <label for="f_payer_names">Select payer</label>
            <select class="form-select" aria-label="Default select example" name="f_payer_names">
              <?php
                $queryUsername = "SELECT * FROM `users` WHERE userid=".$_SESSION['user_id']."";
                $resultName = mysqli_query($mysqli, $queryUsername);
                
                //get username of current userid
                $username = mysqli_fetch_assoc($resultName);
                if ($resultNames->num_rows > 0) {
                  echo '<option value='.$username['userid'].'>' .$username['uname'] . '</option>';
                  while ($row = $resultNames->fetch_assoc()) {
                      echo '<option value='.$row['userid'].'>' . $row['uname'] . '</option>';
                  }
              }
              ?>
            </select>
            
            <!-- FRIEND DROPDOWN -->
            <?php
              $queryNames = "SELECT u.userid, u.uname FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id']."";
              $resultNames = mysqli_query($mysqli, $queryNames);
            ?>
            <label for="friend_names">Select friend</label>
            <select class="form-select" aria-label="Default select example" name="friend_names">
              <?php
                if ($resultNames->num_rows > 0) {
                  while ($row = $resultNames->fetch_assoc()) {
                      echo '<option value='.$row['userid'].'>' . $row['uname'] . '</option>';
                  }
              }
              ?>
            </select>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-success" name="add_expense_friend" value="Add expense">
        </div>
      </div>
    </div>
  </div>
  </form>

  <!-- Group Modal -->
  <form action="insert_expense_group.php" method="post">
  <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add expense with a group</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
              <label for="e_name">Expense name</label>
              <input type="text" name="g_e_name" class="form-control">
            </div>

            <div class="form-group">
              <label for="orig_amount">Original amount</label>
              <input type="number" name="g_orig_amount" class="form-control">
            </div>

            <!-- PAYER DROPDOWN -->
            <?php
              $queryNames = "SELECT u.userid, u.uname FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id']."";
              $resultNames = mysqli_query($mysqli, $queryNames);
            ?>

            <label for="g_payer_names">Select payer</label>
            <select class="form-select" name="g_payer_names">
              <?php
                $queryUsername = "SELECT * FROM `users` WHERE userid=".$_SESSION['user_id']."";
                $resultName = mysqli_query($mysqli, $queryUsername);
                
                //get username of current userid
                $username = mysqli_fetch_assoc($resultName);
                if ($resultNames->num_rows > 0) {
                  echo '<option value='.$username['userid'].'>' .$username['uname'] . '</option>';
                  while ($row = $resultNames->fetch_assoc()) {
                      echo '<option value='.$row['userid'].'>' .$row['uname']. '</option>';
                  }
              }
              ?>
            </select>

            <!-- GROUP DROPDOWN -->
            <?php
              $queryGroup = "SELECT * FROM is_member_of NATURAL JOIN groups WHERE userid = ".$_SESSION['user_id']."";
              $resultGroup = mysqli_query($mysqli, $queryGroup);
            ?>
            <label for="group_names">Select group</label>
            <select class="form-select" aria-label="Default select example" name="group_names">
              <?php
                if ($resultGroup->num_rows > 0) {
                  while ($row = $resultGroup->fetch_assoc()) {
                      echo '<option value='.$row['groupid'].'>' . $row['groupname'] . '</option>';
                  }
                }
                
              ?>
            </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-success" name="add_expense_friend" value="Add expense">
        </div>
      </div>
    </div>
  </div>
  </form>


</section>


<?php include('footer.php'); ?>