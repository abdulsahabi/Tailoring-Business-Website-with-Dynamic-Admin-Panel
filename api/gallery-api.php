<?php
$items = [];
for ($i = 1; $i <= 55; $i++) {
  $items[] = [
    'title' => "Image Title $i",
    'src' => "../../assets/images/cover.jpg",
    'date' => '2025-07-' . str_pad(($i % 30) + 1, 2, '0', STR_PAD_LEFT)
  ];
}

$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 5;

$filtered = array_filter($items, function ($item) use ($search) {
  return stripos($item['title'], $search) !== false;
});

$total = count($filtered);
$paged = array_slice(array_values($filtered), ($page - 1) * $perPage, $perPage);

header('Content-Type: application/json');
echo json_encode([
  'items' => $paged,
  'total' => $total
]);