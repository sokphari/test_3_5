<?php
    include "config.php";
    $sql = "SELECT * FROM `users`";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>***</td>
                <td>
                    <button class="btn btn-primary btn-sm editBtn" 
                    data-id="<?php echo $row['id']; ?>
                    data-name="<?php echo $row['name']; ?>
                    data-gender="<?php echo $row['gender'];  ?>
                    data-email="<?php echo $row['email']; ?>
                    data-password="<?php echo $row['password'];  ?>
                    ">Edit</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $row['id']; ?>">Delete</button>
                </td>
            </tr>
            <?php
        }
    }