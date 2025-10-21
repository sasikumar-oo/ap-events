            </main>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom scripts -->
    <script>
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Enable popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        // Initialize DataTables
        $(document).ready(function() {
            $('#dataTable').DataTable({
                responsive: true,
                order: [],
                pageLength: 25,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries found",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
            });
            
            // Initialize Summernote
            $('.summernote').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            
            // Toggle sidebar
            $('[data-bs-toggle="offcanvas"]').on('click', function () {
                $('.sidebar').toggleClass('active');
            });
            
            // Confirm before delete
            $('.confirm-delete').on('click', function() {
                return confirm('Are you sure you want to delete this item? This action cannot be undone.');
            });
        });
        
        // Show loading state on form submission
        $('form').on('submit', function() {
            var $btn = $(this).find('button[type="submit"]');
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        });
        
        // Toggle password visibility
        $('.toggle-password').on('click', function() {
            var input = $($(this).attr('toggle'));
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    </script>
    
    <!-- Custom scripts for specific pages -->
    <?php if (file_exists('assets/js/' . basename($_SERVER['PHP_SELF'], '.php') . '.js')): ?>
        <script src="assets/js/<?php echo basename($_SERVER['PHP_SELF'], '.php'); ?>.js"></script>
    <?php endif; ?>
</body>
</html>
