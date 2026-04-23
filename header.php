<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="assets/img/logo-zo.png" rel="shortcut icon">
    <title>ZSQL Zahir Online</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/codemirror.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.12/mode/sql/sql.min.js"></script>

    <style>
        body { 
            background-color: #ecedeb; /* Warna desktop klasik */
            font-family: 'Tahoma', sans-serif; 
            margin: 0; padding: 20px; 
        }

        /* Container Bergaya Window Retro */
        .window-pane {
            background: #c0c0c0;
            border: 2px solid;
            border-color: #fff #808080 #808080 #fff;
            padding:10px;
            margin-bottom: 5px;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.3);
        }

        .window-header {
            background: linear-gradient(90deg, #000080, #1084d0);
            color: white; padding: 3px 10px; margin: -10px -10px 10px -10px;
            font-weight: bold; font-size: 13px;
        }

        /* POWERSHELL EDITOR */
        .CodeMirror { 
            height: 100px; border: 2px inset #808080;
            background: #012456 !important; font-family: 'Consolas', monospace; font-size: 16px;
        }
        
        /* FIX KURSOR: Warna putih agar terlihat di bg biru */
        .CodeMirror-cursor { border-left: 2px solid #ffffff !important; }

        /* Syntax Color */
        .cm-s-default.CodeMirror { color: #ffffff !important; }
        .cm-s-default .cm-keyword { color: #ffff00 !important; font-weight: bold; }
        .cm-s-default .cm-string { color: #00ff00 !important; }
        .CodeMirror-gutters { background: #012456 !important; border-right: 2px solid #005a9e; }
        .CodeMirror-linenumber { color: #5fb6ff !important; }

        /* FIX STICKY HEADER - INI KUNCINYA */
        .query-result-scroll {
            max-height: 350px; /* Batasi tinggi agar scroll muncul */
            overflow: auto;
            background: white;
            border: 2px inset #808080;
            position: relative; /* Wajib untuk sticky child */
        }

        table { width: 100%; border-collapse: collapse; }
        
        /* Memaksa elemen THEAD menempel di atas kontainer scroll */
        .query-result-scroll table thead th,
        thead {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 999;
            background-color: #027FB3 !important;
            color: white !important;
            padding: 10px;
            border: 1px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            text-transform: uppercase;
            font-size: 12px;
        }

        table td {
			    padding: 8px 12px;
			    border: 1px solid #dfdfdf;
			    white-space: nowrap;     /* Data tidak akan terpotong/turun baris */
			}

			/* Warna saat baris diklik (Selected) */
			table tr.selected-row td {
			    background-color: #ffffca !important; /* Kuning pucat khas highlighter */
			    color: #000 !important;
			    border-bottom: 1px solid #ffcc00;
			}

			/* Efek hover agar user tahu baris bisa diklik */
			table tr:hover td {
			    cursor: pointer;
			    background-color: #f0f0f0;
			}

        .retro-input { border: 2px inset #fff; padding: 5px; width: 250px; }
        .btn-retro { 
            background: #c0c0c0; border: 2px solid; border-color: #fff #808080 #808080 #fff;
            padding: 5px 15px; cursor: pointer; font-weight: bold;
        }
        .btn-retro:active { border-color: #808080 #fff #fff #808080; }
    </style>
</head>
<body>

<?php 
// Logika PHP dari kode asli Anda (Disesuaikan)
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$bearer = isset($_GET['bearer']) ? $_GET['bearer'] : '';
$query = isset($_GET['query']) ? $_GET['query'] : '';
$key = isset($_GET['key']) ? $_GET['key'] : 'query';

if (isset($_GET['bearer']) && isset($_GET['slug'])) {
    $radio1 = ($key == 'query') ? 'checked' : '';
    $radio2 = ($key == 'statement') ? 'checked' : '';
    $button_ex = '';
    $txt_area = '';
} else {
    $radio1 = 'disabled';
    $radio2 = 'disabled';
    $button_ex = 'disabled';
    $txt_area = 'disabled';
}
?>

<div style="text-align:center; margin-bottom:15px;">
    <a href="./">
        <img src="assets/img/logo-zahir-online.png" style="max-width: 180px;">
    </a>
</div>

<div class="window-pane">
    <div class="window-header">Connect_API</div>
    <form action="" method="get">
        <input type="text" name="slug" class="retro-input" placeholder="Input slug" value="<?= $slug ?>" required/>
        <input type="text" name="bearer" class="retro-input" placeholder="Input Authorization" value="<?= $bearer ?>" required/>
        <input type="hidden" name="query" value=""/>
        <input type="hidden" name="key" value="query">
        <input type="submit" class="btn-retro" value="Submit">
    </form>
</div>

<div class="window-pane">
    <div class="window-header">SQL_Editor</div>
    <form id="sql-form" action="" method="get">
        <input type="radio" name="key" value="query" <?= $radio1 ?> required/> SQL Query &nbsp;
        <input type="radio" name="key" value="statement" <?= $radio2 ?> required/> SQL Script
        <br><br>
        <input type="hidden" name="slug" value="<?= $slug ?>"/>
        <input type="hidden" name="bearer" value="<?= $bearer ?>"/> 
        
        <textarea id="sql-code" name="query" <?= $txt_area ?>><?php echo $query; ?></textarea> 
        
        <div style="margin-top:10px">
            <input type="submit" class="btn-retro" style="color:darkgreen;" value="Execute [F9]" <?= $button_ex ?>>
        </div>
    </form>
</div>