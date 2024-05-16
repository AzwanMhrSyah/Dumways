<?php
  session_start();
  error_reporting(0);
  if($_SESSION['status'] != "login"){
?>

 <!-- Js -->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <script type="text/javascript" src="../assets/js/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>

<?php } else { ?>

<!-- Js -->
    <script src="../../assets/js/sb-admin-2.min.js"></script>
    
    <script type="text/javascript" src="../../assets/js/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script src="../../assets/datatables/jquery.dataTables.min.js"></script>
    <script src="../../assets/datatables/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        $(document).ready( function () {
            $('#myTable').DataTable();
        });
    </script>

  </body>
</html>

<?php } ?>