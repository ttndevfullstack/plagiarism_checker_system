const PDFReportModule = {
    constants: {
        SCALE: 1.5,
        HIGHLIGHT_TYPES: ['Highlight', 'Text']
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
        document.addEventListener('mousedown', (e) => this.handleTextSelection(e));

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
                renderInteractiveForms: true,
                enableScripting: true
            };

            const renderTask = page.render(renderContext);

            Promise.all([
                this.renderTextContent(page, viewport),
                this.renderAnnotations(page),
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

    renderAnnotations(page) {
        return page.getAnnotations().then(annotations => {
            annotations.forEach(annotation => {
                if (this.constants.HIGHLIGHT_TYPES.includes(annotation.subtype)) {
                    this.createHighlight(annotation);
                }
            });
        });
    },

    createHighlight(annotation) {
        const highlight = document.createElement('div');
        highlight.className = 'pdf-annotation';
        
        const { rect } = annotation;
        Object.assign(highlight.style, {
            left: `${rect[0]}px`,
            top: `${rect[1]}px`,
            width: `${rect[2] - rect[0]}px`,
            height: `${rect[3] - rect[1]}px`
        });

        if (annotation.title) {
            highlight.setAttribute('title', annotation.title);
        }

        if (annotation.contents) {
            highlight.addEventListener('mouseover', (e) => this.showTooltip(e, annotation.contents));
            highlight.addEventListener('mouseout', () => this.hideTooltip());
        }

        this.elements.textLayer.appendChild(highlight);
    },

    showTooltip(event, content) {
        let tooltip = document.getElementById('pdf-tooltip');
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.id = 'pdf-tooltip';
            document.body.appendChild(tooltip);
        }
        
        tooltip.innerHTML = content;
        tooltip.style.left = `${event.pageX + 10}px`;
        tooltip.style.top = `${event.pageY + 10}px`;
        tooltip.style.display = 'block';
    },

    hideTooltip() {
        const tooltip = document.getElementById('pdf-tooltip');
        if (tooltip) {
            tooltip.style.display = 'none';
        }
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

    handleTextSelection(e) {
        if (e.target.closest('.textLayer')) {
            e.stopPropagation();
        }
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