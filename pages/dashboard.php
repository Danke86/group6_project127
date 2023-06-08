<?php include('header.php'); ?>
<?php include('../config.php'); ?>


<section class="main">
  <h1 id="main_title">VIEW EXPENSES</h1>
    <div class="container">
      <h2>All Expenses Made With A Friend</h2>
      <table class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original Amount</th>
            <th>Amount to be payed</th>
            <th>Friend</th>
          </tr>
        </thead>
        <tbody>
          <?php

            $query = "SELECT * FROM `expenses` NATURAL JOIN `user_incurs_expense` NATURAL JOIN `befriends` WHERE `userid` = ".$_SESSION['user_id'].""; //change 001 to $_SESSION[userid]
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
  </div>
  <div class="container">
      <h2>All Expenses Made With A Group</h2>
      <table class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original Amount</th>
            <th>Amount to be payed</th>
            <th>Group</th>
          </tr>
        </thead>
        <tbody>
        <?php

          $query = "SELECT * FROM `expenses` NATURAL JOIN `is_member_of` WHERE `userid` = ".$_SESSION['user_id'].""; //change 001 to $_SESSION[userid]
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
                  <td><?php 
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
  </div>
</section>

<?php include('footer.php'); ?>