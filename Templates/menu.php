<div class="centerDiv" id="menu">
    <ul>
    <?php
    foreach ($pages as $id => $page){
        echo "<li><a href='?page={$id}'>" . $page['name'] . "</a></li>";

    }




    ?>

    </ul>
</div>
