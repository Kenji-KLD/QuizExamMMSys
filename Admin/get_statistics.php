<?php
// Sample data for statistics (replace with actual data when available)
$data = [
    [
        'acadYear' => '2024-2025',
        'acadTerm' => 'Midterm',
        'subject_ID' => 'MAT101',
        'passed_count' => 1,
        'failed_count' => 1,
        'highest_score' => 85,
        'lowest_score' => 60,
        'lowest_unanswered' => 2
    ],
    [
        'acadYear' => '2024-2025',
        'acadTerm' => 'Final term',
        'subject_ID' => 'PHY201',
        'passed_count' => 2,
        'failed_count' => 0,
        'highest_score' => 90,
        'lowest_score' => 75,
        'lowest_unanswered' => 3
    ]
];

// Output the sample data as JSON (simulating database query result)
echo json_encode($data);
?>
