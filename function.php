<?php
if (!empty($slug) && !empty($bearer) && !empty($query)) {
    // Pecah query berdasarkan titik koma (;)
    $queries = explode(';', $query);
    $last_response = '';

    echo '<div class="window-pane">';
    echo '<div class="window-header">Execution_Log</div>';
    echo '<div style="padding:10px; font-family:monospace; font-size:12px; background:#000; color:#0f0; max-height:150px; overflow:auto;">';

foreach ($queries as $index => $single_query) {
    $single_query = trim($single_query);
    if (empty($single_query)) continue;

    $curl = curl_init();
    
    // Pastikan headers lengkap
    $headersJSON = array(
        'Content-Type: application/json',
        'Authorization: ' . trim($bearer),
        'slug: ' . trim($slug),
        'Accept: application/json'
    );

    $cleanQ = trim(preg_replace('/\s+/', ' ', $single_query));
    
    // Gunakan JSON_UNESCAPED_SLASHES agar karakter aman
    $payload = json_encode(array($key => $cleanQ), JSON_UNESCAPED_SLASHES);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://go.zahironline.com/api/v2/zsql?is_show_as_table=true',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => $headersJSON,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false // Hindari masalah sertifikat SSL di beberapa server lokal
    ));

    $res = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);
    curl_close($curl);

    $res_data = json_decode($res, true);
    
    if ($http_code == 200 || $http_code == 201) {
        $status = '<span style="color:#00ff00;">SUCCESS</span>';
        $msg = "";
    } else {
        $status = '<span style="color:#ff5f5f;">FAILED</span>';
        // Cek apakah error dari cURL atau dari API
        if ($curl_error) {
            $msg = " - cURL Error: " . $curl_error;
        } else {
            // Ambil detail error jika ada di 'message' atau 'error'
            $detail = isset($res_data['message']) ? $res_data['message'] : (isset($res_data['error']) ? $res_data['error'] : $res);
            $msg = " - HTTP $http_code: " . (is_array($detail) ? json_encode($detail) : substr(strip_tags($detail), 0, 100));
        }
    }

    echo "[Line ".($index+1)."] Executing... " . htmlspecialchars(substr($cleanQ, 0, 50)) . "... <strong>$status</strong>$msg<br>";
    $last_response = $res;
}
    
    echo '</div></div>';

    // Tampilkan Hasil Terakhir (Tabel)
    echo '<div class="window-pane">';
    echo '<div class="window-header">Output_Result</div>';
    echo '<div class="query-result-scroll">' . $last_response . '</div>';
    echo '</div>';

    $button = ($key == 'query') ? '' : 'disabled';
} else if (isset($_GET['slug']) && empty($_GET['query'])) {
    echo '<div class="window-pane">Please input query ...</div>';
    $button = 'disabled';
} else {
    $button = 'disabled';
}
?>