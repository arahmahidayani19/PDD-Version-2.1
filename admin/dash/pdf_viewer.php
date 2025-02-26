<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer</title>
    <script src="pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'pdf.worker.min.js';
    </script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #404040;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .toolbar {
            background-color: #2f2f2f;
            padding: 8px;
            display: flex;
            align-items: center;
            gap: 16px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .zoom-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toolbar button {
            background: #404040;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .toolbar button:hover {
            background: #505050;
        }

        .page-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #pdf-container {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
            background-color: #404040;
        }

        .pdf-page {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
        }

        #zoom-level {
            background: transparent;
            border: 1px solid #505050;
            color: white;
            padding: 4px 8px;
            width: 80px;
            text-align: center;
        }

        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 1.2em;
            background: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        canvas {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            -ms-interpolation-mode: nearest-neighbor;
        }

        #quality-selector {
            background: #404040;
            color: white;
            border: 1px solid #505050;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .textLayer {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            color: transparent;
            pointer-events: none;
        }

        .page-container {
            position: relative;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <div class="page-info">
            <span>Page</span>
            <input type="number" id="current-page" value="1" min="1" style="width: 50px; background: #404040; color: white; border: 1px solid #505050; border-radius: 4px; padding: 4px;">
            <span>of</span>
            <span id="page-count">1</span>
        </div>
        <div class="zoom-controls">
            <button id="zoom-out" title="Zoom Out">-</button>
            <input type="text" id="zoom-level" value="100%" readonly>
            <button id="zoom-in" title="Zoom In">+</button>
        </div>
        <select id="quality-selector" title="Rendering Quality">
            <option value="1">Normal Quality</option>
            <option value="2" selected>High Quality</option>
            <option value="3">Ultra Quality</option>
        </select>
        <button id="download" title="Download PDF">Download</button>
    </div>
    <div id="pdf-container">
        <div class="loading">Loading PDF...</div>
    </div>

    <script>
        let pdfDoc = null;
        let currentZoom = 1;
        let BASE_SCALE = 2;
        const ZOOM_STEP = 0.10;
        const MAX_ZOOM = 5;
        const MIN_ZOOM = 0.10;

        function updateZoomDisplay() {
            document.getElementById('zoom-level').value = `${Math.round(currentZoom * 100)}%`;
        }

        async function renderPage(pageNum) {
            try {
                const page = await pdfDoc.getPage(pageNum);

                const pixelRatio = window.devicePixelRatio || 1;
                const actualScale = currentZoom * BASE_SCALE * pixelRatio;

                const viewport = page.getViewport({
                    scale: currentZoom
                });
                const renderViewport = page.getViewport({
                    scale: actualScale
                });

                const pageContainer = document.createElement('div');
                pageContainer.className = 'page-container';
                pageContainer.style.width = `${viewport.width}px`;
                pageContainer.style.height = `${viewport.height}px`;

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d', {
                    alpha: false,
                    antialias: false
                });

                canvas.classList.add('pdf-page');
                canvas.width = renderViewport.width;
                canvas.height = renderViewport.height;
                canvas.style.width = `${viewport.width}px`;
                canvas.style.height = `${viewport.height}px`;

                const renderContext = {
                    canvasContext: context,
                    viewport: renderViewport,
                    enableWebGL: true,
                    renderInteractiveForms: true,
                    imageResourcesPath: 'images/',
                    renderTextLayer: true,
                    useSystemFonts: true
                };

                context.fillStyle = 'white';
                context.fillRect(0, 0, canvas.width, canvas.height);

                context.imageSmoothingEnabled = true;
                context.imageSmoothingQuality = 'high';

                pageContainer.appendChild(canvas);
                document.getElementById('pdf-container').appendChild(pageContainer);

                await page.render(renderContext).promise;

                const textLayer = document.createElement('div');
                textLayer.className = 'textLayer';
                textLayer.style.width = `${viewport.width}px`;
                textLayer.style.height = `${viewport.height}px`;
                pageContainer.appendChild(textLayer);

                const textContent = await page.getTextContent();
                pdfjsLib.renderTextLayer({
                    textContent,
                    container: textLayer,
                    viewport: viewport,
                    textDivs: []
                });

            } catch (error) {
                console.error(`Error rendering page ${pageNum}:`, error);
            }
        }

        async function renderAllPages() {
            const container = document.getElementById('pdf-container');
            container.innerHTML = '';

            for (let num = 1; num <= pdfDoc.numPages; num++) {
                await renderPage(num);
            }
        }

        async function initPDF(url) {
            try {
                console.log('Loading PDF from:', url);
                const loadingTask = pdfjsLib.getDocument(url);

                loadingTask.onProgress = function(progress) {
                    const percent = (progress.loaded / progress.total * 100).toFixed(2);
                    document.querySelector('.loading').textContent = `Loading PDF... ${percent}%`;
                };

                pdfDoc = await loadingTask.promise;
                console.log('PDF loaded successfully');

                document.getElementById('page-count').textContent = pdfDoc.numPages;

                // Set initial zoom based on window width
                const firstPage = await pdfDoc.getPage(1);
                const viewport = firstPage.getViewport({
                    scale: 1
                });
                currentZoom = Math.min(1.5, window.innerWidth / (viewport.width * 1.2));
                updateZoomDisplay();

                await renderAllPages();

                // Event Listeners
                document.getElementById('zoom-in').addEventListener('click', () => {
                    if (currentZoom < MAX_ZOOM) {
                        currentZoom += ZOOM_STEP;
                        updateZoomDisplay();
                        renderAllPages();
                    }
                });

                document.getElementById('zoom-out').addEventListener('click', () => {
                    if (currentZoom > MIN_ZOOM) {
                        currentZoom -= ZOOM_STEP;
                        updateZoomDisplay();
                        renderAllPages();
                    }
                });

                document.getElementById('current-page').addEventListener('change', (e) => {
                    const pageNum = parseInt(e.target.value);
                    if (pageNum >= 1 && pageNum <= pdfDoc.numPages) {
                        const container = document.getElementById('pdf-container');
                        const pages = container.children;
                        if (pages[pageNum - 1]) {
                            pages[pageNum - 1].scrollIntoView({
                                behavior: 'smooth'
                            });
                        }
                    }
                });

                document.getElementById('quality-selector').addEventListener('change', (e) => {
                    BASE_SCALE = parseInt(e.target.value);
                    renderAllPages();
                });

                document.getElementById('download').addEventListener('click', () => {
                    window.location.href = url;
                });

                // Keyboard shortcuts
                document.addEventListener('keydown', (e) => {
                    if (e.key === '+' || e.key === '=') {
                        document.getElementById('zoom-in').click();
                    } else if (e.key === '-') {
                        document.getElementById('zoom-out').click();
                    }
                });

            } catch (error) {
                console.error('Error loading PDF:', error);
                document.querySelector('.loading').textContent = 'Error loading PDF. Please check console for details.';
            }
        }

        // Get the file parameter from URL
        const urlParams = new URLSearchParams(window.location.search);
        const file = urlParams.get('file');

        if (file) {
            const pdfUrl = `file_proxy.php?path=${encodeURIComponent(file)}`;
            console.log('Attempting to load PDF from:', pdfUrl);
            initPDF(pdfUrl);
        } else {
            document.querySelector('.loading').textContent = 'No PDF file specified';
        }
    </script>
</body>

</html>