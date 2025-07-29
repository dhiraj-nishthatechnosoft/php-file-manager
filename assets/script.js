/**
 * PHP File Manager - Enhanced JavaScript Functionality
 */

class FileManager {
    constructor() {
        this.currentPath = '';
        this.selectedItems = [];
        this.clipboard = [];
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupDragAndDrop();
        this.setupKeyboardShortcuts();
        this.setupSearch();
        this.loadSettings();
    }

    bindEvents() {
        // File operations
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-delete')) {
                this.confirmDelete(e.target.dataset.item);
            } else if (e.target.classList.contains('btn-rename')) {
                this.showRenameDialog(e.target.dataset.item);
            } else if (e.target.classList.contains('file-item')) {
                this.selectItem(e.target);
            }
        });

        // Context menu
        document.addEventListener('contextmenu', (e) => {
            if (e.target.closest('.file-item')) {
                e.preventDefault();
                this.showContextMenu(e, e.target.closest('.file-item'));
            }
        });

        // Close context menu when clicking elsewhere
        document.addEventListener('click', () => {
            this.hideContextMenu();
        });

        // Form submissions
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                this.handleFormSubmit(e);
            });
        });
    }

    setupDragAndDrop() {
        const dropZone = document.createElement('div');
        dropZone.className = 'drop-zone';
        dropZone.innerHTML = '<div class="drop-zone-text">Drop files here to upload</div>';
        
        const actionsDiv = document.querySelector('.actions');
        if (actionsDiv) {
            actionsDiv.appendChild(dropZone);
        }

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
            document.body.addEventListener(eventName, this.preventDefaults, false);
        });

        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', this.handleDrop.bind(this), false);
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl+A - Select all
            if (e.ctrlKey && e.key === 'a') {
                e.preventDefault();
                this.selectAll();
            }
            // Delete key - Delete selected items
            else if (e.key === 'Delete') {
                this.deleteSelected();
            }
            // F2 - Rename selected item
            else if (e.key === 'F2') {
                this.renameSelected();
            }
            // Ctrl+C - Copy selected items
            else if (e.ctrlKey && e.key === 'c') {
                this.copySelected();
            }
            // Ctrl+V - Paste items
            else if (e.ctrlKey && e.key === 'v') {
                this.pasteItems();
            }
            // Ctrl+F - Focus search
            else if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                this.focusSearch();
            }
            // Escape - Clear selection
            else if (e.key === 'Escape') {
                this.clearSelection();
                this.hideContextMenu();
            }
        });
    }

    setupSearch() {
        const searchBox = document.createElement('div');
        searchBox.className = 'search-box';
        searchBox.innerHTML = `
            <input type="text" class="search-input" placeholder="Search files and folders..." />
            <span class="search-icon">🔍</span>
        `;

        const fileList = document.querySelector('.file-list');
        if (fileList) {
            fileList.parentNode.insertBefore(searchBox, fileList);
        }

        const searchInput = searchBox.querySelector('.search-input');
        searchInput.addEventListener('input', (e) => {
            this.filterFiles(e.target.value);
        });
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    handleDrop(e) {
        const files = e.dataTransfer.files;
        this.uploadFiles(files);
    }

    uploadFiles(files) {
        const formData = new FormData();
        formData.append('action', 'upload_multiple');

        Array.from(files).forEach((file, index) => {
            formData.append(`files[${index}]`, file);
        });

        this.showProgress('Uploading files...');

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            this.hideProgress();
            this.showNotification('Files uploaded successfully', 'success');
            location.reload();
        })
        .catch(error => {
            this.hideProgress();
            this.showNotification('Upload failed: ' + error.message, 'error');
        });
    }

    confirmDelete(item) {
        if (confirm(`Are you sure you want to delete "${item}"?`)) {
            this.deleteItems([item]);
        }
    }

    showRenameDialog(item) {
        const newName = prompt('Enter new name:', item);
        if (newName && newName !== item) {
            this.renameItem(item, newName);
        }
    }

    selectItem(element) {
        if (event.ctrlKey) {
            element.classList.toggle('selected');
        } else {
            this.clearSelection();
            element.classList.add('selected');
        }
        this.updateSelectedItems();
    }

    selectAll() {
        const items = document.querySelectorAll('.file-item');
        items.forEach(item => item.classList.add('selected'));
        this.updateSelectedItems();
    }

    clearSelection() {
        const selected = document.querySelectorAll('.file-item.selected');
        selected.forEach(item => item.classList.remove('selected'));
        this.selectedItems = [];
    }

    updateSelectedItems() {
        const selected = document.querySelectorAll('.file-item.selected');
        this.selectedItems = Array.from(selected).map(item => 
            item.querySelector('.file-name').textContent.trim()
        );
    }

    deleteSelected() {
        if (this.selectedItems.length > 0) {
            if (confirm(`Delete ${this.selectedItems.length} selected items?`)) {
                this.deleteItems(this.selectedItems);
            }
        }
    }

    renameSelected() {
        if (this.selectedItems.length === 1) {
            this.showRenameDialog(this.selectedItems[0]);
        }
    }

    copySelected() {
        this.clipboard = [...this.selectedItems];
        this.showNotification(`${this.clipboard.length} items copied to clipboard`, 'info');
    }

    pasteItems() {
        if (this.clipboard.length > 0) {
            // Implementation would depend on server-side copy functionality
            this.showNotification('Paste functionality requires server implementation', 'warning');
        }
    }

    focusSearch() {
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }

    filterFiles(query) {
        const items = document.querySelectorAll('.file-item');
        const lowerQuery = query.toLowerCase();

        items.forEach(item => {
            const fileName = item.querySelector('.file-name').textContent.toLowerCase();
            if (fileName.includes(lowerQuery)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    showContextMenu(e, fileItem) {
        this.hideContextMenu();

        const menu = document.createElement('div');
        menu.className = 'context-menu';
        menu.innerHTML = `
            <a href="#" class="context-menu-item" data-action="edit">Edit</a>
            <a href="#" class="context-menu-item" data-action="download">Download</a>
            <a href="#" class="context-menu-item" data-action="rename">Rename</a>
            <a href="#" class="context-menu-item" data-action="delete">Delete</a>
            <a href="#" class="context-menu-item" data-action="properties">Properties</a>
        `;

        menu.style.left = e.pageX + 'px';
        menu.style.top = e.pageY + 'px';

        document.body.appendChild(menu);

        // Handle menu item clicks
        menu.addEventListener('click', (e) => {
            e.preventDefault();
            const action = e.target.dataset.action;
            const fileName = fileItem.querySelector('.file-name').textContent.trim();
            
            this.handleContextAction(action, fileName);
            this.hideContextMenu();
        });
    }

    hideContextMenu() {
        const menu = document.querySelector('.context-menu');
        if (menu) {
            menu.remove();
        }
    }

    handleContextAction(action, fileName) {
        switch (action) {
            case 'edit':
                window.location.href = `?action=edit&file=${encodeURIComponent(fileName)}`;
                break;
            case 'download':
                window.location.href = `?action=download&file=${encodeURIComponent(fileName)}`;
                break;
            case 'rename':
                this.showRenameDialog(fileName);
                break;
            case 'delete':
                this.confirmDelete(fileName);
                break;
            case 'properties':
                this.showProperties(fileName);
                break;
        }
    }

    deleteItems(items) {
        const formData = new FormData();
        formData.append('action', 'delete_multiple');
        formData.append('items', JSON.stringify(items));

        this.showProgress('Deleting items...');

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(() => {
            this.hideProgress();
            this.showNotification('Items deleted successfully', 'success');
            location.reload();
        })
        .catch(error => {
            this.hideProgress();
            this.showNotification('Delete failed: ' + error.message, 'error');
        });
    }

    renameItem(oldName, newName) {
        const formData = new FormData();
        formData.append('action', 'rename');
        formData.append('old_name', oldName);
        formData.append('new_name', newName);

        this.showProgress('Renaming item...');

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(() => {
            this.hideProgress();
            this.showNotification('Item renamed successfully', 'success');
            location.reload();
        })
        .catch(error => {
            this.hideProgress();
            this.showNotification('Rename failed: ' + error.message, 'error');
        });
    }

    showProperties(fileName) {
        // Create modal for file properties
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Properties: ${fileName}</h3>
                    <span class="modal-close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="loading"></div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'block';

        // Close modal functionality
        modal.querySelector('.modal-close').addEventListener('click', () => {
            modal.remove();
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        // Load file properties
        this.loadFileProperties(fileName, modal.querySelector('.modal-body'));
    }

    loadFileProperties(fileName, container) {
        fetch(`?action=get_properties&file=${encodeURIComponent(fileName)}`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = `
                <table>
                    <tr><td><strong>Name:</strong></td><td>${data.name}</td></tr>
                    <tr><td><strong>Size:</strong></td><td>${data.size}</td></tr>
                    <tr><td><strong>Type:</strong></td><td>${data.type}</td></tr>
                    <tr><td><strong>Modified:</strong></td><td>${data.modified}</td></tr>
                    <tr><td><strong>Permissions:</strong></td><td>${data.permissions}</td></tr>
                </table>
            `;
        })
        .catch(error => {
            container.innerHTML = '<p>Error loading properties: ' + error.message + '</p>';
        });
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Show animation
        setTimeout(() => notification.classList.add('show'), 100);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }

    showProgress(message) {
        const progress = document.createElement('div');
        progress.className = 'progress-overlay';
        progress.innerHTML = `
            <div class="progress-content">
                <div class="loading"></div>
                <p>${message}</p>
            </div>
        `;

        document.body.appendChild(progress);
    }

    hideProgress() {
        const progress = document.querySelector('.progress-overlay');
        if (progress) {
            progress.remove();
        }
    }

    handleFormSubmit(e) {
        const form = e.target;
        const action = form.querySelector('[name="action"]')?.value;

        if (action === 'upload') {
            const fileInput = form.querySelector('[type="file"]');
            if (fileInput.files.length === 0) {
                e.preventDefault();
                this.showNotification('Please select a file to upload', 'warning');
                return;
            }
        }

        // Show loading state
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
        }
    }

    loadSettings() {
        const settings = localStorage.getItem('fileManagerSettings');
        if (settings) {
            const parsed = JSON.parse(settings);
            if (parsed.theme) {
                document.body.className = parsed.theme;
            }
        }
    }

    saveSettings() {
        const settings = {
            theme: document.body.className,
            viewMode: this.viewMode || 'list'
        };
        localStorage.setItem('fileManagerSettings', JSON.stringify(settings));
    }

    toggleTheme() {
        const body = document.body;
        if (body.classList.contains('dark-theme')) {
            body.className = 'light-theme';
        } else {
            body.className = 'dark-theme';
        }
        this.saveSettings();
    }

    // File preview functionality
    previewFile(fileName, fileType) {
        const modal = document.createElement('div');
        modal.className = 'modal';
        
        let content = '<div class="loading"></div>';
        
        if (['jpg', 'jpeg', 'png', 'gif', 'svg'].includes(fileType.toLowerCase())) {
            content = `<img src="?action=preview&file=${encodeURIComponent(fileName)}" style="max-width: 100%; height: auto;" />`;
        } else if (['txt', 'md', 'html', 'css', 'js', 'php'].includes(fileType.toLowerCase())) {
            // Load text content for preview
            this.loadTextPreview(fileName, modal);
        }

        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Preview: ${fileName}</h3>
                    <span class="modal-close">&times;</span>
                </div>
                <div class="modal-body">
                    ${content}
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        modal.style.display = 'block';

        // Close modal functionality
        modal.querySelector('.modal-close').addEventListener('click', () => {
            modal.remove();
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    loadTextPreview(fileName, modal) {
        fetch(`?action=preview&file=${encodeURIComponent(fileName)}`)
        .then(response => response.text())
        .then(content => {
            const modalBody = modal.querySelector('.modal-body');
            modalBody.innerHTML = `<pre class="code-preview">${this.escapeHtml(content)}</pre>`;
        })
        .catch(error => {
            const modalBody = modal.querySelector('.modal-body');
            modalBody.innerHTML = '<p>Error loading preview: ' + error.message + '</p>';
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize the file manager when the page loads
document.addEventListener('DOMContentLoaded', () => {
    window.fileManager = new FileManager();

    // Add keyboard shortcuts help
    const shortcutsHelp = document.createElement('div');
    shortcutsHelp.className = 'shortcuts-help';
    shortcutsHelp.innerHTML = `
        <strong>Keyboard Shortcuts:</strong><br>
        Ctrl+A: Select All | Del: Delete | F2: Rename<br>
        Ctrl+C: Copy | Ctrl+V: Paste | Ctrl+F: Search | Esc: Clear
    `;
    document.body.appendChild(shortcutsHelp);

    // Show shortcuts help on hover over help area
    let helpTimeout;
    document.addEventListener('mousemove', (e) => {
        if (e.clientY > window.innerHeight - 100 && e.clientX < 250) {
            clearTimeout(helpTimeout);
            shortcutsHelp.classList.add('show');
        } else {
            clearTimeout(helpTimeout);
            helpTimeout = setTimeout(() => {
                shortcutsHelp.classList.remove('show');
            }, 1000);
        }
    });
});

// Utility functions
function formatFileSize(bytes) {
    const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    if (bytes === 0) return '0 B';
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
}

function getFileExtension(filename) {
    return filename.split('.').pop().toLowerCase();
}

function isImageFile(filename) {
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp'];
    return imageExtensions.includes(getFileExtension(filename));
}

function isTextFile(filename) {
    const textExtensions = ['txt', 'md', 'html', 'css', 'js', 'php', 'json', 'xml', 'yml', 'yaml'];
    return textExtensions.includes(getFileExtension(filename));
}
