

<div class="container">
  <div class="row">
    <div class="col-12" style="height: 8vh;">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb ">
          <li class="breadcrumb-item active crumb" aria-current="page"><a href="index.php" class="breadlink"> Home</a></li>
          <?php foreach($crumbs as $crumb) { ?>
          <li class="breadcrumb-item active crumb" aria-current="page"><?= $crumb ?></li>
          <?php } ?>
        </ol>
      </nav>
    </div>
  </div>
</div>
