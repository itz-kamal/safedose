<?php

require_once '../../classes/db.php';
require_once '../../classes/user.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$token = $_POST['token'] ?? '';
$result = $user->getStaff($token);
$isSuperAdmin = $result['isSuperAdmin'] ?? false;

if (!$result['success']) {
    http_response_code(401);
    exit;
}

if (empty($result['data'])) {
    echo '<tr><td colspan="7" class="text-center py-4 text-muted">No staff members found.</td></tr>';
    exit;
}

foreach ($result['data'] as $index => $staff) {
    $isActive = $staff['status'] === 'active';
    $badgeClass = $isActive ? 'bg-success' : 'bg-secondary';
    $statusLabel = $isActive ? 'Active' : 'Inactive';
    $btnClass = $isActive ? 'btn-outline-danger' : 'btn-outline-success';
    $btnLabel = $isActive ? 'Deactivate' : 'Reactivate';
    $newStatus = $isActive ? 'inactive' : 'active';

    echo '<tr id="staff-row-' . $staff['id'] . '">';
    echo '<td class="px-4">' . ($index + 1) . '</td>';
    echo '<td>' . htmlspecialchars($staff['name']) . '</td>';
    echo '<td>' . htmlspecialchars($staff['email']) . '</td>';
    echo '<td>' . htmlspecialchars($staff['phone']) . '</td>';
    $isStaff = $staff['role'] === 'staff';
    $roleBadgeClass = $isStaff ? 'bg-info text-dark' : 'bg-warning text-dark';
    $roleLabel = $isStaff ? 'Staff' : 'Admin';
    $roleBtnClass = $isStaff ? 'btn-outline-primary' : 'btn-outline-secondary';
    $roleBtnLabel = $isStaff ? 'Promote to Admin' : 'Demote to Staff';
    $newRole = $isStaff ? 'admin' : 'staff';

    echo '<td><span class="badge ' . $roleBadgeClass . '">' . $roleLabel . '</span></td>';
    echo '<td id="status-' . $staff['id'] . '"><span class="badge ' . $badgeClass . '">' . $statusLabel . '</span></td>';
    echo '<td>';
    if ($staff['role'] === 'staff') {
        echo '<button class="btn btn-sm ' . $btnClass . ' status-btn me-2" data-id="' . $staff['id'] . '" data-status="' . $newStatus . '">' . $btnLabel . '</button>';
    }
    if ($isSuperAdmin) {
        echo '<button class="btn btn-sm ' . $roleBtnClass . ' role-btn" data-id="' . $staff['id'] . '" data-role="' . $newRole . '">' . $roleBtnLabel . '</button>';
    }
    echo '</td>';
    echo '</tr>';
}
