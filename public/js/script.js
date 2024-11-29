document.addEventListener('DOMContentLoaded', function () {
    const admin = document.getElementById('admin');
    const staff = document.getElementById('staff');
    const formAdmin = document.getElementById('form-add-admin');

    admin.addEventListener('change', function () {
        if (admin.checked) {
            formAdmin.style.display = 'block';
        }
    });

    staff.addEventListener('change', function () {
        if (staff.checked) {
            formAdmin.style.display = 'none';
        }
    });
});