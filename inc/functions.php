<?php
define("DB_NAME", "E:\\Learn Files\\php-course\\৮ - CRUD প্রজেক্ট\\curd\\data\\db.text");  // Use double backslashes in paths

function seed(){
    $data = array(
        array("id" => 1, "fname" => "Kamal", "lname" => "Ahmed", "roll" => "11"),
        array("id" => 2, "fname" => "Jamal", "lname" => "Ahmed", "roll" => "12"),
        array("id" => 3, "fname" => "Ripon", "lname" => "Ahmed", "roll" => "9"),
        array("id" => 4, "fname" => "Nikhil", "lname" => "Ahmed", "roll" => "8"),
        array("id" => 5, "fname" => "John", "lname" => "Rozario", "roll" => "7"),
    );

    $serializedData = serialize($data);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX); 
}

function generateReport(){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    ?>
    <table>
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Roll</th>
            <th>Action</th>
           
        </tr>
        <?php foreach($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student["id"]); ?></td> <!-- ✅ Simpler output -->
                <td><?php printf("%s %s", $student["fname"], $student["lname"]); ?></td> <!-- ✅ Fixed: %s instead of $s -->
                <td><?php echo htmlspecialchars($student["roll"]); ?></td> <!-- ✅ Simpler output -->
                
                <td>
                    <a href="/curd/index.php?task=edit&id=<?php echo $student['id']; ?>">Edit</a> |
                    <a class="delete" href="/curd/index.php?task=delete&id=<?php echo $student['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

function addStudent($fname , $lname , $roll){
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    if (!is_array($students)) {
        $students = [];
    }
    foreach($students as $student){
        if($student['roll'] == $roll){
            $found = true;
            break;
        }
    }
    if(!$found){
        $newId = getNewId($students);
        $student = array(
            "id" => $newId,
            "fname" => $fname,
            "lname" => $lname,
            "roll" => $roll
        );
        array_push($students , $student);
        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX); 
        return true;
    }
    return false;
}

function getStudent($id){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    foreach($students as $student){
        if($student['id'] == $id){
            return $student;
        }
    }
    return false;

} 

function updateStudent($id, $fname, $lname, $roll) {
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    foreach($students as $student){
        if($student["roll"] == $roll & $student["id"] != $id){
            $found = true;
            break;
        }
    }
    if(!$found){
        $students[$id - 1] ["fname"] = $fname;
        $students[$id - 1] ["lname"] = $lname;
        $students[$id - 1] ["roll"] = $roll;
        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX); 
        return true;
    }

    return false;
}

function deleteStudent($id){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    foreach($students as $key => $student){
        if($student["id"] == $id){
            unset($students[$key]);
            break; // stop after deleting
        }
    }

    // Reindex the array to keep keys clean
    $students = array_values($students);

    $serializedData = serialize($students);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX); 
}

function printRaw(){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    print_r($students);
}

function getNewId($students){
    $maxId = max(array_column($students , "id"));
    return $maxId+1;
}


?>
