<?php include 'function.php'; ?>
<br>

<div style="text-align: right;">
    <button id="download-button" class="btn-retro" <?php echo $button; ?>>💾 Download CSV</button>
</div>

<script type="text/javascript" src="style/js/export.js"></script>

<script>
    // Inisialisasi CodeMirror
    var editor = CodeMirror.fromTextArea(document.getElementById("sql-code"), {
        mode: "text/x-sql",
        lineNumbers: true,
        indentWithTabs: true,
        smartIndent: true,
        autofocus: true,
        matchBrackets: true,
        cursorHeight: 0.85
    });

    // Handle F9 & Form Submit (Normal Submit, No Fetch)
    document.addEventListener('keydown', function(event) {
        if (event.key === 'F9' || event.keyCode === 120) {
            event.preventDefault();
            editor.save(); 
            document.getElementById('sql-form').submit();
        }
    });

    // Fitur Klik Baris untuk Highlight
    document.addEventListener('click', function (e) {
        const row = e.target.closest('table tr');
        if (row && !row.closest('thead')) {
            row.classList.toggle('selected-row');
        }
    });
</script>
</body>
</html>