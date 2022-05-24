<?php require_once '../utils/is_logged_in.php'; ?>

<style>
    #navbar {
        width: 100%;
        background-color: gray;
    }
    
    #navbar ul {
        margin: 0;
        padding: 1%;
        list-style-type: none;
        justify-content: space-evenly;
    }

    #navbar li {
        display: inline-block;
    }

    #navbar p {
        width: fit-content;
        margin: 0;
        margin-left: auto;
    }
    
    .link {
        padding: 2%;
    }

    .link:hover {
        background-color: cyan;
        outline: 2px solid green;
    }

</style>

<div id="navbar">
    <ul>
        <li>
            <a class="link" href="/">Home</a>
        </li>

        <?php if(!is_logged_in()): ?>
        <li>
            <a class="link" href="/login.php">Login</a>
        </li>
        <li>
            <a class="link" href="/signup.php">Signup</a>
        </li>

        <?php else: ?>
        <li>
            <p>Welcome <?php echo $_SESSION['username']; ?>!</p>
        </li>
        <?php endif; ?>
    </ul>
</div>