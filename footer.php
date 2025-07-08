<!-- Footer Section -->
 <style>
.foot {
  bottom: 0;
  left: 0;
  color:#999;
  padding: 20px 30px;
  right: 0;
  background: #fff;
  border-top: 1px solid #eef1f2;

 
}
  </style>
</div> <!-- close #content-wrapper -->


 <!-- FOOTER -->
    <footer class="foot bg-primary text-inverse text-center mt-4" >
        <div class="container"><span class="fs-13 heading-font-family">Copyrights © 2025 Medha Tech. All Rights Reserved. </span>
        </div>
        <!-- /.container -->
    </footer>
    </div>
</div> <!-- close #wrapper -->

<!-- Core Scripts (Globally required) -->
  <script src="assets/js/template.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
    // Ensure modals in tabbed content behave correctly
    $(document).on('shown.bs.tab', function () {
        $('.modal').each(function () {
            $(this).appendTo('body');
        });
    });

    // Also fix it on page load (in case active tab loads them)
    $(function () {
        $('.modal').each(function () {
            $(this).appendTo('body');
        });
    });
</script>

</body>
</html>
