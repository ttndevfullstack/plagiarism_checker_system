const PDFReportModule = {
    constants: {
        SCALE: 1.5
    },

    elements: {
        canvas: null,
        ctx: null,
        textLayer: null,
        pageNum: document.querySelector('#page-num'),
        pageCount: document.querySelector('#page-count'),
        prevButton: document.querySelector('#prev-page'),
        nextButton: document.querySelector('#next-page'),
        topBar: document.querySelector('.top-bar')
    },

    state: {
        pdfDoc: null,
        currentPage: 1,
        isRendering: false,
        pendingPage: null,
        url: window.pdfUrl
    },

    init() {
        // Initialize elements
        this.elements.canvas = document.querySelector('#pdf-render');
        this.elements.ctx = this.elements.canvas.getContext('2d');

        // Add event listeners
        this.elements.prevButton.addEventListener('click', () => this.showPrevPage());
        this.elements.nextButton.addEventListener('click', () => this.showNextPage());

        // Load PDF
        if (this.state.url) {
            this.loadPDF();
        } else {
            console.error('No PDF URL provided');
        }
    },

    loadPDF() {
        pdfjsLib.getDocument(this.state.url).promise
            .then(pdfDoc => {
                this.state.pdfDoc = pdfDoc;
                this.elements.pageCount.textContent = pdfDoc.numPages;
                this.renderPage(this.state.currentPage);
            })
            .catch(err => this.handleError(err));
    },

    renderPage(num) {
        this.state.isRendering = true;

        this.state.pdfDoc.getPage(num).then(page => {
            const viewport = page.getViewport({ scale: this.constants.SCALE });
            this.setupCanvas(viewport);
            this.setupTextLayer();

            const renderContext = {
                canvasContext: this.elements.ctx,
                viewport,
                textLayer: this.elements.textLayer,
            };

            const renderTask = page.render(renderContext);

            Promise.all([
                this.renderTextContent(page, viewport),
                renderTask.promise
            ]).then(() => {
                this.state.isRendering = false;
                if (this.state.pendingPage !== null) {
                    this.renderPage(this.state.pendingPage);
                    this.state.pendingPage = null;
                }
            });

            this.elements.pageNum.textContent = num;
        });
    },

    setupCanvas(viewport) {
        this.elements.canvas.height = viewport.height;
        this.elements.canvas.width = viewport.width;
    },

    setupTextLayer() {
        if (!this.elements.textLayer) {
            this.elements.textLayer = document.createElement('div');
            this.elements.textLayer.className = 'textLayer';
            this.elements.canvas.parentNode.insertBefore(
                this.elements.textLayer,
                this.elements.canvas.nextSibling
            );
        }
        this.elements.textLayer.innerHTML = '';
        this.elements.textLayer.style.width = this.elements.canvas.style.width;
        this.elements.textLayer.style.height = this.elements.canvas.style.height;
    },

    renderTextContent(page, viewport) {
        return page.getTextContent().then(textContent => {
            pdfjsLib.renderTextLayer({
                textContent,
                container: this.elements.textLayer,
                viewport,
                textDivs: []
            });
        });
    },

    queueRenderPage(num) {
        if (this.state.isRendering) {
            this.state.pendingPage = num;
        } else {
            this.renderPage(num);
        }
    },

    showPrevPage() {
        if (this.state.currentPage <= 1) return;
        this.state.currentPage--;
        this.queueRenderPage(this.state.currentPage);
    },

    showNextPage() {
        if (this.state.currentPage >= this.state.pdfDoc.numPages) return;
        this.state.currentPage++;
        this.queueRenderPage(this.state.currentPage);
    },

    handleError(error) {
        const div = document.createElement('div');
        div.className = 'error';
        div.appendChild(document.createTextNode(error.message));
        document.querySelector('body').insertBefore(div, this.elements.canvas);
        this.elements.topBar.style.display = 'none';
    }
};

// Initialize the module when document is ready
document.addEventListener('DOMContentLoaded', () => PDFReportModule.init());