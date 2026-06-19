<?php

$notif_admin_items = [];
$unread_admin_count = 0;

if (isset($conn)) {
    $stmt_count = $conn->prepare("SELECT COUNT(id) as jml FROM reports WHERE status = 'Menunggu' AND is_admin_read = 0");
    $stmt_count->execute();
    $unread_admin_count = $stmt_count->get_result()->fetch_assoc()['jml'];
    $stmt_count->close();

    $stmt_notif = $conn->prepare("SELECT id, judul_laporan, created_at, is_admin_read FROM reports WHERE status = 'Menunggu' ORDER BY created_at DESC LIMIT 5");
    $stmt_notif->execute();
    $res_notif = $stmt_notif->get_result();
    while ($row = $res_notif->fetch_assoc()) {
        $notif_admin_items[] = $row;
    }
    $stmt_notif->close();
}

if (!function_exists('time_ago_notif')) {
    function time_ago_notif(string $datetime): string
    {
        $diff = time() - strtotime($datetime);
        if ($diff < 60) return "Baru saja";
        if ($diff < 3600) return floor($diff / 60) . " mnt lalu";
        if ($diff < 86400) return floor($diff / 3600) . " jam lalu";
        return floor($diff / 86400) . " hari lalu";
    }
}
