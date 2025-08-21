<div>
    <div class="float-left">
        <p>
            <a href="/curd/index.php?task=report">All Students |</a>
            <a href="/curd/index.php?task=add">Add New Student |</a>
            <a href="/curd/index.php?task=seed">Seed</a> 
        </p>
    </div>
    <div class="float-right">
        <p>
            <?php if(!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]): ?>
                <a href="/curd/login.php">Log In</a>
            <?php else: ?>
                <a href="/curd/login.php?logout=true">Log Out</a>
            <?php endif; ?>
        </p>
    </div>
</div>