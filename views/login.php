<?php include('layout/header.php'); ?>
    <?php error_reporting(0); ?>
    <!-- Jumbotron -->
    <div class="container section">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center">Login Page</h5>

              <?php if(isset($_GET['pesan'])){ ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_GET['pesan']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
              <?php } ?>
              <?php if(isset($_GET['pesanError'])){ ?> 
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?= $_GET['pesanError']; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php } ?>

              <form action="../controllers/login.php" method="post">
                <div class="mt-4">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="mb-3">
                        <!-- <input type="email" class="form-control" id="email" name="email" placeholder="Email"> -->
                        <div class="form-floating">
                          <input type="email" class="form-control" name="email" id="email" placeholder="Emmail">
                          <label for="floatingTextarea">Email</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="mb-3">
                        <!-- <input type="password" class="form-control" id="password" name="password" placeholder="Password"> -->
                        <div class="form-floating">
                          <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                          <label for="floatingTextarea">Password</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary col-sm-12 mt-4">Login</button>
                </div>
                <div class="mt-3 text-center">
                  <a href="home_page.php">Back to home</a>
                </div>
              </form>

            </div>
          </div>
        </div>
        <div class="col-sm-4"></div>
      </div>
    </div>
    <!-- End Jumbotron -->
    

<?php include('layout/footer.php'); ?>
