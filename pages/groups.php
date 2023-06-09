<?php include('header.php'); ?>
<?php include('../config.php'); ?>

<section class="main">
  <h1 id="main_title">GROUPS</h1>
  <div class="container">
    <h2>List of Groups</h2>

    <div>
      <input id="grouplistSearchInput" type="search" class="form-control rounded" placeholder="Search a group" aria-label="Search" aria-describedby="search-addon" />
    </div>
    <table id="grouplistTable" class="table table-hover table-bordered table-str">
      <thead>
        <tr>
          <th>Group Name</th>
          <th>Member Count</th>
          <th>Group Members</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $userId = $_SESSION['user_id'];

          $query = "SELECT * FROM `groups` JOIN `is_member_of` ON `groups`.`groupid` = `is_member_of`.`groupid` WHERE `is_member_of`.`userid` = $userId";
          $result = mysqli_query($mysqli, $query);

          if (!$result) {
            die("Query failed: " . mysqli_error($mysqli));
          } else {
            while ($row = mysqli_fetch_assoc($result)) {
              $groupId = $row['groupid'];
              $groupName = $row['groupname'];
              $memberCount = $row['member_count'];

              // Get group members
              $groupMembersQuery = "SELECT `users`.`uname` FROM `users` JOIN `is_member_of` ON `users`.`userid` = `is_member_of`.`userid` WHERE `is_member_of`.`groupid` = $groupId AND `is_member_of`.`userid` != $userId";
              $groupMembersResult = mysqli_query($mysqli, $groupMembersQuery);

              if (!$groupMembersResult) {
                die("Query failed: " . mysqli_error($mysqli));
              }

              $groupMembers = array();
              while ($memberRow = mysqli_fetch_assoc($groupMembersResult)) {
                $groupMembers[] = $memberRow['uname'];
              }

              ?>
              <tr>
                <td><?php echo $groupName; ?></td>
                <td><?php echo $memberCount; ?></td>
                <td>
                  <?php
                    if ($memberCount == 1) {
                      echo "You are the only member";
                    } else {
                      echo "<ul>";
                      foreach ($groupMembers as $member) {
                        echo "<li>" . $member . "</li>";
                      }
                      echo "</ul>";
                    }
                  ?>
                </td>
                <td>
                  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $groupId; ?>">Edit</button>

                  <?php if ($memberCount == 1): ?>
                    <form method="post" action="../backend/delete_group.php">
                      <input type="hidden" name="groupid" value="<?php echo $groupId; ?>" />
                      <button type="submit" class="btn btn-danger">Delete Group</button>
                    </form>
                  <?php else: ?>
                    <form method="post" action="../backend/leave_group.php">
                      <input type="hidden" name="groupid" value="<?php echo $groupId; ?>" />
                      <button type="submit" class="btn btn-danger">Leave Group</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
              <?php include('../modals/modal_editGroup.php'); ?>
              <?php
            }
          }
        ?>
      </tbody>
    </table>

    <!-- Create Group Button -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#groupModal">Create Group</button>
  </div>
</section>

<!-- Create Group Modal -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php include('../modals/modal_insertGroup.php'); ?>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>
