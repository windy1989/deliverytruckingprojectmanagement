            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">
                        Copyright &copy; {{ date('Y') }}
                        <a href="javascript:void(0)">Digitrans</a>, All rights reserved.
                    </p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Made By Hiro Solution</p>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('template/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('template/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/app.js') }}"></script>
    <script>$(function() {App.init();});</script>
    <script src="{{ asset('template/plugins/highlight/highlight.pack.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom.js') }}"></script>
    <script src="{{ asset('template/assets/js/apps/invoice.js') }}"></script>
    <script>
        $(function() {
            var success_login = '{{ session("success_login") }}';
            if(success_login) {
                swal('Berhasil', success_login, 'success');
            }
        });

        function logout() {
            swal({
                title: 'Anda yakin ingin keluar?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, keluar!',
                cancelButtonText: 'Batal!',
                reverseButtons: true,
                padding: '2em'
            }).then(function(result) {
                if(result.value) {
                    location.href = '{{ url("logout") }}';
                }
            });
        }
    </script>
</body>
</html>
