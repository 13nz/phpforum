
<nav aria-label="Page navigation example" class="my-4 pag">
  <ul class="pagination justify-content-end">

    <?php
      $numrows = getCount($numSQL);
      $perpage = 10;
      $pages = ceil($numrows / 10);


      if (isset($_GET['page'])) {
        $page = (int) $_GET['page'];
      } else {
         $page = 1;
      };

      $page = (int)$page;
      if ($page > $pages) {
         $page = $pages;
      }
      if ($page < 1) {
         $page = 1;
      }
      if($pages > 1)
      {if ($page != 1) {

        $prev = $page-1;
        if($pages > 2 && $page > 2) {
         ?>
        <li class="page-item ">
          <a class="page-link catlink" href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $id ?>&page=1" tabindex="-1">First</a>
        </li>
      <?php } ?>


         <li class="page-item ">
           <a class="page-link catlink" href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $id ?>&page=<?= $prev ?>" tabindex="-1">Prev</a>
         </li>
      <?php } ?>

      <li class="page-item disabled">
        <a class="page-link catlink" href="" tabindex="-1">Page <?= $page  ?> of <?= $pages ?></a>
      </li>

      <?php
      if ($page != $pages) {
           $next = $page+1;
           ?>
           <li class="page-item ">
             <a class="page-link catlink" href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $id ?>&page=<?= $next ?>" tabindex="-1">Next</a>
           </li>
           <?php if($pages > 2 && $page < ($pages - 1)) { ?>
           <li class="page-item ">
             <a class="page-link catlink" href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $id ?>&page=<?= $pages ?>" tabindex="-1">Last</a>
           </li>
        <?php }}} ?>

    </ul>
  </nav>
</div>
