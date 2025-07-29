<!DOCTYPE html>
<html>
<head>
    <title>PHP File Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f8f9fa; 
            color: #333;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { 
            background: linear-gradient(135deg, #007cba, #0056b3); 
            color: white; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header h1 { margin: 0; font-size: 28px; }
        .breadcrumb { 
            background: white; 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .breadcrumb a { color: #007cba; text-decoration: none; margin-right: 5px; }
        .breadcrumb a:hover { text-decoration: underline; }
        .actions { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .actions h3 { margin-top: 0; color: #333; }
        .action-group { display: inline-block; margin-right: 20px; margin-bottom: 15px; }
        .action-group input, .action-group button { 
            padding: 8px 12px; 
            margin: 2px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
        }
        .action-group button { 
            background: #007cba; 
            color: white; 
            cursor: pointer; 
            border: none;
        }
        .action-group button:hover { background: #0056b3; }
        .file-list { 
            background: white; 
            border-radius: 8px; 
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .file-item { 
            display: flex; 
            align-items: center; 
            padding: 12px 20px; 
            border-bottom: 1px solid #eee; 
            transition: background-color 0.2s;
        }
        .file-item:hover { background: #f8f9fa; }
        .file-item.selected { background: #e3f2fd; }
        .file-item:last-child { border-bottom: none; }
        .file-checkbox { margin-right: 12px; }
        .file-checkbox input[type="checkbox"] { 
            width: 18px; 
            height: 18px; 
            cursor: pointer; 
        }
        .file-icon { font-size: 20px; margin-right: 12px; min-width: 30px; }
        .file-name { flex: 1; }
        .file-name a { color: #333; text-decoration: none; font-weight: 500; }
        .file-name a:hover { color: #007cba; }
        .file-size { color: #666; margin-right: 15px; font-size: 14px; }
        .file-actions { display: flex; gap: 8px; }
        .file-actions a, .file-actions button { 
            padding: 4px 8px; 
            font-size: 12px; 
            border-radius: 3px; 
            text-decoration: none; 
            border: none; 
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-edit { background: #28a745; color: white; }
        .btn-download { background: #17a2b8; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-rename { background: #ffc107; color: #333; }
        .btn-archive { background: #6f42c1; color: white; }
        .btn-unarchive { background: #20c997; color: white; }
        .btn-copy { background: #17a2b8; color: white; }
        .btn-move { background: #fd7e14; color: white; }
        .btn-edit:hover { background: #218838; }
        .btn-download:hover { background: #138496; }
        .btn-delete:hover { background: #c82333; }
        .btn-rename:hover { background: #e0a800; }
        .btn-archive:hover { background: #5a32a3; }
        .btn-unarchive:hover { background: #1aa179; }
        .btn-copy:hover { background: #138496; }
        .btn-move:hover { background: #e8590c; }
        .bulk-actions { 
            background: white; 
            padding: 15px 20px; 
            border-radius: 8px; 
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: none;
        }
        .bulk-actions.show { display: block; }
        .bulk-actions h4 { margin: 0 0 10px 0; color: #333; }
        .bulk-btn { 
            padding: 8px 15px; 
            margin: 5px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            font-size: 14px;
        }
        .bulk-btn-delete { background: #dc3545; color: white; }
        .bulk-btn-archive { background: #6f42c1; color: white; }
        .bulk-btn-copy { background: #17a2b8; color: white; }
        .bulk-btn-move { background: #fd7e14; color: white; }
        .bulk-btn-select-all { background: #28a745; color: white; }
        .bulk-btn-deselect { background: #6c757d; color: white; }
        .bulk-btn:hover { opacity: 0.8; }
        .footer { 
            text-align: center; 
            margin-top: 30px; 
            color: #666; 
            font-size: 14px;
        }
        .path-input-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .path-input {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: monospace;
            background: #f8f9fa;
        }
        .path-input:focus {
            outline: none;
            border-color: #007cba;
            background: white;
        }
        .path-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .path-suggestion {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            font-family: monospace;
            font-size: 14px;
        }
        .path-suggestion:hover, .path-suggestion.selected {
            background: #e3f2fd;
        }
        .path-suggestion:last-child {
            border-bottom: none;
        }
        @media (max-width: 768px) {
            .file-item { flex-direction: column; align-items: flex-start; }
            .file-actions { margin-top: 10px; }
            .action-group { display: block; margin-bottom: 10px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📁 PHP File Manager</h1>
            <p>Manage your files and folders with ease</p>
        </div>

        <div class="breadcrumb">
            <?php
            $pathParts = explode('/', str_replace($this->config->getRootPath(), '', $currentDir));
            $currentPath = $this->config->getRootPath();
            echo '<a href="?dir=' . urlencode($this->config->getRootPath()) . '">🏠 Home</a>';

            foreach ($pathParts as $part) {
                if (!empty($part)) {
                    $currentPath .= '/' . $part;
                    echo ' / <a href="?dir=' . urlencode($currentPath) . '">' . htmlspecialchars($part) . '</a>';
                }
            }
            ?>
        </div>

        <div class="actions">
            <h3>📝 Quick Actions</h3>
            
            <div class="action-group">
                <input type="text" id="filename" placeholder="filename.txt" style="padding: 8px; margin-right: 5px;">
                <button onclick="createFile()">Create File</button>
            </div>

            <div class="action-group">
                <input type="text" id="foldername" placeholder="folder name" style="padding: 8px; margin-right: 5px;">
                <button onclick="createFolder()">Create Folder</button>
            </div>

            <div class="action-group">
                <input type="file" id="fileUpload" style="margin-right: 5px;">
                <button onclick="uploadFile()">Upload File</button>
            </div>

            <div class="action-group">
                <button onclick="createArchive()" class="btn-archive" style="padding: 8px 12px; border-radius: 4px; border: none; cursor: pointer;">📦 Create Archive</button>
            </div>

            <div class="action-group">
                <a href="?action=logout" style="padding: 8px 12px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px;">🚪 Logout</a>
            </div>
        </div>

        <div class="bulk-actions" id="bulkActions">
            <h4>📋 Bulk Actions</h4>
            <button class="bulk-btn bulk-btn-select-all" onclick="selectAll()">Select All</button>
            <button class="bulk-btn bulk-btn-deselect" onclick="deselectAll()">Deselect All</button>
            <button class="bulk-btn bulk-btn-copy" onclick="bulkCopy()">Copy Selected</button>
            <button class="bulk-btn bulk-btn-move" onclick="bulkMove()">Move Selected</button>
            <button class="bulk-btn bulk-btn-delete" onclick="bulkDelete()">Delete Selected</button>
            <button class="bulk-btn bulk-btn-archive" onclick="bulkArchive()">Archive Selected</button>
            <span id="selectedCount">0 items selected</span>
        </div>

        <div class="file-list">
            <?php
            // Extract directories and files from the listing
            $directories = $files['directories'] ?? [];
            $regularFiles = $files['files'] ?? [];

            // Show parent directory link
            if ($currentDir !== $this->config->getRootPath()) {
                $parentDir = dirname($currentDir);
                echo '<div class="file-item">';
                echo '<div class="file-checkbox"></div>';
                echo '<div class="file-icon">📁</div>';
                echo '<div class="file-name"><a href="?dir=' . urlencode($parentDir) . '">.. (Parent Directory)</a></div>';
                echo '<div class="file-size"></div>';
                echo '<div class="file-actions"></div>';
                echo '</div>';
            }

            // Show directories
            foreach ($directories as $item) {
                // Add safety checks
                $itemName = $item['name'] ?? '';

                if (empty($itemName)) {
                    continue;
                }

                $dirpath = $currentDir . '/' . $itemName;
                echo '<div class="file-item" onclick="toggleSelection(this, event)">';
                echo '<div class="file-checkbox"><input type="checkbox" name="selected_items[]" value="' . htmlspecialchars($itemName) . '" onchange="updateBulkActions()"></div>';
                echo '<div class="file-icon">📁</div>';
                echo '<div class="file-name"><a href="?dir=' . urlencode($dirpath) . '" onclick="event.stopPropagation()">' . htmlspecialchars($itemName) . '</a></div>';
                echo '<div class="file-size">Directory</div>';
                echo '<div class="file-actions">';
                echo '<button class="btn-archive" onclick="event.stopPropagation(); archiveItem(\'' . htmlspecialchars($itemName) . '\')">Archive</button>';
                echo '<button class="btn-copy" onclick="event.stopPropagation(); copyItem(\'' . htmlspecialchars($itemName) . '\')">Copy</button>';
                echo '<button class="btn-move" onclick="event.stopPropagation(); moveItem(\'' . htmlspecialchars($itemName) . '\')">Move</button>';
                echo '<button class="btn-rename" onclick="event.stopPropagation(); renameItem(\'' . htmlspecialchars($itemName) . '\')">Rename</button>';
                echo '<button class="btn-delete" onclick="event.stopPropagation(); deleteItem(\'' . htmlspecialchars($itemName) . '\')">Delete</button>';
                echo '</div>';
                echo '</div>';
            }

            // Show files
            foreach ($regularFiles as $item) {
                // Add safety checks
                $itemName = $item['name'] ?? '';
                $itemSize = $item['size'] ?? 0;

                if (empty($itemName)) {
                    continue;
                }

                $extension = strtolower(pathinfo($itemName, PATHINFO_EXTENSION));

                echo '<div class="file-item" onclick="toggleSelection(this, event)">';
                echo '<div class="file-checkbox"><input type="checkbox" name="selected_items[]" value="' . htmlspecialchars($itemName) . '" onchange="updateBulkActions()"></div>';
                echo '<div class="file-icon">' . $this->getFileIcon($itemName) . '</div>';
                echo '<div class="file-name">' . htmlspecialchars($itemName) . '</div>';
                echo '<div class="file-size">' . $this->formatBytes($itemSize) . '</div>';
                echo '<div class="file-actions">';

                if ($this->config->isExtensionAllowed($itemName)) {
                    echo '<a href="?action=edit&file=' . urlencode($itemName) . '&dir=' . urlencode($currentDir) . '" class="btn-edit" onclick="event.stopPropagation()">Edit</a>';
                }

                // Add unarchive button for archive files
                if (in_array($extension, ['zip', 'gz', 'tgz']) || strpos($itemName, '.tar.gz') !== false) {
                    echo '<button class="btn-unarchive" onclick="event.stopPropagation(); unarchiveFile(\'' . htmlspecialchars($itemName) . '\')">Unarchive</button>';
                }

                echo '<a href="?action=download&file=' . urlencode($itemName) . '&dir=' . urlencode($currentDir) . '" class="btn-download" onclick="event.stopPropagation()">Download</a>';
                echo '<button class="btn-copy" onclick="event.stopPropagation(); copyItem(\'' . htmlspecialchars($itemName) . '\')">Copy</button>';
                echo '<button class="btn-move" onclick="event.stopPropagation(); moveItem(\'' . htmlspecialchars($itemName) . '\')">Move</button>';
                echo '<button class="btn-rename" onclick="event.stopPropagation(); renameItem(\'' . htmlspecialchars($itemName) . '\')">Rename</button>';
                echo '<button class="btn-delete" onclick="event.stopPropagation(); deleteItem(\'' . htmlspecialchars($itemName) . '\')">Delete</button>';
                echo '</div>';
                echo '</div>';
            }

            if (empty($directories) && empty($regularFiles)) {
                echo '<div class="file-item" style="text-align: center; color: #666;">';
                echo '<div style="padding: 40px;">📂 This directory is empty</div>';
                echo '</div>';
            }
            ?>
        </div>

        <div class="footer">
            <p>🚀 PHP File Manager | Current Directory: <strong><?php echo htmlspecialchars($currentDir); ?></strong></p>
        </div>
    </div>

    <script>
        let selectedItems = [];
        let currentPathInput = null;
        let currentSuggestions = null;
        let selectedSuggestionIndex = -1;

        function createPathInput(placeholder = 'Enter absolute path (e.g. /documents/backups/)') {
            const container = document.createElement('div');
            container.className = 'path-input-container';
            
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'path-input';
            input.placeholder = placeholder;
            input.value = '/';
            
            const suggestionsDiv = document.createElement('div');
            suggestionsDiv.className = 'path-suggestions';
            
            container.appendChild(input);
            container.appendChild(suggestionsDiv);
            
            input.addEventListener('input', function() {
                handlePathInput(this, suggestionsDiv);
            });
            
            input.addEventListener('keydown', function(e) {
                handlePathKeydown(e, this, suggestionsDiv);
            });
            
            input.addEventListener('blur', function() {
                setTimeout(() => {
                    suggestionsDiv.style.display = 'none';
                }, 200);
            });
            
            return container;
        }

        function handlePathInput(input, suggestionsDiv) {
            const value = input.value;
            
            if (!value.startsWith('/')) {
                suggestionsDiv.style.display = 'none';
                return;
            }
            
            if (value.length < 2) {
                suggestionsDiv.style.display = 'none';
                return;
            }
            
            fetch(`?action=get_path_suggestions&path=${encodeURIComponent(value)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.suggestions && data.suggestions.length > 0) {
                        displayPathSuggestions(data.suggestions, suggestionsDiv, input);
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                })
                .catch(() => {
                    suggestionsDiv.style.display = 'none';
                });
        }

        function displayPathSuggestions(suggestions, suggestionsDiv, input) {
            suggestionsDiv.innerHTML = '';
            selectedSuggestionIndex = -1;
            
            suggestions.forEach((suggestion, index) => {
                const div = document.createElement('div');
                div.className = 'path-suggestion';
                div.textContent = suggestion;
                div.addEventListener('click', () => {
                    input.value = suggestion.endsWith('/') ? suggestion : suggestion + '/';
                    suggestionsDiv.style.display = 'none';
                    input.focus();
                });
                suggestionsDiv.appendChild(div);
            });
            
            suggestionsDiv.style.display = 'block';
        }

        function handlePathKeydown(e, input, suggestionsDiv) {
            const suggestions = suggestionsDiv.children;
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedSuggestionIndex = Math.min(selectedSuggestionIndex + 1, suggestions.length - 1);
                updateSuggestionSelection(suggestions);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedSuggestionIndex = Math.max(selectedSuggestionIndex - 1, -1);
                updateSuggestionSelection(suggestions);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedSuggestionIndex >= 0 && suggestions[selectedSuggestionIndex]) {
                    const suggestion = suggestions[selectedSuggestionIndex].textContent;
                    input.value = suggestion.endsWith('/') ? suggestion : suggestion + '/';
                    suggestionsDiv.style.display = 'none';
                }
            } else if (e.key === 'Escape') {
                suggestionsDiv.style.display = 'none';
                selectedSuggestionIndex = -1;
            }
        }

        function updateSuggestionSelection(suggestions) {
            Array.from(suggestions).forEach((suggestion, index) => {
                suggestion.classList.toggle('selected', index === selectedSuggestionIndex);
            });
        }

        function toggleSelection(element, event) {
            if (event.target.type === 'checkbox') return;
            
            const checkbox = element.querySelector('input[type="checkbox"]');
            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                element.classList.toggle('selected', checkbox.checked);
                updateBulkActions();
            }
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
            const count = checkboxes.length;
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            
            if (count > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = count + ' item' + (count > 1 ? 's' : '') + ' selected';
            } else {
                bulkActions.classList.remove('show');
            }
        }

        function selectAll() {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
                checkbox.closest('.file-item').classList.add('selected');
            });
            updateBulkActions();
        }

        function deselectAll() {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.closest('.file-item').classList.remove('selected');
            });
            updateBulkActions();
        }

        function createFile() {
            const filename = document.getElementById('filename').value;
            if (filename) {
                const formData = new FormData();
                formData.append('action', 'create_file');
                formData.append('filename', filename);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(() => {
                    document.getElementById('filename').value = '';
                    window.location.reload();
                });
            }
        }

        function createFolder() {
            const foldername = document.getElementById('foldername').value;
            if (foldername) {
                const formData = new FormData();
                formData.append('action', 'create_folder');
                formData.append('foldername', foldername);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(() => {
                    document.getElementById('foldername').value = '';
                    window.location.reload();
                });
            }
        }

        function uploadFile() {
            const fileInput = document.getElementById('fileUpload');
            if (fileInput.files[0]) {
                const formData = new FormData();
                formData.append('action', 'upload');
                formData.append('file', fileInput.files[0]);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(() => {
                    fileInput.value = '';
                    window.location.reload();
                });
            }
        }

        function deleteItem(itemName) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'delete');
                    formData.append('item', itemName);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function renameItem(itemName) {
            Swal.fire({
                title: 'Rename Item',
                input: 'text',
                inputValue: itemName,
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to write something!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'rename');
                    formData.append('old_name', itemName);
                    formData.append('new_name', result.value);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function copyItem(itemName) {
            const pathContainer = createPathInput('Enter destination path (e.g. /documents/backups/)');
            
            Swal.fire({
                title: 'Copy Item',
                html: pathContainer,
                showCancelButton: true,
                confirmButtonText: 'Copy',
                didOpen: () => {
                    pathContainer.querySelector('.path-input').focus();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const destination = pathContainer.querySelector('.path-input').value;
                    const formData = new FormData();
                    formData.append('action', 'copy');
                    formData.append('item', itemName);
                    formData.append('destination', destination);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function moveItem(itemName) {
            const pathContainer = createPathInput('Enter destination path (e.g. /documents/backups/)');
            
            Swal.fire({
                title: 'Move Item',
                html: pathContainer,
                showCancelButton: true,
                confirmButtonText: 'Move',
                didOpen: () => {
                    pathContainer.querySelector('.path-input').focus();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const destination = pathContainer.querySelector('.path-input').value;
                    const formData = new FormData();
                    formData.append('action', 'move');
                    formData.append('item', itemName);
                    formData.append('destination', destination);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function bulkDelete() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_items[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) return;

            Swal.fire({
                title: 'Delete Selected Items?',
                text: `This will delete ${selected.length} selected item(s)`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete All'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'bulk_delete');
                    selected.forEach(item => formData.append('items[]', item));

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function bulkCopy() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_items[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) return;

            const pathContainer = createPathInput('Enter destination path (e.g. /documents/backups/)');
            
            Swal.fire({
                title: `Copy ${selected.length} Items`,
                html: pathContainer,
                showCancelButton: true,
                confirmButtonText: 'Copy All',
                didOpen: () => {
                    pathContainer.querySelector('.path-input').focus();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const destination = pathContainer.querySelector('.path-input').value;
                    const formData = new FormData();
                    formData.append('action', 'bulk_copy');
                    formData.append('destination', destination);
                    selected.forEach(item => formData.append('items[]', item));

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function bulkMove() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_items[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) return;

            const pathContainer = createPathInput('Enter destination path (e.g. /documents/backups/)');
            
            Swal.fire({
                title: `Move ${selected.length} Items`,
                html: pathContainer,
                showCancelButton: true,
                confirmButtonText: 'Move All',
                didOpen: () => {
                    pathContainer.querySelector('.path-input').focus();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const destination = pathContainer.querySelector('.path-input').value;
                    const formData = new FormData();
                    formData.append('action', 'bulk_move');
                    formData.append('destination', destination);
                    selected.forEach(item => formData.append('items[]', item));

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function bulkArchive() {
            const selected = Array.from(document.querySelectorAll('input[name="selected_items[]"]:checked')).map(cb => cb.value);
            if (selected.length === 0) return;

            Swal.fire({
                title: 'Create Archive',
                text: 'Enter archive name (without extension):',
                input: 'text',
                inputPlaceholder: 'selected-items',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Archive name is required';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'create_archive_with_name');
                    formData.append('archive_name', result.value);
                    selected.forEach(item => formData.append('items[]', item));

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function createArchive() {
            Swal.fire({
                title: 'Create Archive',
                text: 'Enter archive name (without extension):',
                input: 'text',
                inputPlaceholder: 'my-archive',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Archive name is required';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `?action=create_archive&name=${encodeURIComponent(result.value)}`;
                }
            });
        }

        function archiveItem(itemName) {
            Swal.fire({
                title: 'Archive Item',
                text: 'Enter archive name (without extension):',
                input: 'text',
                inputPlaceholder: itemName + '-archive',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Archive name is required';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action', 'create_archive_with_name');
                    formData.append('archive_name', result.value);
                    formData.append('items[]', itemName);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        function unarchiveFile(filename) {
            const pathContainer = createPathInput('Enter extraction path (leave empty for current directory)');
            
            Swal.fire({
                title: 'Extract Archive',
                html: pathContainer,
                showCancelButton: true,
                confirmButtonText: 'Extract',
                didOpen: () => {
                    pathContainer.querySelector('.path-input').value = '';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const destination = pathContainer.querySelector('.path-input').value;
                    const formData = new FormData();
                    
                    if (destination) {
                        formData.append('action', 'unarchive_to_path');
                        formData.append('destination', destination);
                    } else {
                        formData.append('action', 'unarchive');
                    }
                    
                    formData.append('archive_file', filename);

                    fetch(window.location.href, {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => window.location.reload());
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateBulkActions();
        });
    </script>
</body>
</html>
