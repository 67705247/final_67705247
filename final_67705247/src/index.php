<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดการข้อมูลสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">รายชื่อสมาชิก</h4>
                <button class="btn btn-light btn-sm" onclick="showAddModal()">+ เพิ่มสมาชิก</button>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>คณะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="memberTable">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="memberModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="memberForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">เพิ่มข้อมูลสมาชิก</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="member_id_pk">
                    <div class="mb-3">
                        <label>รหัสนักศึกษา/บุคลากร</label>
                        <input type="text" name="member_id" id="member_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname" id="fullname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>คณะต้นสังกัด</label>
                        <input type="text" name="faculty" id="faculty" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            loadMembers();

            $('#memberForm').submit(function(e) {
                e.preventDefault();
                const action = $('#member_id_pk').val() ? 'update' : 'insert';
                $.ajax({
                    url: 'action/member_action.php?action=' + action,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        Swal.fire('สำเร็จ!', 'ดำเนินการเรียบร้อย', 'success');
                        $('#memberModal').modal('hide');
                        loadMembers();
                    }
                });
            });
        });

        function loadMembers() {
            $.get('action/member_action.php?action=fetch', function(data) {
                let rows = '';
                JSON.parse(data).forEach(m => {
                    rows += `<tr>
                        <td>${m.member_id}</td>
                        <td>${m.fullname}</td>
                        <td>${m.faculty}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick='editMember(${JSON.stringify(m)})'>แก้ไข</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteMember(${m.id})">ลบ</button>
                        </td>
                    </tr>`;
                });
                $('#memberTable').html(rows);
            });
        }

        function showAddModal() {
            $('#memberForm')[0].reset();
            $('#member_id_pk').val('');
            $('#modalTitle').text('เพิ่มข้อมูลสมาชิก');
            $('#memberModal').modal('show');
        }

        function editMember(data) {
            $('#member_id_pk').val(data.id);
            $('#member_id').val(data.member_id);
            $('#fullname').val(data.fullname);
            $('#faculty').val(data.faculty);
            $('#modalTitle').text('แก้ไขข้อมูลสมาชิก');
            $('#memberModal').modal('show');
        }

        function deleteMember(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "คุณจะไม่สามารถกู้คืนข้อมูลนี้ได้",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get('action/member_action.php?action=delete&id=' + id, function() {
                        loadMembers();
                        Swal.fire('ลบแล้ว!', 'ข้อมูลถูกลบออกจากระบบ', 'success');
                    });
                }
            });
        }
    </script>
</body>
</html>