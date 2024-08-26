  <!-- PAGINATION DIV -->
  <div class=" paginationNum row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3">
      <div>
          <?php if ($page > 1) : ?>
              <a href="?page=<?php echo $page - 1; ?>" style="color:black" class="btn btn-orange">&laquo; Previous</a>
          <?php endif; ?>
      </div>

      <div style="text-align: center;"><?php for ($i = 1; $i <= $totalPages; $i++) : ?>
              <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?> btn btn-orange" style="color:black" class="">
                  <?php echo $i; ?>
              </a>
          <?php endfor; ?>
      </div>

      <div style="text-align: right;">
          <?php if ($page < $totalPages) : ?>
              <a href="?page=<?php echo $page + 1; ?>" style="color:black" class="btn btn-orange">Next &raquo;</a>
          <?php endif; ?>
      </div>
  </div>
  <!-- END OF PAGINATION DIV -->